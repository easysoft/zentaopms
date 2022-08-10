#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->create();
cid=1
pid=1

测试创建敏捷私有执行 >> 新增私有敏捷执行,sprint
测试创建敏捷公开执行 >> 新增公开敏捷执行code,sprint
测试创建瀑布私有执行 >> 新增私有瀑布执行,stage
测试创建瀑布公开执行 >> 新增公开瀑布执行code,stage
测试创建看板私有执行 >> 新增私有看板执行,kanban
测试创建看板公开执行 >> 新增公开看板执行code,kanban
测试创建迭代团队分配 >> pd58,pd72,dev10,dev10
测试不输入项目 >> 所属项目不能为空。
测试不输入产品 >> 关联产品不能为空！
测试不输入执行名称 >> 『执行名称』不能为空。
测试不输入执行代号 >> 『执行代号』不能为空。
测试一样的执行名称 >> 『执行名称』已经有『新增私有敏捷执行』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。
测试一样的执行代号 >> 『执行代号』已经有『新增私有敏捷执行code』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。

*/

$task = new executionTest();

$projectIDList = array('11', '12', '41', '71');
$dayNum        = '6';
$days          = '5';
$products      = array('1', '0');

$prvExecution            = array('name' => '新增私有敏捷执行', 'code' => '新增私有敏捷执行code', 'products' => $products);
$openExecution           = array('name' => '新增公开敏捷执行', 'code' => '新增公开敏捷执行code', 'products' => $products, 'acl' => 'open');
$prvExecution_waterfall  = array('name' => '新增私有瀑布执行', 'code' => '新增私有瀑布执行code', 'products' => $products);
$openExecution_waterfall = array('name' => '新增公开瀑布执行', 'code' => '新增公开瀑布执行code', 'products' => $products, 'acl' => 'open');
$prvExecution_kanban     = array('name' => '新增私有看板执行', 'code' => '新增私有看板执行code', 'products' => $products);
$openExecution_kanban    = array('name' => '新增公开看板执行', 'code' => '新增公开看板执行code', 'products' => $products, 'acl' => 'open');
$teamExecution           = array('name' => '新增团队执行', 'code' => '新增团队执行code', 'products' => $products, 'PO' => 'pd58' ,'QD' => 'pd72', 'PM' => 'dev10', 'RD' => 'dev10');
$noProjectID             = array('name' => '新增私有敏捷执行', 'code' => '新增私有敏捷执行code', 'products' => $products);
$noProductID             = array('name' => '新增私有敏捷执行', 'code' => '新增私有敏捷执行code');
$noName                  = array('name' => '', 'code' => '名称校验code', 'products' => $products);
$noCode                  = array('name' => 'code校验', 'code' => '', 'products' => $products);
$equallyName             = array('name' => '新增私有敏捷执行', 'code' => '一样名称校验code', 'products' => $products);
$equallyCode             = array('name' => '一样code校验', 'code' => '新增私有敏捷执行code', 'products' => $products);

r($task->createObject($prvExecution, $projectIDList[1], $dayNum, $days))            && p('name,type')   && e('新增私有敏捷执行,sprint');                                                                                       // 测试创建敏捷私有执行
r($task->createObject($openExecution, $projectIDList[1], $dayNum, $days))           && p('code,type')   && e('新增公开敏捷执行code,sprint');                                                                                   // 测试创建敏捷公开执行
r($task->createObject($prvExecution_waterfall, $projectIDList[2], $dayNum, $days))  && p('name,type')   && e('新增私有瀑布执行,stage');                                                                                        // 测试创建瀑布私有执行
r($task->createObject($openExecution_waterfall, $projectIDList[2], $dayNum, $days)) && p('code,type')   && e('新增公开瀑布执行code,stage');                                                                                    // 测试创建瀑布公开执行
r($task->createObject($prvExecution_kanban, $projectIDList[3], $dayNum, $days))     && p('name,type')   && e('新增私有看板执行,kanban');                                                                                       // 测试创建看板私有执行
r($task->createObject($openExecution_kanban, $projectIDList[3], $dayNum, $days))    && p('code,type')   && e('新增公开看板执行code,kanban');                                                                                   // 测试创建看板公开执行
r($task->createObject($teamExecution, $projectIDList[1], $dayNum, $days))           && p('PO,QD,PM,RD') && e('pd58,pd72,dev10,dev10');                                                                                         // 测试创建迭代团队分配
r($task->createObject($prvExecution, '', $dayNum, $days))                           && p('message:0')   && e('所属项目不能为空。');                                                                                            // 测试不输入项目
r($task->createObject($noProductID, $projectIDList[1], $dayNum, $days))             && p('message:0')   && e('关联产品不能为空！');                                                                                            // 测试不输入产品
r($task->createObject($noName, $projectIDList[1], $dayNum, $days))                  && p('name:0')      && e('『执行名称』不能为空。');                                                                                        // 测试不输入执行名称
r($task->createObject($noCode, $projectIDList[1], $dayNum, $days))                  && p('code:0')      && e('『执行代号』不能为空。');                                                                                        // 测试不输入执行代号
r($task->createObject($equallyName, $projectIDList[1], $dayNum, $days))             && p('name:0')      && e('『执行名称』已经有『新增私有敏捷执行』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。');     // 测试一样的执行名称
r($task->createObject($equallyCode, $projectIDList[1], $dayNum, $days))             && p('code:0')      && e('『执行代号』已经有『新增私有敏捷执行code』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。'); // 测试一样的执行代号

$db->restoreDB();