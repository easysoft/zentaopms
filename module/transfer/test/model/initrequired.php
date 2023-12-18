#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

/**

title=测试 transfer->initRequired();
timeout=0
cid=1

- 测试Task模块execution字段是否必填 @yes
- 测试获取bug模块title字段是否必填 @yes
- 测试获取bug模块desc字段是否必填 @no
- 测试当字段不存时 @no
- 当field为空时 @no

*/
global $tester;
$transfer = $tester->loadModel('transfer');

r($transfer->initRequired('task', 'execution')) && p('') && e('yes'); // 测试Task模块execution字段是否必填
r($transfer->initRequired('bug',  'title'))     && p('') && e('yes'); // 测试获取bug模块title字段是否必填
r($transfer->initRequired('bug',  'desc'))      && p('') && e('no');  // 测试获取bug模块desc字段是否必填
r($transfer->initRequired('task', 'notIsset'))  && p('') && e('no');  // 测试当字段不存时
r($transfer->initRequired('task', ''))          && p('') && e('no');  // 当field为空时
