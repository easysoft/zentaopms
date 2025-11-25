#!/usr/bin/env php
<?php

/**

title=- 属性color @
timeout=0
cid=18690

- 步骤1：传入有效需求ID验证数据复制
 - 属性title @软件需求1
 - 属性pri @1
 - 属性plan @1
- 步骤2：传入不存在的需求ID属性title @~~
- 步骤3：传入0作为需求ID属性title @~~
- 步骤4：传入负数需求ID属性title @~~
- 步骤5：验证复制需求字段的正确性
 - 属性source @po
 - 属性color @#3cb371

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$story = zenData('story');
$story->id->range('1-10');
$story->plan->range('1,2,3,1,2,3,1,2,3,1');
$story->module->range('1-3:R');
$story->source->range('customer,po,market,other,customer,po,market,other,customer,po');
$story->sourceNote->range('客户反馈,产品需求,市场调研,其他来源,客户反馈,产品需求,市场调研,其他来源,客户反馈,产品需求');
$story->color->range('#3da7f5,#3cb371,#ff6347,#ffd700,#9370db,#3da7f5,#3cb371,#ff6347,#ffd700,#9370db');
$story->pri->range('1-4:R');
$story->estimate->range('1-5:R');
$story->grade->range('1-2:R');
$story->keywords->range('关键词1,关键词2,关键词3,关键词4,关键词5,关键词6,关键词7,关键词8,关键词9,关键词10');
$story->mailto->range('admin@zentao.net,user1@zentao.net,user2@zentao.net,admin@zentao.net,user1@zentao.net,user2@zentao.net,admin@zentao.net,user1@zentao.net,user2@zentao.net,admin@zentao.net');
$story->category->range('feature,bugfix,performance,security,feature,bugfix,performance,security,feature,bugfix');
$story->feedbackBy->range('admin,user1,user2,admin,user1,user2,admin,user1,user2,admin');
$story->notifyEmail->range('notify1@zentao.net,notify2@zentao.net,notify3@zentao.net,notify1@zentao.net,notify2@zentao.net,notify3@zentao.net,notify1@zentao.net,notify2@zentao.net,notify3@zentao.net,notify1@zentao.net');
$story->parent->range('0-5:R');
$story->version->range('1');
$story->deleted->range('0');
$story->gen(10);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-10');
$storyspec->version->range('1');
$storyspec->title->range('软件需求1,软件需求2,软件需求3,软件需求4,软件需求5,软件需求6,软件需求7,软件需求8,软件需求9,软件需求10');
$storyspec->spec->range('需求详细描述1,需求详细描述2,需求详细描述3,需求详细描述4,需求详细描述5,需求详细描述6,需求详细描述7,需求详细描述8,需求详细描述9,需求详细描述10');
$storyspec->verify->range('验收标准1,验收标准2,验收标准3,验收标准4,验收标准5,验收标准6,验收标准7,验收标准8,验收标准9,验收标准10');
$storyspec->files->range('[]');
$storyspec->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->getInitStoryByStoryTest(1, new stdclass())) && p('title,pri,plan') && e('软件需求1,1,1'); // 步骤1：传入有效需求ID验证数据复制
r($storyTest->getInitStoryByStoryTest(999, new stdclass())) && p('title') && e('~~'); // 步骤2：传入不存在的需求ID
r($storyTest->getInitStoryByStoryTest(0, new stdclass())) && p('title') && e('~~'); // 步骤3：传入0作为需求ID
r($storyTest->getInitStoryByStoryTest(-1, new stdclass())) && p('title') && e('~~'); // 步骤4：传入负数需求ID
r($storyTest->getInitStoryByStoryTest(2, new stdclass())) && p('source,color') && e('po,#3cb371'); // 步骤5：验证复制需求字段的正确性