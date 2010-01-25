<?php
/**
 * The config file of ZenTaoMS
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *                                                                             
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     ZenTaoMS
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
$config->version     = '0.3 beta';        // 版本号，切勿修改。
$config->installed   = false;             // 是否已经安装。手工安装，需要修改此参数为true。 
$config->debug       = true;              // 是否打开debug功能。
$config->webRoot     = '/';               // web网站的根目录。
$config->encoding    = 'UTF-8';           // 网站的编码。
$config->cookiePath  = '/';               // cookie的有效路径。
$config->cookieLife  = time() + 2592000;  // cookie的生命周期。

$config->requestType = 'PATH_INFO';       // 如何获取当前请求的信息，可选值：PATH_INFO|GET
$config->pathType    = 'clean';           // requestType=PATH_INFO: 请求url的格式，可选值为full|clean，full格式会带有参数名称，clean则只有取值。
$config->strictParams= false;             // 传递参数的名称是否与方法定义中名称完全一致。如果设为false，则需要保证顺序一致。
$config->requestFix  = '-';               // requestType=PATH_INFO: 请求url的分隔符，可选值为斜线、下划线、减号。后面两种形式有助于SEO。
$config->moduleVar   = 'm';               // requestType=GET: 模块变量名。
$config->methodVar   = 'f';               // requestType=GET: 方法变量名。
$config->viewVar     = 't';               // requestType=GET: 模板变量名。

$config->views       = ',html,xml,json,txt,csv,doc,pdf,'; // 支持的视图列表。
$config->langs       = 'zh-cn,zh-tw,zh-hk,en';            // 支持的语言列表。
$config->themes      = 'default';                         // 支持的主题列表。

$config->super2OBJ   = true;    // 是否通过对象来访问全局变量。

$config->default->view   = 'html';                      // 默认的视图格式。
$config->default->lang   = 'zh-cn';                     // 默认的语言。
$config->default->theme  = 'default';                   // 默认的主题。
$config->default->module = 'index';                     // 默认的模块。当请求中没有指定模块时，加载该模块。
$config->default->method = 'index';                     // 默认的方法。当请求中没有指定方法或者指定的方法不存在时，调用该方法。
$config->default->domain = 'pms.easysoft.com';          // 默认的域名，当请求中的域名没有对应的记录时，使用此默认域名对应的公司信息。

$config->file->dangers = 'php,jsp,py,rb,asp,';          // 不允许上传的文件类型列表。
$config->file->maxSize = 1024 * 1024;                   // 允许上传的文件大小，单位为字节。

$config->db->persistant = false;                        // 是否打开持久连接。
$config->db->driver     = 'mysql';                      // pdo的驱动类型，目前暂时只支持mysql。
$config->db->host       = '127.0.0.1';                  // mysql主机。
$config->db->port       = '3306';                       // mysql主机端口号。
$config->db->name       = 'zentao';                     // 数据库名称。
$config->db->user       = 'root';                       // 数据库用户名。
$config->db->password   = '';                           // 密码。
$config->db->encoding   = 'UTF8';                       // 数据库的编码。
$config->db->prefix     = 'zt_';                        // 数据表前缀。
$config->db->dao        = true;                         // 是否使用DAO。

/* 包含自定义配置文件。*/
$myConfig = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'my.php';
if(file_exists($myConfig)) include $myConfig;

/* 数据表的定义。*/
define('TABLE_ACTION',         $config->db->prefix . 'action');
define('TABLE_BUG',            $config->db->prefix . 'bug');
define('TABLE_BUILD',          $config->db->prefix . 'build');
define('TABLE_CASE',           $config->db->prefix . 'case');
define('TABLE_CASERESULT',     $config->db->prefix . 'caseResult');
define('TABLE_CASESTEP',       $config->db->prefix . 'caseStep');
define('TABLE_COMPANY',        $config->db->prefix . 'company');
define('TABLE_CONFIG',         $config->db->prefix . 'config');
define('TABLE_DEPT',           $config->db->prefix . 'dept');
define('TABLE_EFFORT',         $config->db->prefix . 'effort');
define('TABLE_FILE',           $config->db->prefix . 'file');
define('TABLE_HISTORY',        $config->db->prefix . 'history');
define('TABLE_MODULE',         $config->db->prefix . 'module');
define('TABLE_USER',           $config->db->prefix . 'user');
define('TABLE_GROUP',          $config->db->prefix . 'group');
define('TABLE_USERGROUP',      $config->db->prefix . 'userGroup');
define('TABLE_GROUPPRIV',      $config->db->prefix . 'groupPriv');
define('TABLE_PLANCASE',       $config->db->prefix . 'planCase');
define('TABLE_PRODUCT',        $config->db->prefix . 'product');
define('TABLE_PRODUCTPLAN',    $config->db->prefix . 'productPlan');
define('TABLE_RELEASE',        $config->db->prefix . 'release');
define('TABLE_RELEATION',      $config->db->prefix . 'releation');
define('TABLE_RESULTSTEP',     $config->db->prefix . 'resultStep');
define('TABLE_PROJECT',        $config->db->prefix . 'project');
define('TABLE_TEAM',           $config->db->prefix . 'team');
define('TABLE_STORY',          $config->db->prefix . 'story');
define('TABLE_PROJECTSTORY',   $config->db->prefix . 'projectStory');
define('TABLE_TASK',           $config->db->prefix . 'task');
define('TABLE_TASKESTIMATE',   $config->db->prefix . 'taskEstimate');
define('TABLE_TESTPLAN',       $config->db->prefix . 'testPlan');
define('TABLE_PROJECTPRODUCT', $config->db->prefix . 'projectProduct');
define('TABLE_TODO',           $config->db->prefix . 'todo');
define('TABLE_BURN',           $config->db->prefix . 'burn');
