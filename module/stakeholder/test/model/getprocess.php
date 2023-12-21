#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->getProcess();
cid=1

- 获取进度id=>name的键值对属性1 @过程名称1
- 获取进度id=>name的键值对的数量 @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

zdTable('user')->gen(5);
zdTable('process')->gen(10);

$stakeholderTester = new stakeholderTest();
$processPairs      = $stakeholderTester->getProcessTest();

r($processPairs)        && p('1') && e('过程名称1'); // 获取进度id=>name的键值对
r(count($processPairs)) && p()    && e('10');        // 获取进度id=>name的键值对的数量
