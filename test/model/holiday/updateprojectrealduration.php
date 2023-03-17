#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/holiday.class.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';
su('admin');

/**

title=测试 holidayModel->updateProjectRealDuration();
cid=1
pid=1

测试新插入holiday1时项目的realDuration字段 >> 29
测试新插入holiday3时项目的realDuration字段 >> 29
测试新插入holiday99时项目的realDuration字段 >> 29
测试新插入holiday100时项目的realDuration字段 >> 29

*/

$project  = new Project();

$insertNewProject = array(
    'parent'     => 1,
    'name'       => '用于测试holiday的项目',
    'budget'     => '',
    'budgetUnit' => 'CNY',
    'begin'      => '2022-03-07',
    'end'        => '2022-03-10',
    'realBegan'  => '2022-04-01',
    'realEnd'    => '2022-05-01',
    'desc'       => '测试项目描述',
    'acl'        => 'private',
    'whitelist'  => '',
    'PM'         => '',
    'products'   => array(1),
);

$newProject   = $project->create($insertNewProject);
$newProjectID = $newProject->id;

$holidayIDList = array('1', '3', '99', '100');

$holiday = new holidayTest();

r($holiday->updateProjectRealDurationTest($newProjectID, $holidayIDList[0])) && p() && e('29'); //测试新插入holiday1时项目的realDuration字段
r($holiday->updateProjectRealDurationTest($newProjectID, $holidayIDList[1])) && p() && e('29'); //测试新插入holiday3时项目的realDuration字段
r($holiday->updateProjectRealDurationTest($newProjectID, $holidayIDList[2])) && p() && e('29'); //测试新插入holiday99时项目的realDuration字段
r($holiday->updateProjectRealDurationTest($newProjectID, $holidayIDList[3])) && p() && e('29'); //测试新插入holiday100时项目的realDuration字段

