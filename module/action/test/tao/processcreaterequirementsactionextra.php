#!/usr/bin/env php
<?php

/**

title=- 步骤1：正常单个需求属性extra @<a href='/home/z/rzto/module/action/test/tao/processcreaterequirementsactionextra.php?m=story&f=view&storyID=1'  >
timeout=0
cid=1

- 步骤1：正常单个需求属性extra @<a href='/home/z/rzto/module/action/test/tao/processcreaterequirementsactionextra.php?m=story&f=view&storyID=1'  >#1 需求标题1</a>
- 步骤2：多个需求
 - 属性extra @<a href='/home/z/rzto/module/action/test/tao/processcreaterequirementsactionextra.php?m=story&f=view&storyID=1'  >#1 需求标题1</a>
- 步骤3：单个需求ID2属性extra @<a href='/home/z/rzto/module/action/test/tao/processcreaterequirementsactionextra.php?m=story&f=view&storyID=2'  >#2 需求标题2</a>
- 步骤4：单个需求ID3属性extra @<a href='/home/z/rzto/module/action/test/tao/processcreaterequirementsactionextra.php?m=story&f=view&storyID=3'  >#3 需求标题3</a>
- 步骤5：混合有效无效ID
 - 属性extra @<a href='/home/z/rzto/module/action/test/tao/processcreaterequirementsactionextra.php?m=story&f=view&storyID=1'  >#1 需求标题1</a>

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$story = zenData('story');
$story->id->range('1-10');
$story->title->range('需求标题1,需求标题2,需求标题3,需求标题4,需求标题5{5}');
$story->product->range('1{10}');
$story->type->range('requirement{10}');
$story->status->range('active{10}');
$story->openedBy->range('admin{10}');
$story->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$actionTest = new actionTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($actionTest->processCreateRequirementsActionExtraTest('1')) && p('extra') && e("<a href='/home/z/rzto/module/action/test/tao/processcreaterequirementsactionextra.php?m=story&f=view&storyID=1'  >#1 需求标题1</a>"); // 步骤1：正常单个需求  
r($actionTest->processCreateRequirementsActionExtraTest('1,2,3')) && p('extra') && e("<a href='/home/z/rzto/module/action/test/tao/processcreaterequirementsactionextra.php?m=story&f=view&storyID=1'  >#1 需求标题1</a>, <a href='/home/z/rzto/module/action/test/tao/processcreaterequirementsactionextra.php?m=story&f=view&storyID=2'  >#2 需求标题2</a>, <a href='/home/z/rzto/module/action/test/tao/processcreaterequirementsactionextra.php?m=story&f=view&storyID=3'  >#3 需求标题3</a>"); // 步骤2：多个需求
r($actionTest->processCreateRequirementsActionExtraTest('2')) && p('extra') && e("<a href='/home/z/rzto/module/action/test/tao/processcreaterequirementsactionextra.php?m=story&f=view&storyID=2'  >#2 需求标题2</a>"); // 步骤3：单个需求ID2
r($actionTest->processCreateRequirementsActionExtraTest('3')) && p('extra') && e("<a href='/home/z/rzto/module/action/test/tao/processcreaterequirementsactionextra.php?m=story&f=view&storyID=3'  >#3 需求标题3</a>"); // 步骤4：单个需求ID3  
r($actionTest->processCreateRequirementsActionExtraTest('1,,2')) && p('extra') && e("<a href='/home/z/rzto/module/action/test/tao/processcreaterequirementsactionextra.php?m=story&f=view&storyID=1'  >#1 需求标题1</a>, <a href='/home/z/rzto/module/action/test/tao/processcreaterequirementsactionextra.php?m=story&f=view&storyID=2'  >#2 需求标题2</a>"); // 步骤5：混合有效无效ID