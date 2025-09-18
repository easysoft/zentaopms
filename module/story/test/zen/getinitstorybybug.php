#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getInitStoryByBug();
timeout=0
cid=0

- 步骤1：正常bug转换
 - 属性source @bug
 - 属性title @Bug标题1
 - 属性pri @1
- 步骤2：空bug ID
 - 属性source @
 - 属性title @
- 步骤3：不存在的bug ID
 - 属性source @
 - 属性title @
- 步骤4：验证关键词和步骤转换
 - 属性source @bug
 - 属性keywords @关键词2
 - 属性spec @步骤2
- 步骤5：验证mailto处理逻辑
 - 属性mailto @user1@test.com

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$bug = zenData('bug');
$bug->id->range('1-10');
$bug->product->range('1');
$bug->title->range('Bug标题1,Bug标题2,Bug标题3,Bug标题4,Bug标题5');
$bug->keywords->range('关键词1,关键词2,关键词3,关键词4,关键词5');
$bug->steps->range('步骤1,步骤2,步骤3,步骤4,步骤5');
$bug->pri->range('1-4');
$bug->mailto->range('user1@test.com,user2@test.com,,user3@test.com,user4@test.com');
$bug->openedBy->range('admin,user1,user2,user3,user4');
$bug->gen(5);

$file = zenData('file');
$file->id->range('1-5');
$file->objectType->range('bug');
$file->objectID->range('1-5');
$file->pathname->range('file1.txt,file2.txt,file3.txt,file4.txt,file5.txt');
$file->title->range('文件1,文件2,文件3,文件4,文件5');
$file->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyZenTest();

// 5. 准备初始story对象
$initStory = new stdclass();
$initStory->product = 0;
$initStory->source = '';
$initStory->title = '';
$initStory->keywords = '';
$initStory->spec = '';
$initStory->pri = '3';
$initStory->mailto = '';
$initStory->files = array();

// 6. 强制要求：必须包含至少5个测试步骤
r($storyTest->getInitStoryByBugTest(1, clone $initStory)) && p('source,title,pri') && e('bug,Bug标题1,1'); // 步骤1：正常bug转换
r($storyTest->getInitStoryByBugTest(0, clone $initStory)) && p('source,title') && e(','); // 步骤2：空bug ID
r($storyTest->getInitStoryByBugTest(999, clone $initStory)) && p('source,title') && e(','); // 步骤3：不存在的bug ID
r($storyTest->getInitStoryByBugTest(2, clone $initStory)) && p('source,keywords,spec') && e('bug,关键词2,步骤2'); // 步骤4：验证关键词和步骤转换
r($storyTest->getInitStoryByBugTest(1, clone $initStory)) && p('mailto') && e('user1@test.com,admin,'); // 步骤5：验证mailto处理逻辑