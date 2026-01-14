#!/usr/bin/env php
<?php

/**

title=测试 convertModel::createTmpTable4Jira();
timeout=0
cid=15768

- 执行convertTest模块的createTmpTable4JiraTest方法 属性id @int(8)
- 执行convertTest模块的createTmpTable4JiraTest方法 属性AType @char(30)
- 执行convertTest模块的createTmpTable4JiraTest方法 属性AID @char(100)
- 执行convertTest模块的createTmpTable4JiraTest方法 属性BType @char(30)
- 执行convertTest模块的createTmpTable4JiraTest方法 属性BID @char(100)

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$convertTest = new convertModelTest();

r($convertTest->createTmpTable4JiraTest()) && p('id') && e('int(8)');
r($convertTest->createTmpTable4JiraTest()) && p('AType') && e('char(30)');
r($convertTest->createTmpTable4JiraTest()) && p('AID') && e('char(100)');
r($convertTest->createTmpTable4JiraTest()) && p('BType') && e('char(30)');
r($convertTest->createTmpTable4JiraTest()) && p('BID') && e('char(100)');