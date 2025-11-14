#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('user')->gen('1');
zenData('case')->gen('5');
zenData('story')->gen('10');

su('admin');

/**

title=测试 testcaseModel->getById();
cid=18976
pid=1

- 测试获取case 1 的信息
 - 属性title @这个是测试用例1
 - 属性type @feature
 - 属性status @wait
- 测试获取case 2 的信息
 - 属性title @这个是测试用例2
 - 属性type @performance
 - 属性status @normal
- 测试获取case 3 的信息
 - 属性title @这个是测试用例3
 - 属性type @config
 - 属性status @blocked
- 测试获取case 4 的信息
 - 属性title @这个是测试用例4
 - 属性type @install
 - 属性status @investigate
- 测试获取case 5 的信息
 - 属性title @这个是测试用例5
 - 属性type @security
 - 属性status @wait
- 测试获取不存在的 case 的信息 @0

*/

$caseIDList = array(1, 2, 3, 4, 5, 1001);

$testcase = new testcaseTest();

r($testcase->getByIdTest($caseIDList[0])) && p('title,type,status') && e('这个是测试用例1,feature,wait');        // 测试获取case 1 的信息
r($testcase->getByIdTest($caseIDList[1])) && p('title,type,status') && e('这个是测试用例2,performance,normal');  // 测试获取case 2 的信息
r($testcase->getByIdTest($caseIDList[2])) && p('title,type,status') && e('这个是测试用例3,config,blocked');      // 测试获取case 3 的信息
r($testcase->getByIdTest($caseIDList[3])) && p('title,type,status') && e('这个是测试用例4,install,investigate'); // 测试获取case 4 的信息
r($testcase->getByIdTest($caseIDList[4])) && p('title,type,status') && e('这个是测试用例5,security,wait');       // 测试获取case 5 的信息
r($testcase->getByIdTest($caseIDList[5])) && p()                    && e('0');                                   // 测试获取不存在的 case 的信息
