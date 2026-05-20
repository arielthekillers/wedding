<?php
require 'config/config.php';
$w = \Models\Wedding::getBySlug('syifadanrochim');
echo $w['section_settings'];
