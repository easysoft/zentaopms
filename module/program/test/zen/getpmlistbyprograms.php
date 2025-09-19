#!/usr/bin/env php
<?php

/**

title=测试 programZen::getPMListByPrograms();
timeout=0
cid=0

- 执行programTest模块的getPMListByProgramsTest方法，参数是array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programzen.unittest.class.php';

zendata('project')->loadYaml('project_getpmlistbyprograms', false, 2)->gen(20);
zendata('user')->loadYaml('user_getpmlistbyprograms', false, 2)->gen(10);

su('admin');

$programTest = new programTest();

r($programTest->getPMListByProgramsTest(array())) && p() && e('0');
r($programTest->getPMListByProgramsTest(array(
    (object)array('id' => 1, 'type' => 'program', 'PM' => 'admin'),
    (object)array('id' => 2, 'type' => 'program', 'PM' => 'user1')
))) && p() && e('2');
r($programTest->getPMListByProgramsTest(array(
    (object)array('id' => 1, 'type' => 'program', 'PM' => 'admin'),
    (object)array('id' => 2, 'type' => 'program', 'PM' => ''),
    (object)array('id' => 3, 'type' => 'program', 'PM' => 'user2')
))) && p() && e('2');
r($programTest->getPMListByProgramsTest(array(
    (object)array('id' => 1, 'type' => 'program', 'PM' => 'admin'),
    (object)array('id' => 2, 'type' => 'program', 'PM' => 'admin'),
    (object)array('id' => 3, 'type' => 'program', 'PM' => 'user1')
))) && p() && e('2');
r($programTest->getPMListByProgramsTest(array(
    (object)array('id' => 1, 'type' => 'program', 'PM' => 'admin'),
    (object)array('id' => 2, 'type' => 'project', 'PM' => 'user1')
))) && p() && e('2');