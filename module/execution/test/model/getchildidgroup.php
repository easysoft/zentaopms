#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

zenData('project')->loadYaml('execution')->gen(50);
zenData('user')->gen(1);

su('admin');

/**

title=测试executionModel->getChildIdGroupTest();
cid=16309

- 测试获取父ID 11 的所有子ID @11:101,102,103,104,105,133,134,135,136,137;

- 测试获取父ID 0 的所有子ID @0
- 测试获取父ID 106 的所有子ID @106:112,144;

- 测试获取父ID 106,107 的所有子ID @106:112,144;107:113,145;

- 测试获取父ID 108,109,110 的所有子ID @108:114,146;109:115;110:116;

*/

$executionIDList = array('11', '0', '106', '106,107', '108,109,110');

$executionTester = new executionTest();
r($executionTester->getChildIdGroupTest($executionIDList[0])) && p() && e('11:101,102,103,104,105,133,134,135,136,137;'); // 测试获取父ID 11 的所有子ID
r($executionTester->getChildIdGroupTest($executionIDList[1])) && p() && e('0');                                           // 测试获取父ID 0 的所有子ID
r($executionTester->getChildIdGroupTest($executionIDList[2])) && p() && e('106:112,144;');                                // 测试获取父ID 106 的所有子ID
r($executionTester->getChildIdGroupTest($executionIDList[3])) && p() && e('106:112,144;107:113,145;');                    // 测试获取父ID 106,107 的所有子ID
r($executionTester->getChildIdGroupTest($executionIDList[4])) && p() && e('108:114,146;109:115;110:116;');                // 测试获取父ID 108,109,110 的所有子ID
