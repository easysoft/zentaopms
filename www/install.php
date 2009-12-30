<?php
/**
 * The install router file of ZenTaoMS.
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
/* 包含必须的类文件。*/
include '../../../framework/router.class.php';
include '../../../framework/control.class.php';
include '../../../framework/model.class.php';
include '../../../framework/helper.class.php';
include './myrouter.class.php';

/* 实例化路由对象，加载配置，连接到数据库。*/
$app    = router::createApp('pms', '', 'myRouter');
$config = $app->loadConfig('common');
$config->set('requestType', 'GET');
$config->set('default.module', 'install');

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
