#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 datatableModel::formatFields();
timeout=0
cid=15941

- 获取产品模块的目标配置
 - 第id条的order属性 @1
 - 第id条的title属性 @编号
 - 第name条的order属性 @2
 - 第name条的title属性 @产品名称
- 获取项目模块的目标配置
 - 第id条的order属性 @1
 - 第id条的title属性 @项目ID
 - 第name条的order属性 @2
 - 第name条的title属性 @项目名称
- 获取需求模块的目标配置
 - 第id条的order属性 @1
 - 第id条的title属性 @编号
 - 第name条的order属性 @2
 - 第name条的title属性 @需求名称
- 获取任务模块的目标配置
 - 第id条的order属性 @1
 - 第id条的title属性 @编号
 - 第name条的order属性 @2
 - 第name条的title属性 @任务名称
- 获取测试单模块的目标配置
 - 第id条的order属性 @1
 - 第id条的title属性 @编号
 - 第name条的order属性 @2
 - 第name条的title属性 @测试单名称

*/
global $tester;
$tester->loadModel('datatable');

$fieldList = array('id' => array('show' => true), 'name' => array('show' => true), 'status' => array('show' => false), 'desc' => array('required' => true));

r($tester->datatable->formatFields('product', $fieldList))  && p('id:order;id:title;name:order;name:title')    && e('1,编号,2,产品名称');   //获取产品模块的目标配置
r($tester->datatable->formatFields('project', $fieldList))  && p('id:order;id:title;name:order;name:title')    && e('1,项目ID,2,项目名称'); //获取项目模块的目标配置
r($tester->datatable->formatFields('story', $fieldList))    && p('id:order;id:title;name:order;name:title')    && e('1,编号,2,需求名称');   //获取需求模块的目标配置
r($tester->datatable->formatFields('task', $fieldList))     && p('id:order;id:title;name:order;name:title')    && e('1,编号,2,任务名称');   //获取任务模块的目标配置
r($tester->datatable->formatFields('testtask', $fieldList)) && p('id:order;id:title;name:order;name:title')    && e('1,编号,2,测试单名称'); //获取测试单模块的目标配置