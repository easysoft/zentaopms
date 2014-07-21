<?php
/**
 * The upgrade router file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: upgrade.php 4677 2013-04-26 06:23:58Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
/* Judge my.php exists or not. */
define('IN_UPGRADE', true);
$myConfig = dirname(dirname(__FILE__)) . '/config/my.php';
if(!file_exists($myConfig))
{
    echo "文件" . $myConfig . "不存在！ 提示：不要重命名原来的禅道安装目录，下载最新的源码包，覆盖即可。" . "<br />";
    echo $myConfig . " doesn't exists! Please don't rename zentao before overriding the source code!";
    exit;
}

error_reporting(0);

/* Load the framework. */
include '../framework/router.class.php';
include '../framework/control.class.php';
include '../framework/model.class.php';
include '../framework/helper.class.php';

/* Instance the app. */
$app = router::createApp('pms', dirname(dirname(__FILE__)));
$common = $app->loadCommon();

/* Reset the config params to make sure the install program will be lauched. */
$config->set('requestType', 'GET');
$config->set('default.module', 'upgrade');
$app->setDebug();

/* Check the installed version is the latest or not. */
$config->installedVersion = $common->loadModel('setting')->getVersion();
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) and version_compare($config->version, $config->installedVersion) <= 0) die(header('location: index.php'));

/* Run it. */
$app->parseRequest();
$app->loadModule();
