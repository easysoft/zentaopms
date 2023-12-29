#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/datatable.class.php';

zdTable('product')->gen(1);
zdTable('lang')->gen(0);
su('admin');

/**

title=测试 datatableModel::getFieldList();
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

*/

global $app, $lang;
$lang->SRCommon = '研发需求';
$lang->URCommon = '用户需求';
$app::$loadedLangs = array();

$datatable = new datatableTest();
r($datatable->getFieldListTest('product', 'browse'))  && p('id:title;id:width;title:title;title:width')    && e('ID,80,研发需求名称,0.44');  //获取产品模块browse方法自定义列
r($datatable->getFieldListTest('project', 'browse'))  && p('id:title;id:width;name:title;name:width')      && e('ID,80,项目名称,0.44');      //获取项目模块browse方法自定义列
r($datatable->getFieldListTest('execution', 'task'))  && p('id:title;id:width;name:title;name:width')      && e('ID,80,任务名称,0.5');       //获取执行模块task方法自定义列
r($datatable->getFieldListTest('testcase', 'browse')) && p('id:title;id:width;title:title;title:width')    && e('ID,80,用例名称,0.44');      //获取测试用例模块browse方法自定义列
