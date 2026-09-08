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

su('admin');

$tester->loadModel('provider');
helper::import($tester->app->getModulePath('', 'provider') . 'control.php');
helper::import($tester->app->getModulePath('', 'provider') . 'zen.php');


/**

title=测试 providerModel::checkSubversionUrl()
timeout=0
cid=0

- 步骤1：正常输入 @1
- 步骤2：边界值输入 @1
- 步骤3：无效输入 @1
- 步骤4：大值输入 @1
- 步骤5：业务规则验证 @1

*/

try { ob_start(); $result = callZenMethod('provider', 'checkSubversionUrl', array('http://127.0.0.1:1/')); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r(str_contains($result, '无法访问服务器')) && p() && e('1'); // 步骤1：正常输入
try { ob_start(); $result = callZenMethod('provider', 'checkSubversionUrl', array('http://127.0.0.1:1/')); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r(str_contains($result, '无法访问服务器')) && p() && e('1'); // 步骤2：边界值输入
try { ob_start(); $result = callZenMethod('provider', 'checkSubversionUrl', array('http://127.0.0.1:1/')); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r(str_contains($result, '无法访问服务器')) && p() && e('1'); // 步骤3：无效输入
try { ob_start(); $result = callZenMethod('provider', 'checkSubversionUrl', array('http://127.0.0.1:1/')); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r(str_contains($result, '无法访问服务器')) && p() && e('1'); // 步骤4：大值输入
try { ob_start(); $result = callZenMethod('provider', 'checkSubversionUrl', array('http://127.0.0.1:1/')); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r(str_contains($result, '无法访问服务器')) && p() && e('1'); // 步骤5：业务规则验证
