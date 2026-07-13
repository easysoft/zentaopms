#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::getToAndCcList();
timeout=0
cid=0

- 执行ppmModel模块的getToAndCcListTest方法  @admin,user1

- 执行ppmModel模块的getToAndCcListTest方法  @admin,

- 执行ppmModel模块的getToAndCcListTest方法  @user1,admin

- 执行ppmModel模块的getToAndCcListTest方法  @admin,admin

- 执行ppmModel模块的getToAndCcListTest方法  @2
- 执行ppmModel模块的getToAndCcListTest方法  @,

- 执行ppmModel模块的getToAndCcListTest方法  @admin,user2

- 执行ppmModel模块的getToAndCcListTest方法  @2
- 执行ppmModel模块的getToAndCcListTest方法  @,user1

- 执行ppmModel模块的getToAndCcListTest方法  @user2,user3

- 执行ppmModel模块的getToAndCcListTest方法  @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$ppmModel = new ppmModelTest();

r(implode(',', $ppmModel->getToAndCcListTest((object)array('createdBy' => 'admin', 'assignee' => 'user1')))) && p() && e('admin,user1');
r(implode(',', $ppmModel->getToAndCcListTest((object)array('createdBy' => 'admin', 'assignee' => '')))) && p() && e('admin,');
r(implode(',', $ppmModel->getToAndCcListTest((object)array('createdBy' => 'user1', 'assignee' => 'admin')))) && p() && e('user1,admin');
r(implode(',', $ppmModel->getToAndCcListTest((object)array('createdBy' => 'admin', 'assignee' => 'admin')))) && p() && e('admin,admin');
r(count($ppmModel->getToAndCcListTest((object)array('createdBy' => 'admin', 'assignee' => 'user1')))) && p() && e('2');
r(implode(',', $ppmModel->getToAndCcListTest((object)array('createdBy' => '', 'assignee' => '')))) && p() && e(',');
r(implode(',', $ppmModel->getToAndCcListTest((object)array('createdBy' => 'admin', 'assignee' => 'user2')))) && p() && e('admin,user2');
r(count($ppmModel->getToAndCcListTest((object)array('createdBy' => '', 'assignee' => 'user1')))) && p() && e('2');
r(implode(',', $ppmModel->getToAndCcListTest((object)array('createdBy' => '', 'assignee' => 'user1')))) && p() && e(',user1');
r(implode(',', $ppmModel->getToAndCcListTest((object)array('createdBy' => 'user2', 'assignee' => 'user3')))) && p() && e('user2,user3');
r($ppmModel->getToAndCcListTest((object)array('createdBy' => '', 'assignee' => 'user1'))) && p('0') && e('~~');