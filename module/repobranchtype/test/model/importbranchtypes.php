#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';

error_reporting(E_ERROR);

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

$branchType = zenData('ops_branch_type');
$branchType->id->range('1-2');
$branchType->repo->range('0');
$branchType->name->range('global1,global2');
$branchType->key->range('global1,global2');
$branchType->prefix->range('global1/,global2/');
$branchType->createdBy->range('admin');
$branchType->deleted->range('0');
$branchType->gen(2);

$entry = zenData('entry');
$entry->id->range('1');
$entry->name->range('GitFox');
$entry->account->range('admin');
$entry->code->range('gitfox');
$entry->key->range('gitfox');
$entry->freePasswd->range('1');
$entry->ip->range('*');
$entry->createdBy->range('admin');
$entry->createdDate->range('`2026-01-01 00:00:00`');
$entry->calledTime->range('0');
$entry->editedBy->range('admin');
$entry->editedDate->range('`2026-01-01 00:00:00`');
$entry->deleted->range('0');
$entry->gen(1);

su('admin');

$tester->loadModel('repobranchtype');


/**

title=测试 repobranchtypeModel::importBranchTypes()
timeout=0
cid=0

- 步骤1：正常输入 @0
- 步骤2：边界值输入 @0
- 步骤3：无效输入 @0
- 步骤4：大值输入 @0
- 步骤5：业务规则验证 @1:{"apiMessage":"尝试认证失败"}

*/

try { ob_start(); $result = $tester->repobranchtype->importBranchTypes((object)array(), array()); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('0'); // 步骤1：正常输入
try { ob_start(); $result = $tester->repobranchtype->importBranchTypes((object)array(), array()); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('0'); // 步骤2：边界值输入
try { ob_start(); $result = $tester->repobranchtype->importBranchTypes((object)array(), array()); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('0'); // 步骤3：无效输入
try { ob_start(); $result = $tester->repobranchtype->importBranchTypes((object)array('id' => 999999), array()); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('0'); // 步骤4：大值输入
try { ob_start(); $result = $tester->repobranchtype->importBranchTypes((object)array('id' => 1, 'name' => 'test'), array(1, 2)); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('daoError:{"apiMessage":"尝试认证失败"}'); // 步骤5：业务规则验证
