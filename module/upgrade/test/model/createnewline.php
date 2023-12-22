#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->createNewLine();
timeout=0
cid=1

- 测试新建产品线
 - 属性type @line
 - 属性name @产品线1
 - 属性root @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$lineName = '产品线1';
$programID = 1;

$upgrade = new upgradeTest();
r($upgrade->createNewLineTest($lineName, $programID)) && p('type,name,root') && e('line,产品线1,1'); // 测试新建产品线