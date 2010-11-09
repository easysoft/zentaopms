<?php
/**
 * The install router file of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoMS
 * @version     $Id$
 * @link        http://www.zentao.net
 */
error_reporting(0);
session_start();
define('IN_INSTALL', true);

/* 包含必须的类文件。*/
include '../framework/router.class.php';
include '../framework/control.class.php';
include '../framework/model.class.php';
include '../framework/helper.class.php';

/* 实例化路由对象，加载配置。*/
$app    = router::createApp('pms', dirname(dirname(__FILE__)));
$config = $app->loadConfig('common');

/* 检查是否已经安装过。*/
if(!isset($_SESSION['installing']) and isset($config->installed) and $config->installed) die(header('location: index.php'));

/* 重新设置config参数，进行安装。*/
$config->set('requestType', 'GET');
$config->set('debug', true);
$config->set('default.module', 'install');
$app->setDebug();

/* 如果已经保存配置文件，则自动连接到数据库。*/
if(isset($config->installed) and $config->installed) $dbh = $app->connectDB();

/* 设置客户端所使用的语言、风格。*/
$app->setClientLang();
$app->setClientTheme();

/* 加载语言文件，加载公共模块。*/
$lang = $app->loadLang('common');

/* 加载相应的lib文件，并设置超全局变量的引用。*/
$app->loadClass('front',  $static = true);
$app->loadClass('filter', $static = true);
$app->setSuperVars();

/* 处理请求，验证权限，加载相应的模块。*/
$app->parseRequest();
$app->loadModule();
