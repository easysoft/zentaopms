<?php
/**
 * The config file of ZenTaoPMS.
 *
 * Don't modify this file directly, copy the item to my.php and change it.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     config
 * @version     $Id: config.php 5068 2013-07-08 02:41:22Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
/* Judge class config and function getWebRoot exists or not, make sure php shells can work. */
if(!class_exists('config')){class config{}}
if(!function_exists('getWebRoot')){function getWebRoot(){}}

/* Basic settings. */
$config->version      = '9.2.1';        // The version of zentaopms. Don't change it.
$config->charset      = 'UTF-8';           // The charset of zentaopms.
$config->cookieLife   = time() + 2592000;  // The cookie life time.
$config->timezone     = 'Asia/Shanghai';   // The time zone setting, for more see http://www.php.net/manual/en/timezones.php
$config->webRoot      = '';                // The root path of the pms.

/* The request settings. */
$config->requestType = 'PATH_INFO';       // The request type: PATH_INFO|GET, if PATH_INFO, must use url rewrite.
$config->requestFix  = '-';               // The divider in the url when PATH_INFO.
$config->moduleVar   = 'm';               // requestType=GET: the module var name.
$config->methodVar   = 'f';               // requestType=GET: the method var name.
$config->viewVar     = 't';               // requestType=GET: the view var name.
$config->sessionVar  = 'zentaosid';       // requestType=GET: the session var name.
$config->views       = ',html,json,mhtml,'; 

/* 支持的主题和语言。Supported thems and languages. */
$config->themes['default'] = 'default'; 
$config->langs['zh-cn'] = '简体';
$config->langs['en']    = 'English';
$config->langs['zh-tw'] = '繁體';

$config->allowedTags = '<p><span><h1><h2><h3><h4><h5><em><u><strong><br><ol><ul><li><img><a><b><font><hr><pre><div><table><td><th><tr><tbody><embed><style>';
$config->accountRule = '|^[a-zA-Z0-9_]{1}[a-zA-Z0-9_\.]{1,}[a-zA-Z0-9_]{1}$|';
$config->checkVersion = true;              // Auto check for new version or not.

/* Set the wide window size and timeout(ms) and duplicate interval time(s). */
$config->wideSize      = 1400;
$config->timeout       = 30000;
$config->duplicateTime = 60;


$config->productCommonList['en'][0]    = 'Product';
$config->productCommonList['en'][1]    = 'Project';
$config->projectCommonList['en'][0]    = 'Project';
$config->projectCommonList['en'][1]    = 'Sprint';
$config->productCommonList['zh-cn'][0] = '产品';
$config->productCommonList['zh-cn'][1] = '项目';
$config->projectCommonList['zh-cn'][0] = '项目';
$config->projectCommonList['zh-cn'][1] = '迭代';
$config->productCommonList['zh-tw'][0] = '產品';
$config->productCommonList['zh-tw'][1] = '項目';
$config->projectCommonList['zh-tw'][0] = '項目';
$config->projectCommonList['zh-tw'][1] = '迭代';

/* Supported charsets. */
$config->charsets['zh-cn']['utf-8'] = 'UTF-8';
$config->charsets['zh-cn']['gbk']   = 'GBK';
$config->charsets['zh-tw']['utf-8'] = 'UTF-8';
$config->charsets['zh-tw']['big5']  = 'BIG5';
$config->charsets['en']['utf-8']    = 'UTF-8';
$config->charsets['en']['GBK']      = 'GBK';

/* IP white list settings.*/
$config->ipWhiteList = '*';

/* 设备类型视图文件前缀。The prefix for view file for different device. */ 
$config->devicePrefix['mhtml'] = 'm.';

/* Default settings. */
$config->default = new stdclass();
$config->default->view   = 'html';        // Default view.
$config->default->lang   = 'en';          // Default language.
$config->default->theme  = 'default';     // Default theme.
$config->default->module = 'index';       // Default module.
$config->default->method = 'index';       // Default method.


/* Master database settings. */
$config->db = new stdclass();
$config->slaveDB = new stdclass();
$config->db->persistant     = false;     // Pconnect or not.
$config->db->driver         = 'mysql';   // Must be MySQL. Don't support other database server yet.
$config->db->encoding       = 'UTF8';    // Encoding of database.
$config->db->strictMode     = false;     // Turn off the strict mode of MySQL.
$config->db->prefix          = '';        // 数据库表名前缀。       The prefix of the table name.
$config->slaveDB->persistant = false;      
$config->slaveDB->driver     = 'mysql';    
$config->slaveDB->encoding   = 'UTF8';     
$config->slaveDB->strictMode = false;      

/* Framework config. */
$config->framework = new stdclass();
$config->framework->autoConnectDB   = true;  // 是否自动连接数据库。              Whether auto connect database or not.
$config->framework->multiLanguage   = true; // 是否启用多语言功能。              Whether enable multi lanuage or not.
$config->framework->multiTheme      = true; // 是否启用多风格功能。              Whether enable multi theme or not.
$config->framework->multiSite       = false; // 是否启用多站点模式。              Whether enable multi site mode or not.
$config->framework->extensionLevel  = 1;     // 0=>无扩展,1=>公共扩展,2=>站点扩展 0=>no extension, 1=> common extension, 2=> every site has it's extension.
$config->framework->jsWithPrefix    = false;  // js::set()输出的时候是否增加前缀。 When us js::set(), add prefix or not.
$config->framework->filterBadKeys   = true;  // 是否过滤不合要求的键值。          Whether filter bad keys or not.
$config->framework->filterTrojan    = true;  // 是否过滤木马攻击代码。            Whether strip trojan code or not.
$config->framework->filterXSS       = true;  // 是否过滤XSS攻击代码。             Whether strip xss code or not.
$config->framework->filterParam     = 2;     // 是否开启过滤参数功能。            Whether strip param or not.
$config->framework->purifier        = true;  // 是否对数据做purifier处理。        Whether purifier data or not.
$config->framework->logDays         = 14;    // 日志文件保存的天数。              The days to save log files.
$config->framework->autoRepairTable = true;
$config->framework->autoLang        = false;

$config->framework->detectDevice['zh-cn'] = true; // 在zh-cn语言情况下，是否启用设备检测功能。 Whether enable device detect or not.
$config->framework->detectDevice['zh-tw'] = true; // 在zh-tw语言情况下，是否启用设备检测功能。 Whether enable device detect or not.
$config->framework->detectDevice['en']    = true; // 在en语言情况下，是否启用设备检测功能。 Whether enable device detect or not.

/* Include the custom config file. */
$configRoot = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$myConfig   = $configRoot . 'my.php';
if(file_exists($myConfig)) include $myConfig;

/* 文件上传设置。 Upload settings. */
$config->file = new stdclass();    
// 危险文件类型。 Dangerous file types.
$config->file->dangers = 'php,php3,php4,phtml,php5,jsp,py,rb,asp,aspx,ashx,asa,cer,cdx,aspl,shtm,shtml,html,htm';
// 允许上传的文件类型。 Allowed file types.
$config->file->allowed = ',txt,doc,docx,dot,wps,wri,pdf,ppt,xls,xlsx,ett,xlt,xlsm,csv,jpg,jpeg,png,psd,gif,ico,bmp,swf,avi,rmvb,rm,mp3,mp4,3gp,flv,mov,movie,rar,zip,bz,bz2,tar,gz,';

/* 配置参数过滤。Filter param settings. */
/* Like $config->filterParam->param[moduleName][methodname][ruleType] = rule. */
$config->filterParam          = new stdclass();
$config->filterParam->badKeys = '[^a-zA-Z0-9_\.]'; 
$config->filterParam->module['reg'] = '/^[a-zA-Z0-9]+$/';
$config->filterParam->method['common']['reg'] = '/^[a-zA-Z0-9]+$/';
$config->filterParam->param['common']['name']['reg']  = '/^[a-zA-Z0-9_\.]+$/';
$config->filterParam->param['common']['value']['reg'] = '/^[a-zA-Z0-9=_,`#+\^\/\.%\|\x7f-\xff]+$/';

$config->filterParam->get['common']['hold'] = 'onlybody,HTTP_X_REQUESTED_WITH,' . $config->sessionVar;
$config->filterParam->get['common']['params']['onlybody']['reg']                = '/^yes$|^no$/';
$config->filterParam->get['common']['params']['HTTP_X_REQUESTED_WITH']['equal'] = 'XMLHttpRequest';
$config->filterParam->get['common']['params'][$config->sessionVar]['reg']       = '/^[a-zA-Z0-9]+$/';

$config->filterParam->cookie['common']['hold'] = 'lang,theme,device,za,zp';
$config->filterParam->cookie['common']['params']['lang']['reg']   = '/^[a-zA-Z\-_]+$/';
$config->filterParam->cookie['common']['params']['theme']['reg']  = '/^[a-zA-Z0-9_]+$/';
$config->filterParam->cookie['common']['params']['device']['reg'] = '/^[a-zA-Z0-9_]+$/';
$config->filterParam->cookie['common']['params']['za']['account'] = '';
$config->filterParam->cookie['common']['params']['zp']['reg']     = '/^[a-f0-9]{40}$/';

/* Set default table prefix. */
if(!isset($config->db->prefix)) $config->db->prefix = 'zt_';

/* Define the tables. */
define('TABLE_COMPANY',       '`' . $config->db->prefix . 'company`');
define('TABLE_DEPT',          '`' . $config->db->prefix . 'dept`');
define('TABLE_CONFIG',        '`' . $config->db->prefix . 'config`');
define('TABLE_USER',          '`' . $config->db->prefix . 'user`');
define('TABLE_TODO',          '`' . $config->db->prefix . 'todo`');
define('TABLE_GROUP',         '`' . $config->db->prefix . 'group`');
define('TABLE_GROUPPRIV',     '`' . $config->db->prefix . 'grouppriv`');
define('TABLE_USERGROUP',     '`' . $config->db->prefix . 'usergroup`');
define('TABLE_USERQUERY',     '`' . $config->db->prefix . 'userquery`');
define('TABLE_USERCONTACT',   '`' . $config->db->prefix . 'usercontact`');

define('TABLE_BUG',           '`' . $config->db->prefix . 'bug`');
define('TABLE_CASE',          '`' . $config->db->prefix . 'case`');
define('TABLE_CASESTEP',      '`' . $config->db->prefix . 'casestep`');
define('TABLE_TESTTASK',      '`' . $config->db->prefix . 'testtask`');
define('TABLE_TESTRUN',       '`' . $config->db->prefix . 'testrun`');
define('TABLE_TESTRESULT',    '`' . $config->db->prefix . 'testresult`');
define('TABLE_USERTPL',       '`' . $config->db->prefix . 'usertpl`');

define('TABLE_PRODUCT',       '`' . $config->db->prefix . 'product`');
define('TABLE_BRANCH',        '`' . $config->db->prefix . 'branch`');
define('TABLE_STORY',         '`' . $config->db->prefix . 'story`');
define('TABLE_STORYSPEC',     '`' . $config->db->prefix . 'storyspec`');
define('TABLE_STORYSTAGE',    '`' . $config->db->prefix . 'storystage`');
define('TABLE_PRODUCTPLAN',   '`' . $config->db->prefix . 'productplan`');
define('TABLE_RELEASE',       '`' . $config->db->prefix . 'release`');

define('TABLE_PROJECT',       '`' . $config->db->prefix . 'project`');
define('TABLE_TASK',          '`' . $config->db->prefix . 'task`');
define('TABLE_TEAM',          '`' . $config->db->prefix . 'team`');
define('TABLE_PROJECTPRODUCT','`' . $config->db->prefix . 'projectproduct`');
define('TABLE_PROJECTSTORY',  '`' . $config->db->prefix . 'projectstory`');
define('TABLE_TASKESTIMATE',  '`' . $config->db->prefix . 'taskestimate`');
define('TABLE_EFFORT',        '`' . $config->db->prefix . 'effort`');
define('TABLE_BURN',          '`' . $config->db->prefix . 'burn`');
define('TABLE_BUILD',         '`' . $config->db->prefix . 'build`');

define('TABLE_DOCLIB',        '`' . $config->db->prefix . 'doclib`');
define('TABLE_DOC',           '`' . $config->db->prefix . 'doc`');

define('TABLE_MODULE',        '`' . $config->db->prefix . 'module`');
define('TABLE_ACTION',        '`' . $config->db->prefix . 'action`');
define('TABLE_FILE',          '`' . $config->db->prefix . 'file`');
define('TABLE_HISTORY',       '`' . $config->db->prefix . 'history`');
define('TABLE_EXTENSION',     '`' . $config->db->prefix . 'extension`');
define('TABLE_CRON',          '`' . $config->db->prefix . 'cron`');
define('TABLE_MAILQUEUE',     '`' . $config->db->prefix . 'mailqueue`');
define('TABLE_BLOCK',         '`' . $config->db->prefix . 'block`');
define('TABLE_DOCCONTENT',    '`' . $config->db->prefix . 'doccontent`');
define('TABLE_TESTSUITE',     '`' . $config->db->prefix . 'testsuite`');
define('TABLE_SUITECASE',     '`' . $config->db->prefix . 'suitecase`');
define('TABLE_TESTREPORT',    '`' . $config->db->prefix . 'testreport`');
if(!defined('TABLE_LANG')) define('TABLE_LANG', '`' . $config->db->prefix . 'lang`');

$config->objectTables['product']     = TABLE_PRODUCT;
$config->objectTables['story']       = TABLE_STORY;
$config->objectTables['productplan'] = TABLE_PRODUCTPLAN;
$config->objectTables['release']     = TABLE_RELEASE;
$config->objectTables['project']     = TABLE_PROJECT;
$config->objectTables['task']        = TABLE_TASK;
$config->objectTables['build']       = TABLE_BUILD;
$config->objectTables['bug']         = TABLE_BUG;
$config->objectTables['case']        = TABLE_CASE;
$config->objectTables['testcase']    = TABLE_CASE;
$config->objectTables['testtask']    = TABLE_TESTTASK;
$config->objectTables['testsuite']   = TABLE_TESTSUITE;
$config->objectTables['testreport']  = TABLE_TESTREPORT;
$config->objectTables['user']        = TABLE_USER;
$config->objectTables['doc']         = TABLE_DOC;
$config->objectTables['doclib']      = TABLE_DOCLIB;
$config->objectTables['todo']        = TABLE_TODO;
$config->objectTables['custom']      = TABLE_LANG;
$config->objectTables['branch']      = TABLE_BRANCH;
$config->objectTables['module']      = TABLE_MODULE;
$config->objectTables['caselib']     = TABLE_TESTSUITE;

/* Include extension config files. */
$extConfigFiles = glob($configRoot . 'ext/*.php');
if($extConfigFiles) foreach($extConfigFiles as $extConfigFile) include $extConfigFile;
