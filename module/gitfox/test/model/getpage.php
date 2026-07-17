#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::getPage();
timeout=0
cid=0

- 执行model模块的getPage方法，参数是null  @0
- 执行model模块的getPage方法
 - 属性page @3
 - 属性pageSize @50
- 执行model模块的getPage方法
 - 属性page @5
 - 属性pageSize @20
- 执行model模块的getPage方法，参数是new stdclass
 - 属性page @1
 - 属性pageSize @20
- 执行model模块的getPage方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r(count($model->getPage(null))) && p() && e('0');
r($model->getPage((object)array('pageID' => 3, 'recPerPage' => 50))) && p('page,pageSize') && e('3,50');
r($model->getPage((object)array('pageID' => 5))) && p('page,pageSize') && e('5,20');
r($model->getPage(new stdclass())) && p('page,pageSize') && e('1,20');
r(isset($model->getPage((object)array('pageID' => 3, 'recPerPage' => 50))['page'])) && p() && e('1');
