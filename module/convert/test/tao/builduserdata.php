#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildUserData();
timeout=0
cid=15827

- 执行convertTest模块的buildUserDataTest方法，参数是$testData 属性id @1
- 执行convertTest模块的buildUserDataTest方法，参数是$testData 属性account @admin
- 执行convertTest模块的buildUserDataTest方法，参数是$testData 属性realname @管理员
- 执行convertTest模块的buildUserDataTest方法，参数是$testData2 属性id @2
- 执行convertTest模块的buildUserDataTest方法，参数是$testData 属性join @2024-01-01 10:00:00

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$convertTest = new convertTaoTest();

$testData = array(
    'id' => 1,
    'lowerUserName' => 'admin',
    'lowerDisplayName' => '管理员',
    'emailAddress' => 'admin@test.com',
    'createdDate' => '2024-01-01 10:00:00'
);

$testData2 = array(
    'id' => 2,
    'lowerUserName' => 'user01',
    'lowerDisplayName' => '测试用户',
    'emailAddress' => 'user01@company.com',
    'createdDate' => '2024-01-02 11:00:00'
);

r($convertTest->buildUserDataTest($testData)) && p('id') && e('1');
r($convertTest->buildUserDataTest($testData)) && p('account') && e('admin');
r($convertTest->buildUserDataTest($testData)) && p('realname') && e('管理员');
r($convertTest->buildUserDataTest($testData2)) && p('id') && e('2');
r($convertTest->buildUserDataTest($testData)) && p('join') && e('2024-01-01 10:00:00');