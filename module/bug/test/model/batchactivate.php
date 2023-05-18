#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/bug.class.php';
su('user1');

/**

title=测试bugModel->batchActivate();
timeout=0
cid=1

- 测试激活bug一个第53条的status属性 @active

- 测试批量激活bug第82条的status属性 @active

- 测试激活bug一个,修改版本第53条的openedBuild属性 @11

- 测试批量激活bug,修改版本第82条的openedBuild属性 @11

*/

$bug = zdTable('bug');
$bug->product->range('1');
$bug->gen(100);

zdTable('build')->gen(100);

$bugIDList = array('1' => '1', '53' => '53', '82' => '82');
$buildList = array('53' => 11, '82' => 11);

$bug = new bugTest();
r($bug->batchActivateObject(array('53' => '53')))                    && p('53:status')      && e('active'); // 测试激活bug一个
r($bug->batchActivateObject($bugIDList))                             && p('82:status')      && e('active'); // 测试批量激活bug
r($bug->batchActivateObject(array('53' => '53'), array('53' => 11))) && p('53:openedBuild') && e('11');     // 测试激活bug一个,修改版本
r($bug->batchActivateObject($bugIDList, $buildList))                 && p('82:openedBuild') && e('11');     // 测试批量激活bug,修改版本