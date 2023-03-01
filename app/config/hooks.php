<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

# Load phpdotenv
$hook['pre_system'] = function () {
    require_once BASEPATH . 'dotenv/autoloader.php';
    $dotenv = new Dotenv\Dotenv(FCPATH);
    $dotenv->load();
};

$hook['display_override'] = [
    'filepath' => 'hooks',
    'filename' => 'ViewCompiler.php',
    'class' => 'ViewCompiler',
    'function' => 'compile'
];
