#!/usr/bin/env php
<?php

/**

title=测试 repoZen::linkObject();
timeout=0
cid=0

- 步骤1：正常关联story对象属性result @success
- 步骤2：正常关联bug对象属性result @success
- 步骤3：正常关联task对象属性result @success
- 步骤4：无效repoID参数测试属性result @fail
- 步骤5：无效type参数测试属性result @fail

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('repo');
$table->id->range('1-10');
$table->name->range('测试版本库{1-10}');
$table->SCM->range('Git{5},Gitlab{3},Subversion{2}');
$table->serviceHost->range('1-3');
$table->path->range('/path/to/repo{1-10}');
$table->deleted->range('0');
$table->gen(10);

// 准备关联表数据
$relationTable = zenData('relation');
$relationTable->id->range('1-20');
$relationTable->product->range('1-5');
$relationTable->project->range('1-5');
$relationTable->AType->range('story{10},bug{5},task{5}');
$relationTable->AID->range('1-20');
$relationTable->BType->range('revision');
$relationTable->BID->range('1-20');
$relationTable->relation->range('fix{10},resolve{5},implement{5}');
$relationTable->extra->range('1-10');
$relationTable->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 5. 必须包含至少5个测试步骤
r($repoTest->linkObjectTest(1, 'abc123', 'story')) && p('result') && e('success'); // 步骤1：正常关联story对象
r($repoTest->linkObjectTest(2, 'def456', 'bug')) && p('result') && e('success'); // 步骤2：正常关联bug对象
r($repoTest->linkObjectTest(3, 'ghi789', 'task')) && p('result') && e('success'); // 步骤3：正常关联task对象
r($repoTest->linkObjectTest(0, 'abc123', 'story')) && p('result') && e('fail'); // 步骤4：无效repoID参数测试
r($repoTest->linkObjectTest(1, 'abc123', 'invalid')) && p('result') && e('fail'); // 步骤5：无效type参数测试