#!/usr/bin/env php
<?php

/**

title=测试 programZen::getPMListByPrograms();
timeout=0
cid=17729

- 执行programTest模块的getPMListByProgramsTest方法，参数是$testData1  @0
- 执行programTest模块的getPMListByProgramsTest方法，参数是$testData2  @2
- 执行programTest模块的getPMListByProgramsTest方法，参数是$testData3  @2
- 执行programTest模块的getPMListByProgramsTest方法，参数是$testData4  @2
- 执行programTest模块的getPMListByProgramsTest方法，参数是$testData5  @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programzen.unittest.class.php';

zendata('project')->loadYaml('project_getpmlistbyprograms', false, 2)->gen(20);
zendata('user')->loadYaml('user_getpmlistbyprograms', false, 2)->gen(10);

su('admin');

$programTest = new programTest();

$testData1 = array();
r($programTest->getPMListByProgramsTest($testData1)) && p() && e('0');

$testData2 = array(
    (object)array('id' => 1, 'type' => 'program', 'PM' => 'admin'),
    (object)array('id' => 2, 'type' => 'program', 'PM' => 'user1')
);
r($programTest->getPMListByProgramsTest($testData2)) && p() && e('2');

$testData3 = array(
    (object)array('id' => 1, 'type' => 'program', 'PM' => 'admin'),
    (object)array('id' => 2, 'type' => 'program', 'PM' => ''),
    (object)array('id' => 3, 'type' => 'program', 'PM' => 'user2')
);
r($programTest->getPMListByProgramsTest($testData3)) && p() && e('2');

$testData4 = array(
    (object)array('id' => 1, 'type' => 'program', 'PM' => 'admin'),
    (object)array('id' => 2, 'type' => 'program', 'PM' => 'admin'),
    (object)array('id' => 3, 'type' => 'program', 'PM' => 'user1')
);
r($programTest->getPMListByProgramsTest($testData4)) && p() && e('2');

$testData5 = array(
    (object)array('id' => 1, 'type' => 'program', 'PM' => 'admin'),
    (object)array('id' => 2, 'type' => 'project', 'PM' => 'user1')
);
r($programTest->getPMListByProgramsTest($testData5)) && p() && e('2');