#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getJiraStatusList();
timeout=0
cid=15782

- 步骤1：正常情况 @2
- 步骤2：空relations参数 @0
- 步骤3：step不存在 @0
- 步骤4：没有zentaoObject键 @0
- 步骤5：匹配数据 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$convertTest = new convertModelTest();

r($convertTest->getJiraStatusListTest(1, array('zentaoObject' => array(1 => 'story')))) && p() && e('2'); // 步骤1：正常情况
r($convertTest->getJiraStatusListTest(1, array())) && p() && e('0'); // 步骤2：空relations参数
r($convertTest->getJiraStatusListTest(999, array('zentaoObject' => array(1 => 'story')))) && p() && e('0'); // 步骤3：step不存在
r($convertTest->getJiraStatusListTest(1, array('otherKey' => 'value'))) && p() && e('0'); // 步骤4：没有zentaoObject键
r($convertTest->getJiraStatusListTest(2, array('zentaoObject' => array(2 => 'task')))) && p() && e('2'); // 步骤5：匹配数据