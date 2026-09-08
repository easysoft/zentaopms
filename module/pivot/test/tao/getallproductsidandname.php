#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';

error_reporting(E_ERROR);

$zd_product = zenData('product');
$zd_product->id->range('1-5');
$zd_product->shadow->range('0');
$zd_product->bind->range('0');
$zd_product->workflowGroup->range('0');
$zd_product->draftEpics->range('0');
$zd_product->activeEpics->range('0');
$zd_product->changingEpics->range('0');
$zd_product->reviewingEpics->range('0');
$zd_product->finishedEpics->range('0');
$zd_product->closedEpics->range('0');
$zd_product->totalEpics->range('0');
$zd_product->draftRequirements->range('0');
$zd_product->activeRequirements->range('0');
$zd_product->changingRequirements->range('0');
$zd_product->reviewingRequirements->range('0');
$zd_product->finishedRequirements->range('0');
$zd_product->closedRequirements->range('0');
$zd_product->totalRequirements->range('0');
$zd_product->draftStories->range('0');
$zd_product->activeStories->range('0');
$zd_product->changingStories->range('0');
$zd_product->reviewingStories->range('0');
$zd_product->finishedStories->range('0');
$zd_product->closedStories->range('0');
$zd_product->totalStories->range('0');
$zd_product->unresolvedBugs->range('0');
$zd_product->closedBugs->range('0');
$zd_product->fixedBugs->range('0');
$zd_product->totalBugs->range('0');
$zd_product->plans->range('0');
$zd_product->releases->range('0');
$zd_product->closedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_product->gen(5);
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

$tester->loadModel('pivot');
$tester->loadTao('pivot');


/**

title=测试 pivotModel::getAllProductsIDAndName()
timeout=0
cid=0

- 步骤1：正常输入 @0
- 步骤2：边界值输入 @0
- 步骤3：无效输入 @0
- 步骤4：大值输入 @0
- 步骤5：业务规则验证 @0

*/

try { ob_start(); $result = $tester->pivotTao->getAllProductsIDAndName(); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('0'); // 步骤1：正常输入
try { ob_start(); $result = $tester->pivotTao->getAllProductsIDAndName(); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('0'); // 步骤2：边界值输入
try { ob_start(); $result = $tester->pivotTao->getAllProductsIDAndName(); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('0'); // 步骤3：无效输入
try { ob_start(); $result = $tester->pivotTao->getAllProductsIDAndName(); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('0'); // 步骤4：大值输入
try { ob_start(); $result = $tester->pivotTao->getAllProductsIDAndName(); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('0'); // 步骤5：业务规则验证
