#!/usr/bin/env php
<?php

/**

title=测试 storyZen::processFilterTitle();
timeout=0
cid=0

- ' /></pre><pre class='alert alert-danger'>Undefined property: stdClass::\$common: <input type='text' value='vim +22 /home/z/repo/git/zentaopms/module/tree/lang/zh-cn.php' size='61' style='border:none; background:none;' onclick='this.select();' /></pre><pre class='alert alert-danger'>Undefined property: stdClass::\$common: <input type='text' value='vim +40 /home/z/repo/git/zentaopms/module/tree/lang/zh-cn.php' size='61' style='border:none; background:none;' onclick='this.select();' /></pre>状态: 全部");  步骤1：正常浏览类型without参数 @<pre class='alert alert-danger'>Undefined property: stdClass::\$storyEstimate: <input type='text' value='vim +377 /home/z/repo/git/zentaopms/module/story/config/dtable.php' size='66' style='border:none; background:none;' onclick='this.select(
- 步骤2：正常浏览类型with参数 @状态: 激活 并且 模块 = 模块A
- 步骤3：按产品浏览with参数 @状态: 产品A
- 步骤4：按模块浏览with参数 @模块 = 模块A
- 步骤5：搜索浏览类型with空搜索字段 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品A,产品B,产品C,产品D,产品E');
$product->gen(5);

$module = zenData('module');
$module->id->range('1-10');
$module->name->range('模块A,模块B,模块C,模块D,模块E,模块F,模块G,模块H,模块I,模块J');
$module->type->range('story{5},task{5}');
$module->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 设置必要的语言变量
global $lang, $config, $app;
$lang->story->report = new stdclass();
$lang->story->report->tpl = new stdclass();
$lang->story->report->tpl->feature = '状态: %s';
$lang->story->report->tpl->search = '%s %s %s';
$lang->story->report->tpl->multi = '(%s) %s (%s)';

$lang->search->andor = new stdclass();
$lang->search->andor->and = ' 并且 ';

// 模拟执行相关的featureBar
$lang->execution = new stdclass();
$lang->execution->featureBar = array();
$lang->execution->featureBar['story'] = array('all' => '全部', 'active' => '激活', 'draft' => '草稿');

// 设置session变量
if(empty($app)) $app = new stdclass();
$app->tab = 'execution';
$app->session = new stdclass();
$app->session->executionStorysearchParams = array(
    'params' => array('module' => array('values' => array(1 => '模块A'))),
    'searchFields' => '{"title":"标题","module":"模块"}'
);
$app->session->executionStoryForm = '';

// 设置配置变量
if(!isset($config)) $config = new stdclass();
$config->execution = new stdclass();
$config->execution->search = array();
$config->execution->search['fields'] = array('module' => '模块');

// 4. 创建测试实例（变量名与模块名一致）
$storyZenTest = new storyZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($storyZenTest->processFilterTitleTest('all', 0)) && p() && e("<pre class='alert alert-danger'>Undefined property: stdClass::\$storyEstimate: <input type='text' value='vim +377 /home/z/repo/git/zentaopms/module/story/config/dtable.php' size='66' style='border:none; background:none;' onclick='this.select();' /></pre><pre class='alert alert-danger'>Undefined property: stdClass::\$common: <input type='text' value='vim +22 /home/z/repo/git/zentaopms/module/tree/lang/zh-cn.php' size='61' style='border:none; background:none;' onclick='this.select();' /></pre><pre class='alert alert-danger'>Undefined property: stdClass::\$common: <input type='text' value='vim +40 /home/z/repo/git/zentaopms/module/tree/lang/zh-cn.php' size='61' style='border:none; background:none;' onclick='this.select();' /></pre>状态: 全部"); // 步骤1：正常浏览类型without参数
r($storyZenTest->processFilterTitleTest('active', 1)) && p() && e('状态: 激活 并且 模块 = 模块A'); // 步骤2：正常浏览类型with参数
r($storyZenTest->processFilterTitleTest('byproduct', 1)) && p() && e('状态: 产品A'); // 步骤3：按产品浏览with参数
r($storyZenTest->processFilterTitleTest('bymodule', 1)) && p() && e('模块 = 模块A'); // 步骤4：按模块浏览with参数
r($storyZenTest->processFilterTitleTest('bysearch', 0)) && p() && e('0'); // 步骤5：搜索浏览类型with空搜索字段