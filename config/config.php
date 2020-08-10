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
$config->version    = '12.4.1';             // ZenTaoPHP的版本。 The version of ZenTaoPHP. Don't change it.
$config->charset    = 'UTF-8';              // ZenTaoPHP的编码。 The encoding of ZenTaoPHP.
$config->cookieLife = time() + 2592000;     // Cookie的生存时间。The cookie life time.
$config->timezone   = 'Asia/Shanghai';      // 时区设置。        The time zone setting, for more see http://www.php.net/manual/en/timezones.php.
$config->webRoot    = '';                   // URL根目录。       The root path of the url.

/* 框架路由相关设置。Routing settings. */
$config->requestType = 'PATH_INFO';         // 请求类型：PATH_INFO|PATHINFO2|GET。    The request type: PATH_INFO|PATH_INFO2|GET.
$config->requestFix  = '-';                 // PATH_INFO和PATH_INFO2模式的分隔符。    The divider in the url when PATH_INFO|PATH_INFO2.
$config->moduleVar   = 'm';                 // 请求类型为GET：模块变量名。            requestType=GET: the module var name.
$config->methodVar   = 'f';                 // 请求类型为GET：模块变量名。            requestType=GET: the method var name.
$config->viewVar     = 't';                 // 请求类型为GET：视图变量名。            requestType=GET: the view var name.
$config->sessionVar  = 'zentaosid';         // 请求类型为GET：session变量名。         requestType=GET: the session var name.
$config->views       = ',html,json,mhtml,xhtml,'; // 支持的视图类型。                       Supported view formats.

/* 支持的主题和语言。Supported thems and languages. */
$config->themes['default'] = 'default';
$config->langs['zh-cn']    = '简体';
$config->langs['zh-tw']    = '繁體';
$config->langs['en']       = 'English';
$config->langs['de']       = 'Deutsch';
$config->langs['fr']       = 'Français';
$config->langs['vi']       = 'Tiếng Việt';

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
$config->db->persistant      = false;     // 是否为持续连接。       Pconnect or not.
$config->db->driver          = 'mysql';   // 目前只支持MySQL数据库。Must be MySQL. Don't support other database server yet.
$config->db->encoding        = 'UTF8';    // 数据库编码。           Encoding of database.
$config->db->strictMode      = false;     // 关闭MySQL的严格模式。  Turn off the strict mode of MySQL.
$config->db->prefix          = 'zt_';     // 数据库表名前缀。       The prefix of the table name.
$config->slaveDB->persistant = false;
$config->slaveDB->driver     = 'mysql';
$config->slaveDB->encoding   = 'UTF8';
$config->slaveDB->strictMode = false;

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

/* 系统框架配置。Framework settings. */
$config->framework = new stdclass();
$config->framework->autoConnectDB  = true;  // 是否自动连接数据库。              Whether auto connect database or not.
$config->framework->multiLanguage  = true; // 是否启用多语言功能。              Whether enable multi lanuage or not.
$config->framework->multiTheme     = true; // 是否启用多风格功能。              Whether enable multi theme or not.
$config->framework->multiSite      = false; // 是否启用多站点模式。              Whether enable multi site mode or not.
$config->framework->extensionLevel = 1;     // 0=>无扩展,1=>公共扩展,2=>站点扩展 0=>no extension, 1=> common extension, 2=> every site has it's extension.
$config->framework->jsWithPrefix   = false;  // js::set()输出的时候是否增加前缀。 When us js::set(), add prefix or not.
$config->framework->filterBadKeys  = true;  // 是否过滤不合要求的键值。          Whether filter bad keys or not.
$config->framework->filterTrojan   = true;  // 是否过滤木马攻击代码。            Whether strip trojan code or not.
$config->framework->filterXSS      = true;  // 是否过滤XSS攻击代码。             Whether strip xss code or not.
$config->framework->filterParam    = 2;     // 1=>默认过滤，2=>开启过滤参数功能。0=>default filter 2=>Whether strip param.
$config->framework->purifier       = true;  // 是否对数据做purifier处理。        Whether purifier data or not.
$config->framework->logDays        = 14;    // 日志文件保存的天数。              The days to save log files.

$config->framework->detectDevice['zh-cn'] = true; // 在zh-cn语言情况下，是否启用设备检测功能。 Whether enable device detect or not.
$config->framework->detectDevice['zh-tw'] = true; // 在zh-tw语言情况下，是否启用设备检测功能。 Whether enable device detect or not.
$config->framework->detectDevice['en']    = true; // 在en语言情况下，是否启用设备检测功能。    Whether enable device detect or not.
$config->framework->detectDevice['de']    = true; // 在en语言情况下，是否启用设备检测功能。    Whether enable device detect or not.
$config->framework->detectDevice['fr']    = true; // 在en语言情况下，是否启用设备检测功能。    Whether enable device detect or not.
$config->framework->detectDevice['vi']    = true; // 在en语言情况下，是否启用设备检测功能。    Whether enable device detect or not.

/* 文件上传设置。 Upload settings. */
$config->file = new stdclass();
$config->file->dangers = 'php,php3,php4,phtml,php5,jsp,py,rb,asp,aspx,ashx,asa,cer,cdx,aspl,shtm,shtml,html,htm';
$config->file->allowed = 'txt,doc,docx,dot,wps,wri,pdf,ppt,pptx,xls,xlsx,ett,xlt,xlsm,csv,jpg,jpeg,png,psd,gif,ico,bmp,swf,avi,rmvb,rm,mp3,mp4,3gp,flv,mov,movie,rar,zip,bz,bz2,tar,gz,mpp,rp,pdm,vsdx,vsd,sql';

/* 配置参数过滤。Filter param settings. */
$filterConfig = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'filter.php';
if(file_exists($filterConfig)) include $filterConfig;

/* 引用数据库的配置。 Include the database config file. */
$dbConfig = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'db.php';
if(file_exists($dbConfig)) include $dbConfig;

/* 引用自定义的配置。 Include the custom config file. */
$myConfig = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'my.php';
if(file_exists($myConfig)) include $myConfig;

/* 禅道配置文件。zentaopms settings. */
$zentaopmsConfig = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'zentaopms.php';
if(file_exists($zentaopmsConfig)) include $zentaopmsConfig;

/* Include extension config files. */
$extConfigFiles = glob(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ext/*.php');
if($extConfigFiles) foreach($extConfigFiles as $extConfigFile) include $extConfigFile;
