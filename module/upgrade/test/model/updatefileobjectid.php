#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->updateFileObjectID();
timeout=0
cid=1

- 测试更新类型 空 上一个id 3 的编辑器文件对象 @type:comment,count:8,lastID:0,files:1,2,3,4,5,6,7,8,9,10,11
- 测试更新类型 空 上一个id 0 的编辑器文件对象 @type:comment,count:11,lastID:0,files:1,2,3,4,5,6,7,8,9,10,11
- 测试更新类型 comment 上一个id 3 的编辑器文件对象 @type:comment,count:8,lastID:0,files:1,2,3,4,5,6,7,8,9,10,11
- 测试更新类型 comment 上一个id 0 的编辑器文件对象 @type:comment,count:11,lastID:0,files:1,2,3,4,5,6,7,8,9,10,11
- 测试更新类型 doc 上一个id 3 的编辑器文件对象 @type:project,count:2,lastID:0,files:1,7
- 测试更新类型 doc 上一个id 0 的编辑器文件对象 @type:project,count:5,lastID:0,files:1,7
- 测试更新类型 project 上一个id 3 的编辑器文件对象 @type:project,count:100,lastID:103,files:2,8
- 测试更新类型 project 上一个id 0 的编辑器文件对象 @type:project,count:100,lastID:100,files:2,8
- 测试更新类型 build 上一个id 3 的编辑器文件对象 @type:finish,count:2,lastID:0,files:
- 测试更新类型 build 上一个id 0 的编辑器文件对象 @type:finish,count:5,lastID:0,files:

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('user')->gen(5);
zdTable('action')->config('action_updatefileobjectid')->gen(11);
zdTable('doccontent')->config('doccontent_updatefileobjectid')->gen(5);
zdTable('project')->config('project_updatefileobjectid')->gen(110);
zdTable('build')->config('build_updatefileobjectid')->gen(5);
su('admin');

$upgrade = new upgradeTest();

$type   = array('', 'comment', 'doc', 'project', 'build');
$lastID = array(3, 0);

r($upgrade->updateFileObjectIDTest($type[0], $lastID[0])) && p('', ';') && e('type:comment,count:8,lastID:0,files:1,2,3,4,5,6,7,8,9,10,11');  // 测试更新类型 空 上一个id 3 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[0], $lastID[1])) && p('', ';') && e('type:comment,count:11,lastID:0,files:1,2,3,4,5,6,7,8,9,10,11'); // 测试更新类型 空 上一个id 0 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[1], $lastID[0])) && p('', ';') && e('type:comment,count:8,lastID:0,files:1,2,3,4,5,6,7,8,9,10,11');  // 测试更新类型 comment 上一个id 3 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[1], $lastID[1])) && p('', ';') && e('type:comment,count:11,lastID:0,files:1,2,3,4,5,6,7,8,9,10,11'); // 测试更新类型 comment 上一个id 0 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[2], $lastID[0])) && p('', ';') && e('type:project,count:2,lastID:0,files:1,7');                      // 测试更新类型 doc 上一个id 3 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[2], $lastID[1])) && p('', ';') && e('type:project,count:5,lastID:0,files:1,7');                      // 测试更新类型 doc 上一个id 0 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[3], $lastID[0])) && p('', ';') && e('type:project,count:100,lastID:103,files:2,8');                  // 测试更新类型 project 上一个id 3 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[3], $lastID[1])) && p('', ';') && e('type:project,count:100,lastID:100,files:2,8');                  // 测试更新类型 project 上一个id 0 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[4], $lastID[0])) && p('', ';') && e('type:finish,count:2,lastID:0,files:');                          // 测试更新类型 build 上一个id 3 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[4], $lastID[1])) && p('', ';') && e('type:finish,count:5,lastID:0,files:');                          // 测试更新类型 build 上一个id 0 的编辑器文件对象