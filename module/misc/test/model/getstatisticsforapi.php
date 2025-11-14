#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 miscModel->getStatisticsForAPI();
timeout=0
cid=17214

- 不启用用户体验 @0
- 检查数据键值。 @user,project,execution,task,product,story,bug,case,doc,OS,phpversion,dbversion

- 检查 user 数据。属性user @15
- 检查 task 数据。属性task @20
- 检查 bug 数据。属性bug @100
- 检查 OS 数据。属性OS @Linux

*/
global $tester, $config;
$tester->app->loadConfig('admin');
$miscModel = $tester->loadModel('misc');

$config->admin->register = new stdClass();
$config->admin->register->bindCommunity = 'false';
$config->admin->register->agreeUX       = 'false';
r(count($miscModel->getStatisticsForAPI())) && p() && e('0'); // 不启用用户体验

$config->admin->register->bindCommunity = 'true';
$config->admin->register->agreeUX       = 'true';
r(implode(',', array_keys($miscModel->getStatisticsForAPI()))) && p() && e('user,project,execution,task,product,story,bug,case,doc,OS,phpversion,dbversion'); //检查数据键值。

$config->misc->statistics = json_encode(array('date' => date('Y-m-d'), 'data' => array('user' => 15, 'task' => 20, 'bug' => 100, 'OS' => 'Linux')));
$data = $miscModel->getStatisticsForAPI();
r($data) && p('user') && e('15');    //检查 user 数据。
r($data) && p('task') && e('20');    //检查 task 数据。
r($data) && p('bug')  && e('100');   //检查 bug 数据。
r($data) && p('OS')   && e('Linux'); //检查 OS 数据。
