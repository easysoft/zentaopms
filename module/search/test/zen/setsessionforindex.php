#!/usr/bin/env php
<?php

/**

title=测试 searchZen::setSessionForIndex();
cid=0

- 测试步骤1：正常设置session，传入标准URI和搜索词 >> 期望所有列表session都被正确设置
- 测试步骤2：测试字符串类型的搜索类型参数 >> 期望searchIngType被正确设置为字符串
- 测试步骤3：测试数组类型的搜索类型参数 >> 期望searchIngType被正确设置为数组
- 测试步骤4：测试空搜索词的情况 >> 期望searchIngWord被设置为空字符串
- 测试步骤5：验证HTTP_REFERER不包含search时的referer设置 >> 期望referer被正确设置

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$searchTest = new searchTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($searchTest->setSessionForIndexTest('/search/index.html', 'test keywords', 'bug')) && p('bugList,searchIngWord,searchIngType') && e('/search/index.html,test keywords,bug'); // 步骤1：正常情况
r($searchTest->setSessionForIndexTest('/search/index.html', 'story search', 'story')) && p('storyList,searchIngType') && e('/search/index.html,story'); // 步骤2：字符串类型搜索类型
r($searchTest->setSessionForIndexTest('/search/index.html', 'multi search', array('bug', 'story'))) && p('bugList,searchIngType:0') && e('/search/index.html,bug'); // 步骤3：数组类型搜索类型
r($searchTest->setSessionForIndexTest('/product/index.html', '', 'task')) && p('taskList,searchIngWord') && e('/product/index.html,'); // 步骤4：空搜索词
r($searchTest->setSessionForIndexTest('/project/task.html', 'project search', 'project')) && p('projectList,referer') && e('/project/task.html,http://example.com/test'); // 步骤5：referer设置验证