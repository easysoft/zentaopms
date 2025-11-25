#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/compile.unittest.class.php';

zenData('compile')->gen(1);
zenData('job')->loadYaml('job')->gen(1);
zenData('repo')->loadYaml('repo')->gen(10);
su('admin');

/**

title=测试 compileModel->getList();
timeout=0
cid=15748

- 检查是否能获取到数据
 - 第1条的name属性 @构建1
 - 第1条的status属性 @success
 - 第1条的pipeline属性 @simple-job
 - 第1条的triggerType属性 @tag
- 检查获取不存在的数据会返回什么 @0
*/

$compile = new compileTest();

r($compile->getListTest(1, 1)) && p('1:name,status,pipeline,triggerType') && e('构建1,success,simple-job,tag'); //检查是否能获取到数据
r($compile->getListTest(3, 1)) && p('')                       && e('0');                                        //检查获取不存在的数据会返回什么
