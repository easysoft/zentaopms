<?php
/**
 * The upgrade router file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: upgrade.php 4677 2013-04-26 06:23:58Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
/* Judge my.php exists or not. */
define('IN_UPGRADE', true);
$dbConfig = dirname(dirname(__FILE__)) . '/config/db.php';
if(file_exists($dbConfig))
{
    $myConfig = dirname(dirname(__FILE__)) . '/config/my.php';
    if(file_exists($myConfig))
    {
        $myContent = trim(file_get_contents($myConfig));
        $myContent = str_replace('<?php', '', $myContent);
    }

    if(!@rename($dbConfig, $myConfig))
    {
        $configDir = dirname(dirname(__FILE__)) . '/config/';
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
if(($config->version{0} == $config->installedVersion{0} or (is_numeric($config->version{0}) and is_numeric($config->installedVersion{0}))) and version_compare($config->version, $config->installedVersion) <= 0) die(header('location: index.php'));

/* Run it. */
$app->parseRequest();
$common->checkUpgradeStatus();
$app->loadModule();
