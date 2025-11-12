#!/usr/bin/env php
<?php

/**

title=测试 customZen::setGradeRule();
timeout=0
cid=0

- 执行customTest模块的setGradeRuleTest方法，参数是'story', array  @1
- 执行customTest模块的setGradeRuleTest方法，参数是'requirement', array  @1
- 执行customTest模块的setGradeRuleTest方法，参数是'epic', array  @1
- 执行customTest模块的setGradeRuleTest方法，参数是'story', array  @1
- 执行customTest模块的setGradeRuleTest方法，参数是'requirement', array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('config')->gen(0);

su('admin');

$customTest = new customZenTest();

r($customTest->setGradeRuleTest('story', array('gradeRule' => '1'))) && p() && e('1');
r($customTest->setGradeRuleTest('requirement', array('gradeRule' => '1'))) && p() && e('1');
r($customTest->setGradeRuleTest('epic', array('gradeRule' => '0'))) && p() && e('1');
r($customTest->setGradeRuleTest('story', array('gradeRule' => ''))) && p() && e('1');
r($customTest->setGradeRuleTest('requirement', array('gradeRule' => '1', 'otherConfig' => 'value'))) && p() && e('1');