#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->updateFileObjectID();
timeout=0
cid=1

- 测试更新类型 空 上一个id 3 的编辑器文件对象
 - 属性type @comment
 - 属性count @8
 - 属性lastID @0
 - 属性files @1,2,3,4,5,6,7,8,9,10,11
- 测试更新类型 空 上一个id 0 的编辑器文件对象
 - 属性type @comment
 - 属性count @11
 - 属性lastID @0
 - 属性files @1,2,3,4,5,6,7,8,9,10,11
- 测试更新类型 comment 上一个id 3 的编辑器文件对象
 - 属性type @comment
 - 属性count @8
 - 属性lastID @0
 - 属性files @1,2,3,4,5,6,7,8,9,10,11
- 测试更新类型 comment 上一个id 0 的编辑器文件对象
 - 属性type @comment
 - 属性count @11
 - 属性lastID @0
 - 属性files @1,2,3,4,5,6,7,8,9,10,11
- 测试更新类型 doc 上一个id 3 的编辑器文件对象
 - 属性type @project
 - 属性count @2
 - 属性lastID @0
 - 属性files @1,7
- 测试更新类型 doc 上一个id 0 的编辑器文件对象
 - 属性type @project
 - 属性count @5
 - 属性lastID @0
 - 属性files @1,7
- 测试更新类型 project 上一个id 3 的编辑器文件对象
 - 属性type @project
 - 属性count @100
 - 属性lastID @103
 - 属性files @2,8
- 测试更新类型 project 上一个id 0 的编辑器文件对象
 - 属性type @project
 - 属性count @100
 - 属性lastID @100
 - 属性files @2,8
- 测试更新类型 build 上一个id 3 的编辑器文件对象
 - 属性type @finish
 - 属性count @2
 - 属性lastID @0
 - 属性files @~~
- 测试更新类型 build 上一个id 0 的编辑器文件对象
 - 属性type @finish
 - 属性count @5
 - 属性lastID @0
 - 属性files @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';

zenData('user')->gen(5);
zenData('action')->loadYaml('action_updatefileobjectid')->gen(11);
zenData('doccontent')->loadYaml('doccontent_updatefileobjectid')->gen(5);
zenData('project')->loadYaml('project_updatefileobjectid')->gen(110);
zenData('build')->loadYaml('build_updatefileobjectid')->gen(5);
zenData('file')->loadYaml('file_updatefileobjectid')->gen(11);
su('admin');

$upgrade = new upgradeTest();

$type   = array('', 'comment', 'doc', 'project', 'build');
$lastID = array(3, 0);

r($upgrade->updateFileObjectIDTest($type[0], $lastID[0])) && p('type;count;lastID;files', ';') && e('comment;8;0;1,2,3,4,5,6,7,8,9,10,11');  // 测试更新类型 空 上一个id 3 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[0], $lastID[1])) && p('type;count;lastID;files', ';') && e('comment;11;0;1,2,3,4,5,6,7,8,9,10,11'); // 测试更新类型 空 上一个id 0 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[1], $lastID[0])) && p('type;count;lastID;files', ';') && e('comment;8;0;1,2,3,4,5,6,7,8,9,10,11');  // 测试更新类型 comment 上一个id 3 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[1], $lastID[1])) && p('type;count;lastID;files', ';') && e('comment;11;0;1,2,3,4,5,6,7,8,9,10,11'); // 测试更新类型 comment 上一个id 0 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[2], $lastID[0])) && p('type;count;lastID;files', ';') && e('project;2;0;1,7');                      // 测试更新类型 doc 上一个id 3 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[2], $lastID[1])) && p('type;count;lastID;files', ';') && e('project;5;0;1,7');                      // 测试更新类型 doc 上一个id 0 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[3], $lastID[0])) && p('type;count;lastID;files', ';') && e('project;100;103;2,8');                  // 测试更新类型 project 上一个id 3 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[3], $lastID[1])) && p('type;count;lastID;files', ';') && e('project;100;100;2,8');                  // 测试更新类型 project 上一个id 0 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[4], $lastID[0])) && p('type;count;lastID;files', ';') && e('finish;2;0;~~');                        // 测试更新类型 build 上一个id 3 的编辑器文件对象
r($upgrade->updateFileObjectIDTest($type[4], $lastID[1])) && p('type;count;lastID;files', ';') && e('finish;5;0;~~');                        // 测试更新类型 build 上一个id 0 的编辑器文件对象