#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

$caseData = zdTable('case');
$caseData->gen(10);

/**

title=测试 testcaseModel->getByOpenedBy();
timeout=0
cid=1

- 测试搜索产品1下的用例
 - 第1条的title属性 @这个是测试用例1
 - 第2条的title属性 @这个是测试用例2
 - 第3条的title属性 @这个是测试用例3
 - 第4条的title属性 @这个是测试用例4
- 测试搜索产品1 项目1 下的用例
 - 第1条的title属性 @这个是测试用例1
 - 第3条的title属性 @这个是测试用例3
 - 第5条的title属性 @这个是测试用例5
 - 第7条的title属性 @这个是测试用例7
 - 第9条的title属性 @这个是测试用例9

*/

$tabList       = array('qa', 'project');
$projectIdList = array('0', '1', '2');
$productIdList = array('0', '1', '2');

$testcase = new testcaseTest();
r($testcase->getBySearchTest($tabList[0], $projectIdList[0], $productIdList[1])) && p('1:title;2:title;3:title;4:title')         && e('这个是测试用例1;这个是测试用例2;这个是测试用例3;这个是测试用例4');  // 测试搜索产品1下的用例
r($testcase->getBySearchTest($tabList[1], $projectIdList[1], $productIdList[1])) && p('1:title;3:title;5:title;7:title;9:title') && e('这个是测试用例1;这个是测试用例3;这个是测试用例5;这个是测试用例7;这个是测试用例9');  // 测试搜索产品1 项目1 下的用例