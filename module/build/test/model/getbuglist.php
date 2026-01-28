#!/usr/bin/env php
<?php

/**

title=测试 buildModel::getBugList();
timeout=0
cid=15488

- 测试步骤1：传入空字符串 @0
- 测试步骤2：传入有效bugId列表第4条的title属性 @Bug5
- 测试步骤3：传入不存在的bugId列表 @0
- 测试步骤4：混合存在与不存在的bugId第0条的title属性 @Bug1
- 测试步骤5：验证返回结果的正确性第0条的title属性 @Bug11

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('bug')->loadYaml('bug')->gen(15);

zenData('user')->gen(5);
su('admin');

$buildTest = new buildModelTest();

r($buildTest->getBugListTest('')) && p() && e('0');                                      // 测试步骤1：传入空字符串
r($buildTest->getBugListTest('1,2,3,4,5')) && p('4:title') && e('Bug5');                // 测试步骤2：传入有效bugId列表
r($buildTest->getBugListTest('16,17,18,19,20')) && p() && e('0');                       // 测试步骤3：传入不存在的bugId列表
r($buildTest->getBugListTest('1,2,16,17,5')) && p('0:title') && e('Bug1'); // 测试步骤4：混合存在与不存在的bugId
r($buildTest->getBugListTest('11,12,13')) && p('0:title') && e('Bug11'); // 测试步骤5：验证返回结果的正确性