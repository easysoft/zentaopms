#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$caseData = zenData('case');
$caseData->loadYaml('openedby')->gen(10);

/**

title=测试 testcaseModel->getByOpenedBy();
timeout=0
cid=18978

- 测试查询由 test1 创建的case信息
 - 第1条的title属性 @这个是测试用例1
 - 第5条的title属性 @这个是测试用例5
 - 第9条的title属性 @这个是测试用例9
- 测试查询由 test2 创建的case信息
 - 第2条的title属性 @这个是测试用例2
 - 第6条的title属性 @这个是测试用例6
 - 第10条的title属性 @这个是测试用例10
- 测试查询由 test3 创建的case信息
 - 第3条的title属性 @这个是测试用例3
 - 第7条的title属性 @这个是测试用例7
- 测试查询由 test4 创建的case信息
 - 第4条的title属性 @这个是测试用例4
 - 第8条的title属性 @这个是测试用例8

*/
$accountList = array('test1', 'test2', 'test3', 'test4');

$testcase = new testcaseModelTest();

r($testcase->getByOpenedByTest($accountList[0])) && p('1:title;5:title;9:title')  && e('这个是测试用例1;这个是测试用例5;这个是测试用例9');  // 测试查询由 test1 创建的case信息
r($testcase->getByOpenedByTest($accountList[1])) && p('2:title;6:title;10:title') && e('这个是测试用例2;这个是测试用例6;这个是测试用例10'); // 测试查询由 test2 创建的case信息
r($testcase->getByOpenedByTest($accountList[2])) && p('3:title;7:title')          && e('这个是测试用例3;这个是测试用例7');                  // 测试查询由 test3 创建的case信息
r($testcase->getByOpenedByTest($accountList[3])) && p('4:title;8:title')          && e('这个是测试用例4;这个是测试用例8');                  // 测试查询由 test4 创建的case信息