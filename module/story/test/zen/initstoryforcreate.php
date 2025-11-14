#!/usr/bin/env php
<?php

/**

title=测试 storyZen::initStoryForCreate();
timeout=0
cid=18699

- 步骤1：正常情况，无任何关联对象，只指定计划ID属性pri @3
- 步骤2：基于已有需求复制初始化属性source @customer
- 步骤3：基于bug创建需求初始化属性source @bug
- 步骤4：基于todo创建需求初始化属性source @todo
- 步骤5：多种参数组合测试，边界值验证属性pri @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$story = zenData('story');
$story->id->range('1-10');
$story->title->range('软件需求1,软件需求2,用户故事1,用户故事2,需求标题{6}');
$story->source->range('customer,user,po,market{6}');
$story->sourceNote->range('客户反馈,用户调研,产品规划,市场分析{6}');
$story->pri->range('1-4');
$story->estimate->range('1,2,3,4,5,8,13{3}');
$story->keywords->range('关键词1,关键词2,测试{8}');
$story->mailto->range('admin@zentao.net,test@zentao.net,{8}');
$story->color->range('#3da7f5,#75c941,#2dbdb2,#ffaf38,#ff4e3e{5}');
$story->plan->range('1,2,3,0{7}');
$story->module->range('1-5{10}');
$story->grade->range('1-3{10}');
$story->product->range('1{10}');
$story->status->range('active{10}');
$story->deleted->range('0{10}');
$story->gen(10);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-10');
$storyspec->version->range('1{10}');
$storyspec->title->range('软件需求1,软件需求2,用户故事1,用户故事2,需求标题{6}');
$storyspec->spec->range('需求详细描述1,需求详细描述2,详细说明{8}');
$storyspec->verify->range('验收标准1,验收标准2,验收条件{8}');
$storyspec->gen(10);

$bug = zenData('bug');
$bug->id->range('1-5');
$bug->title->range('系统登录问题,页面加载缓慢,数据显示错误,功能无法使用,界面显示异常');
$bug->steps->range('1.打开系统;2.输入用户名密码;3.点击登录,1.访问页面;2.等待加载;3.查看响应时间,操作步骤描述{3}');
$bug->keywords->range('登录,性能,显示,功能,界面');
$bug->pri->range('1-4{5}');
$bug->mailto->range('developer@zentao.net,tester@zentao.net,admin@zentao.net,{2}');
$bug->openedBy->range('admin,tester,developer{2}');
$bug->product->range('1{5}');
$bug->status->range('active{5}');
$bug->deleted->range('0{5}');
$bug->gen(5);

$todo = zenData('todo');
$todo->id->range('1-3');
$todo->name->range('完成用户模块开发,编写测试用例,更新产品文档');
$todo->desc->range('需要完成用户管理模块的所有功能开发,为新功能编写完整的测试用例,更新产品使用说明文档');
$todo->pri->range('1-3');
$todo->account->range('admin{3}');
$todo->type->range('custom{3}');
$todo->status->range('wait{3}');
$todo->gen(3);

$productplan = zenData('productplan');
$productplan->id->range('1-5');
$productplan->product->range('1{5}');
$productplan->title->range('V1.0计划,V1.1计划,V2.0计划,维护计划,功能增强计划');
$productplan->status->range('wait,doing,done{3}');
$productplan->deleted->range('0{5}');
$productplan->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->initStoryForCreateTest(1, 0, 0, 0, '')) && p('pri') && e('3'); // 步骤1：正常情况，无任何关联对象，只指定计划ID
r($storyTest->initStoryForCreateTest(2, 1, 0, 0, '')) && p('source') && e('customer'); // 步骤2：基于已有需求复制初始化
r($storyTest->initStoryForCreateTest(0, 0, 1, 0, '')) && p('source') && e('bug'); // 步骤3：基于bug创建需求初始化
r($storyTest->initStoryForCreateTest(0, 0, 0, 1, '')) && p('source') && e('todo'); // 步骤4：基于todo创建需求初始化
r($storyTest->initStoryForCreateTest(0, 0, 0, 0, '')) && p('pri') && e('3'); // 步骤5：多种参数组合测试，边界值验证