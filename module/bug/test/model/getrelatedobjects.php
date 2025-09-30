#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

/**

title=测试 bugModel::getRelatedObjects();
timeout=0
cid=0

- 步骤1：测试product对象返回值是否正确 @1
- 步骤2：测试project对象返回值是否正确 @1
- 步骤3：测试openedBuild对象转换处理 @1
- 步骤4：测试resolvedBuild对象转换处理 @1
- 步骤5：测试故事对象处理 @1

*/

$bugTest = new bugTest();

r($bugTest->getRelatedObjectsTest('product', 'id,name')) && p() && e('1'); // 步骤1：测试product对象返回值是否正确
r($bugTest->getRelatedObjectsTest('project', 'id,name')) && p() && e('1'); // 步骤2：测试project对象返回值是否正确
r($bugTest->getRelatedObjectsTest('openedBuild', 'id,name')) && p() && e('1'); // 步骤3：测试openedBuild对象转换处理
r($bugTest->getRelatedObjectsTest('resolvedBuild', 'id,name')) && p() && e('1'); // 步骤4：测试resolvedBuild对象转换处理
r($bugTest->getRelatedObjectsTest('story', 'id,title')) && p() && e('1'); // 步骤5：测试故事对象处理