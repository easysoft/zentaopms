#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

/**

title=测试 transfer->initControl();
timeout=0
cid=1

- 测试获取task模块id字段语言项 @input
- 测试获取bug模块product字段应该是下拉列表 @select
- 测试当获取不到对应的control时默认为input @input

*/
global $tester;
$transfer = $tester->loadModel('transfer');

r($transfer->initControl('task', 'id'))       && p('') && e('input');  // 测试获取task模块id字段语言项
r($transfer->initControl('bug',  'product'))  && p('') && e('select'); // 测试获取bug模块product字段应该是下拉列表
r($transfer->initControl('task', 'notIsset')) && p('') && e('input');  // 测试当获取不到对应的control时默认为input
