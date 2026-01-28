#!/usr/bin/env php
<?php
/**
title=测试 screenModel->getSysOptions();
cid=17403

- 测试type为user，对象类型为空，字段为空，sql为空的情况下，用户列表获取是否正确。
 - 属性admin @admin
 - 属性closed @Closed
- 测试type为product，对象类型为空，字段为空，sql为空的情况下，产品列表获取是否正确。
 - 属性1 @正常产品1
 - 属性10 @正常产品10
- 测试type为project，对象类型为空，字段为空，sql为空的情况下，项目列表获取是否正确。
 - 属性11 @项目集1
 - 属性19 @项目集9
- 测试type为execution，对象类型为空，字段为空，sql为空的情况下，执行列表获取是否正确。
 - 属性101 @/项目集1
 - 属性110 @/项目集10
- 测试type为dept，对象类型为空，字段为空，sql为空的情况下，部门列表获取是否正确。
 - 属性1 @/产品部1
 - 属性10 @/一级部门10
- 测试type为dept，对象类型为空，字段为空，sql为空的情况下，项目状态获取是否正确。
 - 属性wait @未开始
 - 属性doing @进行中
 - 属性suspended @已挂起
 - 属性closed @已关闭
 - 属性delay @已延期
- 测试type为option，对象类型为空，字段为空，sql为空的情况下，数据获取是否正确。
 - 属性active @激活
 - 属性resolved @已解决
 - 属性closed @已关闭
- 测试type为object，对象类型为空，字段为空，sql为空的情况下，数据获取是否正确。
 - 属性1 @BUG1
 - 属性5 @BUG5
- 测试type为other，对象类型为空，字段为空，sql为空的情况下，数据获取是否正确。
 - 属性user1 @user1
 - 属性user9 @user9

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('user')->gen(10);
zenData('project')->gen(0);
zenData('product')->loadYaml('product_option')->gen(10);
zenData('project')->loadYaml('program_option')->gen(10);
zenData('project')->loadYaml('project_option')->gen(10, false, false);
zenData('project')->loadYaml('execution_option')->gen(10, false, false);
zenData('dept')->gen(10);
zenData('bug')->gen(5);

$pivot = new pivotModelTest();

$sql = 'SELECT * FROM `zt_user` WHERE `id` > 0 ';
$typeList   = array('user', 'product', 'project', 'execution', 'dept', 'project.status', 'option', 'object', 'string');
$objectList = array('',     '',        '',        '',          '',     '',               'bug',    'bug',    '');
$fieldList  = array('',     '',        '',        '',          '',     '',               'status', 'title',  'account');
$sqlList    = array('',     '',        '',        '',          '',     '',               '',       '',       $sql);

r($pivot->getSysOptions($typeList[0], $objectList[0], $fieldList[0], $sqlList[0])) && p('admin,closed') && e('admin,Closed');                                              //测试type为user，对象类型为空，字段为空，sql为空的情况下，用户列表获取是否正确。
r($pivot->getSysOptions($typeList[1], $objectList[1], $fieldList[1], $sqlList[1])) && p('1,10')  && e('正常产品1,正常产品10');                                             //测试type为product，对象类型为空，字段为空，sql为空的情况下，产品列表获取是否正确。
r($pivot->getSysOptions($typeList[2], $objectList[2], $fieldList[2], $sqlList[2])) && p('11,19') && e('项目集1,项目集9');                                                  //测试type为project，对象类型为空，字段为空，sql为空的情况下，项目列表获取是否正确。
r($pivot->getSysOptions($typeList[3], $objectList[3], $fieldList[3], $sqlList[3])) && p('101,110') && e('/项目集1,/项目集10');                                             //测试type为execution，对象类型为空，字段为空，sql为空的情况下，执行列表获取是否正确。
r($pivot->getSysOptions($typeList[4], $objectList[4], $fieldList[4], $sqlList[4])) && p('1,10') && e('/产品部1,/一级部门10');                                              //测试type为dept，对象类型为空，字段为空，sql为空的情况下，部门列表获取是否正确。
r($pivot->getSysOptions($typeList[5], $objectList[5], $fieldList[5], $sqlList[5])) && p('wait,doing,suspended,closed,delay') && e('未开始,进行中,已挂起,已关闭,已延期');   //测试type为dept，对象类型为空，字段为空，sql为空的情况下，项目状态获取是否正确。
r($pivot->getSysOptions($typeList[6], $objectList[6], $fieldList[6], $sqlList[6])) && p('active,resolved,closed') && e('激活,已解决,已关闭');                              //测试type为option，对象类型为空，字段为空，sql为空的情况下，数据获取是否正确。
r($pivot->getSysOptions($typeList[7], $objectList[7], $fieldList[7], $sqlList[7])) && p('1,5') && e('BUG1,BUG5');                                                          //测试type为object，对象类型为空，字段为空，sql为空的情况下，数据获取是否正确。
r($pivot->getSysOptions($typeList[8], $objectList[8], $fieldList[8], $sqlList[8])) && p('user1,user9') && e('user1,user9');                                                //测试type为other，对象类型为空，字段为空，sql为空的情况下，数据获取是否正确。
