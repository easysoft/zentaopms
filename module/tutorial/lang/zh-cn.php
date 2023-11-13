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
$lang->tutorial->congratulateTask = '恭喜，你完成了任务 ';
$lang->tutorial->serverErrorTip   = '发生了一些错误。';
$lang->tutorial->ajaxSetError     = '必须指定已完成的任务，如果要重置任务，请设置值为空。';
$lang->tutorial->novice           = "你可能初次使用禅道，是否进入新手教程";
$lang->tutorial->dataNotSave      = "教程任务中，数据不会保存。";

$lang->tutorial->tasks = array();
$lang->tutorial->tasks['createAccount'] = array();
$lang->tutorial->tasks['createAccount']['title'] = '创建帐号';

$lang->tutorial->tasks['createAccount']['nav']   = array();
$lang->tutorial->tasks['createAccount']['nav']['app']            = 'admin';
$lang->tutorial->tasks['createAccount']['nav']['module']         = 'user';
$lang->tutorial->tasks['createAccount']['nav']['method']         = 'create';
$lang->tutorial->tasks['createAccount']['nav']['menuModule']     = 'company';
$lang->tutorial->tasks['createAccount']['nav']['menu']           = 'browseUser';
$lang->tutorial->tasks['createAccount']['nav']['form']           = '#createUser';
$lang->tutorial->tasks['createAccount']['nav']['requiredFields'] = 'account,realname,verifyPassword,password1,password2';
$lang->tutorial->tasks['createAccount']['nav']['submit']         = 'button[type=submit]';
$lang->tutorial->tasks['createAccount']['nav']['target']         = '.create-user-btn';
$lang->tutorial->tasks['createAccount']['nav']['targetPageName'] = '添加用户';

$lang->tutorial->tasks['createAccount']['desc']                  = "<p>在系统创建一个新的用户帐号：</p><ul><li data-target='nav'>打开 <span class='task-nav'>后台 <i class='icon icon-angle-right'></i> 人员管理 <i class='icon icon-angle-right'></i> 用户 <i class='icon icon-angle-right'></i> 添加用户</span> 页面；</li><li data-target='form'>在添加用户表单中填写新用户信息；</li><li data-target='submit'>保存用户信息。</li></ul>";

$lang->tutorial->tasks['createProgram'] = array();
$lang->tutorial->tasks['createProgram']['title'] = '创建项目集';

$lang->tutorial->tasks['createProgram']['nav']   = array();
$lang->tutorial->tasks['createProgram']['nav']['app']            = 'program';
$lang->tutorial->tasks['createProgram']['nav']['module']         = 'program';
$lang->tutorial->tasks['createProgram']['nav']['method']         = 'create';
$lang->tutorial->tasks['createProgram']['nav']['menuModule']     = 'program';
$lang->tutorial->tasks['createProgram']['nav']['menu']           = '.create-program-btn';
$lang->tutorial->tasks['createProgram']['nav']['form']           = '#createProgram';
$lang->tutorial->tasks['createProgram']['nav']['submit']         = 'button[type=submit]';
$lang->tutorial->tasks['createProgram']['nav']['target']         = '.create-program-btn';
$lang->tutorial->tasks['createProgram']['nav']['targetPageName'] = '添加项目集';

$lang->tutorial->tasks['createProgram']['desc'] = "<p>在系统创建一个新的项目集：</p><ul><li data-target='nav'>打开 <span class='task-nav'>项目集 <i class='icon icon-angle-right'></i> 项目集列表 <i class='icon icon-angle-right'></i> 添加项目集</span> 页面；</li><li data-target='form'>在添加项目集表单中填写项目集信息；</li><li data-target='submit'>保存项目集信息。</li></ul>";

$lang->tutorial->tasks['createProduct'] = array();
$lang->tutorial->tasks['createProduct']['title'] = '创建产品';

$lang->tutorial->tasks['createProduct']['nav']   = array();
$lang->tutorial->tasks['createProduct']['nav']['app']            = 'product';
$lang->tutorial->tasks['createProduct']['nav']['module']         = 'product';
$lang->tutorial->tasks['createProduct']['nav']['method']         = 'create';
$lang->tutorial->tasks['createProduct']['nav']['menuModule']     = 'product';
$lang->tutorial->tasks['createProduct']['nav']['menu']           = '#heading > .toolbar > .toolbar-item, .create-product-btn';
$lang->tutorial->tasks['createProduct']['nav']['form']           = '#mainContent';
$lang->tutorial->tasks['createProduct']['nav']['submit']         = 'button[type=submit]';
$lang->tutorial->tasks['createProduct']['nav']['target']         = '';
$lang->tutorial->tasks['createProduct']['nav']['targetPageName'] = '添加产品';

$lang->tutorial->tasks['createProduct']['desc'] = "<p>在系统创建一个新的{$lang->productCommon}：</p><ul><li data-target='nav'>打开 <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i> {$lang->productCommon}列表 <i class='icon icon-angle-right'></i> 添加{$lang->productCommon}</span> 页面；</li><li data-target='form'>在添加{$lang->productCommon}表单中填写要创建的{$lang->productCommon}信息；</li><li data-target='submit'>保存{$lang->productCommon}信息。</li></ul>";

$lang->tutorial->tasks['createStory'] = array();
$lang->tutorial->tasks['createStory']['title'] = "创建{$lang->SRCommon}";

$lang->tutorial->tasks['createStory']['nav']   = array();
$lang->tutorial->tasks['createStory']['nav']['app']            = 'product';
$lang->tutorial->tasks['createStory']['nav']['module']         = 'story';
$lang->tutorial->tasks['createStory']['nav']['method']         = 'create';
$lang->tutorial->tasks['createStory']['nav']['menuModule']     = 'story';
$lang->tutorial->tasks['createStory']['nav']['menu']           = '#products > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell > .dtable-cell-content > a, #heading > .toolbar > .toolbar-item, .create-story-btn';
$lang->tutorial->tasks['createStory']['nav']['form']           = '#mainContent';
$lang->tutorial->tasks['createStory']['nav']['submit']         = '#saveButton';
$lang->tutorial->tasks['createStory']['nav']['target']         = '.create-story-btn';
$lang->tutorial->tasks['createStory']['nav']['targetPageName'] = "提{$lang->SRCommon}";

$lang->tutorial->tasks['createStory']['desc'] = "<p>在系统创建一个新的{$lang->SRCommon}：</p><ul><li data-target='nav'>打开 <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 提{$lang->SRCommon}</span> 页面；</li><li data-target='form'>在{$lang->productCommon}表单中填写要创建的{$lang->SRCommon}信息；</li><li data-target='submit'>保存{$lang->SRCommon}信息。</li></ul>";

$lang->tutorial->tasks['createProject'] = array();
$lang->tutorial->tasks['createProject']['title'] = '创建项目';

$lang->tutorial->tasks['createProject']['nav']   = array();
$lang->tutorial->tasks['createProject']['nav']['app']            = 'project';
$lang->tutorial->tasks['createProject']['nav']['module']         = 'project';
$lang->tutorial->tasks['createProject']['nav']['method']         = 'create';
$lang->tutorial->tasks['createProject']['nav']['menuModule']     = 'project';
$lang->tutorial->tasks['createProject']['nav']['menu']           = '#heading > .toolbar > .toolbar-item, .create-project-btn';
$lang->tutorial->tasks['createProject']['nav']['form']           = '#mainContent';
$lang->tutorial->tasks['createProject']['nav']['submit']         = 'button[type=submit]';
$lang->tutorial->tasks['createProject']['nav']['target']         = '';
$lang->tutorial->tasks['createProject']['nav']['targetPageName'] = '添加项目';

$lang->tutorial->tasks['createProject']['desc'] = "<p>在系统创建一个新的{$lang->projectCommon}：</p><ul><li data-target='nav'>打开 <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> {$lang->projectCommon}列表 <i class='icon icon-angle-right'></i> 创建{$lang->projectCommon}</span> 页面；</li><li data-target='form'>在{$lang->projectCommon}表单中填写要创建的{$lang->projectCommon}信息；</li><li data-target='submit'>保存{$lang->projectCommon}信息。</li></ul>";

$lang->tutorial->tasks['manageTeam'] = array();
$lang->tutorial->tasks['manageTeam']['title'] = "管理{$lang->projectCommon}团队";

$lang->tutorial->tasks['manageTeam']['nav']   = array();
$lang->tutorial->tasks['manageTeam']['nav']['app']            = 'project';
$lang->tutorial->tasks['manageTeam']['nav']['module']         = 'project';
$lang->tutorial->tasks['manageTeam']['nav']['method']         = 'managemembers';
$lang->tutorial->tasks['manageTeam']['nav']['menuModule']     = '';
$lang->tutorial->tasks['manageTeam']['nav']['menu']           = '#actionBar, #header > .container > #heading > .toolbar, #table-tutorial-wizard > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="name"] > .dtable-cell-content > a, #table-project-browse > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="name"] > .dtable-cell-content > a, .nav-item > a[data-id="settings"], #mainNavbar > .container > .nav > .nav-item > a[data-id="members"]';
$lang->tutorial->tasks['manageTeam']['nav']['form']           = '#teamForm';
$lang->tutorial->tasks['manageTeam']['nav']['requiredFields'] = 'account1';
$lang->tutorial->tasks['manageTeam']['nav']['formType']       = 'table';
$lang->tutorial->tasks['manageTeam']['nav']['submit']         = '.form-row > .form-actions > button:first-child';
$lang->tutorial->tasks['manageTeam']['nav']['target']         = '.manage-team-btn';
$lang->tutorial->tasks['manageTeam']['nav']['targetPageName'] = '团队管理';

$lang->tutorial->tasks['manageTeam']['desc'] = "<p>管理{$lang->projectCommon}团队成员：</p><ul><li data-target='nav'>打开 <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> 设置 <i class='icon icon-angle-right'></i> 团队 <i class='icon icon-angle-right'></i> 团队管理</span> 页面；</li><li data-target='form'>选择要加入{$lang->projectCommon}团队的成员；</li><li data-target='submit'>保存团队成员信息。</li></ul>";

$lang->tutorial->tasks['createProjectExecution'] = array();
$lang->tutorial->tasks['createProjectExecution']['title'] = '创建执行';

$lang->tutorial->tasks['createProjectExecution']['nav']   = array();
$lang->tutorial->tasks['createProjectExecution']['nav']['app']            = 'project';
$lang->tutorial->tasks['createProjectExecution']['nav']['module']         = 'execution';
$lang->tutorial->tasks['createProjectExecution']['nav']['method']         = 'create';
$lang->tutorial->tasks['createProjectExecution']['nav']['menuModule']     = '';
$lang->tutorial->tasks['createProjectExecution']['nav']['menu']           = '#heading > .toolbar > .toolbar-item, #table-tutorial-wizard > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="name"] > .dtable-cell-content > a, .create-execution-btn';
$lang->tutorial->tasks['createProjectExecution']['nav']['form']           = '#mainContent';
$lang->tutorial->tasks['createProjectExecution']['nav']['submit']         = 'button[type=submit]';
$lang->tutorial->tasks['createProjectExecution']['nav']['targetPageName'] = "添加{$lang->executionCommon}";

$lang->tutorial->tasks['createProjectExecution']['desc'] = "<p>在系统创建一个新的{$lang->executionCommon}：</p><ul><li data-target='nav'>打开 <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> {$lang->executionCommon} <i class='icon icon-angle-right'></i> 添加{$lang->executionCommon}</span> 页面；</li><li data-target='form'>在{$lang->executionCommon}表单中填写要创建的{$lang->executionCommon}信息；</li><li data-target='submit'>保存{$lang->executionCommon}信息。</li></ul>";

$lang->tutorial->tasks['linkStory'] = array();
$lang->tutorial->tasks['linkStory']['title'] = "关联{$lang->SRCommon}";

$lang->tutorial->tasks['linkStory']['nav']   = array();
$lang->tutorial->tasks['linkStory']['nav']['app']            = 'execution';
$lang->tutorial->tasks['linkStory']['nav']['module']         = 'execution';
$lang->tutorial->tasks['linkStory']['nav']['method']         = 'linkStory';
$lang->tutorial->tasks['linkStory']['nav']['menuModule']     = 'story';
$lang->tutorial->tasks['linkStory']['nav']['menu']           = '#heading > .toolbar > a[title="执行"], #table-execution-all > .dtable-body > .dtable-cells > .dtable-cells-container > .dtable-cell[data-col="name"], .link-story-btn';
$lang->tutorial->tasks['linkStory']['nav']['form']           = '#table-tutorial-wizard';
$lang->tutorial->tasks['linkStory']['nav']['formType']       = 'table';
$lang->tutorial->tasks['linkStory']['nav']['submit']         = '.link-story-btn';
$lang->tutorial->tasks['linkStory']['nav']['target']         = '.dtable-checkbox';
$lang->tutorial->tasks['linkStory']['nav']['targetPageName'] = "关联{$lang->SRCommon}";

$lang->tutorial->tasks['linkStory']['desc'] = "<p>将{$lang->SRCommon}关联到执行：</p><ul><li data-target='nav'>打开 <span class='task-nav'> 执行 <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 关联{$lang->SRCommon}</span> 页面；</li><li data-target='form'>在{$lang->SRCommon}列表中勾选要关联的{$lang->SRCommon}；</li><li data-target='submit'>保存关联的{$lang->SRCommon}信息。</li></ul>";

$lang->tutorial->tasks['createTask'] = array();
$lang->tutorial->tasks['createTask']['title'] = '创建任务';

$lang->tutorial->tasks['createTask']['nav']   = array();
$lang->tutorial->tasks['createTask']['nav']['app']            = 'execution';
$lang->tutorial->tasks['createTask']['nav']['module']         = 'task';
$lang->tutorial->tasks['createTask']['nav']['method']         = 'create';
$lang->tutorial->tasks['createTask']['nav']['menuModule']     = 'story';
$lang->tutorial->tasks['createTask']['nav']['menu']           = '#heading > .toolbar > .toolbar-item, .create-task-btn';
$lang->tutorial->tasks['createTask']['nav']['form']           = '#mainContent';
$lang->tutorial->tasks['createTask']['nav']['submit']         = 'button[type=submit]';
$lang->tutorial->tasks['createTask']['nav']['target']         = '.create-task-btn';
$lang->tutorial->tasks['createTask']['nav']['targetPageName'] = '创建任务';

$lang->tutorial->tasks['createTask']['desc'] = "<p>将执行{$lang->SRCommon}分解为任务：</p><ul><li data-target='nav'>打开 <span class='task-nav'> 执行 <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 分解任务</span> 页面；</li><li data-target='form'>在表单中填写任务信息；</li><li data-target='submit'>保存任务信息。</li></ul>";

$lang->tutorial->tasks['createBug'] = array();
$lang->tutorial->tasks['createBug']['title'] = '提Bug';

$lang->tutorial->tasks['createBug']['nav']   = array();
$lang->tutorial->tasks['createBug']['nav']['app']            = 'qa';
$lang->tutorial->tasks['createBug']['nav']['module']         = 'bug';
$lang->tutorial->tasks['createBug']['nav']['method']         = 'create';
$lang->tutorial->tasks['createBug']['nav']['menuModule']     = 'bug';
$lang->tutorial->tasks['createBug']['nav']['menu']           = '#heading > .toolbar > .toolbar-item, .create-bug-btn';
$lang->tutorial->tasks['createBug']['nav']['form']           = '#mainContent';
$lang->tutorial->tasks['createBug']['nav']['submit']         = 'button[type=submit]';
$lang->tutorial->tasks['createBug']['nav']['target']         = '.create-bug-btn';
$lang->tutorial->tasks['createBug']['nav']['targetPageName'] = '提Bug';

$lang->tutorial->tasks['createBug']['desc'] = "<p>在系统中提交一个Bug：</p><ul><li data-target='nav'>打开 <span class='task-nav'> 测试 <i class='icon icon-angle-right'></i> Bug <i class='icon icon-angle-right'></i> 提Bug</span>；</li><li data-target='form'>在表单中填写Bug信息；</li><li data-target='submit'>保存Bug信息。</li></ul>";

global $config;
if($config->systemMode == 'light') unset($lang->tutorial->tasks['createProgram']);
