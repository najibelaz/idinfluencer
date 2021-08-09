<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

if (defined('TIMEZONE')) {
    date_default_timezone_set(TIMEZONE);
}
define("TEMP_PATH", APPPATH . "../assets/tmp");
define('DEMO_VERSION', false);
define("NOW", date("Y-m-d H:i:s"));
define("FILE_MANAGER", "general_file_manager");
define("USERS", "general_users");
define("PACKAGES", "general_packages");
define("COUPONS", "general_coupons");
define("OPTIONS", "general_options");
define("PROXIES", "general_proxies");
define("CAPTION", "general_caption");
define("PAYMENT_HISTORY", "general_payment_history");
define("PURCHASE", "general_purchase");
define("LANGUAGE", "general_lang");
define("LANGUAGE_LIST", "general_lang_list");
define("GROUPES", "general_groups");
define("GROUPES_MANAGER", "manager_group");





if (!defined('CUSTOM_PAGE')) {
    define("CUSTOM_PAGE", "general_custom_page");
}

// Status des posts
define("ST_PLANIFIED", 1);
define("ST_PUBLISHED", 2);
define("ST_FAILED", 3);
define("ST_INVALID", 4);
define("ST_WAITTING", 5);
define("ST_DRAFT", 6);
define("ST_DELETED", 7);


define("PACK_PREMIUM", 9);
define("PACK_STANDARD", 8);
define("PACK_BASIC", 2);
define("PACK_TRIAL", 1);
define("PACK_STARTER", 1);


//Teamleader acces
define("ACCESSTOKENDEFAULT", "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImIzNzNmMTRmZTYxNjdmYWQwODZiYzlmMGZmMTYzNWRmNjAxM2Y3MjFkYzYzNjFhZTU0OTcwYTg2MWU5M2I1MTc2NDU5ODgzMWRkOTI4MTUxIn0.eyJhdWQiOiJlMTFiNDNlN2I3Zjc4MzYwZDEzZjdkMWEzMTRhNDYzMCIsImp0aSI6ImIzNzNmMTRmZTYxNjdmYWQwODZiYzlmMGZmMTYzNWRmNjAxM2Y3MjFkYzYzNjFhZTU0OTcwYTg2MWU5M2I1MTc2NDU5ODgzMWRkOTI4MTUxIiwiaWF0IjoxNTgxOTM1NTc3LCJuYmYiOjE1ODE5MzU1NzcsImV4cCI6MTU4MTkzOTE3Nywic3ViIjoiMzI5NjM6NTczMjMiLCJzY29wZXMiOlsiaW52b2ljZXMiLCJxdW90YXRpb25zIiwidXNlcnMiXSwicGVybWlzc2lvbnMiOlsiYWRtaW4iLCJiaWxsaW5nIiwiY2FsZW5kYXIiLCJjb21wYW5pZXMiLCJjb250YWN0cyIsImNyZWRpdF9ub3RlcyIsImRhc2hib2FyZCIsImRlYWxzIiwiZGVsaXZlcnlfbm90ZXMiLCJpbnNpZ2h0cyIsImludm9pY2VzIiwibWFpbGluZyIsIm9yZGVycyIsInByb2R1Y3RfcHVyY2hhc2VfcHJpY2UiLCJwcm9kdWN0cyIsInNldHRpbmdzIiwic3Vic2NyaXB0aW9ucyIsInRhcmdldHMiLCJ0aW1lX3RyYWNraW5nIiwidG9kb3MiLCJ1c2VycyIsIndlYmhvb2tzIiwid29ya19vcmRlcnMiXX0.IUuGUvOdgi0g1X7vqNsTJVr1MQHKykp3TYnX1M0dkHHiB-uOvSiYcQHy1xCEDxT7ssBEo9JQouPFChPw5xiNsXfVlGZPU624eAvwB97gYWmcsnU1DrySCHoaCeJPmcI1hICwx9RwfxCryZcfAV8Eh0u6kVTG4fISS5LEo5gYKy0");
define("CLIENID", "e11b43e7b7f78360d13f7d1a314a4630");
define("CLIENSECRECT", "da9c1346723e8da1630533c60369a431");
