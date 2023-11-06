<?php
/**
 * The tutorial lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: zh-cn.php 5116 2013-07-12 06:37:48Z sunhao@cnezsoft.com $
 * @link        http://www.zentao.net
 */
$lang->tutorial = new stdclass();
$lang->tutorial->common           = '新手教程';
$lang->tutorial->desc             = '通过完成一系列任务，快速了解禅道的基本使用方法。这可能会花费你10分钟，你可以随时退出任务。';
$lang->tutorial->start            = '立即开始';
$lang->tutorial->exit             = '退出教程';
$lang->tutorial->congratulation   = '恭喜，你已完成了所有任务！';
$lang->tutorial->restart          = '重新开始';
$lang->tutorial->currentTask      = '当前任务';
$lang->tutorial->allTasks         = '所有任务';
$lang->tutorial->previous         = '上一个';
$lang->tutorial->nextTask         = '下一个任务';
$lang->tutorial->openTargetPage   = '打开 <strong class="task-page-name">目标</strong> 页面';
$lang->tutorial->atTargetPage     = '已在 <strong class="task-page-name">目标</strong> 页面';
$lang->tutorial->reloadTargetPage = '重新载入';
$lang->tutorial->target           = '目标';
$lang->tutorial->targetPageTip    = '按此指示打开【%s】页面';
$lang->tutorial->targetAppTip     = '按此指示打开【%s】应用';
$lang->tutorial->requiredTip      = '【%s】为必填项';
$lang->tutorial->congratulateTask = '恭喜，你完成了任务 【<span class="task-name-current"></span>】！';
$lang->tutorial->serverErrorTip   = '发生了一些错误。';
$lang->tutorial->ajaxSetError     = '必须指定已完成的任务，如果要重置任务，请设置值为空。';
$lang->tutorial->novice           = "你可能初次使用禅道，是否进入新手教程";
$lang->tutorial->dataNotSave      = "教程任务中，数据不会保存。";

$lang->tutorial->tasks = array();

$lang->tutorial->tasks['createAccount']         = array('title' => '创建帐号');
$lang->tutorial->tasks['createAccount']['nav']  = array(
    'app' => 'admin', 
    'module' => 'user', 
    'method' => 'create', 
    'menuModule' => 'company', 
    'menu' => 'browseUser', 
    'form' => '#mainContent', 
    'requiredFields' => 'account,realname,verifyPassword,password1,password2', 
    'submit' => 'button[type=submit]', 
    'target' => '.create-user-btn', 
    'targetPageName' => '添加用户'
);
$lang->tutorial->tasks['createAccount']['desc'] = "<p>在系统创建一个新的用户帐号：</p><ul><li data-target='nav'>打开 <span class='task-nav'>后台 <i class='icon icon-angle-right'></i> 人员管理 <i class='icon icon-angle-right'></i> 用户 <i class='icon icon-angle-right'></i> 添加用户</span> 页面；</li><li data-target='form'>在添加用户表单中填写新用户信息；</li><li data-target='submit'>保存用户信息。</li></ul>";

$lang->tutorial->tasks['createProgram']         = array('title' => '创建项目集');
$lang->tutorial->tasks['createProgram']['nav']  = array('app' => 'program', 'module' => 'program', 'method' => 'create', 'menuModule' => 'program', 'menu' => '.create-program-btn', 'form' => '#mainContent', 'submit' => 'button[type=submit]', 'target' => '.create-program-btn', 'targetPageName' => '添加项目集');
$lang->tutorial->tasks['createProgram']['desc'] = "<p>在系统创建一个新的项目集：</p><ul><li data-target='nav'>打开 <span class='task-nav'>项目集 <i class='icon icon-angle-right'></i> 项目集列表 <i class='icon icon-angle-right'></i> 添加项目集</span> 页面；</li><li data-target='form'>在添加项目集表单中填写项目集信息；</li><li data-target='submit'>保存项目集信息。</li></ul>";

$lang->tutorial->tasks['createProduct']         = array('title' => '创建' . $lang->productCommon);
$lang->tutorial->tasks['createProduct']['nav']  = array(
    'app' => 'product',
    'module' => 'product',
    'method' => 'create',
    'menuModule' => 'product',
    'menu' => '#heading > .toolbar > .toolbar-item, .create-product-btn',
    'form' => '#mainContent',
    'submit' => 'button[type=submit]',
    'target' => '',
    'targetPageName' => '添加' . $lang->productCommon);
$lang->tutorial->tasks['createProduct']['desc'] = "<p>在系统创建一个新的{$lang->productCommon}：</p><ul><li data-target='nav'>打开 <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i> {$lang->productCommon}列表 <i class='icon icon-angle-right'></i> 添加{$lang->productCommon}</span> 页面；</li><li data-target='form'>在添加{$lang->productCommon}表单中填写要创建的{$lang->productCommon}信息；</li><li data-target='submit'>保存{$lang->productCommon}信息。</li></ul>";

$lang->tutorial->tasks['createStory']         = array('title' => "创建{$lang->SRCommon}");
$lang->tutorial->tasks['createStory']['nav']  = array(
    'app' => 'product',
    'module' => 'story',
    'method' => 'create',
    'menuModule' => 'story',
    'menu' => '#products > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell > .dtable-cell-content > a, #heading > .toolbar > .toolbar-item, .create-story-btn',
    'target' => '.create-story-btn',
    'form' => '#mainContent',
    'submit' => '#saveButton',
    'targetPageName' => "提{$lang->SRCommon}"
);
$lang->tutorial->tasks['createStory']['desc'] = "<p>在系统创建一个新的{$lang->SRCommon}：</p><ul><li data-target='nav'>打开 <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 提{$lang->SRCommon}</span> 页面；</li><li data-target='form'>在{$lang->productCommon}表单中填写要创建的{$lang->SRCommon}信息；</li><li data-target='submit'>保存{$lang->SRCommon}信息。</li></ul>";

$lang->tutorial->tasks['createProject']         = array('title' => '创建' . $lang->projectCommon);
$lang->tutorial->tasks['createProject']['nav']  = array(
    'app' => 'project',
    'module' => 'project',
    'method' => 'create',
    'menuModule' => '',
    'menu' => '#header > .container > #heading > .toolbar, .create-project-btn',
    'form' => '#mainContent',
    'submit' => 'button[type=submit]',
    'target' => '',
    'targetPageName' => '添加' . $lang->projectCommon
);
$lang->tutorial->tasks['createProject']['desc'] = "<p>在系统创建一个新的{$lang->projectCommon}：</p><ul><li data-target='nav'>打开 <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> {$lang->projectCommon}列表 <i class='icon icon-angle-right'></i> 创建{$lang->projectCommon}</span> 页面；</li><li data-target='form'>在{$lang->projectCommon}表单中填写要创建的{$lang->projectCommon}信息；</li><li data-target='submit'>保存{$lang->projectCommon}信息。</li></ul>";

$lang->tutorial->tasks['manageTeam']         = array('title' => "管理{$lang->projectCommon}团队");
$lang->tutorial->tasks['manageTeam']['nav']  = array(
    'app' => 'project',
    'module' => 'project',
    'method' => 'managemembers',
    'menuModule' => '',
    'menu' => '#actionBar, #header > .container > #heading > .toolbar, #table-tutorial-wizard > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="name"] > .dtable-cell-content > a, #table-project-browse > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="name"] > .dtable-cell-content > a , #mainNavbar > .container > .nav > .nav-item > a[data-id="settings"], #mainNavbar > .container > .nav > .nav-item > a[data-id="members"]',
    'target' => '.manage-team-btn',
    'vars' => 'projectID=0',
    'form' => '#teamForm',
    'requiredFields' => 'accounts1',
    'submit' => '.form-row > .form-actions > button:first-child',
    'targetPageName' => '团队管理'
);
$lang->tutorial->tasks['manageTeam']['desc'] = "<p>管理{$lang->projectCommon}团队成员：</p><ul><li data-target='nav'>打开 <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> 设置 <i class='icon icon-angle-right'></i> 团队 <i class='icon icon-angle-right'></i> 团队管理</span> 页面；</li><li data-target='form'>选择要加入{$lang->projectCommon}团队的成员；</li><li data-target='submit'>保存团队成员信息。</li></ul>";

$lang->tutorial->tasks['createProjectExecution']         = array('title' => '添加' . $lang->executionCommon);
$lang->tutorial->tasks['createProjectExecution']['nav']  = array(
    'app' => 'project',
    'module' => 'execution',
    'method' => 'create',
    'menuModule' => '',
    'menu' => '#table-tutorial-wizard > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="name"] > .dtable-cell-content >a',
    'form' => '#mainContent',
    'submit' => 'button[type=submit]',
    'target' => '.create-execution-btn',
    'targetPageName' => '添加' . $lang->executionCommon
);
$lang->tutorial->tasks['createProjectExecution']['desc'] = "<p>在系统创建一个新的{$lang->executionCommon}：</p><ul><li data-target='nav'>打开 <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> {$lang->executionCommon} <i class='icon icon-angle-right'></i> 添加{$lang->executionCommon}</span> 页面；</li><li data-target='form'>在{$lang->executionCommon}表单中填写要创建的{$lang->executionCommon}信息；</li><li data-target='submit'>保存{$lang->executionCommon}信息。</li></ul>";

$lang->tutorial->tasks['linkStory']         = array('title' => "关联{$lang->SRCommon}");
$lang->tutorial->tasks['linkStory']['nav']  = array(
    'app' => 'execution',
    'module' => 'execution',
    'method' => 'linkStory',
    'menuModule' => 'story',
    'menu' => '#heading > .toolbar > a[title="执行"], #table-execution-all > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="name"], .link-story-btn',
    'target' => '',
    'form' => '#table-tutorial-wizard',
    'formType' => 'table',
    'submit' => '#saveButton',
    'targetPageName' => "关联{$lang->SRCommon}"
);
$lang->tutorial->tasks['linkStory']['desc'] = "<p>将{$lang->SRCommon}关联到执行：</p><ul><li data-target='nav'>打开 <span class='task-nav'> 执行 <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 关联{$lang->SRCommon}</span> 页面；</li><li data-target='form'>在{$lang->SRCommon}列表中勾选要关联的{$lang->SRCommon}；</li><li data-target='submit'>保存关联的{$lang->SRCommon}信息。</li></ul>";

$lang->tutorial->tasks['createTask']         = array('title' => '分解任务');
$lang->tutorial->tasks['createTask']['nav']  = array(
    'app' => 'execution', 
    'module' => 'task', 
    'method' => 'create', 
    'menuModule' => 'story', 
    'menu' => '', 
    'target' => '.create-task-btn', 
    'form' => '#mainContent', 
    'submit' => 'button[type=submit]', 
    'targetPageName' => '建任务'
);
$lang->tutorial->tasks['createTask']['desc'] = "<p>将执行{$lang->SRCommon}分解为任务：</p><ul><li data-target='nav'>打开 <span class='task-nav'> 执行 <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 分解任务</span> 页面；</li><li data-target='form'>在表单中填写任务信息；</li><li data-target='submit'>保存任务信息。</li></ul>";

$lang->tutorial->tasks['createBug']         = array('title' => '提Bug');
$lang->tutorial->tasks['createBug']['nav']  = array(
    'app' => 'qa',
    'module' => 'bug',
    'method' => 'create',
    'menuModule' => 'bug',
    'menu' => 'bug',
    'target' => '.create-bug-btn',
    'vars' => 'productID=0',
    'form' => '#mainContent',
    'submit' => 'button[type=submit]',
    'targetPageName' => '提Bug'
);
$lang->tutorial->tasks['createBug']['desc'] = "<p>在系统中提交一个Bug：</p><ul><li data-target='nav'>打开 <span class='task-nav'> 测试 <i class='icon icon-angle-right'></i> Bug <i class='icon icon-angle-right'></i> 提Bug</span>；</li><li data-target='form'>在表单中填写Bug信息；</li><li data-target='submit'>保存Bug信息。</li></ul>";

global $config;
if($config->systemMode == 'light') unset($lang->tutorial->tasks['createProgram']);
