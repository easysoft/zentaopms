#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

/**
title=测试 pivotModel->getByID();
cid=1
pid=1

测试不存在的透视表                       >> 0,0,0
测试id为1001的透视表                     >> 1001,完成项目工时透视表,85
测试id为1001的透视表的字段生成是否正确   >> 项目名称,closedDate
测试id为1001的透视表的筛选器生成是否正确 >> closedDate
测试id为1003的透视表                     >> 1003,项目工时透视表,59
*/

$pivot = new pivotTest();

$idList = array(0, 1001, 1002, 1003, 1004, 1005, 1006, 1007);

r($pivot->getByIDTest($idList[0])) && p('id,name,group') && e('0,0,0');                    //测试不存在的透视表
$res = $pivot->getByIDTest($idList[1]);
r($res) && p('id,name,group') && e('1001,完成项目工时透视表,85');                          //测试id为1001的透视表
$fields = get_object_vars($res->fieldSettings);
r(array_keys($fields)) && p('0,9') && e('项目名称,closedDate');                            //测试id为1001的透视表的字段生成是否正确
r($res->filters) && p('0:field') && e('closedDate');                                       //测试id为1001的透视表的筛选器生成是否正确
r($pivot->getByIDTest($idList[3])) && p('id,name,group') && e('1003,产品完成度统计表,59');   //测试id为1003的透视表
