#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

/**

title=测试 transfer->initTitle();
timeout=0
cid=1

- 测试获取task模块id字段语言项 @编号
- 测试获取bug模块product字段语言项 @所属产品
- 测试当获取不到对应的语言项时 @notIsset
- 当field为空时 @0

*/
global $tester;
$transfer = $tester->loadModel('transfer');

r($transfer->initTitle('task', 'id'))       && p('') && e('编号');     // 测试获取task模块id字段语言项
r($transfer->initTitle('bug',  'product'))  && p('') && e('所属产品'); // 测试获取bug模块product字段语言项
r($transfer->initTitle('task', 'notIsset')) && p('') && e('notIsset'); // 测试当获取不到对应的语言项时
r($transfer->initTitle('task', ''))         && p('') && e('0');        // 当field为空时
