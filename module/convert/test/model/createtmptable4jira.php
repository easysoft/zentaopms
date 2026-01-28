#!/usr/bin/env php
<?php

/**

title=测试 convertModel::createTmpTable4Jira();
timeout=0
cid=15768

- 执行convertTest模块的createTmpTable4JiraTest方法 第id条的type属性 @int(8)
- 执行convertTest模块的createTmpTable4JiraTest方法 第AType条的type属性 @char(30)
- 执行convertTest模块的createTmpTable4JiraTest方法 第AID条的type属性 @char(100)
- 执行convertTest模块的createTmpTable4JiraTest方法 第BType条的type属性 @char(30)
- 执行convertTest模块的createTmpTable4JiraTest方法 第BID条的type属性 @char(100)

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$convertTest = new convertModelTest();

r($convertTest->createTmpTable4JiraTest()) && p('id:type')    && e('int(8)');
r($convertTest->createTmpTable4JiraTest()) && p('AType:type') && e('char(30)');
r($convertTest->createTmpTable4JiraTest()) && p('AID:type')   && e('char(100)');
r($convertTest->createTmpTable4JiraTest()) && p('BType:type') && e('char(30)');
r($convertTest->createTmpTable4JiraTest()) && p('BID:type')   && e('char(100)');
