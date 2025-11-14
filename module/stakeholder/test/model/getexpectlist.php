#!/usr/bin/env php
<?php

/**

title=测试 stakeholderModel->getExpectList();
timeout=0
cid=18434

- 获取项目ID=0时，Id按照倒序排列的所有期望 @0
- 获取项目ID=0时，期望按照倒序排列的所有期望 @0
- 获取项目ID=11时，Id按照倒序排列的所有期望
 - 第19条的userID属性 @11
 - 第19条的project属性 @11
- 获取项目ID=11时，期望按照倒序排列的所有期望
 - 第19条的userID属性 @11
 - 第19条的project属性 @11
- 获取项目ID=11、queryID=0时，按照搜索条件获取Id按照倒序排列的所有期望
 - 第19条的userID属性 @11
 - 第19条的project属性 @11
- 获取项目ID=11、queryID=0时，按照搜索条件获取期望按照倒序排列的所有期望
 - 第19条的userID属性 @11
 - 第19条的project属性 @11
- 获取项目ID=11、queryID=1时，按照搜索条件获取Id按照倒序排列的所有期望
 - 第19条的userID属性 @11
 - 第19条的project属性 @11
- 获取项目ID=11、queryID=1时，按照搜索条件获取期望按照倒序排列的所有期望
 - 第19条的userID属性 @11
 - 第19条的project属性 @11
- 获取项目ID=11、queryID不存在时，按照搜索条件获取Id按照倒序排列的所有期望
 - 第19条的userID属性 @11
 - 第19条的project属性 @11
- 获取项目ID=11、queryID不存在时，按照搜索条件获取期望按照倒序排列的所有期望
 - 第19条的userID属性 @11
 - 第19条的project属性 @11
- 获取项目ID不存在时，Id按照倒序排列的所有期望 @0
- 获取项目ID不存在时，期望按照倒序排列的所有期望 @0
- 获取项目ID不存在、queryID=0时，按照搜索条件获取Id按照倒序排列的所有期望 @0
- 获取项目ID不存在、queryID=0时，按照搜索条件获取期望按照倒序排列的所有期望 @0
- 获取项目ID不存在、queryID=1时，按照搜索条件获取Id按照倒序排列的所有期望 @0
- 获取项目ID不存在、queryID=1时，按照搜索条件获取期望按照倒序排列的所有期望 @0
- 获取项目ID不存在、queryID不存在时，按照搜索条件获取Id按照倒序排列的所有期望 @0
- 获取项目ID不存在、queryID不存在时，按照搜索条件获取期望按照倒序排列的所有期望 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/stakeholder.unittest.class.php';

zenData('user')->gen(20);
zenData('expect')->loadYaml('expect')->gen(20);
zenData('stakeholder')->loadYaml('stakeholder')->gen(20);

$userqueryTable = zenData('userquery');
$userqueryTable->id->range('1');
$userqueryTable->sql->range("`(( 1  AND `project` = '11' ) AND ( 1  ))`");
$userqueryTable->module->range('stakeholder');
$userqueryTable->gen(1);

$projectIds  = array(0, 11, 200);
$browseTypes = array('all', 'bysearch');
$queryIds    = array(0, 1, 2);
$sorts       = array('id_desc', 'expect_desc');

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->getExpectListTest($projectIds[0], $browseTypes[0], $queryIds[0], $sorts[0])) && p()                    && e('0');     // 获取项目ID=0时，Id按照倒序排列的所有期望
r($stakeholderTester->getExpectListTest($projectIds[0], $browseTypes[0], $queryIds[0], $sorts[1])) && p()                    && e('0');     // 获取项目ID=0时，期望按照倒序排列的所有期望
r($stakeholderTester->getExpectListTest($projectIds[1], $browseTypes[0], $queryIds[0], $sorts[0])) && p('19:userID,project') && e('11,11'); // 获取项目ID=11时，Id按照倒序排列的所有期望
r($stakeholderTester->getExpectListTest($projectIds[1], $browseTypes[0], $queryIds[0], $sorts[1])) && p('19:userID,project') && e('11,11'); // 获取项目ID=11时，期望按照倒序排列的所有期望
r($stakeholderTester->getExpectListTest($projectIds[1], $browseTypes[1], $queryIds[0], $sorts[0])) && p('19:userID,project') && e('11,11'); // 获取项目ID=11、queryID=0时，按照搜索条件获取Id按照倒序排列的所有期望
r($stakeholderTester->getExpectListTest($projectIds[1], $browseTypes[1], $queryIds[0], $sorts[1])) && p('19:userID,project') && e('11,11'); // 获取项目ID=11、queryID=0时，按照搜索条件获取期望按照倒序排列的所有期望
r($stakeholderTester->getExpectListTest($projectIds[1], $browseTypes[1], $queryIds[1], $sorts[0])) && p('19:userID,project') && e('11,11'); // 获取项目ID=11、queryID=1时，按照搜索条件获取Id按照倒序排列的所有期望
r($stakeholderTester->getExpectListTest($projectIds[1], $browseTypes[1], $queryIds[1], $sorts[1])) && p('19:userID,project') && e('11,11'); // 获取项目ID=11、queryID=1时，按照搜索条件获取期望按照倒序排列的所有期望
r($stakeholderTester->getExpectListTest($projectIds[1], $browseTypes[1], $queryIds[2], $sorts[0])) && p('19:userID,project') && e('11,11'); // 获取项目ID=11、queryID不存在时，按照搜索条件获取Id按照倒序排列的所有期望
r($stakeholderTester->getExpectListTest($projectIds[1], $browseTypes[1], $queryIds[2], $sorts[1])) && p('19:userID,project') && e('11,11'); // 获取项目ID=11、queryID不存在时，按照搜索条件获取期望按照倒序排列的所有期望
r($stakeholderTester->getExpectListTest($projectIds[2], $browseTypes[0], $queryIds[0], $sorts[0])) && p()                    && e('0');     // 获取项目ID不存在时，Id按照倒序排列的所有期望
r($stakeholderTester->getExpectListTest($projectIds[2], $browseTypes[0], $queryIds[0], $sorts[1])) && p()                    && e('0');     // 获取项目ID不存在时，期望按照倒序排列的所有期望
r($stakeholderTester->getExpectListTest($projectIds[2], $browseTypes[1], $queryIds[0], $sorts[0])) && p()                    && e('0');     // 获取项目ID不存在、queryID=0时，按照搜索条件获取Id按照倒序排列的所有期望
r($stakeholderTester->getExpectListTest($projectIds[2], $browseTypes[1], $queryIds[0], $sorts[1])) && p()                    && e('0');     // 获取项目ID不存在、queryID=0时，按照搜索条件获取期望按照倒序排列的所有期望
r($stakeholderTester->getExpectListTest($projectIds[2], $browseTypes[1], $queryIds[1], $sorts[0])) && p()                    && e('0');     // 获取项目ID不存在、queryID=1时，按照搜索条件获取Id按照倒序排列的所有期望
r($stakeholderTester->getExpectListTest($projectIds[2], $browseTypes[1], $queryIds[1], $sorts[1])) && p()                    && e('0');     // 获取项目ID不存在、queryID=1时，按照搜索条件获取期望按照倒序排列的所有期望
r($stakeholderTester->getExpectListTest($projectIds[2], $browseTypes[1], $queryIds[2], $sorts[0])) && p()                    && e('0');     // 获取项目ID不存在、queryID不存在时，按照搜索条件获取Id按照倒序排列的所有期望
r($stakeholderTester->getExpectListTest($projectIds[2], $browseTypes[1], $queryIds[2], $sorts[1])) && p()                    && e('0');     // 获取项目ID不存在、queryID不存在时，按照搜索条件获取期望按照倒序排列的所有期望