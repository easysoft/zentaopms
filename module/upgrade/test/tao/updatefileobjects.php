#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->updateFileObjects();
cid=1

- 测试更新类型 doc 上一个id 3 限制 1 的文件对象 @count:1,lastID:4,fileID:1

- 测试更新类型 doc 上一个id 0 限制 10 的文件对象 @count:5,lastID:5,fileID:1

- 测试更新类型 project 上一个id 3 限制 1 的文件对象 @count:1,lastID:4,fileID:2

- 测试更新类型 project 上一个id 0 限制 10 的文件对象 @count:5,lastID:5,fileID:2

- 测试更新类型 bug 上一个id 3 限制 1 的文件对象 @count:1,lastID:4,fileID:3

- 测试更新类型 bug 上一个id 0 限制 10 的文件对象 @count:5,lastID:5,fileID:3

- 测试更新类型 release 上一个id 3 限制 1 的文件对象 @count:1,lastID:4,fileID:4

- 测试更新类型 release 上一个id 0 限制 10 的文件对象 @count:5,lastID:5,fileID:4

- 测试更新类型 productplan 上一个id 3 限制 1 的文件对象 @count:1,lastID:4,fileID:5

- 测试更新类型 productplan 上一个id 0 限制 10 的文件对象 @count:5,lastID:5,fileID:5

- 测试更新类型 product 上一个id 3 限制 1 的文件对象 @count:1,lastID:4,fileID:6

- 测试更新类型 product 上一个id 0 限制 10 的文件对象 @count:5,lastID:5,fileID:6

- 测试更新类型 story 上一个id 3 限制 1 的文件对象 @count:1,lastID:4,fileID:7

- 测试更新类型 story 上一个id 0 限制 10 的文件对象 @count:5,lastID:5,fileID:7

- 测试更新类型 testtask 上一个id 3 限制 1 的文件对象 @count:1,lastID:4,fileID:8

- 测试更新类型 testtask 上一个id 0 限制 10 的文件对象 @count:5,lastID:5,fileID:8

- 测试更新类型 todo 上一个id 3 限制 1 的文件对象 @count:1,lastID:4,fileID:9

- 测试更新类型 todo 上一个id 0 限制 10 的文件对象 @count:5,lastID:5,fileID:9

- 测试更新类型 task 上一个id 3 限制 1 的文件对象 @count:1,lastID:4,fileID:10

- 测试更新类型 task 上一个id 0 限制 10 的文件对象 @count:5,lastID:5,fileID:10

- 测试更新类型 build 上一个id 3 限制 1 的文件对象 @count:1,lastID:4,fileID:11

- 测试更新类型 build 上一个id 0 限制 10 的文件对象 @count:5,lastID:5,fileID:11

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';

zenData('user')->gen(5);
zenData('file')->loadYaml('file_updatefileobjectid')->gen(11);
zenData('doccontent')->loadYaml('doccontent_updatefileobjectid')->gen(5);
zenData('project')->loadYaml('project_updatefileobjectid')->gen(5);
zenData('bug')->loadYaml('bug_updatefileobjectid')->gen(5);
zenData('release')->loadYaml('release_updatefileobjectid')->gen(5);
zenData('productplan')->loadYaml('productplan_updatefileobjectid')->gen(5);
zenData('product')->loadYaml('product_updatefileobjectid')->gen(5);
zenData('storyspec')->loadYaml('storyspec_updatefileobjectid')->gen(5);
zenData('testtask')->loadYaml('testtask_updatefileobjectid')->gen(5);
zenData('todo')->loadYaml('todo_updatefileobjectid')->gen(5);
zenData('task')->loadYaml('task_updatefileobjectid')->gen(5);
zenData('build')->loadYaml('build_updatefileobjectid')->gen(5);
su('admin');

$upgrade = new upgradeTest();

$type   = array('doc', 'project', 'bug', 'release', 'productplan', 'product', 'story', 'testtask', 'todo', 'task', 'build');
$lastID = array(3, 0);
$limit  = array(1, 10);

r($upgrade->updateFileObjectsTest($type[0],  $lastID[0], $limit[0])) && p() && e('count:1,lastID:4,fileID:1');  // 测试更新类型 doc 上一个id 3 限制 1 的文件对象
r($upgrade->updateFileObjectsTest($type[0],  $lastID[1], $limit[1])) && p() && e('count:5,lastID:5,fileID:1');  // 测试更新类型 doc 上一个id 0 限制 10 的文件对象
r($upgrade->updateFileObjectsTest($type[1],  $lastID[0], $limit[0])) && p() && e('count:1,lastID:4,fileID:2');  // 测试更新类型 project 上一个id 3 限制 1 的文件对象
r($upgrade->updateFileObjectsTest($type[1],  $lastID[1], $limit[1])) && p() && e('count:5,lastID:5,fileID:2');  // 测试更新类型 project 上一个id 0 限制 10 的文件对象
r($upgrade->updateFileObjectsTest($type[2],  $lastID[0], $limit[0])) && p() && e('count:1,lastID:4,fileID:3');  // 测试更新类型 bug 上一个id 3 限制 1 的文件对象
r($upgrade->updateFileObjectsTest($type[2],  $lastID[1], $limit[1])) && p() && e('count:5,lastID:5,fileID:3');  // 测试更新类型 bug 上一个id 0 限制 10 的文件对象
r($upgrade->updateFileObjectsTest($type[3],  $lastID[0], $limit[0])) && p() && e('count:1,lastID:4,fileID:4');  // 测试更新类型 release 上一个id 3 限制 1 的文件对象
r($upgrade->updateFileObjectsTest($type[3],  $lastID[1], $limit[1])) && p() && e('count:5,lastID:5,fileID:4');  // 测试更新类型 release 上一个id 0 限制 10 的文件对象
r($upgrade->updateFileObjectsTest($type[4],  $lastID[0], $limit[0])) && p() && e('count:1,lastID:4,fileID:5');  // 测试更新类型 productplan 上一个id 3 限制 1 的文件对象
r($upgrade->updateFileObjectsTest($type[4],  $lastID[1], $limit[1])) && p() && e('count:5,lastID:5,fileID:5');  // 测试更新类型 productplan 上一个id 0 限制 10 的文件对象
r($upgrade->updateFileObjectsTest($type[5],  $lastID[0], $limit[0])) && p() && e('count:1,lastID:4,fileID:6');  // 测试更新类型 product 上一个id 3 限制 1 的文件对象
r($upgrade->updateFileObjectsTest($type[5],  $lastID[1], $limit[1])) && p() && e('count:5,lastID:5,fileID:6');  // 测试更新类型 product 上一个id 0 限制 10 的文件对象
r($upgrade->updateFileObjectsTest($type[6],  $lastID[0], $limit[0])) && p() && e('count:1,lastID:4,fileID:7');  // 测试更新类型 story 上一个id 3 限制 1 的文件对象
r($upgrade->updateFileObjectsTest($type[6],  $lastID[1], $limit[1])) && p() && e('count:5,lastID:5,fileID:7');  // 测试更新类型 story 上一个id 0 限制 10 的文件对象
r($upgrade->updateFileObjectsTest($type[7],  $lastID[0], $limit[0])) && p() && e('count:1,lastID:4,fileID:8');  // 测试更新类型 testtask 上一个id 3 限制 1 的文件对象
r($upgrade->updateFileObjectsTest($type[7],  $lastID[1], $limit[1])) && p() && e('count:5,lastID:5,fileID:8');  // 测试更新类型 testtask 上一个id 0 限制 10 的文件对象
r($upgrade->updateFileObjectsTest($type[8],  $lastID[0], $limit[0])) && p() && e('count:1,lastID:4,fileID:9');  // 测试更新类型 todo 上一个id 3 限制 1 的文件对象
r($upgrade->updateFileObjectsTest($type[8],  $lastID[1], $limit[1])) && p() && e('count:5,lastID:5,fileID:9');  // 测试更新类型 todo 上一个id 0 限制 10 的文件对象
r($upgrade->updateFileObjectsTest($type[9],  $lastID[0], $limit[0])) && p() && e('count:1,lastID:4,fileID:10'); // 测试更新类型 task 上一个id 3 限制 1 的文件对象
r($upgrade->updateFileObjectsTest($type[9],  $lastID[1], $limit[1])) && p() && e('count:5,lastID:5,fileID:10'); // 测试更新类型 task 上一个id 0 限制 10 的文件对象
r($upgrade->updateFileObjectsTest($type[10], $lastID[0], $limit[0])) && p() && e('count:1,lastID:4,fileID:11'); // 测试更新类型 build 上一个id 3 限制 1 的文件对象
r($upgrade->updateFileObjectsTest($type[10], $lastID[1], $limit[1])) && p() && e('count:5,lastID:5,fileID:11'); // 测试更新类型 build 上一个id 0 限制 10 的文件对象
