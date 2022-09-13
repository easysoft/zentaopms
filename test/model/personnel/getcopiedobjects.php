#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';
su('admin');

/**

title=测试 personnelModel->getCopiedObjects();
cid=1
pid=1

查找与项目1同项目集的其他项目数量 >> 8
查找与项目1同项目集的其他项目并标明项目人数 >> 项目11（2人）
查找与迭代1同项目的其他迭代及所属项目 >> 7
查找与迭代1同项目的其他迭代及所属项目并标明项目人数 >> 项目1（2人）,&nbsp;&nbsp;&nbsp;迭代91（1人）
查找与正常产品2同项目集的其他产品数量 >> 10
查找出项目集1外的其他所有项目集 >> 9

*/

global $tester;
$personnel = $tester->loadModel('personnel');

$copyProject1 = $personnel->getCopiedObjects(11, 'project');
$copyProject2 = $personnel->getCopiedObjects(11, 'project', true);
$copySprint1  = $personnel->getCopiedObjects(101, 'sprint');
$copySprint2  = $personnel->getCopiedObjects(101, 'sprint', true);
$copyProduct  = $personnel->getCopiedObjects(2, 'product');
$copyProgram  = $personnel->getCopiedObjects(1, 'program');

r(count($copyProject1)) && p()         && e('8');                                            //查找与项目1同项目集的其他项目数量
r($copyProject2)        && p('21')     && e('项目11（2人）');                                //查找与项目1同项目集的其他项目并标明项目人数
r(count($copySprint1))  && p()         && e('7');                                            //查找与迭代1同项目的其他迭代及所属项目
r($copySprint2)         && p('11,191') && e('项目1（2人）,&nbsp;&nbsp;&nbsp;迭代91（1人）'); //查找与迭代1同项目的其他迭代及所属项目并标明项目人数
r(count($copyProduct))  && p()         && e('10');                                           //查找与正常产品2同项目集的其他产品数量
r(count($copyProgram))  && p()         && e('9');                                            //查找出项目集1外的其他所有项目集
