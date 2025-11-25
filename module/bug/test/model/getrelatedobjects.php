#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';
su('admin');

// 准备测试数据
$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->deleted->range('0');
$product->gen(3);

$story = zenData('story');
$story->id->range('1-3');
$story->title->range('需求1,需求2,需求3');
$story->product->range('1-2');
$story->deleted->range('0');
$story->gen(3);

$build = zenData('build');
$build->id->range('1-2');
$build->name->range('构建1,构建2');
$build->product->range('1-2');
$build->deleted->range('0');
$build->gen(2);

$bug = zenData('bug');
$bug->id->range('1-5');
$bug->title->range('缺陷1,缺陷2,缺陷3,缺陷4,缺陷5');
$bug->product->range('1-3');
$bug->story->range('1-3');
$bug->openedBuild->range('1-2');
$bug->resolvedBuild->range('1-2');
$bug->deleted->range('0');
$bug->gen(5);

/**

title=测试 bugModel::getRelatedObjects();
timeout=0
cid=15393

- 测试product对象返回关联对象列表
 - 属性count @4
 - 属性hasEmpty @1
 - 属性hasZero @1
- 测试story对象返回关联对象列表
 - 属性count @4
 - 属性hasEmpty @1
 - 属性hasZero @1
- 测试build对象返回包含trunk选项
 - 属性count @4
 - 属性hasEmpty @1
 - 属性hasZero @1
 - 属性hasTrunk @1
- 测试openedBuild对象转换为build处理
 - 属性count @4
 - 属性hasEmpty @1
 - 属性hasZero @1
 - 属性hasTrunk @1
- 测试resolvedBuild对象转换为build处理
 - 属性count @4
 - 属性hasEmpty @1
 - 属性hasZero @1
 - 属性hasTrunk @1

*/

$bugTest = new bugTest();

r($bugTest->getRelatedObjectsTest('product', 'id,name')) && p('count,hasEmpty,hasZero') && e('4,1,1'); // 测试product对象返回关联对象列表
r($bugTest->getRelatedObjectsTest('story', 'id,title')) && p('count,hasEmpty,hasZero') && e('4,1,1'); // 测试story对象返回关联对象列表
r($bugTest->getRelatedObjectsTest('build', 'id,name')) && p('count,hasEmpty,hasZero,hasTrunk') && e('4,1,1,1'); // 测试build对象返回包含trunk选项
r($bugTest->getRelatedObjectsTest('openedBuild', 'id,name')) && p('count,hasEmpty,hasZero,hasTrunk') && e('4,1,1,1'); // 测试openedBuild对象转换为build处理
r($bugTest->getRelatedObjectsTest('resolvedBuild', 'id,name')) && p('count,hasEmpty,hasZero,hasTrunk') && e('4,1,1,1'); // 测试resolvedBuild对象转换为build处理