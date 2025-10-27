#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::getFilterOptionUrl();
timeout=0
cid=0

- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是array 属性method @post
- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是array 第data条的type属性 @options
- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是array 第data条的sql属性 @SELECT * FROM user
- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是array 第data条的saveAs属性 @test
- 执行pivotTest模块的getFilterOptionUrlTest方法，参数是array 第data条的originalField属性 @name

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

su('admin');

$pivotTest = new pivotTest();

r($pivotTest->getFilterOptionUrlTest(array('field' => 'status', 'from' => 'query', 'typeOption' => 'user', 'default' => 'active'))) && p('method') && e('post');
r($pivotTest->getFilterOptionUrlTest(array('field' => 'priority', 'from' => 'result', 'default' => array('1', '2')), '', array('priority' => array('type' => 'options', 'object' => 'bug', 'field' => 'priority')))) && p('data:type') && e('options');
r($pivotTest->getFilterOptionUrlTest(array('field' => 'name', 'default' => 'test', 'saveAs' => 'userName'), 'SELECT * FROM user', array('name' => array('type' => 'object', 'object' => 'user', 'field' => 'realname')))) && p('data:sql') && e('SELECT * FROM user');
r($pivotTest->getFilterOptionUrlTest(array('field' => 'test', 'from' => 'result'), '', array())) && p('data:saveAs') && e('test');
r($pivotTest->getFilterOptionUrlTest(array('field' => 'category', 'from' => 'result'), 'SELECT id,name FROM category WHERE deleted=0', array('category' => array('type' => 'select', 'object' => 'category', 'field' => 'name')))) && p('data:originalField') && e('name');