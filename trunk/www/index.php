<?php
/**
 * The router file of ZenTaoMS.
 *
 * All request should be routed by this router.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

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
error_reporting(E_ALL);

/* 记录最开始的时间。*/
$timeStart = _getTime();

/* 包含必须的类文件。*/
include '../../../framework/router.class.php';
include '../../../framework/control.class.php';
include '../../../framework/model.class.php';
include '../../../framework/helper.class.php';
include './myrouter.class.php';

/* 实例化路由对象，加载配置，连接到数据库。*/
$app    = router::createApp('pms', '', 'myRouter');
$config = $app->loadConfig('common');
$dbh    = $app->connectDB();
setRevision();

/* 设置客户端所使用的语言、风格。*/
$app->setClientLang();
$app->setClientTheme();

/* 加载语言文件，加载公共模块。*/
$lang   = $app->loadLang('common');
$common = $app->loadCommon();

/* 加载相应的lib文件，并设置超全局变量的引用。*/
$app->loadClass('front',  $static = true);
$app->loadClass('filter', $static = true);
$app->setSuperVars();

/* 如果是debug模式，记录sql查询。*/
if($config->debug) register_shutdown_function('_saveQuery');

/* 处理请求，验证权限，加载相应的模块。*/
$app->parseRequest();
$common->checkPriv();
$app->loadModule();

/* Debug信息，监控页面的执行时间和内存占用。*/
$timeUsed = round(_getTime() - $timeStart, 4) * 1000;
$memory   = round(memory_get_peak_usage() / 1024, 1);

if(!$config->debug) exit;
$querys = count(dao::$querys);

echo <<<EOT
<div>
<strong>TIME</strong>: $timeUsed ms,
<strong>MEM</strong>: $memory KB,
<strong>SQL</strong>: $querys. 
</div>
EOT;

/* 获取系统时间，微秒为单位。*/
function _getTime()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

/* 保存query记录。*/
function _saveQuery()
{
    global $app;
    $fh = fopen('/tmp/zentao.log', 'a');
    fwrite($fh, date('Ymd H:i:s') . ": " . $app->getURI() . "\n");
    foreach(dao::$querys as $query) fwrite($fh, "  $query\n");
    fwrite($fh, "\n");
    fclose($fh);
}

/* print_r。*/
function a($var)
{
    echo "<xmp>";
    print_r($var);
    echo "</xmp>";
}

/* 设置svn版本号。*/
function setRevision()
{
    global $config;
    $revisionTxt = dirname(dirname(__FILE__)) . '/cache/revision.txt';
    if(file_exists($revisionTxt))
    {
        list($revision, $date) = file($revisionTxt);
        $config->set('svn.revision', $revision);
        $config->set('svn.lastDate', $date);
    }
    else
    {
        $config->set('svn.revision', '');
        $config->set('svn.lastDate', '');
    }
}
