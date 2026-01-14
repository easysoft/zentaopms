#!/usr/bin/env php
<?php

/**

title=测试 docModel->deleteTemplate();
cid=16065

- 测试删除ID为0的模板 @0
- 测试删除ID为1的模板 @1
- 测试删除ID为2的模板 @1
- 测试删除ID为3的模板 @1
- 测试删除ID不存在的模板 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('doc')->loadYaml('template')->gen(10);
zenData('user')->gen(5);
su('admin');

$templateIdList = array(0, 1, 2, 3, 11);

$docTester = new docModelTest();
r($docTester->deleteTemplateTest($templateIdList[0])) && p() && e('0'); // 测试删除ID为0的模板
r($docTester->deleteTemplateTest($templateIdList[1])) && p() && e('1'); // 测试删除ID为1的模板
r($docTester->deleteTemplateTest($templateIdList[2])) && p() && e('1'); // 测试删除ID为2的模板
r($docTester->deleteTemplateTest($templateIdList[3])) && p() && e('1'); // 测试删除ID为3的模板
r($docTester->deleteTemplateTest($templateIdList[4])) && p() && e('0'); // 测试删除ID不存在的模板
