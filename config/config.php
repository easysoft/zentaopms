<?php
/**
 * ZenTaoPHP的config文件。如果更改配置，不要直接修改该文件，复制到my.php修改相应的值。
 * The config file of zentaophp.  Don't modify this file directly, copy the item to my.php and change it.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/* 保证在命令行环境也能运行。Make sure to run in ztcli env. */
if(!class_exists('config')){class config{}}
if(!function_exists('getWebRoot')){function getWebRoot(){}}

/* 基本设置。Basic settings. */
$config->version       = '20.0.alpha1';        // ZenTaoPHP的版本。 The version of ZenTaoPHP. Don't change it.
$config->liteVersion   = '1.2';                // 迅捷版版本。      The version of Lite.
$config->charset       = 'UTF-8';              // ZenTaoPHP的编码。 The encoding of ZenTaoPHP.
$config->cookieLife    = time() + 2592000;     // Cookie的生存时间。The cookie life time.
$config->timezone      = 'Asia/Shanghai';      // 时区设置。        The time zone setting, for more see http://www.php.net/manual/en/timezones.php.
$config->webRoot       = '';                   // URL根目录。       The root path of the url.
$config->customSession = false;                // 是否开启自定义session的存储路径。Whether custom the session save path.
$config->edition       = 'open';               // 设置系统的edition，可选值：open|biz|max。Set edition, optional: open|biz|max.
$config->tabSession    = false;                // 是否开启浏览器新标签独立session.

/* 框架路由相关设置。Routing settings. */
$config->requestType = 'PATH_INFO';               // 请求类型：PATH_INFO|PATHINFO2|GET。    The request type: PATH_INFO|PATH_INFO2|GET.
$config->requestFix  = '-';                       // PATH_INFO和PATH_INFO2模式的分隔符。    The divider in the url when PATH_INFO|PATH_INFO2.
$config->moduleVar   = 'm';                       // 请求类型为GET：模块变量名。            requestType=GET: the module var name.
$config->methodVar   = 'f';                       // 请求类型为GET：模块变量名。            requestType=GET: the method var name.
$config->viewVar     = 't';                       // 请求类型为GET：视图变量名。            requestType=GET: the view var name.
$config->sessionVar  = 'zentaosid';               // 请求类型为GET：session变量名。         requestType=GET: the session var name.
$config->views       = ',html,json,mhtml,xhtml,'; // 支持的视图类型。                       Supported view formats.
$config->visions     = ',rnd,lite,or,';           // 支持的界面类型。                       Supported vision formats.

/* 支持的主题和语言。Supported themes and languages. */
$config->themes['default'] = 'default';
$config->langs['zh-cn']    = '简体';
$config->langs['zh-tw']    = '繁體';
$config->langs['en']       = 'English';
$config->langs['de']       = 'Deutsch';
$config->langs['fr']       = 'Français';
//$config->langs['vi']       = 'Tiếng Việt';
//$config->langs['ja']       = '日本語';

/* 设备类型视图文件前缀。The prefix for view file for different device. */
$config->devicePrefix['mhtml'] = '';
$config->devicePrefix['xhtml'] = 'x.';

/* 默认值设置。Default settings. */
$config->default = new stdclass();
$config->default->view   = 'html';        //默认视图。 Default view.
$config->default->lang   = 'en';          //默认语言。 Default language.
$config->default->theme  = 'default';     //默认主题。 Default theme.
$config->default->module = 'index';       //默认模块。 Default module.
$config->default->method = 'index';       //默认方法。 Default method.

/* 数据库设置。Database settings. */
$config->db = new stdclass();
$config->slaveDB = new stdclass();
$config->db->persistent      = false;     // 是否为持续连接。       Pconnect or not.
$config->db->driver          = 'mysql';   // 目前只支持MySQL数据库。Must be MySQL. Don't support other database server yet.
$config->db->encoding        = 'UTF8';    // 数据库编码。           Encoding of database.
$config->db->strictMode      = true;      // 默认开启MySQL的严格模式。  Turn on the strict mode of MySQL.
$config->db->prefix          = 'zt_';     // 数据库表名前缀。       The prefix of the table name.
$config->db->enableSqlite    = false;     // 是否启用SQLite         Enable SQLite or not.
$config->slaveDBList         = array();   // 支持多个从库。         Support multiple slave dbs.

/* 可用域名后缀列表。Domain postfix lists. */
$config->domainPostfix  = "|com|com.cn|com.hk|com.tw|com.vc|edu.cn|es|";
$config->domainPostfix .= "|eu|fm|gov.cn|gs|hk|im|in|info|jp|kr|la|me|";
$config->domainPostfix .= "|mobi|my|name|net|net.cn|org|org.cn|pk|pro|";
$config->domainPostfix .= "|sg|so|tel|tk|to|travel|tv|tw|uk|us|ws|";
$config->domainPostfix .= "|ac.cn|bj.cn|sh.cn|tj.cn|cq.cn|he.cn|sn.cn|";
$config->domainPostfix .= "|sx.cn|nm.cn|ln.cn|jl.cn|hl.cn|js.cn|zj.cn|";
$config->domainPostfix .= "|ah.cn|fj.cn|jx.cn|sd.cn|ha.cn|hb.cn|hn.cn|";
$config->domainPostfix .= "|gd.cn|gx.cn|hi.cn|sc.cn|gz.cn|yn.cn|gs.cn|pub|pw|";
$config->domainPostfix .= "|qh.cn|nx.cn|xj.cn|tw.cn|hk.cn|mo.cn|xz.cn|xyz|wang|";
$config->domainPostfix .= "|ae|asia|biz|cc|cd|cm|cn|co|co.jp|co.kr|co.uk|";
$config->domainPostfix .= "|top|ren|club|space|tm|website|cool|company|city|email|";
$config->domainPostfix .= "|market|software|ninja|bike|today|life|co.il|io|";
$config->domainPostfix .= "|mn|ph|ps|tl|uz|vn|co.nz|cz|gg|gl|gr|je|md|me.uk|org.uk|pl|si|sx|vg|ag|";
$config->domainPostfix .= "|bz|cl|ec|gd|gy|ht|lc|ms|mx|pe|tc|vc|ac|bi|mg|mu|sc|as|com.sb|cx|ki|nf|sh|";
$config->domainPostfix .= "|rocks|social|co.com|bio|reviews|link|sexy|us.com|consulting|moda|desi|";
$config->domainPostfix .= "|menu|info|events|webcam|dating|vacations|flights|cruises|global|ca|guru|";
$config->domainPostfix .= "|futbol|rentals|dance|lawyer|attorney|democrat|republican|actor|condos|immobilien|";
$config->domainPostfix .= "|villas|foundation|expert|works|tools|watch|zone|bargains|agency|best|solar|";
$config->domainPostfix .= "|farm|pics|photo|marketing|holiday|gift|buzz|guitars|trade|construction|";
$config->domainPostfix .= "|international|house|coffee|florist|rich|ceo|camp|education|repair|win|site|";

/* Config for Content-Security-Policy. */
$config->CSPs = array();
$config->CSPs[] = "form-action 'self';connect-src 'self'";

/* Config for kanban col setting */
$config->colWidth    = 264;
$config->minColWidth = 264;
$config->maxColWidth = 384;

/* 系统框架配置。Framework settings. */
$config->framework = new stdclass();
$config->framework->autoConnectDB   = true;  // 是否自动连接数据库。              Whether auto connect database or not.
$config->framework->multiLanguage   = true; // 是否启用多语言功能。              Whether enable multi language or not.
$config->framework->multiTheme      = true; // 是否启用多风格功能。              Whether enable multi theme or not.
$config->framework->multiSite       = false; // 是否启用多站点模式。              Whether enable multi site mode or not.
$config->framework->extensionLevel  = 1;     // 0=>无扩展,1=>公共扩展,2=>站点扩展 0=>no extension, 1=> common extension, 2=> every site has it's extension.
$config->framework->jsWithPrefix    = false;  // js::set()输出的时候是否增加前缀。 When us js::set(), add prefix or not.
$config->framework->filterBadKeys   = true;  // 是否过滤不合要求的键值。          Whether filter bad keys or not.
$config->framework->filterTrojan    = true;  // 是否过滤木马攻击代码。            Whether strip trojan code or not.
$config->framework->filterXSS       = true;  // 是否过滤XSS攻击代码。             Whether strip xss code or not.
$config->framework->filterParam     = 2;     // 1=>默认过滤，2=>开启过滤参数功能。0=>default filter 2=>Whether strip param.
$config->framework->purifier        = true;  // 是否对数据做purifier处理。        Whether purifier data or not.
$config->framework->logDays         = 14;    // 日志文件保存的天数。              The days to save log files.
$config->framework->autoRepairTable = true;
$config->framework->autoLang        = false;
$config->framework->filterCSRF      = true;
$config->framework->setCookieSecure = true;
$config->framework->sendXCTO        = true;   // Send X-Content-Type-Options header.
$config->framework->sendXXP         = true;   // Send X-XSS-Protection header.
$config->framework->sendHSTS        = true;   // Send HTTP Strict Transport Security header.
$config->framework->sendRP          = true;   // Send Referrer-Policy header.
$config->framework->sendXPCDP       = true;   // Send X-Permitted-Cross-Domain-Policies header.
$config->framework->sendXDO         = true;   // Send X-Download-Options header.

$config->framework->detectDevice['zh-cn'] = true; // 在zh-cn语言情况下，是否启用设备检测功能。 Whether enable device detect or not.
$config->framework->detectDevice['zh-tw'] = true; // 在zh-tw语言情况下，是否启用设备检测功能。 Whether enable device detect or not.
$config->framework->detectDevice['en']    = true; // 在en语言情况下，是否启用设备检测功能。    Whether enable device detect or not.
$config->framework->detectDevice['de']    = true; // 在de语言情况下，是否启用设备检测功能。    Whether enable device detect or not.
$config->framework->detectDevice['fr']    = true; // 在fr语言情况下，是否启用设备检测功能。    Whether enable device detect or not.
$config->framework->detectDevice['vi']    = true; // 在vi语言情况下，是否启用设备检测功能。    Whether enable device detect or not.

/* IP white list settings.*/
$config->ipWhiteList   = '*';
$config->xFrameOptions = 'SAMEORIGIN';

/* Switch for zentao features. */
$config->features = new stdclass();
$config->features->apiGetModel    = false;
$config->features->apiSQL         = false;
$config->features->cronSystemCall = false;
$config->features->checkClient    = true;

/* 文件上传设置。 Upload settings. */
$config->file = new stdclass();
$config->file->dangers     = 'php,php3,php4,phtml,php5,jsp,py,rb,asp,aspx,ashx,asa,cer,cdx,aspl,shtm,shtml,html,htm';
$config->file->allowed     = 'txt,doc,docx,dot,wps,wri,pdf,ppt,pptx,xls,xlsx,ett,xlt,xlsm,csv,jpg,jpeg,png,psd,gif,ico,bmp,swf,avi,rmvb,rm,mp3,mp4,3gp,flv,mov,movie,rar,zip,bz,bz2,tar,gz,mpp,rp,pdm,vsdx,vsd,sql';
$config->file->storageType = 'fs';         // fs or s3

/* Upload settings. */
$config->allowedTags = '<p><span><h1><h2><h3><h4><h5><em><u><strong><br><ol><ul><li><img><a><b><font><hr><pre><div><table><td><th><tr><tbody><embed><style><s>';
$config->accountRule = '|^[a-zA-Z0-9_]{1}[a-zA-Z0-9_\.]{1,}[a-zA-Z0-9_]{1}$|';
$config->checkVersion = true;              // Auto check for new version or not.

/* Set the wide window size and timeout(ms) and duplicate interval time(s). */
$config->wideSize      = 1400;
$config->timeout       = 30000;
$config->duplicateTime = 30;
$config->maxCount      = 500;
$config->moreLinks     = array();

/* 渠成平台设置。CNE Api settings. */
$config->inQuickon    = getenv('IN_QUICKON');
$config->inContainer  = getenv('IN_CONTAINER');
$config->k8space      = 'quickon-system';
$config->demoAccounts = '';  // 用于演示的账号列表，该账号安装的应用30钟后会自动删除。 In account list for demo, app instance of demo will be removed in 30 minutes.
$config->demoAppLife  = 30; // Demo安装的应用实例存续时长(分钟)。The minutes life of instance which demo account installed.
$config->CNE = new stdclass();
$config->CNE->api = new stdclass();
$config->CNE->api->host    = getenv('CNE_API_HOST');
$config->CNE->api->auth    = 'X-Auth-Token';
$config->CNE->api->token   = getenv('CNE_API_TOKEN'); // Please set token in my.php.
$config->CNE->api->headers = array('Content-Type: application/json');
$config->CNE->api->channel = 'stable';

$config->CNE->app = new stdclass;
$config->CNE->app->domain = 'dev.haogs.cn';

$config->cloud = new stdclass;
$config->cloud->api = new stdclass;
$config->cloud->api->host          = getenv('CLOUD_API_HOST');
$config->cloud->api->auth          = 'X-Auth-Token';
$config->cloud->api->token         = getenv('CLOUD_API_TOKEN'); // Please set token in my.php.
$config->cloud->api->headers       = array('Content-Type: application/json');
$config->cloud->api->channel       = getenv('CLOUD_DEFAULT_CHANNEL') ? getenv('CLOUD_DEFAULT_CHANNEL') : 'stable';
$config->cloud->api->switchChannel = false;

/* 配置参数过滤。Filter param settings. */
$filterConfig = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'filter.php';
if(file_exists($filterConfig)) include $filterConfig;

/* 引用数据库的配置。 Include the database config file. */
$dbConfig = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'db.php';
if(file_exists($dbConfig)) include $dbConfig;

/* 引用缓存的配置。 Include the cache config file. */
$cacheConfig = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache.php';
if(file_exists($cacheConfig)) include $cacheConfig;

/* 读取环境变量的配置。 Read the env config. */
if($config->inContainer || $config->inQuickon)
{
    $webRoot = getenv('ZT_WEB_ROOT') ? trim(getenv('ZT_WEB_ROOT'), '/') : '';
    $config->installed     = (bool)getenv('ZT_INSTALLED');
    $config->debug         = (int)getenv('ZT_DEBUG');
    $config->requestType   = getenv('ZT_REQUEST_TYPE');
    $config->timezone      = getenv('ZT_TIMEZONE');
    $config->db->driver    = getenv('ZT_DB_DRIVER');
    $config->db->host      = getenv('ZT_DB_HOST');
    $config->db->port      = getenv('ZT_DB_PORT');
    $config->db->name      = getenv('ZT_DB_NAME');
    $config->db->user      = getenv('ZT_DB_USER');
    $config->db->encoding  = getenv('ZT_DB_ENCODING');
    $config->db->password  = getenv('ZT_DB_PASSWORD');
    $config->db->prefix    = getenv('ZT_DB_PREFIX');
    $config->webRoot       = $webRoot ? "/{$webRoot}/" : '/';
    $config->default->lang = getenv('ZT_DEFAULT_LANG');
}

/* 引用自定义的配置。 Include the custom config file. */
$myConfigRoot = (defined('RUN_MODE') and RUN_MODE == 'test') ? dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'config' : dirname(__FILE__);
$myConfig = $myConfigRoot . DIRECTORY_SEPARATOR . 'my.php';
if(file_exists($myConfig)) include $myConfig;

/* 禅道配置文件。zentaopms settings. */
$zentaopmsConfig = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'zentaopms.php';
if(file_exists($zentaopmsConfig)) include $zentaopmsConfig;

/* 数据表格操作配置文件。dtable actions settings. */
$actionsMapConfig = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'actionsmap.php';
if(file_exists($actionsMapConfig)) include $actionsMapConfig;

/* API路由配置。API route settings. */
$routesConfig = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'routes.php';
if(file_exists($routesConfig)) include $routesConfig;

/* Include extension config files. */
$extConfigFiles = glob(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ext/*.php');
if($extConfigFiles) foreach($extConfigFiles as $extConfigFile) include $extConfigFile;

/* Set version. */
if($config->edition != 'open')
{
    $config->version = $config->edition . $config->{$config->edition . 'Version'};
    if($config->edition != 'max') unset($config->maxVersion);
    if($config->edition != 'ipd') unset($config->ipdVersion);
}
else
{
    unset($config->bizVersion);
    unset($config->maxVersion);
    unset($config->ipdVersion);
}
