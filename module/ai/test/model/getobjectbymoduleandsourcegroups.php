#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getObjectByModuleAndSourceGroups();
timeout=0
cid=25075

- 步骤1：产品模块按sourceGroups加载产品对象 @product:3
- 步骤2：产品模块未传product分组时返回空对象 @empty
- 步骤3：项目模块未传project分组时返回空对象 @empty
- 步骤4：my模块返回空对象 @empty
- 步骤5：未知模块返回空对象 @empty

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('product')->gen(5);

su('admin');

$aiTest = new aiModelTest();

$productSourceGroups  = array('product' => array('name', 'desc'));
$executionSourceGroup = array('executions' => array('name'));

r($aiTest->getObjectByModuleAndSourceGroupsTest('product', $productSourceGroups, 3))  && p() && e('product:3'); // 步骤1：产品模块按sourceGroups加载产品对象
r($aiTest->getObjectByModuleAndSourceGroupsTest('product', array(), 3))              && p() && e('empty');     // 步骤2：产品模块未传product分组时返回空对象
r($aiTest->getObjectByModuleAndSourceGroupsTest('project', $executionSourceGroup, 3)) && p() && e('empty');     // 步骤3：项目模块未传project分组时返回空对象
r($aiTest->getObjectByModuleAndSourceGroupsTest('my', array(), 0))                   && p() && e('empty');     // 步骤4：my模块返回空对象
r($aiTest->getObjectByModuleAndSourceGroupsTest('unknown', $productSourceGroups, 3)) && p() && e('empty');     // 步骤5：未知模块返回空对象
