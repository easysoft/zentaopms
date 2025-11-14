#!/usr/bin/env php
<?php

/**

title=测试 aiappModel::getLatestMiniPrograms();
timeout=0
cid=15085

- 步骤1：正常情况获取最新小程序总数 @15
- 步骤2：排序测试（按发布时间倒序，最新的是test）第18条的name属性 @test
- 步骤3：按ID升序排序，第一个有效记录第4条的name属性 @健身计划
- 步骤4：按创建时间升序排序第4条的name属性 @健身计划
- 步骤5：按名称升序排序测试第10条的name属性 @调研问卷生成器

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/aiapp.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('ai_miniprogram');
$table->id->range('1-20');
$table->name->range('职业发展导航,工作汇报,市场分析报告,健身计划,广告创意大师,文章撰写助手,视频脚本创意工坊,邮件起草助手,新人介绍,调研问卷生成器,test{10}');
$table->category->range('personal{5},work{5},life{3},creative{7}');
$table->desc->range('这是一个AI小程序的描述信息{20}');
$table->icon->range('writinghand-7,technologist-2,chart-6,cactus-5,palette-3{16}');
$table->createdBy->range('system,admin,user{18}');
$table->createdDate->range('`(-2 months)`:`(-1 weeks)`:7D')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$table->editedBy->range('system,admin,user{18}');
$table->editedDate->range('`(-1 months)`:`(now)`:7D')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$table->published->range('0{3},1{17}');
$table->publishedDate->range('`(-1 months)`:`(now)`:7D')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$table->deleted->range('0{18},1{2}');
$table->prompt->range('请帮我生成{20}');
$table->builtIn->range('0{10},1{10}');
$table->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 加载pager类
global $tester;
$tester->app->loadClass('pager', true);

// 5. 创建测试实例（变量名与模块名一致）
$aiappTest = new aiappTest();

// 5. 强制要求：必须包含至少5个测试步骤  
r(count($aiappTest->getLatestMiniProgramsTest())) && p() && e('15'); // 步骤1：正常情况获取最新小程序总数
r($aiappTest->getLatestMiniProgramsTest(null, 'publishedDate_desc')) && p('18:name') && e('test'); // 步骤2：排序测试（按发布时间倒序，最新的是test）
r($aiappTest->getLatestMiniProgramsTest(null, 'id_asc')) && p('4:name') && e('健身计划'); // 步骤3：按ID升序排序，第一个有效记录
r($aiappTest->getLatestMiniProgramsTest(null, 'createdDate_asc')) && p('4:name') && e('健身计划'); // 步骤4：按创建时间升序排序
r($aiappTest->getLatestMiniProgramsTest(null, 'name_asc')) && p('10:name') && e('调研问卷生成器'); // 步骤5：按名称升序排序测试