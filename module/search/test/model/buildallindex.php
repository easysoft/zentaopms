#!/usr/bin/env php
<?php

/**

title=测试 searchModel::buildAllIndex();
timeout=0
cid=18292

- 测试构建所有索引从空开始属性finished @1
- 测试指定类型为build构建索引属性type @build
- 测试构建索引返回的数量正确属性count @5
- 测试指定类型为task构建索引属性type @task
- 测试task类型的数量正确属性count @20

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$search = new searchModelTest();

r($search->buildAllIndexTest()) && p('finished') && e('1');  // 测试构建所有索引从空开始
r($search->buildAllIndexTest('build')) && p('type') && e('build');  // 测试指定类型为build构建索引
r($search->buildAllIndexTest('build')) && p('count') && e('5');  // 测试构建索引返回的数量正确
r($search->buildAllIndexTest('task')) && p('type') && e('task');  // 测试指定类型为task构建索引
r($search->buildAllIndexTest('task')) && p('count') && e('20');  // 测试task类型的数量正确