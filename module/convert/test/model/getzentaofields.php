#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getZentaoFields();
timeout=0
cid=0

- 执行convertTest模块的getZentaoFieldsTest方法，参数是'epic'  @6
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'story'  @6
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'bug'  @13
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'task'  @5
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'testcase'  @9
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'requirement'  @6
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'notexist'  @0
- 执行convertTest模块的getZentaoFieldsTest方法，参数是''  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$convertTest = new convertModelTest();

r(count($convertTest->getZentaoFieldsTest('epic')))        && p() && e('6');
r(count($convertTest->getZentaoFieldsTest('story')))       && p() && e('6');
r(count($convertTest->getZentaoFieldsTest('bug')))         && p() && e('13');
r(count($convertTest->getZentaoFieldsTest('task')))        && p() && e('5');
r(count($convertTest->getZentaoFieldsTest('testcase')))    && p() && e('9');
r(count($convertTest->getZentaoFieldsTest('requirement'))) && p() && e('6');
r(count($convertTest->getZentaoFieldsTest('notexist')))    && p() && e('0');
r(count($convertTest->getZentaoFieldsTest('')))            && p() && e('0');