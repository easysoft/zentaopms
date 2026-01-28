#!/usr/bin/env php
<?php

/**

title=测试 actionModel::getAccountFirstAction();
timeout=0
cid=14887

- 执行actionTest模块的getAccountFirstActionTest方法，参数是'admin' 属性actor @admin
- 执行actionTest模块的getAccountFirstActionTest方法，参数是'user' 属性actor @user
- 执行actionTest模块的getAccountFirstActionTest方法，参数是'nonexistuser'  @0
- 执行actionTest模块的getAccountFirstActionTest方法，参数是''  @0
- 执行actionTest模块的getAccountFirstActionTest方法，参数是'special123'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('action');
$table->id->range('1-50');
$table->objectType->range('story,task,bug,build');
$table->objectID->range('1-10');
$table->product->range('0,1,2,3');
$table->project->range('0,1,2,3');
$table->execution->range('0,1,2,3');
$table->actor->range('admin{10},user{10},test{10},guest{10},dev{10}');
$table->action->range('opened,edited,changed,closed,activated');
$table->date->range('`2024-01-01 00:00:01`,`2024-01-02 00:00:01`,`2024-01-03 00:00:01`');
$table->comment->range('comment1,comment2,comment3');
$table->extra->range('extra1,extra2,extra3');
$table->read->range('0,1');
$table->vision->range('rnd');
$table->efforted->range('0,1');
$table->gen(50);

su('admin');

$actionTest = new actionModelTest();

r($actionTest->getAccountFirstActionTest('admin')) && p('actor') && e('admin');
r($actionTest->getAccountFirstActionTest('user')) && p('actor') && e('user');
r($actionTest->getAccountFirstActionTest('nonexistuser')) && p() && e('0');
r($actionTest->getAccountFirstActionTest('')) && p() && e('0');
r($actionTest->getAccountFirstActionTest('special123')) && p() && e('0');