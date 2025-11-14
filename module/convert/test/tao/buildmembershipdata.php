#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildMemberShipData();
timeout=0
cid=15818

- 执行convertTest模块的buildMemberShipDataTest方法，参数是$testData1 属性id @1
- 执行convertTest模块的buildMemberShipDataTest方法，参数是$testData1 属性parent_id @1001
- 执行convertTest模块的buildMemberShipDataTest方法，参数是$testData1 属性child_id @2001
- 执行convertTest模块的buildMemberShipDataTest方法，参数是$testData4 属性parent_id @0
- 执行convertTest模块的buildMemberShipDataTest方法，参数是$testData5 属性membership_type @specialgroup

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

su('admin');

$convertTest = new convertTest();

$testData1 = array(
    'id' => 1,
    'parentId' => 1001,
    'childId' => 2001,
    'membershipType' => 'group',
    'parentName' => 'developers',
    'childName' => 'john.doe'
);

$testData2 = array(
    'id' => 2,
    'parentId' => '',
    'childId' => 2002,
    'membershipType' => '',
    'parentName' => 'testers',
    'childName' => ''
);

$testData3 = array(
    'id' => 3,
    'parentId' => 1003,
    'childId' => 2003,
    'membershipType' => 'user',
    'parentName' => 'managers',
    'childName' => 'admin'
);

$testData4 = array(
    'id' => 4,
    'parentId' => 0,
    'childId' => 2004,
    'membershipType' => 'empty',
    'parentName' => '',
    'childName' => 'empty_user'
);

$testData5 = array(
    'id' => 5,
    'parentId' => 1005,
    'childId' => 2005,
    'membershipType' => 'specialgroup',
    'parentName' => 'test-group_name',
    'childName' => 'user.name.test'
);

r($convertTest->buildMemberShipDataTest($testData1)) && p('id') && e('1');
r($convertTest->buildMemberShipDataTest($testData1)) && p('parent_id') && e('1001');
r($convertTest->buildMemberShipDataTest($testData1)) && p('child_id') && e('2001');
r($convertTest->buildMemberShipDataTest($testData4)) && p('parent_id') && e('0');
r($convertTest->buildMemberShipDataTest($testData5)) && p('membership_type') && e('specialgroup');