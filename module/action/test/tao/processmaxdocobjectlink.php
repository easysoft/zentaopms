#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processMaxDocObjectLink();
timeout=0
cid=0

- 步骤1：处理practice类型doc >> 应设置moduleName为assetlib，methodName为practiceView
- 步骤2：处理component类型doc >> 应设置moduleName为assetlib，methodName为componentView
- 步骤3：处理空assetLibType的doc >> 应保持原有moduleName和methodName
- 步骤4：处理非doc类型且有配置 >> 应设置moduleName为assetlib，methodName为taskView
- 步骤5：处理不存在的doc >> 应保持原有moduleName和methodName

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('doc')->loadYaml('doc_processmaxdocobjectlink', false, 2)->gen(10);

su('admin');

$actionTest = new actionTest();

r($actionTest->processMaxDocObjectLinkTest(1, 'doc', 'view', 'docID=%s')) && p('moduleName,methodName') && e('assetlib,practiceView');
r($actionTest->processMaxDocObjectLinkTest(2, 'doc', 'view', 'docID=%s')) && p('moduleName,methodName') && e('assetlib,componentView');
r($actionTest->processMaxDocObjectLinkTest(5, 'doc', 'view', 'docID=%s')) && p('moduleName,methodName') && e('doc,view');
r($actionTest->processMaxDocObjectLinkTest(1, 'task', 'view', 'taskID=%s')) && p('moduleName,methodName') && e('assetlib,taskView');
r($actionTest->processMaxDocObjectLinkTest(999, 'doc', 'view', 'docID=%s')) && p('moduleName,methodName') && e('doc,view');