#!/usr/bin/env php
<?php

/**

title=测试 stakeholderModel::isClickable();
timeout=0
cid=18445

- 测试存在的干系人使用普通操作按钮 @1
- 测试存在的干系人使用notexists操作 @0
- 测试存在的干系人使用userissue操作(开源版) @0
- 测试不存在的干系人使用普通操作按钮 @1
- 测试空对象使用普通操作按钮 @1
- 测试大小写不敏感的操作名称 @0
- 测试默认可点击的其他操作 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('stakeholder')->gen(2);

su('admin');

$stakeholderTester = new stakeholderModelTest();
r($stakeholderTester->isClickableTest(1, 'edit')) && p() && e('1');         // 测试存在的干系人使用普通操作按钮
r($stakeholderTester->isClickableTest(1, 'notexists')) && p() && e('0');    // 测试存在的干系人使用notexists操作
r($stakeholderTester->isClickableTest(1, 'userissue')) && p() && e('0');    // 测试存在的干系人使用userissue操作(开源版)
r($stakeholderTester->isClickableTest(999, 'edit')) && p() && e('1');       // 测试不存在的干系人使用普通操作按钮
r($stakeholderTester->isClickableTest(0, 'delete')) && p() && e('1');       // 测试空对象使用普通操作按钮
r($stakeholderTester->isClickableTest(1, 'NOTEXISTS')) && p() && e('0');    // 测试大小写不敏感的操作名称
r($stakeholderTester->isClickableTest(1, 'view')) && p() && e('1');         // 测试默认可点击的其他操作