#!/usr/bin/env php
<?php

/**

title=测试 aiModel::setInjectData();
timeout=0
cid=15065

- 执行aiTest模块的setInjectDataTest方法，参数是'story.create', 'test story data'  @0
- 执行aiTest模块的setInjectDataTest方法，参数是'story.change', 'test change data'  @0
- 执行aiTest模块的setInjectDataTest方法，参数是array  @0
- 执行aiTest模块的setInjectDataTest方法，参数是'productplan.create', array  @0
- 执行aiTest模块的setInjectDataTest方法，参数是'bug.edit', 'test bug data'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

r($aiTest->setInjectDataTest('story.create', 'test story data')) && p() && e('0');
r($aiTest->setInjectDataTest('story.change', 'test change data')) && p() && e('0');
r($aiTest->setInjectDataTest(array('task', 'edit'), 'test task data')) && p() && e('0');
r($aiTest->setInjectDataTest('productplan.create', array('name' => 'test plan', 'desc' => 'test description'))) && p() && e('0');
r($aiTest->setInjectDataTest('bug.edit', 'test bug data')) && p() && e('0');