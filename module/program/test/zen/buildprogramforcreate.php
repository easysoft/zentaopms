#!/usr/bin/env php
<?php

/**

title=测试 programZen::buildProgramForCreate();
timeout=0
cid=0

- 执行programTest模块的buildProgramForCreateTest方法，参数是$postData1
 - 属性name @测试项目集1
 - 属性type @program
 - 属性begin @2024-01-01
 - 属性end @2024-12-31
 - 属性budget @100000
 - 属性budgetUnit @CNY
 - 属性openedBy @admin
- 执行programTest模块的buildProgramForCreateTest方法，参数是$postData2
 - 属性name @长期项目集
 - 属性type @program
 - 属性begin @2024-01-01
 - 属性end @2059-12-31
- 执行programTest模块的buildProgramForCreateTest方法，参数是$postData3
 - 属性name @开放项目集
 - 属性acl @open
 - 属性whitelist @~~
- 执行programTest模块的buildProgramForCreateTest方法，参数是$postData4
 - 属性name @私有项目集
 - 属性acl @private
 - 属性whitelist @admin
- 执行programTest模块的buildProgramForCreateTest方法，参数是$postData5
 - 属性name @最小配置项目集
 - 属性type @program
 - 属性code @~~
 - 属性openedBy @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('user');
$table->account->range('admin,user1,user2,user3,pm1');
$table->realname->range('管理员,用户1,用户2,用户3,项目经理1');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$programTest = new programTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 步骤1：基本的创建项目集数据
$postData1 = array(
    'name'      => '测试项目集1',
    'begin'     => '2024-01-01',
    'end'       => '2024-12-31',
    'budget'    => '100000',
    'budgetUnit'=> 'CNY',
    'desc'      => '这是项目集描述',
    'acl'       => 'private',
    'whitelist' => array('admin', 'user1'),
    'uid'       => 'test123'
);
r($programTest->buildProgramForCreateTest($postData1)) && p('name,type,begin,end,budget,budgetUnit,openedBy') && e('测试项目集1,program,2024-01-01,2024-12-31,100000,CNY,admin');

// 步骤2：长期项目集（longTime=1）
$postData2 = array(
    'name'      => '长期项目集',
    'begin'     => '2024-01-01',
    'end'       => '2024-12-31',
    'longTime'  => 1,
    'budget'    => '500000',
    'budgetUnit'=> 'USD',
    'desc'      => '长期项目集',
    'acl'       => 'private',
    'whitelist' => array('pm1'),
    'uid'       => 'test456'
);
r($programTest->buildProgramForCreateTest($postData2)) && p('name,type,begin,end') && e('长期项目集,program,2024-01-01,2059-12-31');

// 步骤3：开放权限的项目集（acl=open）
$postData3 = array(
    'name'      => '开放项目集',
    'begin'     => '2024-03-01',
    'end'       => '2024-09-30',
    'budget'    => '200000',
    'budgetUnit'=> 'EUR',
    'desc'      => '开放权限项目集',
    'acl'       => 'open',
    'whitelist' => array('user1', 'user2', 'user3'),
    'uid'       => 'test789'
);
r($programTest->buildProgramForCreateTest($postData3)) && p('name,acl,whitelist') && e('开放项目集,open,~~');

// 步骤4：私有权限的项目集（acl=private）
$postData4 = array(
    'name'      => '私有项目集',
    'begin'     => '2024-04-01',
    'end'       => '2024-10-31',
    'budget'    => '150000',
    'budgetUnit'=> 'CNY',
    'desc'      => '私有权限项目集',
    'acl'       => 'private',
    'whitelist' => array('admin'),
    'uid'       => 'test111'
);
r($programTest->buildProgramForCreateTest($postData4)) && p('name,acl,whitelist') && e('私有项目集,private,admin');

// 步骤5：最小必填字段配置
$postData5 = array(
    'name'      => '最小配置项目集',
    'begin'     => '2024-06-01',
    'end'       => '2024-06-30',
    'uid'       => 'test222'
);
r($programTest->buildProgramForCreateTest($postData5)) && p('name,type,code,openedBy') && e('最小配置项目集,program,~~,admin');