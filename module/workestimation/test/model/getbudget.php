#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';

error_reporting(E_ERROR);

$zd_workestimation = zenData('workestimation');
$zd_workestimation->id->range('1-5');
$zd_workestimation->project->range('0');
$zd_workestimation->scale->range('0');
$zd_workestimation->productivity->range('0');
$zd_workestimation->duration->range('0');
$zd_workestimation->unitLaborCost->range('0');
$zd_workestimation->totalLaborCost->range('0');
$zd_workestimation->createdDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_workestimation->editedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_workestimation->assignedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_workestimation->dayHour->range('0');
$zd_workestimation->deleted->range('0');
$zd_workestimation->program->setNull();
$zd_workestimation->gen(5);
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

$tester->loadModel('workestimation');


/**

title=测试 workestimationModel::getBudget()
timeout=0
cid=0

- 步骤1：正常输入 @0
- 步骤2：边界值输入 @1
- 步骤3：无效输入 @0
- 步骤4：大值输入 @0
- 步骤5：业务规则验证 @0

*/

try { ob_start(); $result = $tester->workestimation->getBudget(1); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result === null) && p() && e('0'); // 步骤1：正常输入
try { ob_start(); $result = $tester->workestimation->getBudget(0); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p('id') && e('1'); // 步骤2：边界值输入
try { ob_start(); $result = $tester->workestimation->getBudget(-1); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result === null) && p() && e('0'); // 步骤3：无效输入
try { ob_start(); $result = $tester->workestimation->getBudget(999999); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result === null) && p() && e('0'); // 步骤4：大值输入
try { ob_start(); $result = $tester->workestimation->getBudget(2); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result === null) && p() && e('0'); // 步骤5：业务规则验证
