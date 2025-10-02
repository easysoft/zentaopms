#!/usr/bin/env php
<?php

/**

title=测试 searchTao::getObjectList();
cid=0

- 测试步骤1：项目类型对象列表获取 >> 验证返回项目对象列表
- 测试步骤2：执行类型对象列表获取 >> 验证返回执行对象列表
- 测试步骤3：需求类型对象列表获取 >> 验证返回需求对象列表
- 测试步骤4：空数据输入处理 >> 验证空数据返回0
- 测试步骤5：不存在模块类型处理 >> 验证过滤不存在的模块返回空数组

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

$searchTest = new searchTest();

r($searchTest->getObjectListTest(array('project' => array(1, 2)))) && p() && e('array');
r($searchTest->getObjectListTest(array('execution' => array(3, 4, 5)))) && p() && e('array');
r($searchTest->getObjectListTest(array('story' => array(1, 2)))) && p() && e('array');
r($searchTest->getObjectListTest(array())) && p() && e('0');
r($searchTest->getObjectListTest(array('nonexistent' => array(1, 2)))) && p() && e('array');