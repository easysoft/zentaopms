#!/usr/bin/env php
<?php

/**

title=测试 searchModel::processSearchParams();
timeout=0
cid=0

- 步骤1：无缓存且无 session 数据时返回空数组 @1
- 步骤2：无缓存时优先返回已有 session 搜索参数 @Legacy
- 步骤3：命中缓存回调且加载真实值时返回 module @task
- 步骤4：命中缓存回调且加载真实值时返回 actionURL @/my-task/
- 步骤5：命中缓存回调且加载真实值时会填充动态下拉值 @1
- 步骤6：命中缓存回调且继续缓存时不返回 actionURL @0
- 步骤7：命中缓存回调且继续缓存时保留占位字符串值 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

unset($_SESSION['taskSearchFunc'], $_SESSION['tasksearchParams']);

$search = new searchModelTest();
$emptyResult = $search->processSearchParamsTest('task');

$_SESSION['tasksearchParams'] = array(
    'fields' => array('legacy' => 'Legacy'),
    'params' => array('legacy' => array('operator' => '=', 'control' => 'input', 'values' => '')),
);
$fallbackResult = $search->processSearchParamsTest('task');

$myModel = $tester->loadModel('my');
$myModel->buildTaskSearchForm(0, '/my-task/', 'task');

$realResult   = $search->processSearchParamsTest('task');
$cachedResult = $search->processSearchParamsTest('task', true);

r(empty($emptyResult))                                         && p() && e('1');        // 步骤1：无缓存且无 session 数据时返回空数组
r($fallbackResult['fields']['legacy'])                         && p() && e('Legacy');   // 步骤2：无缓存时优先返回已有 session 搜索参数
r($realResult['module'])                                       && p() && e('task');     // 步骤3：命中缓存回调且加载真实值时返回 module
r($realResult['actionURL'])                                    && p() && e('/my-task/'); // 步骤4：命中缓存回调且加载真实值时返回 actionURL
r(is_array($realResult['params']['module']['values']))         && p() && e('1');        // 步骤5：命中缓存回调且加载真实值时会填充动态下拉值
r(isset($cachedResult['actionURL']))                           && p() && e('0');        // 步骤6：命中缓存回调且继续缓存时不返回 actionURL
r(is_string($cachedResult['params']['module']['values']))      && p() && e('1');        // 步骤7：命中缓存回调且继续缓存时保留占位字符串值
