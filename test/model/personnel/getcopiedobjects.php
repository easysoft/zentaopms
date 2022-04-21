#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';

/**

title=测试 personnelModel->getCopiedObjects();
cid=1
pid=1

取出匹配的项目数量 >> 20
取出其中一个匹配的项目 >> 独立项目20
取出匹配的产品数量 >> 119
取出其中一个匹配的产品 >> 多平台产品100
取出匹配的项目集数量 >> 9
取出其中一个匹配的项目集 >> 项目集10
取出匹配的执行数量 >> 10
取出其中一个匹配的执行 >> &nbsp;&nbsp;&nbsp;独立项目20
传入不存在的objectID匹配的执行数量 >> 0
传入空值时取出匹配的执行数量 >> 0

*/

$personnel = new personnelTest('admin');

$objectID = array();
$objectID[0] = 1;
$objectID[1] = 2;
$objectID[2] = 11111;
$objectID[3] = '';

$objectType = array();
$objectType[0] = 'project';
$objectType[1] = 'product';
$objectType[2] = 'program';
$objectType[3] = 'sprint';

$result1 = $personnel->getCopiedObjectsTest($objectID[0], $objectType[0]);
$result2 = $personnel->getCopiedObjectsTest($objectID[0], $objectType[1]);
$result3 = $personnel->getCopiedObjectsTest($objectID[1], $objectType[2]);
$result4 = $personnel->getCopiedObjectsTest($objectID[1], $objectType[3]);
$result5 = $personnel->getCopiedObjectsTest($objectID[2], $objectType[3]);
$result6 = $personnel->getCopiedObjectsTest($objectID[2], $objectType[3]);

//a($result);die;
r(count($result1)) && p()      && e('20');                           //取出匹配的项目数量
r($result1)        && p('750') && e('独立项目20');                   //取出其中一个匹配的项目
r(count($result2)) && p()      && e('119');                          //取出匹配的产品数量
r($result2)        && p('100') && e('多平台产品100');                //取出其中一个匹配的产品
r(count($result3)) && p()      && e('9');                            //取出匹配的项目集数量
r($result3)        && p('10')  && e('项目集10');                     //取出其中一个匹配的项目集
r(count($result4)) && p()      && e('10');                           //取出匹配的执行数量
r($result4)        && p('750') && e('&nbsp;&nbsp;&nbsp;独立项目20'); //取出其中一个匹配的执行
r(count($result5)) && p()      && e('0');                            //传入不存在的objectID匹配的执行数量
r(count($result6)) && p()      && e('0');                            //传入空值时取出匹配的执行数量