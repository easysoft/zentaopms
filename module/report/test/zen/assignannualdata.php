#!/usr/bin/env php
<?php

/**

title=测试 reportZen::assignAnnualData();
timeout=0
cid=0

- 测试有account参数的情况,返回hasLogins标识属性hasLogins @yes
- 测试有dept参数的情况,返回hasUsers标识属性hasUsers @yes
- 测试dept为0且account为空的情况,返回allTimeStatus标识属性allTimeStatus @yes
- 测试dept不为0且accounts为空的边界情况,返回deptEmptyAccounts标识属性deptEmptyAccounts @yes
- 测试正常dept和accounts参数的情况,返回hasTodos标识属性hasTodos @yes
- 测试正常参数的情况,返回hasContributions标识属性hasContributions @yes
- 测试正常参数的情况,返回hasConsumed标识属性hasConsumed @yes

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

zenData('user')->gen(20);
zenData('dept')->gen(10);
zenData('action')->gen(100);
zenData('todo')->gen(50);
zenData('effort')->gen(50);
zenData('story')->gen(30);
zenData('task')->gen(40);
zenData('bug')->gen(25);
zenData('case')->gen(30);
zenData('product')->gen(5);

su('admin');

$reportTest = new reportTest();

r($reportTest->assignAnnualDataTest('2024', '1', 'admin', array('admin'), 10)) && p('hasLogins') && e('yes'); // 测试有account参数的情况,返回hasLogins标识
r($reportTest->assignAnnualDataTest('2024', '1', '', array('user1', 'user2'), 10)) && p('hasUsers') && e('yes'); // 测试有dept参数的情况,返回hasUsers标识
r($reportTest->assignAnnualDataTest('2024', '0', '', array(), 10)) && p('allTimeStatus') && e('yes'); // 测试dept为0且account为空的情况,返回allTimeStatus标识
r($reportTest->assignAnnualDataTest('2024', '1', '', array(), 10)) && p('deptEmptyAccounts') && e('yes'); // 测试dept不为0且accounts为空的边界情况,返回deptEmptyAccounts标识
r($reportTest->assignAnnualDataTest('2024', '1', '', array('admin', 'user1'), 10)) && p('hasTodos') && e('yes'); // 测试正常dept和accounts参数的情况,返回hasTodos标识
r($reportTest->assignAnnualDataTest('2024', '1', '', array('admin', 'user1'), 10)) && p('hasContributions') && e('yes'); // 测试正常参数的情况,返回hasContributions标识
r($reportTest->assignAnnualDataTest('2024', '1', '', array('admin', 'user1'), 10)) && p('hasConsumed') && e('yes'); // 测试正常参数的情况,返回hasConsumed标识