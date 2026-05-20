<?php
namespace Helpers;

class ImageHelper {

    /**
     * Upload, rename, resize and generate thumb for a single uploaded file.
     *
     * @param array  $file       $_FILES['field'] element
     * @param string $uploadDir  Absolute path to base upload dir (no trailing slash)
     * @param int    $maxWidth   Max width for full image (px)
     * @param int    $maxHeight  Max height for full image (px)
     * @param int    $quality    JPEG quality 0-100 for full image
     * @param int    $thumbSize  Square thumb side length (px)
     * @param int    $thumbQuality JPEG quality for thumbnail
     * @return array ['full' => relative_url, 'thumb' => relative_url] | ['error' => msg]
     */
    public static function processUpload(
        array  $file,
        string $uploadDir,
        int    $maxWidth       = 1200,
        int    $maxHeight      = 1200,
        int    $quality        = 85,
        int    $thumbSize      = 300,
        int    $thumbQuality   = 80
    ): array {

        // 1. Basic validation
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'Upload error code: ' . $file['error']];
        }

        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $mime    = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowed)) {
            return ['error' => 'File type not allowed: ' . $mime];
        }

        // 2. Ensure directories exist
        $fullDir  = $uploadDir . '/full';
        $thumbDir = $uploadDir . '/thumb';
        foreach ([$fullDir, $thumbDir] as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }

        // 3. Generate unique filename
        $uid      = bin2hex(random_bytes(10)); // 20-hex unique ID
        $ext      = 'jpg'; // always save as JPEG for uniformity & compression
        $filename = $uid . '.' . $ext;

        // 4. Load image into GD
        $src = self::loadImage($file['tmp_name'], $mime);
        if (!$src) {
            return ['error' => 'Could not read image. GD extension may be missing.'];
        }

        // 5. Resize full image (preserving aspect ratio)
        $srcW = imagesx($src);
        $srcH = imagesy($src);
        [$newW, $newH] = self::calcDimensions($srcW, $srcH, $maxWidth, $maxHeight);

        $full = imagecreatetruecolor($newW, $newH);
        self::preserveTransparency($full, $mime);
        imagecopyresampled($full, $src, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);

        // 6. Save full image
        $fullPath = $fullDir . '/' . $filename;
        imagejpeg($full, $fullPath, $quality);
        imagedestroy($full);

        // 7. Generate square thumbnail (crop centre)
        $thumb = self::cropSquare($src, $srcW, $srcH, $thumbSize);
        $thumbPath = $thumbDir . '/' . $filename;
        imagejpeg($thumb, $thumbPath, $thumbQuality);
        imagedestroy($thumb);
        imagedestroy($src);

        // 8. Build relative URLs (relative to app root, using base URL)
        //    Uses SCRIPT_FILENAME instead of DOCUMENT_ROOT to correctly handle
        //    subdomain-to-subdirectory mapping on shared hosting.
        $appRoot  = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME'])), '/');
        $base     = isset($GLOBALS['base']) ? rtrim($GLOBALS['base'], '/') : '';
        $relFull  = $base . '/' . ltrim(str_replace('\\', '/', substr($fullPath,  strlen($appRoot))), '/');
        $relThumb = $base . '/' . ltrim(str_replace('\\', '/', substr($thumbPath, strlen($appRoot))), '/');

        return ['full' => $relFull, 'thumb' => $relThumb];
    }

    /**
     * Delete full + thumb files for a given relative URL pair.
     */
    public static function deleteFiles(string $fullUrl, string $thumbUrl): void {
        $appRoot = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME'])), '/');
        foreach ([$fullUrl, $thumbUrl] as $url) {
            if (empty($url)) continue;
            // Extract 'uploads/...' portion for reliable path resolution
            // This handles both old (DOCUMENT_ROOT-based) and new (base-relative) paths
            if (preg_match('#(uploads/.+)$#', $url, $m)) {
                $path = $appRoot . '/' . str_replace('/', DIRECTORY_SEPARATOR, $m[1]);
            } else {
                // Fallback: try DOCUMENT_ROOT for non-uploads paths
                $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\');
                $path = $docRoot . '/' . ltrim(str_replace('/', DIRECTORY_SEPARATOR, $url), '/\\');
            }
            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }

    // ─── private helpers ──────────────────────────────────────────────────────

    private static function loadImage(string $path, string $mime) {
        switch ($mime) {
            case 'image/jpeg':
                $src = @imagecreatefromjpeg($path);
                if ($src && function_exists('exif_read_data')) {
                    $exif = @exif_read_data($path);
                    if ($exif && !empty($exif['Orientation'])) {
                        switch ($exif['Orientation']) {
                            case 3:
                                $rot = imagerotate($src, 180, 0);
                                imagedestroy($src);
                                $src = $rot;
                                break;
                            case 6:
                                $rot = imagerotate($src, 270, 0);
                                imagedestroy($src);
                                $src = $rot;
                                break;
                            case 8:
                                $rot = imagerotate($src, 90, 0);
                                imagedestroy($src);
                                $src = $rot;
                                break;
                        }
                    }
                }
                return $src;
            case 'image/png':  return @imagecreatefrompng($path);
            case 'image/webp': return @imagecreatefromwebp($path);
            case 'image/gif':  return @imagecreatefromgif($path);
        }
        return false;
    }

    private static function calcDimensions(int $srcW, int $srcH, int $maxW, int $maxH): array {
        if ($srcW <= $maxW && $srcH <= $maxH) {
            return [$srcW, $srcH]; // already small enough
        }
        $ratio = min($maxW / $srcW, $maxH / $srcH);
        return [(int)round($srcW * $ratio), (int)round($srcH * $ratio)];
    }

    private static function preserveTransparency($img, string $mime): void {
        imagealphablending($img, false);
        imagesavealpha($img, true);
        $transparent = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefilledrectangle($img, 0, 0, imagesx($img), imagesy($img), $transparent);
    }

    /**
     * Crop-and-resize to a centred square thumbnail.
     */
    private static function cropSquare($src, int $srcW, int $srcH, int $size) {
        // Determine crop rect
        if ($srcW > $srcH) {
            $cropX = (int)(($srcW - $srcH) / 2);
            $cropY = 0;
            $cropS = $srcH;
        } else {
            $cropX = 0;
            $cropY = (int)(($srcH - $srcW) / 2);
            $cropS = $srcW;
        }

        $thumb = imagecreatetruecolor($size, $size);
        // white background (for transparent images)
        imagefilledrectangle($thumb, 0, 0, $size, $size, imagecolorallocate($thumb, 255, 255, 255));
        imagecopyresampled($thumb, $src, 0, 0, $cropX, $cropY, $size, $size, $cropS, $cropS);
        return $thumb;
    }
}
