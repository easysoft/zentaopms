#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('compile')->gen(1);
su('admin');

/**

title=测试 compileModel->getByQueue();
timeout=0
cid=1

- 检查当queue存在的时候是否能拿到数据属性name @构建1
- 检查当queue不存在的时候返回的结果属性name @0

*/

$tester->loadModel('compile');
r($tester->compile->getByQueue('1')) && p('name') && e('构建1'); //检查当queue存在的时候是否能拿到数据
r($tester->compile->getByQueue('2')) && p('name') && e('0');     //检查当queue不存在的时候返回的结果