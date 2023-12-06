#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

zdTable('project')->config('execution')->gen(50);
zdTable('product')->gen(30);
zdTable('team')->gen(50);
zdTable('user')->gen(20);

su('admin');

/**

title=测试 personnelModel->getCopiedObjects();
cid=1
pid=1

*/

global $tester;
$personnel = $tester->loadModel('personnel');

$copyProject1 = $personnel->getCopiedObjects(11, 'project');
$copyProject2 = $personnel->getCopiedObjects(11, 'project', true);
$copySprint1  = $personnel->getCopiedObjects(101, 'sprint');
$copySprint2  = $personnel->getCopiedObjects(101, 'sprint', true);
$copyProduct1 = $personnel->getCopiedObjects(2, 'product');
$copyProduct2 = $personnel->getCopiedObjects(2, 'product', true);
$copyProgram  = $personnel->getCopiedObjects(1, 'program');

r($copyProject1) && p('12')     && e('项目2');                                        // 查找与项目1同项目集的其他项目数量
r($copyProject2) && p('12')     && e('项目2（1人）');                                 // 查找与项目1同项目集的其他项目并标明项目人数
r($copySprint1)  && p('11')     && e('项目1');                                        // 查找与迭代1同项目的其他迭代及所属项目
r($copySprint2)  && p('11')     && e('项目1（1人）');                                 // 查找与迭代1同项目的其他迭代及所属项目并标明项目人数
r($copyProduct1) && p('13,24')  && e('正常产品13,已关闭的正常产品24');                // 查找与正常产品2同项目集的其他产品数量
r($copyProduct2) && p('13,24')  && e('正常产品13（0人）,已关闭的正常产品24（0人）');  // 查找与正常产品2同项目集的其他产品数量
r($copyProgram)  && p()         && e('0');                                            // 查找出项目集1外的其他所有项目集
