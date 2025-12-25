<?php
/**
 * The router file of ZenTaoPMS.
 *
 * All request should be routed by this router.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.php 5036 2013-07-06 05:26:44Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
/* Set the error reporting. */
error_reporting(E_ALL);

/* Start output buffer. */
ob_start();

/* Set cookie_httponly. */
ini_set("session.cookie_httponly", 1);

/* Load the framework. */
include '../framework/router.class.php';
include '../framework/control.class.php';
include '../framework/model.class.php';
include '../framework/helper.class.php';

$handler = static function ()
{
    global $app, $config, $lang;

    /* Log the time and define the run mode. */
    $startTime = getTime();

    /* Instance the app. */
    $app = router::createApp('pms', dirname(dirname(__FILE__)), 'router');

    /* installed or not. */
    if(!$app->checkInstalled()) die(header('location: install.php'));

    /* Check for need upgrade. */
    if($app->checkNeedUpgrade()) die(header('location: upgrade.php'));

    /* Run the app. */
    $app->setStartTime($startTime);
    $common = $app->loadCommon();

    /* Check the request is getconfig or not. */
    if(isset($_GET['mode']) and $_GET['mode'] == 'getconfig') die(helper::removeUTF8Bom($app->exportConfig()));

    /* Remove install.php and upgrade.php. */
    if(file_exists('install.php') or file_exists('upgrade.php'))
    {
        if(!empty($config->inContainer) && !isset($_SESSION['installing']))
        {
            if(file_exists('install.php')) unlink('install.php');
            if(file_exists('upgrade.php')) unlink('upgrade.php');
        }
        else
        {
            $undeletedFiles = array();
            if(file_exists('install.php')) $undeletedFiles[] = '<strong style="color:#ed980f">install.php</strong>';
            if(file_exists('upgrade.php')) $undeletedFiles[] = '<strong style="color:#ed980f">upgrade.php</strong>';
            $wwwDir = dirname(__FILE__);
            if($undeletedFiles)
            {
                echo "<html><head><meta charset='utf-8'></head>
                    <body><table align='center' style='width:700px; margin-top:100px; border:1px solid gray; font-size:14px;'><tr><td style='padding:8px'>";
                echo "<div style='margin-bottom:8px;'>安全起见，请删除 <strong style='color:#ed980f'>{$wwwDir}</strong> 目录下的 " . join(' 和 ', $undeletedFiles) . " 文件。</div>";
                echo "<div>Please remove " . join(' and ', $undeletedFiles) . " under <strong style='color:#ed980f'>$wwwDir</strong> dir for security reason.</div>";
                die("</td></tr></table></body></html>");
            }
        }
    }

    if(!empty($_GET['display']) && $_GET['display'] == 'card') $config->default->view = 'xhtml';

    try
    {
        $app->parseRequest();
        $app->setParams();
        $common->checkMaintenance();
        $common->checkPriv();
        $common->checkIframe();

        $isClient = strpos($_SERVER['HTTP_USER_AGENT'], 'xuanxuan') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'uni-app') !== false;
        if($app->getViewType() != 'json' && session_id() != $app->sessionID && !$isClient) helper::restartSession($app->sessionID);

        $app->loadModule();
    }
    catch (EndResponseException $endResponseException)
    {
        echo $endResponseException->getContent();
    }

    /* Flush the buffer. */
    echo helper::removeUTF8Bom(ob_get_clean());
};

/* Classic or FrankenPHP worker. */
if(php_sapi_name() == 'frankenphp')
{
    \frankenphp_handle_request($handler);
}
else
{
    $handler();
}
