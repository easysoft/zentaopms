#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

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

$tester->loadModel('chart');
$tester->loadTao('chart');


/**

title=测试 chartModel::getRows()
timeout=0
cid=0

- 步骤1：正常输入 @0
- 步骤2：边界值输入 @0
- 步骤3：无效输入 @0
- 步骤4：大值输入 @0
- 步骤5：业务规则验证 @0
- 步骤6：合法的字面量过滤器正常返回 @1
- 步骤7：过滤器值注入 payload 被拒绝 @0
- 步骤8：过滤操作符非白名单被拒绝 @0
- 步骤9：过滤字段名非法被拒绝 @0
- 步骤10：聚合函数非白名单被拒绝 @0
- 步骤11：日期分组非白名单被拒绝 @0

*/

try { ob_start(); $result = $tester->chartTao->getRows('1', array(), '1', '1', '1', '1', 'mysql'); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('0'); // 步骤1：正常输入
try { ob_start(); $result = $tester->chartTao->getRows('', array(), '1', '1', '1', '1', 'mysql'); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('0'); // 步骤2：边界值输入
try { ob_start(); $result = $tester->chartTao->getRows('abc', array(), '1', '1', '1', '1', 'mysql'); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('0'); // 步骤3：无效输入
try { ob_start(); $result = $tester->chartTao->getRows('999999', array(), '1', '1', '1', '1', 'mysql'); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('0'); // 步骤4：大值输入
try { ob_start(); $result = $tester->chartTao->getRows('test', array(1, 2), '1', '1', '1', '1', 'mysql'); $echoed = ob_get_clean(); if($echoed !== '') $result = 'echo_yes'; if(dao::isError()) $result = 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE); } catch(Throwable $e) { if(ob_get_level()) ob_end_clean(); if($e instanceof EndResponseException) $result = 0; else $result = 'error:' . get_class($e); }
r($result) && p() && e('0'); // 步骤5：业务规则验证

$defaultSql = "select 'a' as name, 1 as id";
$taoTest    = new chartTaoTest();

$result = $taoTest->getRowsTest($defaultSql, array('name' => array('operator' => '=', 'value' => "'a'")), '', 'name', 'id', 'count', 'mysql');
r(is_array($result) ? count($result) : $result) && p() && e('1'); // 步骤6：合法的字面量过滤器正常返回

$result = $taoTest->getRowsTest($defaultSql, array('name' => array('operator' => '=', 'value' => "'a' and sleep(3) and '1'='1'")), '', 'name', 'id', 'count', 'mysql');
r($result) && p() && e('0'); // 步骤7：过滤器值注入 payload 被拒绝

$result = $taoTest->getRowsTest($defaultSql, array('name' => array('operator' => '= 1 or 1=1', 'value' => "'a'")), '', 'name', 'id', 'count', 'mysql');
r($result) && p() && e('0'); // 步骤8：过滤操作符非白名单被拒绝

$result = $taoTest->getRowsTest($defaultSql, array('name`' => array('operator' => '=', 'value' => "'a'")), '', 'name', 'id', 'count', 'mysql');
r($result) && p() && e('0'); // 步骤9：过滤字段名非法被拒绝

$result = $taoTest->getRowsTest($defaultSql, array(), '', 'name', 'id', 'count) from zt_user--', 'mysql');
r($result) && p() && e('0'); // 步骤10：聚合函数非白名单被拒绝

$result = $taoTest->getRowsTest($defaultSql, array(), 'MONTH) or sleep(3)--', 'name', 'id', 'count', 'mysql');
r($result) && p() && e('0'); // 步骤11：日期分组非白名单被拒绝
