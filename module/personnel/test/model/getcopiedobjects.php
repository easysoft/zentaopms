#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';
su('admin');

zdTable('project')->gen(20);
zdTable('project')->config('execution')->gen(50);

/**

title=测试 personnelModel->getCopiedObjects();
cid=1
pid=1

查找与项目1同项目集的其他项目数量 >> 0
查找与项目1同项目集的其他项目并标明项目人数 >> 0
查找与迭代1同项目的其他迭代及所属项目 >> 迭代1
查找与迭代1同项目的其他迭代及所属项目并标明项目人数 >> 迭代1（1人）
查找与正常产品2同项目集的其他产品数量 >> 正常产品13,已关闭的正常产品24
查找出项目集1外的其他所有项目集 >> 0

*/

global $tester;
$personnel = $tester->loadModel('personnel');

$copyProject1 = $personnel->getCopiedObjects(11, 'project');
$copyProject2 = $personnel->getCopiedObjects(11, 'project', true);
$copySprint1  = $personnel->getCopiedObjects(101, 'sprint');
$copySprint2  = $personnel->getCopiedObjects(101, 'sprint', true);
$copyProduct  = $personnel->getCopiedObjects(2, 'product');
$copyProgram  = $personnel->getCopiedObjects(1, 'program');

r($copyProject1) && p()         && e('0');                              //查找与项目1同项目集的其他项目数量
r($copyProject2) && p()         && e('0');                              //查找与项目1同项目集的其他项目并标明项目人数
r($copySprint1)  && p('11')     && e('迭代1');                          //查找与迭代1同项目的其他迭代及所属项目
r($copySprint2)  && p('11')     && e('迭代1（1人）');                   //查找与迭代1同项目的其他迭代及所属项目并标明项目人数
r($copyProduct)  && p('13,24')  && e('正常产品13,已关闭的正常产品24');  //查找与正常产品2同项目集的其他产品数量
r($copyProgram)  && p()         && e('0');                              //查找出项目集1外的其他所有项目集
