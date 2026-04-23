<?php
/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 */
define('ENVIRONMENT', 'development');

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 */
if (defined('ENVIRONMENT')) {
    switch (ENVIRONMENT) {
        case 'development':
            error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
            ini_set('display_errors', 1);
            ini_set('log_errors', 1);
            ini_set('error_log', 'debug.log');
            break;

        case 'testing':
        case 'production':
            error_reporting(0);
            break;

        default:
            exit('The application environment is not set correctly.');
    }
}

$system_path = 'system';
$application_folder = 'application';

if (defined('STDIN')) {
    chdir(dirname(__FILE__));
}

if (realpath($system_path) !== false) {
    $system_path = realpath($system_path) . '/';
}

$system_path = rtrim($system_path, '/') . '/';

if (! is_dir($system_path)) {
    exit('Your system folder path does not appear to be set correctly. Please open the following file and correct this: ' . pathinfo(__FILE__, PATHINFO_BASENAME));
}

define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('EXT', '.php');
define('BASEPATH', str_replace('\\', '/', $system_path));
define('FCPATH', str_replace(SELF, '', __FILE__));
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));

if (is_dir($application_folder)) {
    define('APPPATH', $application_folder . '/');
} else {
    if (! is_dir(BASEPATH . $application_folder . '/')) {
        exit('Your application folder path does not appear to be set correctly. Please open the following file and correct this: ' . SELF);
    }

    define('APPPATH', BASEPATH . $application_folder . '/');
}

$composer_path = FCPATH . 'vendor/autoload.php';
if (file_exists($composer_path)) {
    require_once $composer_path;
}

require_once BASEPATH . 'core/CodeIgniter.php';
