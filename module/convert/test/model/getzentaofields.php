#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getZentaoFields();
timeout=0
cid=0

- 执行convertTest模块的getZentaoFieldsTest方法，参数是'epic'  @7
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'story'  @7
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'bug'  @14
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'task'  @6
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'testcase'  @10
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'requirement'  @7
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'notexist'  @1
- 执行convertTest模块的getZentaoFieldsTest方法，参数是''

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('workflowfield')->gen(0);

su('admin');

$convertTest = new convertModelTest();

r(count($convertTest->getZentaoFieldsTest('epic')))        && p() && e('7');
r(count($convertTest->getZentaoFieldsTest('story')))       && p() && e('7');
r(count($convertTest->getZentaoFieldsTest('bug')))         && p() && e('14');
r(count($convertTest->getZentaoFieldsTest('task')))        && p() && e('6');
r(count($convertTest->getZentaoFieldsTest('testcase')))    && p() && e('10');
r(count($convertTest->getZentaoFieldsTest('requirement'))) && p() && e('7');
r(count($convertTest->getZentaoFieldsTest('notexist')))    && p() && e('1');
r(count($convertTest->getZentaoFieldsTest('')))            && p() && e('1');
