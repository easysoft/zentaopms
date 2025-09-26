#!/usr/bin/env php
<?php

/**

title=测试 biModel::genWaterpolo();
timeout=0
cid=0

- 步骤1：正常情况第series[0]条的type属性 @liquidFill
- 步骤2：无过滤器第tooltip条的show属性 @1
- 步骤3：空条件数组第series[0]条的type属性 @liquidFill
- 步骤4：分母为零测试type第series[0]条的type属性 @liquidFill
- 步骤5：多过滤器第series[0]条的type属性 @liquidFill

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,test1,test2,test3,test4,test5');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,测试1,测试2,测试3,测试4,测试5');
$user->deleted->range('0{8},1{2}');
$user->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$biTest = new biTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($biTest->genWaterpoloTest(array(), array('calc' => 'count', 'goal' => '*', 'conditions' => array(array('field' => 'deleted', 'condition' => 'eq', 'value' => '0'))), 'select id, deleted from zt_user', array())) && p('series[0]:type') && e('liquidFill'); // 步骤1：正常情况
r($biTest->genWaterpoloTest(array(), array('calc' => 'count', 'goal' => '*', 'conditions' => array(array('field' => 'deleted', 'condition' => 'eq', 'value' => '0'))), 'select id, deleted from zt_user', array())) && p('tooltip:show') && e('1'); // 步骤2：无过滤器
r($biTest->genWaterpoloTest(array(), array('calc' => 'count', 'goal' => '*', 'conditions' => array()), 'select id from zt_user', array())) && p('series[0]:type') && e('liquidFill'); // 步骤3：空条件数组
r($biTest->genWaterpoloTest(array(), array('calc' => 'count', 'goal' => '*', 'conditions' => array(array('field' => 'id', 'condition' => 'eq', 'value' => '999'))), 'select id from zt_user', array())) && p('series[0]:type') && e('liquidFill'); // 步骤4：分母为零测试type
r($biTest->genWaterpoloTest(array(), array('calc' => 'count', 'goal' => '*', 'conditions' => array(array('field' => 'deleted', 'condition' => 'eq', 'value' => '0'))), 'select id, account, deleted from zt_user', array('account' => array('operator' => '=', 'value' => "'admin'"), 'deleted' => array('operator' => '=', 'value' => "'0'")))) && p('series[0]:type') && e('liquidFill'); // 步骤5：多过滤器