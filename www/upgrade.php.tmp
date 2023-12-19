<?php
/**
 * The upgrade router file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: upgrade.php 4677 2013-04-26 06:23:58Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
/* Judge my.php exists or not. */
define('IN_UPGRADE', true);
$basePath = dirname(dirname(__FILE__));
$dbConfig = $basePath . '/config/db.php';
$myConfig = $basePath . '/config/my.php';
if(file_exists($dbConfig))
{
    if(file_exists($myConfig))
    {
        $myContent = trim(file_get_contents($myConfig));
        $myContent = str_replace('<?php', '', $myContent);
    }

    if(!@rename($dbConfig, $myConfig))
    {
        $configDir = $basePath . '/config/';
        echo "请执行命令 chmod 777 $configDir 来修改权限，保证禅道在该目录有操作文件权限" . "<br />";
        echo "Please execute the command 'chmod 777 $configDir' to modify the permissions to ensure that the ZenTao has operating file permissions in this directory";
        exit;
    }

    if(!empty($myContent))
    {
        $myContent = file_get_contents($myConfig) . "\n" . $myContent;
        file_put_contents($myConfig, $myContent);
    }
}
if(!file_exists($myConfig)) die(header('location: install.php'));
if(file_exists("{$basePath}/config/ext/secret.php") and !unlink("{$basePath}/config/ext/secret.php"))
{
    echo "请删除文件 {$basePath}/config/ext/secret.php，后刷新页面<br />";
    echo "Please delete {$basePath}/config/ext/secret.php and refresh.";
    exit;
}

error_reporting(0);

/* Load the framework. */
include '../framework/router.class.php';
include '../framework/control.class.php';
include '../framework/model.class.php';
include '../framework/helper.class.php';

/* Instance the app. */
$app = router::createApp('pms', dirname(dirname(__FILE__)), 'router');
$common = $app->loadCommon();

/* Reset the config params to make sure the install program will be lauched. */
$config->set('requestType', 'GET');
$config->set('default.module', 'upgrade');
$app->setDebug();

/* Check the installed version is the latest or not. */
$config->installedVersion = $common->loadModel('setting')->getVersion();
if(!$app->session->upgrading && ($config->version[0] == $config->installedVersion[0] or (is_numeric($config->version[0]) and is_numeric($config->installedVersion[0]))) and version_compare($config->version, $config->installedVersion) <= 0) die(header('location: index.php'));

/* Upgrade to latest version if it can be upgraded automatically. */
if($app->canAutoUpgrade())
{
    $upgradeModel = $common->loadModel('upgrade');
    $alterSQL     = $upgradeModel->checkConsistency();
    if(!empty($alterSQL)) $upgradeModel->dao->query("SET @@sql_mode= '';" . $alterSQL);

    $config->set('default.method', 'execute');
    $app->session->set('upgrading', true);
    $app->session->set('step', '');
    $app->post->set('fromVersion', str_replace( '.', '_', strtolower($config->installedVersion)));
}

/* Run it. */
$app->parseRequest();
if($common->checkUpgradeStatus()) $app->loadModule();
