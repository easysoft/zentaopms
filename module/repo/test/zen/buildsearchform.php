#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildSearchForm();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性queryID @123
 - 属性hasSearchCommits @1
- 步骤2：空queryID
 - 属性queryID @0
 - 属性hasSearchCommits @1
- 步骤3：空actionURL
 - 属性queryID @456
 - 属性hasSearchCommits @1
- 步骤4：复杂URL
 - 属性queryID @789
 - 属性hasSearchCommits @1
- 步骤5：负数queryID
 - 属性queryID @-1
 - 属性hasSearchCommits @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($repoTest->buildSearchFormTest(123, 'index.php?m=repo&f=commits&repoID=1')) && p('queryID,hasSearchCommits') && e('123,1'); // 步骤1：正常情况
r($repoTest->buildSearchFormTest(0, 'index.php?m=repo&f=commits&repoID=1')) && p('queryID,hasSearchCommits') && e('0,1'); // 步骤2：空queryID
r($repoTest->buildSearchFormTest(456, '')) && p('queryID,hasSearchCommits') && e('456,1'); // 步骤3：空actionURL
r($repoTest->buildSearchFormTest(789, 'index.php?m=repo&f=commits&repoID=1&orderBy=date_desc&recPerPage=20')) && p('queryID,hasSearchCommits') && e('789,1'); // 步骤4：复杂URL
r($repoTest->buildSearchFormTest(-1, 'index.php?m=repo&f=commits&repoID=1')) && p('queryID,hasSearchCommits') && e('-1,1'); // 步骤5：负数queryID