<?php
/**
 * The config file of ZenTaoPMS.
 *
 * Don't modify this file directly, copy the item to my.php and change it.
 *
 * @copyright   Copyright 2009-2012 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     config
 * @version     $Id$
 * @link        http://www.zentao.net
 */
/* Basic settings. */
$config->version     = '3.0';             // The version of zentaopms. Don't change it.
$config->encoding    = 'UTF-8';           // The encoding of znetaopms.
$config->cookieLife  = time() + 2592000;  // The cookie life time.
$config->timezone    = 'Asia/Shanghai';   // The time zone setting, for more see http://www.php.net/manual/en/timezones.php
$config->webRoot     = '';                // The root path of the pms.

/* The request settings. */
$config->requestType = 'PATH_INFO';       // The request type: PATH_INFO|GET, if PATH_INFO, must use url rewrite.
$config->pathType    = 'clean';           // If the request type is PATH_INFO, the path type.
$config->requestFix  = '-';               // The divider in the url when PATH_INFO.
$config->moduleVar   = 'm';               // requestType=GET: the module var name.
$config->methodVar   = 'f';               // requestType=GET: the method var name.
$config->viewVar     = 't';               // requestType=GET: the view var name.
$config->sessionVar  = 'sid';             // requestType=GET: the session var name.

/* Supported views. */
$config->views  = ',html,json,'; 

/* Set the wide window size. */
$config->wideSize = 1400;

/* Supported languages. */
$config->langs['zh-cn'] = '中文简体';
$config->langs['zh-tw'] = '中文繁體';
$config->langs['en']    = 'English';

/* Default settings. */
$config->default->view   = 'html';        // Default view.
$config->default->lang   = 'en';          // Default language.
$config->default->theme  = 'default';     // Default theme.
$config->default->module = 'index';       // Default module.
$config->default->method = 'index';       // Default method.

/* Upload settings. */
$config->file->dangers = 'php,jsp,py,rb,asp,'; // Dangerous files.
$config->file->maxSize = 1024 * 1024;          // Max size.

/* Master database settings. */
$config->db->persistant     = false;     // Pconnect or not.
$config->db->driver         = 'mysql';   // Must be MySQL. Don't support other database server yet.
$config->db->encoding       = 'UTF8';    // Encoding of database.
$config->db->strictMode     = false;     // Turn off the strict mode of MySQL.
//$config->db->emulatePrepare = true;    // PDO::ATTR_EMULATE_PREPARES
//$config->db->bufferQuery    = true;     // PDO::MYSQL_ATTR_USE_BUFFERED_QUERY

/* Slave database settings. */
$config->slaveDB->persistant = false;      
$config->slaveDB->driver     = 'mysql';    
$config->slaveDB->encoding   = 'UTF8';     
$config->slaveDB->strictMode = false;      
$config->slaveDB->checkCentOS= true;       

/* Include the custom config file. */
$configRoot = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$myConfig   = $configRoot . 'my.php';
if(file_exists($myConfig)) include $myConfig;

/* Include extension config files. */
$extConfigFiles = glob($configRoot . 'ext/*.php');
foreach($extConfigFiles as $extConfigFile) include $extConfigFile;

/* Set default table prefix. */
if(!isset($config->db->prefix)) $config->db->prefix = 'zt_';

/* Define the tables. */
if(!defined('TABLE_COMPANY'))        define('TABLE_COMPANY',       '`' . $config->db->prefix . 'company`');
if(!defined('TABLE_DEPT'))           define('TABLE_DEPT',          '`' . $config->db->prefix . 'dept`');
if(!defined('TABLE_CONFIG'))         define('TABLE_CONFIG',        '`' . $config->db->prefix . 'config`');
if(!defined('TABLE_USER'))           define('TABLE_USER',          '`' . $config->db->prefix . 'user`');
if(!defined('TABLE_TODO'))           define('TABLE_TODO',          '`' . $config->db->prefix . 'todo`');
if(!defined('TABLE_GROUP'))          define('TABLE_GROUP',         '`' . $config->db->prefix . 'group`');
if(!defined('TABLE_GROUPPRIV'))      define('TABLE_GROUPPRIV',     '`' . $config->db->prefix . 'groupPriv`');
if(!defined('TABLE_USERGROUP'))      define('TABLE_USERGROUP',     '`' . $config->db->prefix . 'userGroup`');
if(!defined('TABLE_USERQUERY'))      define('TABLE_USERQUERY',     '`' . $config->db->prefix . 'userQuery`');

if(!defined('TABLE_BUG'))            define('TABLE_BUG',           '`' . $config->db->prefix . 'bug`');
if(!defined('TABLE_CASE'))           define('TABLE_CASE',          '`' . $config->db->prefix . 'case`');
if(!defined('TABLE_CASESTEP'))       define('TABLE_CASESTEP',      '`' . $config->db->prefix . 'caseStep`');
if(!defined('TABLE_TESTTASK'))       define('TABLE_TESTTASK',      '`' . $config->db->prefix . 'testTask`');
if(!defined('TABLE_TESTRUN'))        define('TABLE_TESTRUN',       '`' . $config->db->prefix . 'testRun`');
if(!defined('TABLE_TESTRESULT'))     define('TABLE_TESTRESULT',    '`' . $config->db->prefix . 'testResult`');
if(!defined('TABLE_USERTPL'))        define('TABLE_USERTPL',       '`' . $config->db->prefix . 'userTPL`');

if(!defined('TABLE_PRODUCT'))        define('TABLE_PRODUCT',       '`' . $config->db->prefix . 'product`');
if(!defined('TABLE_STORY'))          define('TABLE_STORY',         '`' . $config->db->prefix . 'story`');
if(!defined('TABLE_STORYSPEC'))      define('TABLE_STORYSPEC',     '`' . $config->db->prefix . 'storySpec`');
if(!defined('TABLE_PRODUCTPLAN'))    define('TABLE_PRODUCTPLAN',   '`' . $config->db->prefix . 'productPlan`');
if(!defined('TABLE_RELEASE'))        define('TABLE_RELEASE',       '`' . $config->db->prefix . 'release`');

if(!defined('TABLE_PROJECT'))        define('TABLE_PROJECT',       '`' . $config->db->prefix . 'project`');
if(!defined('TABLE_TASK'))           define('TABLE_TASK',          '`' . $config->db->prefix . 'task`');
if(!defined('TABLE_TEAM'))           define('TABLE_TEAM',          '`' . $config->db->prefix . 'team`');
if(!defined('TABLE_PROJECTPRODUCT')) define('TABLE_PROJECTPRODUCT','`' . $config->db->prefix . 'projectProduct`');
if(!defined('TABLE_PROJECTSTORY'))   define('TABLE_PROJECTSTORY',  '`' . $config->db->prefix . 'projectStory`');
if(!defined('TABLE_TASKESTIMATE'))   define('TABLE_TASKESTIMATE',  '`' . $config->db->prefix . 'taskEstimate`');
if(!defined('TABLE_EFFORT'))         define('TABLE_EFFORT',        '`' . $config->db->prefix . 'effort`');
if(!defined('TABLE_BURN'))           define('TABLE_BURN',          '`' . $config->db->prefix . 'burn`');
if(!defined('TABLE_BUILD'))          define('TABLE_BUILD',         '`' . $config->db->prefix . 'build`');

if(!defined('TABLE_DOCLIB'))         define('TABLE_DOCLIB',        '`' . $config->db->prefix . 'docLib`');
if(!defined('TABLE_DOC'))            define('TABLE_DOC',           '`' . $config->db->prefix . 'doc`');

if(!defined('TABLE_MODULE'))         define('TABLE_MODULE',        '`' . $config->db->prefix . 'module`');
if(!defined('TABLE_ACTION'))         define('TABLE_ACTION',        '`' . $config->db->prefix . 'action`');
if(!defined('TABLE_FILE'))           define('TABLE_FILE',          '`' . $config->db->prefix . 'file`');
if(!defined('TABLE_HISTORY'))        define('TABLE_HISTORY',       '`' . $config->db->prefix . 'history`');
if(!defined('TABLE_EXTENSION'))      define('TABLE_EXTENSION',     '`' . $config->db->prefix . 'extension`');
