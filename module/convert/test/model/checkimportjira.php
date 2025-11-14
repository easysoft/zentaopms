#!/usr/bin/env php
<?php

/**

title=测试 convertModel::checkImportJira();
timeout=0
cid=15763

- 执行convertTest模块的checkImportJiraTest方法，参数是'object', array  @1
- 执行convertTest模块的checkImportJiraTest方法，参数是'object', array 属性message @『禅道对象』不能为空。
- 执行convertTest模块的checkImportJiraTest方法，参数是'object', array  @1
- 执行convertTest模块的checkImportJiraTest方法，参数是'status', array  @1
- 执行convertTest模块的checkImportJiraTest方法，参数是'', array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

su('admin');

$convertTest = new convertTest();

r($convertTest->checkImportJiraTest('object', array('jiraObject' => array('1', '2'), 'zentaoObject' => array('1' => 'story', '2' => 'task')))) && p() && e('1');
r($convertTest->checkImportJiraTest('object', array('jiraObject' => array('1', '2'), 'zentaoObject' => array('1' => 'story', '2' => '')))) && p('message') && e('『禅道对象』不能为空。');
r($convertTest->checkImportJiraTest('object', array('jiraObject' => array(), 'zentaoObject' => array()))) && p() && e('1');
r($convertTest->checkImportJiraTest('status', array('jiraObject' => array('1'), 'zentaoObject' => array('1' => 'story')))) && p() && e('1');
r($convertTest->checkImportJiraTest('', array('jiraObject' => array('1'), 'zentaoObject' => array('1' => 'story')))) && p() && e('1');