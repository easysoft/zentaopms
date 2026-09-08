#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';

error_reporting(E_ERROR);

$zd_story = zenData('story');
$zd_story->id->range('1-5');
$zd_story->parent->range('0');
$zd_story->isParent->range('0');
$zd_story->root->range('0');
$zd_story->branch->range('0');
$zd_story->fromBug->range('0');
$zd_story->feedback->range('0');
$zd_story->lib->range('0');
$zd_story->fromStory->range('0');
$zd_story->fromVersion->range('0');
$zd_story->approvedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_story->lastEditedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_story->changedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_story->reviewedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_story->releasedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_story->closedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_story->activatedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_story->toBug->range('0');
$zd_story->duplicateStory->range('0');
$zd_story->parentVersion->range('0');
$zd_story->demandVersion->range('0');
$zd_story->storyChanged->range('0');
$zd_story->URChanged->range('0');
$zd_story->deleted->range('0');
$zd_story->gen(5);
$zd_file = zenData('file');
$zd_file->id->range('1-5');
$zd_file->gen(5);
$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('1-1');
$zd_user->last->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_user->feedback->range('0');
$zd_user->scoreLevel->range('0');
$zd_user->resetExpired->range('0');
$zd_user->jira->range('0');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

$tester->loadModel('task');
helper::import($tester->app->getModulePath('', 'task') . 'control.php');
helper::import($tester->app->getModulePath('', 'task') . 'zen.php');


/**

title=测试 taskModel::processExportData()
timeout=0
cid=0

- 步骤1：正常输入 @0
- 步骤2：边界值输入 @0
- 步骤3：无效输入 @error:Error
- 步骤4：大值输入 @error:Error
- 步骤5：业务规则验证 @error:Error

*/

try { ob_start(); $result = callZenMethod('task', 'processExportData', array(array(), 1)); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r(count($result)) && p() && e('0'); // 步骤1：正常输入
try { ob_start(); $result = callZenMethod('task', 'processExportData', array(array(), 1)); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r(count($result)) && p() && e('0'); // 步骤2：边界值输入
try { ob_start(); $result = callZenMethod('task', 'processExportData', array(array(-1), 1)); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('error:Error'); // 步骤3：无效输入
try { ob_start(); $result = callZenMethod('task', 'processExportData', array(array(999999), 1)); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('error:Error'); // 步骤4：大值输入
try { ob_start(); $result = callZenMethod('task', 'processExportData', array(array(1, 2), 2)); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('error:Error'); // 步骤5：业务规则验证
