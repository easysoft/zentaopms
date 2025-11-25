#!/usr/bin/env php
<?php

/**

title=测试 groupModel::processDepends();
timeout=0
cid=16721

- 执行groupTest模块的processDependsTest方法，参数是array 属性user-view @user-view
- 执行groupTest模块的processDependsTest方法，参数是array 属性user-view @user-view
- 执行groupTest模块的processDependsTest方法，参数是array 属性user-browse @user-browse
- 执行groupTest模块的processDependsTest方法，参数是array 属性user-view @~~
- 执行groupTest模块的processDependsTest方法，参数是array 属性user-view @user-view

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/group.unittest.class.php';

su('admin');

$groupTest = new groupTest();

r($groupTest->processDependsTest(array(), array('user-view' => 'user-view'), array())) && p('user-view') && e('user-view');
r($groupTest->processDependsTest(array('user-edit' => array('user-view')), array('user-edit' => 'user-edit'), array())) && p('user-view') && e('user-view');
r($groupTest->processDependsTest(array('user-edit' => array('user-view'), 'user-view' => array('user-browse')), array('user-edit' => 'user-edit'), array())) && p('user-browse') && e('user-browse');
r($groupTest->processDependsTest(array('user-edit' => array('user-view')), array('user-edit' => 'user-edit'), array('user-view'))) && p('user-view') && e('~~');
r($groupTest->processDependsTest(array('user-edit' => array('user-view'), 'user-view' => array('user-edit')), array('user-edit' => 'user-edit'), array())) && p('user-view') && e('user-view');