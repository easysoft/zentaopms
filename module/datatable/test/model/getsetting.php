#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/datatable.unittest.class.php';

zenData('lang')->gen(0);
su('admin');

/**

title=测试 datatableModel::getSetting();
timeout=0
cid=1

- 获取产品模块browse方法自定义列
 - 第id条的title属性 @ID
 - 第id条的width属性 @80
 - 第title条的title属性 @研发需求名称
 - 第title条的width属性 @0.44
- 获取项目模块browse方法自定义列
 - 第id条的title属性 @ID
 - 第id条的width属性 @80
 - 第name条的title属性 @项目名称
 - 第name条的width属性 @0.44
- 获取执行模块task方法自定义列
 - 第id条的title属性 @ID
 - 第id条的width属性 @80
 - 第name条的title属性 @任务名称
 - 第name条的width属性 @0.5
- 获取测试用例模块browse方法自定义列
 - 第id条的title属性 @ID
 - 第id条的width属性 @80
 - 第title条的title属性 @用例名称
 - 第title条的width属性 @0.44
- 获取Bug模块browse方法自定义列
 - 第id条的title属性 @ID
 - 第id条的width属性 @80
 - 第title条的title属性 @Bug标题
 - 第title条的width属性 @0.44

*/

global $lang, $app, $config;
$lang->SRCommon    = '研发需求';
$lang->URCommon    = '用户需求';
$config->edition   = 'open';
$app::$loadedLangs = array();

include($app->getModuleRoot() . '/story/control.php');
$app->control = new story();
$app->loadLang('custom');
$app->control->loadModel('task');
$app->control->loadModel('story');

$datatable = new datatableTest();
r($datatable->getSettingTest('product', 'browse'))  && p('id:title;id:width;title:title;title:width')    && e('ID,80,研发需求名称,0.44');  //获取产品模块browse方法自定义列
r($datatable->getSettingTest('project', 'browse'))  && p('id:title;id:width;name:title;name:width')      && e('ID,80,项目名称,0.44');      //获取项目模块browse方法自定义列
r($datatable->getSettingTest('execution', 'task'))  && p('id:title;id:width;name:title;name:width')      && e('ID,80,任务名称,0.5');       //获取执行模块task方法自定义列
r($datatable->getSettingTest('testcase', 'browse')) && p('id:title;id:width;title:title;title:width')    && e('ID,80,用例名称,0.44');      //获取测试用例模块browse方法自定义列
r($datatable->getSettingTest('bug', 'browse'))      && p('id:title;id:width;title:title;title:width')    && e('ID,80,Bug标题,0.44');      //获取Bug模块browse方法自定义列
