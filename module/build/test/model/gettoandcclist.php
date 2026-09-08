#!/usr/bin/env php
<?php

/**

title=测试 buildModel::getToAndCcList();
timeout=0
cid=18004

- 测试构建者和创建者不同 @user1|user2
- 测试构建者和创建者相同 @admin|~~
- 测试构建者为空时使用创建者 @user3|~~
- 测试创建者为空时使用构建者 @user4|~~
- 测试构建者和创建者都为空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$buildTester = new buildModelTest();

$build1 = new stdClass();
$build1->builder   = 'user1';
$build1->createdBy = 'user2';

$build2 = new stdClass();
$build2->builder   = 'admin';
$build2->createdBy = 'admin';

$build3 = new stdClass();
$build3->builder   = '';
$build3->createdBy = 'user3';

$build4 = new stdClass();
$build4->builder   = 'user4';
$build4->createdBy = '';

$build5 = new stdClass();
$build5->builder   = '';
$build5->createdBy = '';

r($buildTester->getToAndCcListTest($build1)) && p('0|1', '|') && e('user1|user2'); // 测试构建者和创建者不同
r($buildTester->getToAndCcListTest($build2)) && p('0|1', '|') && e('admin|~~');    // 测试构建者和创建者相同
r($buildTester->getToAndCcListTest($build3)) && p('0|1', '|') && e('user3|~~');    // 测试构建者为空时使用创建者
r($buildTester->getToAndCcListTest($build4)) && p('0|1', '|') && e('user4|~~');    // 测试创建者为空时使用构建者
r($buildTester->getToAndCcListTest($build5)) && p() && e('0');                     // 测试构建者和创建者都为空
