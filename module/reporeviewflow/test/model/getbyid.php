#!/usr/bin/env php
<?php

/**

title=测试 reporeviewflowModel::getByID();
timeout=0
cid=0

- 测试ID=1查询属性id @1
- 测试ID=1查询属性name @review_flow1
- 测试ID=1查询属性status @enable
- 测试ID=0查询返回空 @0
- 测试ID=999查询返回空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('ops_repo')->loadYaml('ops_repo', false, 2)->gen(10);
zenData('ops_review_flow')->gen(10);

su('admin');

$tester = new reporeviewflowTest();
r($tester->getByID(1)) && p('id') && e('1');
r($tester->getByID(1)) && p('name') && e('review_flow1');
r($tester->getByID(1)) && p('status') && e('enable');
r($tester->getByID(0)) && p() && e('0');
r($tester->getByID(999)) && p() && e('0');
