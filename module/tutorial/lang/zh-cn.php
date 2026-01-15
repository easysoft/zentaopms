<?php
/**
 * The tutorial lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: zh-cn.php 5116 2013-07-12 06:37:48Z sunhao@cnezsoft.com $
 * @link        https://www.zentao.net
 */
$lang->tutorial = new stdclass();
$lang->tutorial->common           = '使用教程';
$lang->tutorial->desc             = '通过完成一系列任务，快速了解禅道的基本使用方法，你可以随时退出任务。';
$lang->tutorial->start            = '开始';
$lang->tutorial->continue         = '继续';
$lang->tutorial->exit             = '退出教程';
$lang->tutorial->exitStep         = '退出';
$lang->tutorial->finish           = '完成';
$lang->tutorial->congratulation   = '恭喜，你已完成了所有任务！';
$lang->tutorial->restart          = '重新开始';
$lang->tutorial->currentTask      = '当前任务';
$lang->tutorial->allTasks         = '所有任务';
$lang->tutorial->previous         = '上一个';
$lang->tutorial->nextTask         = '下一个任务';
$lang->tutorial->nextGuide        = '下一个教程';
$lang->tutorial->nextStep         = '下一步';
$lang->tutorial->openTargetPage   = '打开 <strong class="task-page-name">目标</strong> 页面';
$lang->tutorial->atTargetPage     = '已在 <strong class="task-page-name">目标</strong> 页面';
$lang->tutorial->reloadTargetPage = '重新载入';
$lang->tutorial->target           = '目标';
$lang->tutorial->targetPageTip    = '按此指示打开【%s】页面';
$lang->tutorial->targetAppTip     = '按此指示打开【%s】应用';
$lang->tutorial->requiredTip      = '【%s】为必填项';
$lang->tutorial->congratulateTask = '恭喜，你完成了任务【<span class="task-name-current"></span>】';
$lang->tutorial->serverErrorTip   = '发生了一些错误。';
$lang->tutorial->ajaxSetError     = '必须指定已完成的任务，如果要重置任务，请设置值为空。';
$lang->tutorial->novice           = "你可能初次使用禅道，是否进入新手教程";
$lang->tutorial->dataNotSave      = "教程任务中，数据不会保存。";
$lang->tutorial->clickTipFormat   = "点击%s";
$lang->tutorial->clickAndOpenIt   = "点击%s打开%s。";

$lang->tutorial->guideTypes        = array();
$lang->tutorial->guideTypes['starter'] = '快速上手';
$lang->tutorial->guideTypes['basic']   = '基础教程';
$lang->tutorial->guideTypes['advance'] = '进阶教程';

$lang->tutorial->tasks = new stdclass();
$lang->tutorial->tasks->createAccount = new stdclass();

$lang->tutorial->tasks->createAccount->title          = '创建帐号';
$lang->tutorial->tasks->createAccount->targetPageName = '添加用户';
$lang->tutorial->tasks->createAccount->desc           = "<p>在系统创建一个新的用户帐号：</p><ul><li data-target='nav'>打开 <span class='task-nav'>后台 <i class='icon icon-angle-right'></i> 人员管理 <i class='icon icon-angle-right'></i> 用户 <i class='icon icon-angle-right'></i> 添加用户</span> 页面；</li><li data-target='form'>在添加用户表单中填写新用户信息；</li><li data-target='submit'>保存用户信息。</li></ul>";

$lang->tutorial->tasks->createProgram = new stdClass();
$lang->tutorial->tasks->createProgram->title          = '创建项目集';
$lang->tutorial->tasks->createProgram->targetPageName = '添加项目集';
$lang->tutorial->tasks->createProgram->desc           = "<p>在系统创建一个新的项目集：</p><ul><li data-target='nav'>打开 <span class='task-nav'>项目集 <i class='icon icon-angle-right'></i> 项目集列表 <i class='icon icon-angle-right'></i> 添加项目集</span> 页面；</li><li data-target='form'>在添加项目集表单中填写项目集信息；</li><li data-target='submit'>保存项目集信息。</li></ul>";

$lang->tutorial->tasks->createProduct = new stdClass();
$lang->tutorial->tasks->createProduct->title          = '创建产品';
$lang->tutorial->tasks->createProduct->targetPageName = '添加产品';
$lang->tutorial->tasks->createProduct->desc           = "<p>在系统创建一个新的{$lang->productCommon}：</p><ul><li data-target='nav'>打开 <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i> {$lang->productCommon}列表 <i class='icon icon-angle-right'></i> 添加{$lang->productCommon}</span> 页面；</li><li data-target='form'>在添加{$lang->productCommon}表单中填写要创建的{$lang->productCommon}信息；</li><li data-target='submit'>保存{$lang->productCommon}信息。</li></ul>";

$lang->tutorial->tasks->createStory = new stdClass();
$lang->tutorial->tasks->createStory->title          = "创建{$lang->SRCommon}";
$lang->tutorial->tasks->createStory->targetPageName = "提{$lang->SRCommon}";
$lang->tutorial->tasks->createStory->desc           = "<p>在系统创建一个新的{$lang->SRCommon}：</p><ul><li data-target='nav'>打开 <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 提{$lang->SRCommon}</span> 页面；</li><li data-target='form'>在{$lang->productCommon}表单中填写要创建的{$lang->SRCommon}信息；</li><li data-target='submit'>保存{$lang->SRCommon}信息。</li></ul>";

$lang->tutorial->tasks->createProject = new stdClass();
$lang->tutorial->tasks->createProject->title          = '创建项目';
$lang->tutorial->tasks->createProject->targetPageName = '添加项目';
$lang->tutorial->tasks->createProject->desc           = "<p>在系统创建一个新的{$lang->projectCommon}：</p><ul><li data-target='nav'>打开 <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> {$lang->projectCommon}列表 <i class='icon icon-angle-right'></i> 创建{$lang->projectCommon}</span> 页面；</li><li data-target='form'>在{$lang->projectCommon}表单中填写要创建的{$lang->projectCommon}信息；</li><li data-target='submit'>保存{$lang->projectCommon}信息。</li></ul>";

$lang->tutorial->tasks->manageTeam = new stdClass();
$lang->tutorial->tasks->manageTeam->title          = "管理{$lang->projectCommon}团队";
$lang->tutorial->tasks->manageTeam->targetPageName = '团队管理';
$lang->tutorial->tasks->manageTeam->desc           = "<p>管理{$lang->projectCommon}团队成员：</p><ul><li data-target='nav'>打开 <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> 设置 <i class='icon icon-angle-right'></i> 团队 <i class='icon icon-angle-right'></i> 团队管理</span> 页面；</li><li data-target='form'>选择要加入{$lang->projectCommon}团队的成员；</li><li data-target='submit'>保存团队成员信息。</li></ul>";

$lang->tutorial->tasks->createProjectExecution = new stdClass();
$lang->tutorial->tasks->createProjectExecution->title             = '创建执行';
$lang->tutorial->tasks->createProjectExecution->targetPageName = "添加{$lang->executionCommon}";
$lang->tutorial->tasks->createProjectExecution->desc              = "<p>在系统创建一个新的{$lang->executionCommon}：</p><ul><li data-target='nav'>打开 <span class='task-nav'> {$lang->projectCommon} <i class='icon icon-angle-right'></i> {$lang->executionCommon} <i class='icon icon-angle-right'></i> 添加{$lang->executionCommon}</span> 页面；</li><li data-target='form'>在{$lang->executionCommon}表单中填写要创建的{$lang->executionCommon}信息；</li><li data-target='submit'>保存{$lang->executionCommon}信息。</li></ul>";

$lang->tutorial->tasks->linkStory = new stdClass();
$lang->tutorial->tasks->linkStory->title          = "关联{$lang->SRCommon}";
$lang->tutorial->tasks->linkStory->targetPageName = "关联{$lang->SRCommon}";
$lang->tutorial->tasks->linkStory->desc           = "<p>将{$lang->SRCommon}关联到执行：</p><ul><li data-target='nav'>打开 <span class='task-nav'> 执行 <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 关联{$lang->SRCommon}</span> 页面；</li><li data-target='form'>在{$lang->SRCommon}列表中勾选要关联的{$lang->SRCommon}；</li><li data-target='submit'>保存关联的{$lang->SRCommon}信息。</li></ul>";

$lang->tutorial->tasks->createTask = new stdClass();
$lang->tutorial->tasks->createTask->title          = '分解任务';
$lang->tutorial->tasks->createTask->targetPageName = '建任务';
$lang->tutorial->tasks->createTask->desc           = "<p>将执行{$lang->SRCommon}分解为任务：</p><ul><li data-target='nav'>打开 <span class='task-nav'> 执行 <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 分解任务</span> 页面；</li><li data-target='form'>在表单中填写任务信息；</li><li data-target='submit'>保存任务信息。</li></ul>";

$lang->tutorial->tasks->createBug = new stdClass();
$lang->tutorial->tasks->createBug->title          = '提Bug';
$lang->tutorial->tasks->createBug->targetPageName = '提Bug';
$lang->tutorial->tasks->createBug->desc           = "<p>在系统中提交一个Bug：</p><ul><li data-target='nav'>打开 <span class='task-nav'> 测试 <i class='icon icon-angle-right'></i> Bug <i class='icon icon-angle-right'></i> 提Bug</span>；</li><li data-target='form'>在表单中填写Bug信息；</li><li data-target='submit'>保存Bug信息。</li></ul>";

$lang->tutorial->starter = new stdClass();
$lang->tutorial->starter->title = '快速上手教程';

$lang->tutorial->starter->createAccount = new stdClass();
$lang->tutorial->starter->createAccount->title = '创建账号';

$lang->tutorial->starter->createAccount->step1 = new stdClass();
$lang->tutorial->starter->createAccount->step1->name = '点击后台';
$lang->tutorial->starter->createAccount->step1->desc = '您可以在这里维护管理账号，进行各类配置项的设置。';

$lang->tutorial->starter->createAccount->step2 = new stdClass();
$lang->tutorial->starter->createAccount->step2->name = '点击人员管理';
$lang->tutorial->starter->createAccount->step2->desc = '您可以在这里维护部门、添加人员和分组配置权限';

$lang->tutorial->starter->createAccount->step3 = new stdClass();
$lang->tutorial->starter->createAccount->step3->name = '点击用户';
$lang->tutorial->starter->createAccount->step3->desc = '您可以在这里维护公司人员';

$lang->tutorial->starter->createAccount->step4 = new stdClass();
$lang->tutorial->starter->createAccount->step4->name = '点击添加人员按钮';
$lang->tutorial->starter->createAccount->step4->desc = '点击添加公司人员';

$lang->tutorial->starter->createAccount->step5 = new stdClass();
$lang->tutorial->starter->createAccount->step5->name = '填写表单';

$lang->tutorial->starter->createAccount->step6 = new stdClass();
$lang->tutorial->starter->createAccount->step6->name = '保存表单';
$lang->tutorial->starter->createAccount->step6->desc = '保存后可以在人员列表中查看';

$lang->tutorial->starter->createProgram = new stdClass();
$lang->tutorial->starter->createProgram->title = '创建项目集';

$lang->tutorial->starter->createProgram->step1 = new stdClass();
$lang->tutorial->starter->createProgram->step1->name = '点击项目集';
$lang->tutorial->starter->createProgram->step1->desc = '您可以在这里维护管理项目集';

$lang->tutorial->starter->createProgram->step2 = new stdClass();
$lang->tutorial->starter->createProgram->step2->name = '点击添加项目集';
$lang->tutorial->starter->createProgram->step2->desc = '点击添加项目集';

$lang->tutorial->starter->createProgram->step3 = new stdClass();
$lang->tutorial->starter->createProgram->step3->name = '填写表单';

$lang->tutorial->starter->createProgram->step4 = new stdClass();
$lang->tutorial->starter->createProgram->step4->name = '保存表单';
$lang->tutorial->starter->createProgram->step4->desc = '保存后在项目视角和产品视角列表中均可查看';

$lang->tutorial->starter->createProduct = new stdClass();
$lang->tutorial->starter->createProduct->title = '创建产品';

$lang->tutorial->starter->createProduct->step1 = new stdClass();
$lang->tutorial->starter->createProduct->step1->name = '点击产品';
$lang->tutorial->starter->createProduct->step1->desc = '您可以在这里维护管理产品';

$lang->tutorial->starter->createProduct->step2 = new stdClass();
$lang->tutorial->starter->createProduct->step2->name = '点击添加产品';
$lang->tutorial->starter->createProduct->step2->desc = '您可以在这里添加产品';

$lang->tutorial->starter->createProduct->step3 = new stdClass();
$lang->tutorial->starter->createProduct->step3->name = '填写表单';

$lang->tutorial->starter->createProduct->step4 = new stdClass();
$lang->tutorial->starter->createProduct->step4->name = '保存表单';
$lang->tutorial->starter->createProduct->step4->desc = '保存后可以在产品列表中查看';

$lang->tutorial->starter->createStory = new stdClass();
$lang->tutorial->starter->createStory->title = '创建研发需求';

$lang->tutorial->starter->createStory->step1 = new stdClass();
$lang->tutorial->starter->createStory->step1->name = '点击产品';
$lang->tutorial->starter->createStory->step1->desc = '您可以在这里维护管理产品';

$lang->tutorial->starter->createStory->step2 = new stdClass();
$lang->tutorial->starter->createStory->step2->name = '点击产品名称';
$lang->tutorial->starter->createStory->step2->desc = '点击进入产品，查看产品的详细信息。';

$lang->tutorial->starter->createStory->step3 = new stdClass();
$lang->tutorial->starter->createStory->step3->name = '点击提研发需求';
$lang->tutorial->starter->createStory->step3->desc = '您可以在这里创建研发需求';

$lang->tutorial->starter->createStory->step4 = new stdClass();
$lang->tutorial->starter->createStory->step4->name = '填写表单';

$lang->tutorial->starter->createStory->step5 = new stdClass();
$lang->tutorial->starter->createStory->step5->name = '保存表单';
$lang->tutorial->starter->createStory->step5->desc = '保存后可以在产品需求列表中查看';

$lang->tutorial->starter->createProject = new stdClass();
$lang->tutorial->starter->createProject->title = '创建项目';

$lang->tutorial->starter->createProject->step1 = new stdClass();
$lang->tutorial->starter->createProject->step1->name = '点击项目';
$lang->tutorial->starter->createProject->step1->desc = '您可以在这里创建项目';

$lang->tutorial->starter->createProject->step2 = new stdClass();
$lang->tutorial->starter->createProject->step2->name = '点击创建项目';
$lang->tutorial->starter->createProject->step2->desc = '您可以选择不同项目管理方式来创建不同类型的项目';

$lang->tutorial->starter->createProject->step3 = new stdClass();
$lang->tutorial->starter->createProject->step3->name = '点击Scrum项目';
$lang->tutorial->starter->createProject->step3->desc = '请点击Scrum创建Scrum项目';

$lang->tutorial->starter->createProject->step4 = new stdClass();
$lang->tutorial->starter->createProject->step4->name = '填写表单';

$lang->tutorial->starter->createProject->step5 = new stdClass();
$lang->tutorial->starter->createProject->step5->name = '保存表单';
$lang->tutorial->starter->createProject->step5->desc = '保存后会显示在项目列表中';

$lang->tutorial->starter->manageTeam = new stdClass();
$lang->tutorial->starter->manageTeam->title = '管理项目团队';

$lang->tutorial->starter->manageTeam->step1 = new stdClass();
$lang->tutorial->starter->manageTeam->step1->name = '点击项目';
$lang->tutorial->starter->manageTeam->step1->desc = '您可以在这里维护管理项目';

$lang->tutorial->starter->manageTeam->step2 = new stdClass();
$lang->tutorial->starter->manageTeam->step2->name = '点击项目名称';
$lang->tutorial->starter->manageTeam->step2->desc = '点击项目名称进入项目';

$lang->tutorial->starter->manageTeam->step3 = new stdClass();
$lang->tutorial->starter->manageTeam->step3->name = '点击设置';
$lang->tutorial->starter->manageTeam->step3->desc = '点击设置开始维护团队';

$lang->tutorial->starter->manageTeam->step4 = new stdClass();
$lang->tutorial->starter->manageTeam->step4->name = '点击团队';
$lang->tutorial->starter->manageTeam->step4->desc = '点击团队可以查看该项目中的团队成员';

$lang->tutorial->starter->manageTeam->step5 = new stdClass();
$lang->tutorial->starter->manageTeam->step5->name = '点击团队管理';
$lang->tutorial->starter->manageTeam->step5->desc = '点击团队管理可以对当前项目的团队成员进行维护';

$lang->tutorial->starter->manageTeam->step6 = new stdClass();
$lang->tutorial->starter->manageTeam->step6->name = '填写表单';

$lang->tutorial->starter->manageTeam->step7 = new stdClass();
$lang->tutorial->starter->manageTeam->step7->name = '保存表单';
$lang->tutorial->starter->manageTeam->step7->desc = '保存后可以在团队中查看团队成员';

$lang->tutorial->starter->createProjectExecution = new stdClass();
$lang->tutorial->starter->createProjectExecution->title = '创建执行';

$lang->tutorial->starter->createProjectExecution->step1 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step1->name = '点击项目';
$lang->tutorial->starter->createProjectExecution->step1->desc = '您可以在这里维护管理项目';

$lang->tutorial->starter->createProjectExecution->step2 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step2->name = '点击项目名称';
$lang->tutorial->starter->createProjectExecution->step2->desc = '点击项目名称进入项目';

$lang->tutorial->starter->createProjectExecution->step3 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step3->name = '点击迭代';
$lang->tutorial->starter->createProjectExecution->step3->desc = '点击迭代开始添加新迭代';

$lang->tutorial->starter->createProjectExecution->step4 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step4->name = '点击添加迭代';
$lang->tutorial->starter->createProjectExecution->step4->desc = '您可以在这里添加迭代';

$lang->tutorial->starter->createProjectExecution->step5 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step5->name = '填写表单';

$lang->tutorial->starter->createProjectExecution->step6 = new stdClass();
$lang->tutorial->starter->createProjectExecution->step6->name = '保存表单';
$lang->tutorial->starter->createProjectExecution->step6->desc = '保存后可以选择设置团队、关联需求、创建任务、返回任务列表和返回执行列表';

$lang->tutorial->starter->linkStory = new stdClass();
$lang->tutorial->starter->linkStory->title = "关联{$lang->SRCommon}";

$lang->tutorial->starter->linkStory->step1 = new stdClass();
$lang->tutorial->starter->linkStory->step1->name = '点击迭代';
$lang->tutorial->starter->linkStory->step1->desc = '您可以在这里维护管理迭代';

$lang->tutorial->starter->linkStory->step2 = new stdClass();
$lang->tutorial->starter->linkStory->step2->name = '点击需求';
$lang->tutorial->starter->linkStory->step2->desc = '点击需求查看已关联的需求';

$lang->tutorial->starter->linkStory->step3 = new stdClass();
$lang->tutorial->starter->linkStory->step3->name = '点击关联需求';
$lang->tutorial->starter->linkStory->step3->desc = '点击关联需求进入关联需求列表';

$lang->tutorial->starter->linkStory->step4 = new stdClass();
$lang->tutorial->starter->linkStory->step4->name = '选择需求';

$lang->tutorial->starter->linkStory->step5 = new stdClass();
$lang->tutorial->starter->linkStory->step5->name = '点击保存';
$lang->tutorial->starter->linkStory->step5->desc = '点击保存可以将需求关联到需求列表中，返回到需求列表';

$lang->tutorial->starter->createTask = new stdClass();
$lang->tutorial->starter->createTask->title = '分解任务';

$lang->tutorial->starter->createTask->step1 = new stdClass();
$lang->tutorial->starter->createTask->step1->name = '点击迭代';
$lang->tutorial->starter->createTask->step1->desc = '您可以在这里维护管理迭代';

$lang->tutorial->starter->createTask->step2 = new stdClass();
$lang->tutorial->starter->createTask->step2->name = '点击需求';
$lang->tutorial->starter->createTask->step2->desc = '进入需求列表，您可以在这里看到之前关联的需求';

$lang->tutorial->starter->createTask->step3 = new stdClass();
$lang->tutorial->starter->createTask->step3->name = '分解任务';
$lang->tutorial->starter->createTask->step3->desc = '您可以在这里将需求分解为任务，支持批量分解';

$lang->tutorial->starter->createTask->step4 = new stdClass();
$lang->tutorial->starter->createTask->step4->name = '填写表单';

$lang->tutorial->starter->createTask->step5 = new stdClass();
$lang->tutorial->starter->createTask->step5->name = '保存表单';
$lang->tutorial->starter->createTask->step5->desc = '保存后可以在任务列表中查看分解的任务';

$lang->tutorial->starter->createBug = new stdClass();
$lang->tutorial->starter->createBug->title = '提Bug';

$lang->tutorial->starter->createBug->step1 = new stdClass();
$lang->tutorial->starter->createBug->step1->name = '点击测试';
$lang->tutorial->starter->createBug->step1->desc = '您可以在这里进行测试管理';

$lang->tutorial->starter->createBug->step2 = new stdClass();
$lang->tutorial->starter->createBug->step2->name = '点击Bug';
$lang->tutorial->starter->createBug->step2->desc = '可以在这里进行Bug管理';

$lang->tutorial->starter->createBug->step3 = new stdClass();
$lang->tutorial->starter->createBug->step3->name = '点击提Bug';
$lang->tutorial->starter->createBug->step3->desc = '可以在这里创建Bug';

$lang->tutorial->starter->createBug->step4 = new stdClass();
$lang->tutorial->starter->createBug->step4->name = '填写表单';

$lang->tutorial->starter->createBug->step5 = new stdClass();
$lang->tutorial->starter->createBug->step5->name = '保存表单';
$lang->tutorial->starter->createBug->step5->desc = '保存后进入Bug列表';

$lang->tutorial->scrumProjectManage = new stdClass();
$lang->tutorial->scrumProjectManage->title = 'Scrum项目管理教程';

$lang->tutorial->scrumProjectManage->manageProject = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->title = '项目维护';

$lang->tutorial->scrumProjectManage->manageProject->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step1->name = '点击项目';
$lang->tutorial->scrumProjectManage->manageProject->step1->desc = '您可以在这里创建项目';

$lang->tutorial->scrumProjectManage->manageProject->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step2->name = '点击创建项目';
$lang->tutorial->scrumProjectManage->manageProject->step2->desc = '您可以选择不同项目管理方式来创建不同类型的项目';

$lang->tutorial->scrumProjectManage->manageProject->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step3->name = '点击Scrum项目';
$lang->tutorial->scrumProjectManage->manageProject->step3->desc = '请点击Scrum创建Scrum项目';

$lang->tutorial->scrumProjectManage->manageProject->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step4->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageProject->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step5->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageProject->step5->desc = '保存后会显示在项目列表中';

$lang->tutorial->scrumProjectManage->manageProject->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step6->name = '点击项目名称';
$lang->tutorial->scrumProjectManage->manageProject->step6->desc = '点击项目名称进入项目';

$lang->tutorial->scrumProjectManage->manageProject->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step7->name = '点击设置';
$lang->tutorial->scrumProjectManage->manageProject->step7->desc = '点击设置开始维护团队';

$lang->tutorial->scrumProjectManage->manageProject->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step8->name = '点击团队';
$lang->tutorial->scrumProjectManage->manageProject->step8->desc = '点击团队可以查看该项目中的团队成员';

$lang->tutorial->scrumProjectManage->manageProject->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step9->name = '点击团队管理';
$lang->tutorial->scrumProjectManage->manageProject->step9->desc = '点击团队管理可以对当前项目的团队成员进行维护';

$lang->tutorial->scrumProjectManage->manageProject->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step10->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageProject->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageProject->step11->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageProject->step11->desc = '保存后可以在团队中查看团队成员';

$lang->tutorial->scrumProjectManage->manageExecution = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->title = '迭代管理';

$lang->tutorial->scrumProjectManage->manageExecution->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step1->name = '点击迭代';
$lang->tutorial->scrumProjectManage->manageExecution->step1->desc = '点击迭代开始添加新迭代';

$lang->tutorial->scrumProjectManage->manageExecution->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step2->name = '点击添加迭代';
$lang->tutorial->scrumProjectManage->manageExecution->step2->desc = '您可以在这里添加迭代';

$lang->tutorial->scrumProjectManage->manageExecution->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step3->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageExecution->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step4->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageExecution->step4->desc = '保存后可以选择设置团队、关联需求、创建任务、返回任务列表和返回执行列表';

$lang->tutorial->scrumProjectManage->manageExecution->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step5->name = '点击迭代';
$lang->tutorial->scrumProjectManage->manageExecution->step5->desc = '点击迭代名称进入迭代';

$lang->tutorial->scrumProjectManage->manageExecution->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step6->name = '点击需求';
$lang->tutorial->scrumProjectManage->manageExecution->step6->desc = '可以在这里完成需求的维护';

$lang->tutorial->scrumProjectManage->manageExecution->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step7->name = '点击关联需求';
$lang->tutorial->scrumProjectManage->manageExecution->step7->desc = '可以将需求关联进迭代中';

$lang->tutorial->scrumProjectManage->manageExecution->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step8->name = '选择需求';

$lang->tutorial->scrumProjectManage->manageExecution->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step9->name = '点击保存';
$lang->tutorial->scrumProjectManage->manageExecution->step9->desc = '点击保存可以将需求关联到需求列表中，返回到需求列表';

$lang->tutorial->scrumProjectManage->manageExecution->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step10->name = '点击燃尽图';
$lang->tutorial->scrumProjectManage->manageExecution->step10->desc = '点击燃尽图可以查看迭代燃尽图';

$lang->tutorial->scrumProjectManage->manageTask = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->title = '任务管理';

$lang->tutorial->scrumProjectManage->manageTask->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step1->name = '点击需求';
$lang->tutorial->scrumProjectManage->manageTask->step1->desc = '进入需求列表，您可以在这里看到之前关联的需求';

$lang->tutorial->scrumProjectManage->manageTask->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step3->name = '分解任务';
$lang->tutorial->scrumProjectManage->manageTask->step3->desc = '您可以在这里将需求分解为任务，支持批量分解';

$lang->tutorial->scrumProjectManage->manageTask->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step4->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageTask->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step5->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageTask->step5->desc = '保存后可以在任务列表中查看分解的任务';

$lang->tutorial->scrumProjectManage->manageTask->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step6->name = '点击指派给';
$lang->tutorial->scrumProjectManage->manageTask->step6->desc = '您可以在这里将任务指派给对应的用户';

$lang->tutorial->scrumProjectManage->manageTask->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step7->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageTask->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step8->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageTask->step8->desc = '保存后在任务列表中指派给字段会显示被指派的用户';

$lang->tutorial->scrumProjectManage->manageTask->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step9->name = '点击开始任务';
$lang->tutorial->scrumProjectManage->manageTask->step9->desc = '您可以在这里开始任务，并记录消耗和剩余工时';

$lang->tutorial->scrumProjectManage->manageTask->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step10->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageTask->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step11->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageTask->step11->desc = '保存后返回任务列表';

$lang->tutorial->scrumProjectManage->manageTask->step12 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step12->name = '点击记录工时';
$lang->tutorial->scrumProjectManage->manageTask->step12->desc = '您可以在这里记录消耗和剩余工时，当剩余工时为0后，任务会自动完成';

$lang->tutorial->scrumProjectManage->manageTask->step13 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step13->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageTask->step14 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step14->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageTask->step14->desc = '保存后返回任务列表';

$lang->tutorial->scrumProjectManage->manageTask->step15 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step15->name = '点击完成任务';
$lang->tutorial->scrumProjectManage->manageTask->step15->desc = '您可以在这里完成任务';

$lang->tutorial->scrumProjectManage->manageTask->step16 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step16->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageTask->step17 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step17->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageTask->step17->desc = '保存后返回任务列表';

$lang->tutorial->scrumProjectManage->manageTask->step18 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step18->name = '点击构建';
$lang->tutorial->scrumProjectManage->manageTask->step18->desc = '进入构建模块中可以创建构建';

$lang->tutorial->scrumProjectManage->manageTask->step19 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step19->name = '点击创建构建';
$lang->tutorial->scrumProjectManage->manageTask->step19->desc = '可以在这里创建新的构建';

$lang->tutorial->scrumProjectManage->manageTask->step20 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step20->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageTask->step21 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step21->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageTask->step21->desc = '保存后进入构建详情';

$lang->tutorial->scrumProjectManage->manageTask->step22 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step22->name = '关联需求';
$lang->tutorial->scrumProjectManage->manageTask->step22->desc = '可以将完成的研发需求关联在构建中';

$lang->tutorial->scrumProjectManage->manageTask->step23 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step23->name = '选择需求';
$lang->tutorial->scrumProjectManage->manageTask->step23->desc = '在这里可以选择勾选需要关联的需求';

$lang->tutorial->scrumProjectManage->manageTask->step24 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step24->name = '保存关联的需求';
$lang->tutorial->scrumProjectManage->manageTask->step24->desc = '您可以将完成的需求关联在当前构建中';

$lang->tutorial->scrumProjectManage->manageTest = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->title = '测试管理';

$lang->tutorial->scrumProjectManage->manageTest->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step1->name = '点击测试';
$lang->tutorial->scrumProjectManage->manageTest->step1->desc = '可以在这里进行测试管理';

$lang->tutorial->scrumProjectManage->manageTest->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step2->name = '点击用例';
$lang->tutorial->scrumProjectManage->manageTest->step2->desc = '在这里可以查看用例';

$lang->tutorial->scrumProjectManage->manageTest->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step3->name = '点击创建用例';
$lang->tutorial->scrumProjectManage->manageTest->step3->desc = '在这里可以创建用例';

$lang->tutorial->scrumProjectManage->manageTest->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step4->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageTest->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step5->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageTest->step5->desc = '保存后进入用例列表';

$lang->tutorial->scrumProjectManage->manageTest->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step6->name = '点击执行';
$lang->tutorial->scrumProjectManage->manageTest->step6->desc = '点击执行可以执行用例';

$lang->tutorial->scrumProjectManage->manageTest->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step7->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageTest->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step8->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageTest->step8->desc = '保存后返回用例列表';

$lang->tutorial->scrumProjectManage->manageTest->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step9->name = '点击结果';
$lang->tutorial->scrumProjectManage->manageTest->step9->desc = '点击这里可以查看用例执行结果';

$lang->tutorial->scrumProjectManage->manageTest->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step10->name = '选择步骤';

$lang->tutorial->scrumProjectManage->manageTest->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step11->name = '点击转Bug';
$lang->tutorial->scrumProjectManage->manageTest->step11->desc = '可以将未通过的执行结果转Bug处理';

$lang->tutorial->scrumProjectManage->manageTest->step12 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step12->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageTest->step13 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step13->name = '保存表单';

$lang->tutorial->scrumProjectManage->manageTest->step14 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step14->name = '点击测试单';
$lang->tutorial->scrumProjectManage->manageTest->step14->desc = '点击维护测试单';

$lang->tutorial->scrumProjectManage->manageTest->step15 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step15->name = '点击提交测试';
$lang->tutorial->scrumProjectManage->manageTest->step15->desc = '可以在这里创建测试单';

$lang->tutorial->scrumProjectManage->manageTest->step16 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step16->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageTest->step17 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step17->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageTest->step17->desc = '保存后回到测试单列表中';

$lang->tutorial->scrumProjectManage->manageTest->step18 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step18->name = '点击测试单名称';
$lang->tutorial->scrumProjectManage->manageTest->step18->desc = '可以在这里查看测试单详情列表';

$lang->tutorial->scrumProjectManage->manageTest->step19 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step19->name = '点击关联用例';
$lang->tutorial->scrumProjectManage->manageTest->step19->desc = '可以在这里关联用例';

$lang->tutorial->scrumProjectManage->manageTest->step20 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step20->name = '选择要关联的用例';

$lang->tutorial->scrumProjectManage->manageTest->step21 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step21->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageTest->step21->desc = '您可以将用例关联在测试单中，在这里可以查看到可关联的用例';

$lang->tutorial->scrumProjectManage->manageTest->step22 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step22->name = '点击测试单';
$lang->tutorial->scrumProjectManage->manageTest->step22->desc = '点击这里返回测试单列表';

$lang->tutorial->scrumProjectManage->manageTest->step23 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step23->name = '选择测试单';

$lang->tutorial->scrumProjectManage->manageTest->step24 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step24->name = '点击测试报告';
$lang->tutorial->scrumProjectManage->manageTest->step24->desc = '可以在这里生成测试报告';

$lang->tutorial->scrumProjectManage->manageTest->step25 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step25->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageTest->step26 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step26->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageTest->step26->desc = '保存后可以生成测试报告';

$lang->tutorial->scrumProjectManage->manageTest->step27 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTest->step27->name = '点击测试报告';
$lang->tutorial->scrumProjectManage->manageTest->step27->desc = '可以在这里查看测试报告列表';

$lang->tutorial->scrumProjectManage->manageBug = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->title = 'Bug管理';

$lang->tutorial->scrumProjectManage->manageBug->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step1->name = '点击测试';
$lang->tutorial->scrumProjectManage->manageBug->step1->desc = '可以在这里进行Bug管理';

$lang->tutorial->scrumProjectManage->manageBug->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step2->name = '点击提Bug';
$lang->tutorial->scrumProjectManage->manageBug->step2->desc = '可以在这里创建Bug';

$lang->tutorial->scrumProjectManage->manageBug->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step3->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageBug->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step4->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageBug->step4->desc = '保存后进入Bug列表';

$lang->tutorial->scrumProjectManage->manageBug->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step5->name = '确认Bug';
$lang->tutorial->scrumProjectManage->manageBug->step5->desc = '可以在这里确认Bug';

$lang->tutorial->scrumProjectManage->manageBug->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step6->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageBug->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step7->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageBug->step7->desc = '保存后进入Bug列表';

$lang->tutorial->scrumProjectManage->manageBug->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step8->name = '解决Bug';
$lang->tutorial->scrumProjectManage->manageBug->step8->desc = '可以在这里解决Bug';

$lang->tutorial->scrumProjectManage->manageBug->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step9->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageBug->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step10->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageBug->step10->desc = '保存后可以将解决完的Bug进行验证';

$lang->tutorial->scrumProjectManage->manageBug->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step11->name = '关闭Bug';
$lang->tutorial->scrumProjectManage->manageBug->step11->desc = '可以在这里关闭Bug';

$lang->tutorial->scrumProjectManage->manageBug->step12 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step12->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageBug->step13 = new stdClass();
$lang->tutorial->scrumProjectManage->manageBug->step13->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageBug->step13->desc = '保存后可以将验证完的Bug关闭';

$lang->tutorial->scrumProjectManage->manageIssue = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->title = '问题管理';

$lang->tutorial->scrumProjectManage->manageIssue->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step1->name = '点击其他';

$lang->tutorial->scrumProjectManage->manageIssue->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step2->name = '点击问题';
$lang->tutorial->scrumProjectManage->manageIssue->step2->desc = '可以在这里进行问题管理';

$lang->tutorial->scrumProjectManage->manageIssue->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step3->name = '点击新建问题';
$lang->tutorial->scrumProjectManage->manageIssue->step3->desc = '在这里新建问题，支持批量创建';

$lang->tutorial->scrumProjectManage->manageIssue->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step4->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageIssue->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step5->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageIssue->step5->desc = '保存后进入问题列表';

$lang->tutorial->scrumProjectManage->manageIssue->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step6->name = '确认问题';
$lang->tutorial->scrumProjectManage->manageIssue->step6->desc = '可以在这里确认当前项目的问题';

$lang->tutorial->scrumProjectManage->manageIssue->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step7->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageIssue->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step8->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageIssue->step8->desc = '确认后回到问题列表';

$lang->tutorial->scrumProjectManage->manageIssue->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step9->name = '解决问题';
$lang->tutorial->scrumProjectManage->manageIssue->step9->desc = '可以在这里解决问题';

$lang->tutorial->scrumProjectManage->manageIssue->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step10->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageIssue->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step11->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageIssue->step11->desc = '保存后返回问题列表';

$lang->tutorial->scrumProjectManage->manageIssue->step12 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step12->name = '关闭问题';
$lang->tutorial->scrumProjectManage->manageIssue->step12->desc = '可以将已经处理的问题关闭';

$lang->tutorial->scrumProjectManage->manageIssue->step13 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step13->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageIssue->step14 = new stdClass();
$lang->tutorial->scrumProjectManage->manageIssue->step14->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageIssue->step14->desc = '可以在这里关闭问题';

$lang->tutorial->scrumProjectManage->manageRisk = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->title = '风险管理';

$lang->tutorial->scrumProjectManage->manageRisk->step1 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step1->name = '点击其他';

$lang->tutorial->scrumProjectManage->manageRisk->step2 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step2->name = '点击风险';
$lang->tutorial->scrumProjectManage->manageRisk->step2->desc = '可以在这里进行风险管理';

$lang->tutorial->scrumProjectManage->manageRisk->step3 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step3->name = '点击添加风险';
$lang->tutorial->scrumProjectManage->manageRisk->step3->desc = '在这里可以添加当前项目的风险，支持批量创建';

$lang->tutorial->scrumProjectManage->manageRisk->step4 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step4->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageRisk->step5 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step5->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageRisk->step5->desc = '可以在这里将风险添加到风险列表中';

$lang->tutorial->scrumProjectManage->manageRisk->step6 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step6->name = '跟踪风险';
$lang->tutorial->scrumProjectManage->manageRisk->step6->desc = '可以在这里跟踪风险';

$lang->tutorial->scrumProjectManage->manageRisk->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step7->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageRisk->step8 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step8->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageRisk->step8->desc = '保存后返回风险列表';

$lang->tutorial->scrumProjectManage->manageRisk->step9 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step9->name = '关闭风险';
$lang->tutorial->scrumProjectManage->manageRisk->step9->desc = '可以在这里将风险关闭';

$lang->tutorial->scrumProjectManage->manageRisk->step10 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step10->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageRisk->step11 = new stdClass();
$lang->tutorial->scrumProjectManage->manageRisk->step11->name = '保存表单';

$lang->tutorial->waterfallProjectManage = new stdClass();
$lang->tutorial->waterfallProjectManage->title = '瀑布项目管理教程';

$lang->tutorial->waterfallProjectManage->manageProject = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->title = '项目维护';

$lang->tutorial->waterfallProjectManage->manageProject->step1 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step1->name = '点击项目';
$lang->tutorial->waterfallProjectManage->manageProject->step1->desc = '您可以在这里创建项目';

$lang->tutorial->waterfallProjectManage->manageProject->step2 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step2->name = '点击创建项目';
$lang->tutorial->waterfallProjectManage->manageProject->step2->desc = '您可以选择不同项目管理方式来创建不同类型的项目';

$lang->tutorial->waterfallProjectManage->manageProject->step3 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step3->name = '点击瀑布项目';
$lang->tutorial->waterfallProjectManage->manageProject->step3->desc = '可以在这里创建瀑布项目';

$lang->tutorial->waterfallProjectManage->manageProject->step4 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step4->name = '填写表单';

$lang->tutorial->waterfallProjectManage->manageProject->step5 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step5->name = '保存表单';
$lang->tutorial->waterfallProjectManage->manageProject->step5->desc = '保存后会显示在项目列表中';

$lang->tutorial->waterfallProjectManage->manageProject->step6 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step6->name = '点击项目名称';
$lang->tutorial->waterfallProjectManage->manageProject->step6->desc = '点击项目名称进入瀑布项目';

$lang->tutorial->waterfallProjectManage->manageProject->step7 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step7->name = '点击设置';
$lang->tutorial->waterfallProjectManage->manageProject->step7->desc = '点击设置开始维护团队';

$lang->tutorial->waterfallProjectManage->manageProject->step8 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step8->name = '点击团队';
$lang->tutorial->waterfallProjectManage->manageProject->step8->desc = '点击团队可以查看该项目中的团队成员';

$lang->tutorial->waterfallProjectManage->manageProject->step9 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step9->name = '点击团队管理';
$lang->tutorial->waterfallProjectManage->manageProject->step9->desc = '点击团队管理可以对当前项目的团队成员进行维护';

$lang->tutorial->waterfallProjectManage->manageProject->step10 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step10->name = '填写表单';

$lang->tutorial->waterfallProjectManage->manageProject->step11 = new stdClass();
$lang->tutorial->waterfallProjectManage->manageProject->step11->name = '保存表单';
$lang->tutorial->waterfallProjectManage->manageProject->step11->desc = '保存后可以在团队中查看团队成员';

$lang->tutorial->waterfallProjectManage->setStage = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->title = '阶段设置';

$lang->tutorial->waterfallProjectManage->setStage->step1 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step1->name = '点击阶段';
$lang->tutorial->waterfallProjectManage->setStage->step1->desc = '可以在这里维护阶段';

$lang->tutorial->waterfallProjectManage->setStage->step2 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step2->name = '点击设置阶段';
$lang->tutorial->waterfallProjectManage->setStage->step2->desc = '点击设置阶段可以确定项目的各个阶段，将阶段设置为里程碑，可以查看相关里程碑报告。';

$lang->tutorial->waterfallProjectManage->setStage->step3 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step3->name = '填写表单';

$lang->tutorial->waterfallProjectManage->setStage->step4 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step4->name = '保存表单';
$lang->tutorial->waterfallProjectManage->setStage->step4->desc = '可以为每个阶段设置起止日期，保存在阶段列表中查看所有阶段';

$lang->tutorial->waterfallProjectManage->setStage->step5 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step5->name = '切换视图';
$lang->tutorial->waterfallProjectManage->setStage->step5->desc = '在这里可以切换为列表视图查看阶段';

$lang->tutorial->waterfallProjectManage->setStage->step6 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step6->name = '点击开发阶段';
$lang->tutorial->waterfallProjectManage->setStage->step6->desc = '可以在每个阶段中分配相应的资源和任务';

$lang->tutorial->waterfallProjectManage->setStage->step7 = new stdClass();
$lang->tutorial->waterfallProjectManage->setStage->step7->name = '点击燃尽图';
$lang->tutorial->waterfallProjectManage->setStage->step7->desc = '查看燃尽图可以跟进阶段';

$lang->tutorial->waterfallProjectManage->manageTask = new stdClass();
$lang->tutorial->waterfallProjectManage->manageTask = $lang->tutorial->scrumProjectManage->manageTask;

$lang->tutorial->waterfallProjectManage->manageTest = new stdClass();
$lang->tutorial->waterfallProjectManage->manageTest = $lang->tutorial->scrumProjectManage->manageTest;

$lang->tutorial->waterfallProjectManage->manageBug = new stdClass();
$lang->tutorial->waterfallProjectManage->manageBug = $lang->tutorial->scrumProjectManage->manageBug;

$lang->tutorial->waterfallProjectManage->design = new stdClass();
$lang->tutorial->waterfallProjectManage->design->title = '设计管理';

$lang->tutorial->waterfallProjectManage->design->step1 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step1->name = '点击设计';
$lang->tutorial->waterfallProjectManage->design->step1->desc = '可以在这里进行设计管理';

$lang->tutorial->waterfallProjectManage->design->step2 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step2->name = '点击创建设计';
$lang->tutorial->waterfallProjectManage->design->step2->desc = '您可以在这里创建设计';

$lang->tutorial->waterfallProjectManage->design->step3 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step3->name = '填写表单';

$lang->tutorial->waterfallProjectManage->design->step4 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step4->name = '保存表单';
$lang->tutorial->waterfallProjectManage->design->step4->desc = '保存后进入设计列表中查看全部设计';

$lang->tutorial->waterfallProjectManage->design->step5 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step5->name = '点击设计名称';
$lang->tutorial->waterfallProjectManage->design->step5->desc = '可以在这里进入设计详情';

$lang->tutorial->waterfallProjectManage->design->step6 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step6->name = '点击关联提交';
$lang->tutorial->waterfallProjectManage->design->step6->desc = '您可以在这里关联提交';

$lang->tutorial->waterfallProjectManage->design->step7 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step7->name = '选择提交';

$lang->tutorial->waterfallProjectManage->design->step8 = new stdClass();
$lang->tutorial->waterfallProjectManage->design->step8->name = '保存表单';
$lang->tutorial->waterfallProjectManage->design->step8->desc = '保存后可以在设计详情查看已经关联的提交';

$lang->tutorial->waterfallProjectManage->review = new stdClass();
$lang->tutorial->waterfallProjectManage->review->title = '评审和配置管理';

$lang->tutorial->waterfallProjectManage->review->step1 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step1->name = '点击评审';
$lang->tutorial->waterfallProjectManage->review->step1->desc = '可以在这里进行评审管理';

$lang->tutorial->waterfallProjectManage->review->step2 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step2->name = '点击基线评审列表';
$lang->tutorial->waterfallProjectManage->review->step2->desc = '可以在这里查看所有评审项';

$lang->tutorial->waterfallProjectManage->review->step3 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step3->name = '点击发起评审';
$lang->tutorial->waterfallProjectManage->review->step3->desc = '可以在这里发起评审';

$lang->tutorial->waterfallProjectManage->review->step4 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step4->name = '填写表单';

$lang->tutorial->waterfallProjectManage->review->step5 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step5->name = '保存表单';
$lang->tutorial->waterfallProjectManage->review->step5->desc = '保存后可以在基线评审列表中查看，在后台可配置创建模板，在相关模板字段下引用';

$lang->tutorial->waterfallProjectManage->review->step6 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step6->name = '点击提交审计';
$lang->tutorial->waterfallProjectManage->review->step6->desc = '可以在这里提交审计，评审未通过的可以在问题列表中查看问题和添加问题';

$lang->tutorial->waterfallProjectManage->review->step7 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step7->name = '填写表单';

$lang->tutorial->waterfallProjectManage->review->step8 = new stdClass();
$lang->tutorial->waterfallProjectManage->review->step8->name = '保存表单';
$lang->tutorial->waterfallProjectManage->review->step8->desc = '保存后回到基线评审列表';

$lang->tutorial->waterfallProjectManage->manageIssue = new stdClass();
$lang->tutorial->waterfallProjectManage->manageIssue = $lang->tutorial->scrumProjectManage->manageIssue;

$lang->tutorial->waterfallProjectManage->manageRisk = new stdClass();
$lang->tutorial->waterfallProjectManage->manageRisk = $lang->tutorial->scrumProjectManage->manageRisk;

$lang->tutorial->kanbanProjectManage = new stdClass();
$lang->tutorial->kanbanProjectManage->title = '看板项目管理教程';

$lang->tutorial->kanbanProjectManage->manageProject = new stdClass();
$lang->tutorial->kanbanProjectManage->manageProject = clone $lang->tutorial->scrumProjectManage->manageProject;

$lang->tutorial->kanbanProjectManage->manageProject->step3 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageProject->step3->name = '点击项目看板';
$lang->tutorial->kanbanProjectManage->manageProject->step3->desc = '可以在这里创建看板项目';

$lang->tutorial->kanbanProjectManage->manageKanban = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->title = '看板管理';

$lang->tutorial->kanbanProjectManage->manageKanban->step1 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step1->name = '点击添加看板';
$lang->tutorial->kanbanProjectManage->manageKanban->step1->desc = '您可以在这里添加看板';

$lang->tutorial->kanbanProjectManage->manageKanban->step2 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step2->name = '填写表单';

$lang->tutorial->kanbanProjectManage->manageKanban->step3 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step3->name = '保存表单';
$lang->tutorial->kanbanProjectManage->manageKanban->step3->desc = '可以在这里完成看板的创建';

$lang->tutorial->kanbanProjectManage->manageKanban->step4 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step4->name = '点击更多';

$lang->tutorial->kanbanProjectManage->manageKanban->step5 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step5->name = '点击新增区域';
$lang->tutorial->kanbanProjectManage->manageKanban->step5->desc = '您可以在这里添加新的区域';

$lang->tutorial->kanbanProjectManage->manageKanban->step6 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step6->name = '填写表单';

$lang->tutorial->kanbanProjectManage->manageKanban->step7 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step7->name = '保存表单';
$lang->tutorial->kanbanProjectManage->manageKanban->step7->desc = '可以新增区域到看板项目中';

$lang->tutorial->kanbanProjectManage->manageKanban->step8 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step8->name = '点击新建';
$lang->tutorial->kanbanProjectManage->manageKanban->step8->desc = '可以选择关联/新建需求';

$lang->tutorial->kanbanProjectManage->manageKanban->step9 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step9->name = '点击关联需求';
$lang->tutorial->kanbanProjectManage->manageKanban->step9->desc = '您可以在需求泳道中关联/创建需求';

$lang->tutorial->kanbanProjectManage->manageKanban->step10 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step10->name = '填写表单';

$lang->tutorial->kanbanProjectManage->manageKanban->step11 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step11->name = '保存表单';
$lang->tutorial->kanbanProjectManage->manageKanban->step11->desc = '可以将需求关联到需求泳道中';

$lang->tutorial->kanbanProjectManage->manageKanban->step12 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step12->name = '点击更多';

$lang->tutorial->kanbanProjectManage->manageKanban->step13 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step13->name = '点击分解任务';
$lang->tutorial->kanbanProjectManage->manageKanban->step13->desc = '可以将需求分解为任务';

$lang->tutorial->kanbanProjectManage->manageKanban->step14 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step14->name = '填写表单';

$lang->tutorial->kanbanProjectManage->manageKanban->step15 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step15->name = '保存表单';
$lang->tutorial->kanbanProjectManage->manageKanban->step15->desc = '可以将任务添加到任务泳道中';

$lang->tutorial->kanbanProjectManage->manageKanban->step16 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step16->name = '点击新建';

$lang->tutorial->kanbanProjectManage->manageKanban->step17 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step17->name = '点击新建Bug';
$lang->tutorial->kanbanProjectManage->manageKanban->step17->desc = '可以在这里提Bug';

$lang->tutorial->kanbanProjectManage->manageKanban->step18 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step18->name = '填写表单';

$lang->tutorial->kanbanProjectManage->manageKanban->step19 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step19->name = '保存表单';
$lang->tutorial->kanbanProjectManage->manageKanban->step19->desc = '可以将Bug添加到Bug泳道中';

$lang->tutorial->kanbanProjectManage->manageKanban->step20 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step20->name = '点击更多';

$lang->tutorial->kanbanProjectManage->manageKanban->step21 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step21->name = '点击在制品设置';
$lang->tutorial->kanbanProjectManage->manageKanban->step21->desc = '可以灵活设置在制品数量';

$lang->tutorial->kanbanProjectManage->manageKanban->step22 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step22->name = '填写表单';

$lang->tutorial->kanbanProjectManage->manageKanban->step23 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageKanban->step23->name = '保存表单';

$lang->tutorial->kanbanProjectManage->manageBuild = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->title = '构建管理';

$lang->tutorial->kanbanProjectManage->manageBuild->step1 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->step1->name = '点击构建';
$lang->tutorial->kanbanProjectManage->manageBuild->step1->desc = '可以在这里进行构建管理';

$lang->tutorial->kanbanProjectManage->manageBuild->step2 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->step2->name = '点击创建构建';
$lang->tutorial->kanbanProjectManage->manageBuild->step2->desc = '可以在这里创建新的构建';

$lang->tutorial->kanbanProjectManage->manageBuild->step3 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->step3->name = '填写表单';

$lang->tutorial->kanbanProjectManage->manageBuild->step4 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->step4->name = '保存表单';
$lang->tutorial->kanbanProjectManage->manageBuild->step4->desc = '保存后在构建列表中显示';

$lang->tutorial->kanbanProjectManage->manageBuild->step5 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageBuild->step5->name = '点击累积流图';
$lang->tutorial->kanbanProjectManage->manageBuild->step5->desc = '可以在这里查看累积流图进行看板跟踪';

$lang->tutorial->taskManage = new stdClass();
$lang->tutorial->taskManage->title = '任务管理教程';

$lang->tutorial->taskManage->step1 = new stdClass();
$lang->tutorial->taskManage->step1->name = '点击项目';
$lang->tutorial->taskManage->step1->desc = '点击进入项目，对项目及其任务进行管理';

$lang->tutorial->taskManage->step2 = new stdClass();
$lang->tutorial->taskManage->step2->name = '点击创建项目';
$lang->tutorial->taskManage->step2->desc = '点击创建一个无迭代的项目管理任务';

$lang->tutorial->taskManage->step3 = new stdClass();
$lang->tutorial->taskManage->step3->name = '点击Scrum项目';
$lang->tutorial->taskManage->step3->desc = '点击创建一个无迭代的项目';

$lang->tutorial->taskManage->step4 = new stdClass();
$lang->tutorial->taskManage->step4->name = '填写表单';
$lang->tutorial->taskManage->step4->desc = '“项目类型”选择项目型，“启用迭代”取消勾选，以此来创建无迭代项目';

$lang->tutorial->taskManage->step5 = new stdClass();
$lang->tutorial->taskManage->step5->name = '保存表单';
$lang->tutorial->taskManage->step5->desc = '保存后在项目列表查看';

$lang->tutorial->taskManage->step6 = new stdClass();
$lang->tutorial->taskManage->step6->name = '点击项目名称';
$lang->tutorial->taskManage->step6->desc = '点击项目名称，进入项目';

$lang->tutorial->taskManage->step7 = new stdClass();
$lang->tutorial->taskManage->step7->name = '点击新建任务';
$lang->tutorial->taskManage->step7->desc = '点击创建项目的任务';

$lang->tutorial->taskManage->step8 = new stdClass();
$lang->tutorial->taskManage->step8->name = '填写表单';

$lang->tutorial->taskManage->step9 = new stdClass();
$lang->tutorial->taskManage->step9->name = '保存表单';
$lang->tutorial->taskManage->step9->desc = '保存后可以在任务列表中查看任务';

$lang->tutorial->taskManage->step10 = new stdClass();
$lang->tutorial->taskManage->step10->name = '点击指派给';
$lang->tutorial->taskManage->step10->desc = '点击领取、分配任务到人';

$lang->tutorial->taskManage->step11 = new stdClass();
$lang->tutorial->taskManage->step11->name = '填写表单';

$lang->tutorial->taskManage->step12 = new stdClass();
$lang->tutorial->taskManage->step12->name = '保存表单';
$lang->tutorial->taskManage->step12->desc = '保存后在任务列表中指派给字段会显示被指派的用户';

$lang->tutorial->taskManage->step13 = new stdClass();
$lang->tutorial->taskManage->step13->name = '点击开始任务';
$lang->tutorial->taskManage->step13->desc = '您可以在这里开始任务，并记录消耗和剩余工时';

$lang->tutorial->taskManage->step14 = new stdClass();
$lang->tutorial->taskManage->step14->name = '填写表单';

$lang->tutorial->taskManage->step15 = new stdClass();
$lang->tutorial->taskManage->step15->name = '保存表单';
$lang->tutorial->taskManage->step15->desc = '保存后任务状态变为进行中';

$lang->tutorial->taskManage->step16 = new stdClass();
$lang->tutorial->taskManage->step16->name = '点击记录工时';
$lang->tutorial->taskManage->step16->desc = '您可以在这里记录消耗和剩余工时，当剩余工时为0后，任务会自动完成';

$lang->tutorial->taskManage->step17 = new stdClass();
$lang->tutorial->taskManage->step17->name = '填写表单';

$lang->tutorial->taskManage->step18 = new stdClass();
$lang->tutorial->taskManage->step18->name = '保存表单';
$lang->tutorial->taskManage->step18->desc = '保存后返回任务列表';

$lang->tutorial->taskManage->step19 = new stdClass();
$lang->tutorial->taskManage->step19->name = '点击完成任务';
$lang->tutorial->taskManage->step19->desc = '您可以在这里完成任务';

$lang->tutorial->taskManage->step20 = new stdClass();
$lang->tutorial->taskManage->step20->name = '填写表单';

$lang->tutorial->taskManage->step21 = new stdClass();
$lang->tutorial->taskManage->step21->name = '保存表单';
$lang->tutorial->taskManage->step21->desc = '保存后任务状态变为已完成';

$lang->tutorial->taskManage->step22 = new stdClass();
$lang->tutorial->taskManage->step22->name = '点击关闭任务';
$lang->tutorial->taskManage->step22->desc = '确认任务完成后点击关闭任务';

$lang->tutorial->taskManage->step23 = new stdClass();
$lang->tutorial->taskManage->step23->name = '填写表单';

$lang->tutorial->taskManage->step24 = new stdClass();
$lang->tutorial->taskManage->step24->name = '保存表单';
$lang->tutorial->taskManage->step24->desc = '保存后任务状态变为已关闭';

$lang->tutorial->testManage = new stdClass();
$lang->tutorial->testManage->title = '测试管理教程';

$lang->tutorial->testManage->step1 = new stdClass();
$lang->tutorial->testManage->step1->name = '点击测试';
$lang->tutorial->testManage->step1->desc = '点击测试进行测试管理';

$lang->tutorial->testManage->step2 = new stdClass();
$lang->tutorial->testManage->step2->name = '点击用例';
$lang->tutorial->testManage->step2->desc = '点击用例进行用例管理';

$lang->tutorial->testManage->step3 = new stdClass();
$lang->tutorial->testManage->step3->name = '点击创建用例';
$lang->tutorial->testManage->step3->desc = '可以在这里创建用例';

$lang->tutorial->testManage->step4 = new stdClass();
$lang->tutorial->testManage->step4->name = '填写表单';

$lang->tutorial->testManage->step5 = new stdClass();
$lang->tutorial->testManage->step5->name = '保存表单';
$lang->tutorial->testManage->step5->desc = '保存后在用例列表中查看';

$lang->tutorial->testManage->step6 = new stdClass();
$lang->tutorial->testManage->step6->name = '点击测试单';
$lang->tutorial->testManage->step6->desc = '点击维护测试单信息';

$lang->tutorial->testManage->step7 = new stdClass();
$lang->tutorial->testManage->step7->name = '点击提交测试';
$lang->tutorial->testManage->step7->desc = '点击提交测试单会生成测试单';

$lang->tutorial->testManage->step8 = new stdClass();
$lang->tutorial->testManage->step8->name = '填写表单';

$lang->tutorial->testManage->step9 = new stdClass();
$lang->tutorial->testManage->step9->name = '保存表单';
$lang->tutorial->testManage->step9->desc = '保存后在测试单列表查看';

$lang->tutorial->testManage->step10 = new stdClass();
$lang->tutorial->testManage->step10->name = '点击测试单名称';
$lang->tutorial->testManage->step10->desc = '点击查看测试单详情';

$lang->tutorial->testManage->step11 = new stdClass();
$lang->tutorial->testManage->step11->name = '点击关联用例';
$lang->tutorial->testManage->step11->desc = '点击将用例关联进测试单';

$lang->tutorial->testManage->step12 = new stdClass();
$lang->tutorial->testManage->step12->name = '勾选用例';
$lang->tutorial->testManage->step12->desc = '您可以将用例关联在测试单中';

$lang->tutorial->testManage->step13 = new stdClass();
$lang->tutorial->testManage->step13->name = '点击保存';
$lang->tutorial->testManage->step13->desc = '保存后用例成功关联进测试单';

$lang->tutorial->testManage->step14 = new stdClass();
$lang->tutorial->testManage->step14->name = '点击执行';
$lang->tutorial->testManage->step14->desc = '点击执行用例';

$lang->tutorial->testManage->step15 = new stdClass();
$lang->tutorial->testManage->step15->name = '填写表单';

$lang->tutorial->testManage->step16 = new stdClass();
$lang->tutorial->testManage->step16->name = '保存表单';
$lang->tutorial->testManage->step16->desc = '保存后可以完成用例的执行';

$lang->tutorial->testManage->step17 = new stdClass();
$lang->tutorial->testManage->step17->name = '点击执行结果';
$lang->tutorial->testManage->step17->desc = '可以在这里执行用例';

$lang->tutorial->testManage->step18 = new stdClass();
$lang->tutorial->testManage->step18->name = '选择用例步骤';

$lang->tutorial->testManage->step19 = new stdClass();
$lang->tutorial->testManage->step19->name = '点击转Bug';
$lang->tutorial->testManage->step19->desc = '可以将执行失败的用例步骤转为Bug';

$lang->tutorial->testManage->step20 = new stdClass();
$lang->tutorial->testManage->step20->name = '填写表单';

$lang->tutorial->testManage->step21 = new stdClass();
$lang->tutorial->testManage->step21->name = '保存表单';

$lang->tutorial->testManage->step22 = new stdClass();
$lang->tutorial->testManage->step22->name = '点击测试单';

$lang->tutorial->testManage->step23 = new stdClass();
$lang->tutorial->testManage->step23->name = '生成测试报告';
$lang->tutorial->testManage->step23->desc = '可以在这里生成测试报告';

$lang->tutorial->testManage->step24 = new stdClass();
$lang->tutorial->testManage->step24->name = '填写表单';

$lang->tutorial->testManage->step25 = new stdClass();
$lang->tutorial->testManage->step25->name = '保存表单';
$lang->tutorial->testManage->step25->desc = '保存后可以生成测试报告';

$lang->tutorial->accountManage = new stdClass();
$lang->tutorial->accountManage->title = '账号管理教程';

$lang->tutorial->accountManage->deptManage = new stdClass();
$lang->tutorial->accountManage->deptManage->title = '维护部门';

$lang->tutorial->accountManage->deptManage->step1 = new stdClass();
$lang->tutorial->accountManage->deptManage->step1->name = '点击后台';
$lang->tutorial->accountManage->deptManage->step1->desc = '您可以在这里维护管理账号，进行各类配置项的设置。';

$lang->tutorial->accountManage->deptManage->step2 = new stdClass();
$lang->tutorial->accountManage->deptManage->step2->name = '点击人员管理';
$lang->tutorial->accountManage->deptManage->step2->desc = '您可以在这里维护部门、添加人员和分组配置权限';

$lang->tutorial->accountManage->deptManage->step3 = new stdClass();
$lang->tutorial->accountManage->deptManage->step3->name = '点击部门';
$lang->tutorial->accountManage->deptManage->step3->desc = '您可以点击这里进行部门维护';

$lang->tutorial->accountManage->deptManage->step4 = new stdClass();
$lang->tutorial->accountManage->deptManage->step4->name = '填写表单';

$lang->tutorial->accountManage->deptManage->step5 = new stdClass();
$lang->tutorial->accountManage->deptManage->step5->name = '保存表单';
$lang->tutorial->accountManage->deptManage->step5->desc = '保存后可以在左侧目录中看到';

$lang->tutorial->accountManage->addUser = new stdClass();
$lang->tutorial->accountManage->addUser->title = '添加人员';

$lang->tutorial->accountManage->addUser->step1 = new stdClass();
$lang->tutorial->accountManage->addUser->step1->name = '点击用户';
$lang->tutorial->accountManage->addUser->step1->desc = '您可以在这里维护公司人员';

$lang->tutorial->accountManage->addUser->step2 = new stdClass();
$lang->tutorial->accountManage->addUser->step2->name = '点击添加人员按钮';
$lang->tutorial->accountManage->addUser->step2->desc = '点击添加公司人员';

$lang->tutorial->accountManage->addUser->step3 = new stdClass();
$lang->tutorial->accountManage->addUser->step3->name = '填写表单';

$lang->tutorial->accountManage->addUser->step4 = new stdClass();
$lang->tutorial->accountManage->addUser->step4->name = '保存表单';
$lang->tutorial->accountManage->addUser->step4->desc = '保存后可以在人员列表中查看';

$lang->tutorial->accountManage->privManage = new stdClass();
$lang->tutorial->accountManage->privManage->title = '维护权限';

$lang->tutorial->accountManage->privManage->step1 = new stdClass();
$lang->tutorial->accountManage->privManage->step1->name = '点击权限';
$lang->tutorial->accountManage->privManage->step1->desc = '您可以在这里查看人员分组、维护人员权限。';

$lang->tutorial->accountManage->privManage->step2 = new stdClass();
$lang->tutorial->accountManage->privManage->step2->name = '点击新增分组';
$lang->tutorial->accountManage->privManage->step2->desc = '点击增加人员分组';

$lang->tutorial->accountManage->privManage->step3 = new stdClass();
$lang->tutorial->accountManage->privManage->step3->name = '填写表单';

$lang->tutorial->accountManage->privManage->step4 = new stdClass();
$lang->tutorial->accountManage->privManage->step4->name = '保存表单';
$lang->tutorial->accountManage->privManage->step4->desc = '保存后可以在人员列表中查看';

$lang->tutorial->accountManage->privManage->step5 = new stdClass();
$lang->tutorial->accountManage->privManage->step5->name = '点击成员维护';
$lang->tutorial->accountManage->privManage->step5->desc = '您可以为权限组添加公司人员以便后面分组授权。';

$lang->tutorial->accountManage->privManage->step6 = new stdClass();
$lang->tutorial->accountManage->privManage->step6->name = '填写表单';

$lang->tutorial->accountManage->privManage->step7 = new stdClass();
$lang->tutorial->accountManage->privManage->step7->name = '保存表单';
$lang->tutorial->accountManage->privManage->step7->desc = '保存后可以在人员列表中查看';

$lang->tutorial->accountManage->privManage->step8 = new stdClass();
$lang->tutorial->accountManage->privManage->step8->name = '点击分配权限';
$lang->tutorial->accountManage->privManage->step8->desc = '点击为用户组维护权限';

$lang->tutorial->accountManage->privManage->step9 = new stdClass();
$lang->tutorial->accountManage->privManage->step9->name = '点击权限包的展开按钮';
$lang->tutorial->accountManage->privManage->step9->desc = '点击查看权限包下的权限';

$lang->tutorial->accountManage->privManage->step10 = new stdClass();
$lang->tutorial->accountManage->privManage->step10->name = '保存表单';
$lang->tutorial->accountManage->privManage->step10->desc = '保存后该分组的人员拥有分配到的权限';

$lang->tutorial->productManage = new stdClass();
$lang->tutorial->productManage->title = '产品管理教程';

$lang->tutorial->productManage->addProduct = new stdClass();
$lang->tutorial->productManage->addProduct->title = '产品维护';

$lang->tutorial->productManage->addProduct->step1 = new stdClass();
$lang->tutorial->productManage->addProduct->step1->name = '点击添加产品';
$lang->tutorial->productManage->addProduct->step1->desc = '点击添加产品';

$lang->tutorial->productManage->addProduct->step2 = new stdClass();
$lang->tutorial->productManage->addProduct->step2->name = '填写表单';

$lang->tutorial->productManage->addProduct->step3 = new stdClass();
$lang->tutorial->productManage->addProduct->step3->name = '保存表单';
$lang->tutorial->productManage->addProduct->step3->desc = '保存后可以在产品列表中查看';

$lang->tutorial->productManage->moduleManage = new stdClass();
$lang->tutorial->productManage->moduleManage->title = '产品模块维护';

$lang->tutorial->productManage->moduleManage->step1 = new stdClass();
$lang->tutorial->productManage->moduleManage->step1->name = '点击产品名称';
$lang->tutorial->productManage->moduleManage->step1->desc = '点击进入产品，查看产品的详细信息.';

$lang->tutorial->productManage->moduleManage->step2 = new stdClass();
$lang->tutorial->productManage->moduleManage->step2->name = '点击模块设置';
$lang->tutorial->productManage->moduleManage->step2->desc = '点击去维护产品的模块';

$lang->tutorial->productManage->moduleManage->step3 = new stdClass();
$lang->tutorial->productManage->moduleManage->step3->name = '填写表单';

$lang->tutorial->productManage->moduleManage->step4 = new stdClass();
$lang->tutorial->productManage->moduleManage->step4->name = '保存表单';
$lang->tutorial->productManage->moduleManage->step4->desc = '保存后可以在创建需求时选择模块进行分类';

$lang->tutorial->productManage->storyManage = new stdClass();
$lang->tutorial->productManage->storyManage->title = '需求管理';

$lang->tutorial->productManage->storyManage->step1 = new stdClass();
$lang->tutorial->productManage->storyManage->step1->name = '点击业务需求';
$lang->tutorial->productManage->storyManage->step1->desc = '您可以在这里管理产品的业务需求';

$lang->tutorial->productManage->storyManage->step2 = new stdClass();
$lang->tutorial->productManage->storyManage->step2->name = '点击提业务需求';
$lang->tutorial->productManage->storyManage->step2->desc = '点击提业务需求';

$lang->tutorial->productManage->storyManage->step3 = new stdClass();
$lang->tutorial->productManage->storyManage->step3->name = '填写表单';

$lang->tutorial->productManage->storyManage->step4 = new stdClass();
$lang->tutorial->productManage->storyManage->step4->name = '保存表单';
$lang->tutorial->productManage->storyManage->step4->desc = '保存后在业务需求列表查看';

$lang->tutorial->productManage->storyManage->step5 = new stdClass();
$lang->tutorial->productManage->storyManage->step5->name = '点击拆分业务需求';
$lang->tutorial->productManage->storyManage->step5->desc = '点击将业务需拆分成用户需求';

$lang->tutorial->productManage->storyManage->step6 = new stdClass();
$lang->tutorial->productManage->storyManage->step6->name = '填写表单';

$lang->tutorial->productManage->storyManage->step7 = new stdClass();
$lang->tutorial->productManage->storyManage->step7->name = '保存表单';
$lang->tutorial->productManage->storyManage->step7->desc = '保存后可以在需求列表中查看';

$lang->tutorial->productManage->storyManage->step8 = new stdClass();
$lang->tutorial->productManage->storyManage->step8->name = '点拆分用户需求';
$lang->tutorial->productManage->storyManage->step8->desc = '点击将用户需求拆分成研发需求';

$lang->tutorial->productManage->storyManage->step9 = new stdClass();
$lang->tutorial->productManage->storyManage->step9->name = '填写表单';

$lang->tutorial->productManage->storyManage->step10 = new stdClass();
$lang->tutorial->productManage->storyManage->step10->name = '保存表单';
$lang->tutorial->productManage->storyManage->step10->desc = '保存后在需求列表中查看';

$lang->tutorial->productManage->storyManage->step11 = new stdClass();
$lang->tutorial->productManage->storyManage->step11->name = '点击评审按钮';
$lang->tutorial->productManage->storyManage->step11->desc = '点击对需求进行评审';

$lang->tutorial->productManage->storyManage->step12 = new stdClass();
$lang->tutorial->productManage->storyManage->step12->name = '填写表单';

$lang->tutorial->productManage->storyManage->step13 = new stdClass();
$lang->tutorial->productManage->storyManage->step13->name = '保存表单';
$lang->tutorial->productManage->storyManage->step13->desc = '保存后需求的状态根据评审结果变动';

$lang->tutorial->productManage->storyManage->step14 = new stdClass();
$lang->tutorial->productManage->storyManage->step14->name = '点击变更按钮';
$lang->tutorial->productManage->storyManage->step14->desc = '点击对需求进行变更';

$lang->tutorial->productManage->storyManage->step15 = new stdClass();
$lang->tutorial->productManage->storyManage->step15->name = '填写表单';

$lang->tutorial->productManage->storyManage->step16 = new stdClass();
$lang->tutorial->productManage->storyManage->step16->name = '保存表单';
$lang->tutorial->productManage->storyManage->step16->desc = '保存后，需求变更完成';

$lang->tutorial->productManage->storyManage->step17 = new stdClass();
$lang->tutorial->productManage->storyManage->step17->name = '点击矩阵';
$lang->tutorial->productManage->storyManage->step17->desc = '您可以在这里跟进需求的进展情况';

$lang->tutorial->productManage->planManage = new stdClass();
$lang->tutorial->productManage->planManage->title = '计划管理';

$lang->tutorial->productManage->planManage->step1 = new stdClass();
$lang->tutorial->productManage->planManage->step1->name = '点击计划';
$lang->tutorial->productManage->planManage->step1->desc = '您可以在这里维护管理产品计划';

$lang->tutorial->productManage->planManage->step2 = new stdClass();
$lang->tutorial->productManage->planManage->step2->name = '点击创建计划';
$lang->tutorial->productManage->planManage->step2->desc = '点击为产品创建计划';

$lang->tutorial->productManage->planManage->step3 = new stdClass();
$lang->tutorial->productManage->planManage->step3->name = '填写表单';

$lang->tutorial->productManage->planManage->step4 = new stdClass();
$lang->tutorial->productManage->planManage->step4->name = '保存表单';
$lang->tutorial->productManage->planManage->step4->desc = '保存后可以在计划列表中查看';

$lang->tutorial->productManage->planManage->step5 = new stdClass();
$lang->tutorial->productManage->planManage->step5->name = '点击计划名称';
$lang->tutorial->productManage->planManage->step5->desc = '点击进入计划的详情，管理计划详细信息';

$lang->tutorial->productManage->planManage->step6 = new stdClass();
$lang->tutorial->productManage->planManage->step6->name = '点击关联需求';
$lang->tutorial->productManage->planManage->step6->desc = '将该计划要完成的需求关联进计划中';

$lang->tutorial->productManage->planManage->step7 = new stdClass();
$lang->tutorial->productManage->planManage->step7->name = '勾选需求';

$lang->tutorial->productManage->planManage->step8 = new stdClass();
$lang->tutorial->productManage->planManage->step8->name = '点击保存';
$lang->tutorial->productManage->planManage->step8->desc = '保存后，需求成功关联进计划中';

$lang->tutorial->productManage->planManage->step9 = new stdClass();
$lang->tutorial->productManage->planManage->step9->name = '点击Bug';
$lang->tutorial->productManage->planManage->step9->desc = '将该计划要解决的Bug关联进计划中';

$lang->tutorial->productManage->planManage->step10 = new stdClass();
$lang->tutorial->productManage->planManage->step10->name = '点击关联Bug';
$lang->tutorial->productManage->planManage->step10->desc = '点击将该计划要解决的Bug关联进计划中';

$lang->tutorial->productManage->planManage->step11 = new stdClass();
$lang->tutorial->productManage->planManage->step11->name = '勾选Bug';

$lang->tutorial->productManage->planManage->step12 = new stdClass();
$lang->tutorial->productManage->planManage->step12->name = '点击保存';
$lang->tutorial->productManage->planManage->step12->desc = '保存后，Bug成功关联进计划中';

$lang->tutorial->productManage->releaseManage = new stdClass();
$lang->tutorial->productManage->releaseManage->title = '发布管理';

$lang->tutorial->productManage->releaseManage->step1 = new stdClass();
$lang->tutorial->productManage->releaseManage->step1->name = '点击发布';
$lang->tutorial->productManage->releaseManage->step1->desc = '您可以在这里维护管理产品的发布信息';

$lang->tutorial->productManage->releaseManage->step2 = new stdClass();
$lang->tutorial->productManage->releaseManage->step2->name = '点击创建发布';
$lang->tutorial->productManage->releaseManage->step2->desc = '点击为产品创建发布';

$lang->tutorial->productManage->releaseManage->step3 = new stdClass();
$lang->tutorial->productManage->releaseManage->step3->name = '填写表单';

$lang->tutorial->productManage->releaseManage->step4 = new stdClass();
$lang->tutorial->productManage->releaseManage->step4->name = '保存表单';
$lang->tutorial->productManage->releaseManage->step4->desc = '保存后，在发布列表中查看';

$lang->tutorial->productManage->releaseManage->step5 = new stdClass();
$lang->tutorial->productManage->releaseManage->step5->name = '点击发布名称';
$lang->tutorial->productManage->releaseManage->step5->desc = '点击进入发布，查看管理发布详细信息';

$lang->tutorial->productManage->releaseManage->step6 = new stdClass();
$lang->tutorial->productManage->releaseManage->step6->name = '点击关联需求';
$lang->tutorial->productManage->releaseManage->step6->desc = '点击将本次要发布的研发需求关联进发布';

$lang->tutorial->productManage->releaseManage->step7 = new stdClass();
$lang->tutorial->productManage->releaseManage->step7->name = '勾选需求';

$lang->tutorial->productManage->releaseManage->step8 = new stdClass();
$lang->tutorial->productManage->releaseManage->step8->name = '点击保存';
$lang->tutorial->productManage->releaseManage->step8->desc = '保存后需求成功关联进发布';

$lang->tutorial->productManage->releaseManage->step9 = new stdClass();
$lang->tutorial->productManage->releaseManage->step9->name = '点击解决的Bug';
$lang->tutorial->productManage->releaseManage->step9->desc = '点击查看管理本次发布解决的Bug';

$lang->tutorial->productManage->releaseManage->step10 = new stdClass();
$lang->tutorial->productManage->releaseManage->step10->name = '点击关联Bug';
$lang->tutorial->productManage->releaseManage->step10->desc = '点击将本次发布解决的Bug关联进发布';

$lang->tutorial->productManage->releaseManage->step11 = new stdClass();
$lang->tutorial->productManage->releaseManage->step11->name = '勾选Bug';

$lang->tutorial->productManage->releaseManage->step12 = new stdClass();
$lang->tutorial->productManage->releaseManage->step12->name = '点击保存';
$lang->tutorial->productManage->releaseManage->step12->desc = '保存后，Bug成功关联进发布中';

$lang->tutorial->productManage->releaseManage->step13 = new stdClass();
$lang->tutorial->productManage->releaseManage->step13->name = '点击遗留的Bug';
$lang->tutorial->productManage->releaseManage->step13->desc = '点击查看管理本次发布解决的Bug';

$lang->tutorial->productManage->releaseManage->step14 = new stdClass();
$lang->tutorial->productManage->releaseManage->step14->name = '点击关联Bug';
$lang->tutorial->productManage->releaseManage->step14->desc = '点击将本次发布遗留未解决的Bug关联进发布';

$lang->tutorial->productManage->releaseManage->step15 = new stdClass();
$lang->tutorial->productManage->releaseManage->step15->name = '勾选Bug';

$lang->tutorial->productManage->releaseManage->step16 = new stdClass();
$lang->tutorial->productManage->releaseManage->step16->name = '点击保存';
$lang->tutorial->productManage->releaseManage->step16->desc = '保存后，Bug成功关联进发布中';

$lang->tutorial->productManage->releaseManage->step17 = new stdClass();
$lang->tutorial->productManage->releaseManage->step17->name = '点击发布按钮';
$lang->tutorial->productManage->releaseManage->step17->desc = '点击进行发布';

$lang->tutorial->productManage->releaseManage->step18 = new stdClass();
$lang->tutorial->productManage->releaseManage->step18->name = '填写表单';

$lang->tutorial->productManage->releaseManage->step19 = new stdClass();
$lang->tutorial->productManage->releaseManage->step19->name = '保存表单';
$lang->tutorial->productManage->releaseManage->step19->desc = '保存后，需求会根据发布状态改变阶段';

$lang->tutorial->productManage->releaseManage->step20 = new stdClass();
$lang->tutorial->productManage->releaseManage->step20->name = '点击管理应用';
$lang->tutorial->productManage->releaseManage->step20->desc = '您可以在这里维护管理产品的应用信息';

$lang->tutorial->productManage->releaseManage->step21 = new stdClass();
$lang->tutorial->productManage->releaseManage->step21->name = '点击创建应用';
$lang->tutorial->productManage->releaseManage->step21->desc = '点击为产品创建应用';

$lang->tutorial->productManage->releaseManage->step22 = new stdClass();
$lang->tutorial->productManage->releaseManage->step22->name = '填写表单';

$lang->tutorial->productManage->releaseManage->step23 = new stdClass();
$lang->tutorial->productManage->releaseManage->step23->name = '保存表单';
$lang->tutorial->productManage->releaseManage->step23->desc = '保存后，在应用列表中查看';

$lang->tutorial->productManage->releaseManage->step24 = new stdClass();
$lang->tutorial->productManage->releaseManage->step24->name = '点击返回';
$lang->tutorial->productManage->releaseManage->step24->desc = '您可以在这里维护管理产品的发布信息';

$lang->tutorial->productManage->lineManage = new stdClass();
$lang->tutorial->productManage->lineManage->title = '产品线管理';

$lang->tutorial->productManage->lineManage->step1 = new stdClass();
$lang->tutorial->productManage->lineManage->step1->name = '点击产品';
$lang->tutorial->productManage->lineManage->step1->desc = '您可以在这里对产品进行维护管理';

$lang->tutorial->productManage->lineManage->step2 = new stdClass();
$lang->tutorial->productManage->lineManage->step2->name = '点击产品线按钮';
$lang->tutorial->productManage->lineManage->step2->desc = '点击维护产品线';

$lang->tutorial->productManage->lineManage->step3 = new stdClass();
$lang->tutorial->productManage->lineManage->step3->name = '填写表单';

$lang->tutorial->productManage->lineManage->step4 = new stdClass();
$lang->tutorial->productManage->lineManage->step4->name = '保存表单';
$lang->tutorial->productManage->lineManage->step4->desc = '保存后在维护产品时可以选择对应的产品线';

$lang->tutorial->productManage->branchManage = new stdClass();
$lang->tutorial->productManage->branchManage->title = '多分支/平台管理';

$lang->tutorial->productManage->branchManage->step1 = new stdClass();
$lang->tutorial->productManage->branchManage->step1->name = '点击产品';
$lang->tutorial->productManage->branchManage->step1->desc = '您可以在这里对产品进行维护管理';

$lang->tutorial->productManage->branchManage->step2 = new stdClass();
$lang->tutorial->productManage->branchManage->step2->name = '点击添加产品';
$lang->tutorial->productManage->branchManage->step2->desc = '点击添加产品';

$lang->tutorial->productManage->branchManage->step3 = new stdClass();
$lang->tutorial->productManage->branchManage->step3->name = '填写表单';

$lang->tutorial->productManage->branchManage->step4 = new stdClass();
$lang->tutorial->productManage->branchManage->step4->name = '保存表单';

$lang->tutorial->productManage->branchManage->step5 = new stdClass();
$lang->tutorial->productManage->branchManage->step5->name = '点击设置';
$lang->tutorial->productManage->branchManage->step5->desc = '点击维护产品的信息';

$lang->tutorial->productManage->branchManage->step6 = new stdClass();
$lang->tutorial->productManage->branchManage->step6->name = '点击分支';
$lang->tutorial->productManage->branchManage->step6->desc = '点击维护产品分支';

$lang->tutorial->productManage->branchManage->step7 = new stdClass();
$lang->tutorial->productManage->branchManage->step7->name = '点击新建分支';
$lang->tutorial->productManage->branchManage->step7->desc = '点击为产品添加新的分支';

$lang->tutorial->productManage->branchManage->step8 = new stdClass();
$lang->tutorial->productManage->branchManage->step8->name = '填写表单';

$lang->tutorial->productManage->branchManage->step9 = new stdClass();
$lang->tutorial->productManage->branchManage->step9->name = '保存表单';
$lang->tutorial->productManage->branchManage->step9->desc = '保存后在分支列表查看分支';

$lang->tutorial->productManage->branchManage->step10 = new stdClass();
$lang->tutorial->productManage->branchManage->step10->name = '勾选分支';

$lang->tutorial->productManage->branchManage->step11 = new stdClass();
$lang->tutorial->productManage->branchManage->step11->name = '点击合并';

$lang->tutorial->productManage->branchManage->step12 = new stdClass();
$lang->tutorial->productManage->branchManage->step12->name = '选择分支';

$lang->tutorial->productManage->branchManage->step13 = new stdClass();
$lang->tutorial->productManage->branchManage->step13->name = '保存表单';
$lang->tutorial->productManage->branchManage->step13->desc = '保存后分支下面对应的发布、计划、构建、模块、需求、Bug、用例都合并到新的分支下';

$lang->tutorial->productManage->branchManage->step14 = new stdClass();
$lang->tutorial->productManage->branchManage->step14->name = '点击研发需求';
$lang->tutorial->productManage->branchManage->step14->desc = '您可以在这里管理产品的研发需求';

$lang->tutorial->productManage->branchManage->step15 = new stdClass();
$lang->tutorial->productManage->branchManage->step15->name = '点击提研发需求';
$lang->tutorial->productManage->branchManage->step15->desc = '点击创建孪生需求';

$lang->tutorial->productManage->branchManage->step16 = new stdClass();
$lang->tutorial->productManage->branchManage->step16->name = '填写表单';

$lang->tutorial->productManage->branchManage->step17 = new stdClass();
$lang->tutorial->productManage->branchManage->step17->name = '保存表单';
$lang->tutorial->productManage->branchManage->step17->desc = '保存后每个分支会建立一个需求，需求间互为孪生关系。孪生需求间除产品、分支、模块、计划、阶段字段外均保持同步，在需求详情页可以解除孪生关系。';

$lang->tutorial->programManage = new stdClass();
$lang->tutorial->programManage->title = '项目集管理教程';

$lang->tutorial->programManage->addProgram = new stdClass();
$lang->tutorial->programManage->addProgram->title = '项目集维护';

$lang->tutorial->programManage->addProgram->step1 = new stdClass();
$lang->tutorial->programManage->addProgram->step1->name = '点击项目集';
$lang->tutorial->programManage->addProgram->step1->desc = '您可以在这里维护管理项目集';

$lang->tutorial->programManage->addProgram->step2 = new stdClass();
$lang->tutorial->programManage->addProgram->step2->name = '点击添加项目集';
$lang->tutorial->programManage->addProgram->step2->desc = '点击添加项目集';

$lang->tutorial->programManage->addProgram->step3 = new stdClass();
$lang->tutorial->programManage->addProgram->step3->name = '填写表单';

$lang->tutorial->programManage->addProgram->step4 = new stdClass();
$lang->tutorial->programManage->addProgram->step4->name = '保存表单';
$lang->tutorial->programManage->addProgram->step4->desc = '保存后在项目视角和产品视角列表中均可查看';

$lang->tutorial->programManage->addProgram->step5 = new stdClass();
$lang->tutorial->programManage->addProgram->step5->name = '点击添加项目';
$lang->tutorial->programManage->addProgram->step5->desc = '点击维护项目集下的项目';

$lang->tutorial->programManage->addProgram->step6 = new stdClass();
$lang->tutorial->programManage->addProgram->step6->name = '点击Scrum';
$lang->tutorial->programManage->addProgram->step6->desc = '可以在这里为该项目集添加项目';

$lang->tutorial->programManage->addProgram->step7 = new stdClass();
$lang->tutorial->programManage->addProgram->step7->name = '填写表单';

$lang->tutorial->programManage->addProgram->step8 = new stdClass();
$lang->tutorial->programManage->addProgram->step8->name = '保存表单';
$lang->tutorial->programManage->addProgram->step8->desc = '保存后可以在项目视角列表中查看';

$lang->tutorial->programManage->addProgram->step9 = new stdClass();
$lang->tutorial->programManage->addProgram->step9->name = '点击产品视角';
$lang->tutorial->programManage->addProgram->step9->desc = '在这里您可以查看维护项目集和产品的关系';

$lang->tutorial->programManage->addProgram->step10 = new stdClass();
$lang->tutorial->programManage->addProgram->step10->name = '点击展开';

$lang->tutorial->programManage->addProgram->step11 = new stdClass();
$lang->tutorial->programManage->addProgram->step11->name = '点击添加产品';
$lang->tutorial->programManage->addProgram->step11->desc = '点击维护项目集下的产品';

$lang->tutorial->programManage->addProgram->step12 = new stdClass();
$lang->tutorial->programManage->addProgram->step12->name = '填写表单';

$lang->tutorial->programManage->addProgram->step13 = new stdClass();
$lang->tutorial->programManage->addProgram->step13->name = '保存表单';
$lang->tutorial->programManage->addProgram->step13->desc = '保存后在产品视角列表查看';

$lang->tutorial->programManage->whitelistManage = new stdClass();
$lang->tutorial->programManage->whitelistManage->title = '维护白名单';

$lang->tutorial->programManage->whitelistManage->step1 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step1->name = '点击项目集名称';
$lang->tutorial->programManage->whitelistManage->step1->desc = '点击进入项目集，查看项目集的详细信息.';

$lang->tutorial->programManage->whitelistManage->step2 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step2->name = '点击人员';
$lang->tutorial->programManage->whitelistManage->step2->desc = '点击查看项目集投入人员、可访问人员及白名单信息';

$lang->tutorial->programManage->whitelistManage->step3 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step3->name = '点击白名单';
$lang->tutorial->programManage->whitelistManage->step3->desc = '点击管理项目集白名单';

$lang->tutorial->programManage->whitelistManage->step4 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step4->name = '点击添加白名单';
$lang->tutorial->programManage->whitelistManage->step4->desc = '点击维护白名单人员';

$lang->tutorial->programManage->whitelistManage->step5 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step5->name = '填写表单';

$lang->tutorial->programManage->whitelistManage->step6 = new stdClass();
$lang->tutorial->programManage->whitelistManage->step6->name = '保存表单';
$lang->tutorial->programManage->whitelistManage->step6->desc = '保存后白名单人员可以查看项目集';

$lang->tutorial->programManage->addStakeholder = new stdClass();
$lang->tutorial->programManage->addStakeholder->title = '创建干系人';

$lang->tutorial->programManage->addStakeholder->step1 = new stdClass();
$lang->tutorial->programManage->addStakeholder->step1->name = '点击干系人';
$lang->tutorial->programManage->addStakeholder->step1->desc = '点击管理项目集的干系人';

$lang->tutorial->programManage->addStakeholder->step2 = new stdClass();
$lang->tutorial->programManage->addStakeholder->step2->name = '点击添加干系人';
$lang->tutorial->programManage->addStakeholder->step2->desc = '点击添加项目集内外部干系人';

$lang->tutorial->programManage->addStakeholder->step3 = new stdClass();
$lang->tutorial->programManage->addStakeholder->step3->name = '填写表单';

$lang->tutorial->programManage->addStakeholder->step4 = new stdClass();
$lang->tutorial->programManage->addStakeholder->step4->name = '保存表单';
$lang->tutorial->programManage->addStakeholder->step4->desc = '保存后干系人可以查看项目集';

$lang->tutorial->feedbackManage = new stdClass();
$lang->tutorial->feedbackManage->title = '反馈管理教程';

$lang->tutorial->feedbackManage->feedback = new stdClass();
$lang->tutorial->feedbackManage->feedback->title = '反馈管理';

$lang->tutorial->feedbackManage->feedback->step1 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step1->name = '点击反馈';
$lang->tutorial->feedbackManage->feedback->step1->desc = '您在这里可以添加、处理反馈';

$lang->tutorial->feedbackManage->feedback->step2 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step2->name = '点击创建反馈';
$lang->tutorial->feedbackManage->feedback->step2->desc = '点击给某产品提反馈';

$lang->tutorial->feedbackManage->feedback->step3 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step3->name = '填写表单';

$lang->tutorial->feedbackManage->feedback->step4 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step4->name = '保存表单';
$lang->tutorial->feedbackManage->feedback->step4->desc = '保存后在反馈列表跟进处理进度';

$lang->tutorial->feedbackManage->feedback->step5 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step5->name = '点击评审';
$lang->tutorial->feedbackManage->feedback->step5->desc = '点击对该条反馈进行评审';

$lang->tutorial->feedbackManage->feedback->step6 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step6->name = '填写表单';

$lang->tutorial->feedbackManage->feedback->step7 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step7->name = '保存表单';
$lang->tutorial->feedbackManage->feedback->step7->desc = '保存后反馈状态随之改变';

$lang->tutorial->feedbackManage->feedback->step8 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step8->name = '点击转Bug';
$lang->tutorial->feedbackManage->feedback->step8->desc = '点击选择反馈的处理方式';

$lang->tutorial->feedbackManage->feedback->step9 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step9->name = '填写表单';

$lang->tutorial->feedbackManage->feedback->step10 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step10->name = '保存表单';
$lang->tutorial->feedbackManage->feedback->step10->desc = '保存后反馈的状态变为处理中，当转化的需求、任务等完成后，反馈的状态才会变为已处理';

$lang->tutorial->feedbackManage->feedback->step11 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step11->name = '关闭反馈';
$lang->tutorial->feedbackManage->feedback->step11->desc = '点击关闭处理完的反馈，反馈处理完成';

$lang->tutorial->feedbackManage->feedback->step12 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step12->name = '填写表单';

$lang->tutorial->feedbackManage->feedback->step13 = new stdClass();
$lang->tutorial->feedbackManage->feedback->step13->name = '保存表单';

$lang->tutorial->docManage = new stdClass();
$lang->tutorial->docManage->title = '文档管理教程';

$lang->tutorial->docManage->step1 = new stdClass();
$lang->tutorial->docManage->step1->name = '点击文档';
$lang->tutorial->docManage->step1->desc = '您在这里可以对产品、项目、团队和个人的文档进行管理';

$lang->tutorial->docManage->step2 = new stdClass();
$lang->tutorial->docManage->step2->name = '点击空间';
$lang->tutorial->docManage->step2->desc = '产品空间管理各产品下的文档，项目空间管理各项目下的文档，团队空间管理组织团队文档，接口空间专门管理接口文档，请点击团队空间进入。';

$lang->tutorial->docManage->step3 = new stdClass();
$lang->tutorial->docManage->step3->name = '点击更多';

$lang->tutorial->docManage->step4 = new stdClass();
$lang->tutorial->docManage->step4->name = '点击创建空间';

$lang->tutorial->docManage->step5 = new stdClass();
$lang->tutorial->docManage->step5->name = '填写表单';

$lang->tutorial->docManage->step6 = new stdClass();
$lang->tutorial->docManage->step6->name = '保存表单';
$lang->tutorial->docManage->step6->desc = '保存后，可以在空间下管理库和文档。';

$lang->tutorial->docManage->step7 = new stdClass();
$lang->tutorial->docManage->step7->name = '点击创建库';
$lang->tutorial->docManage->step7->desc = '点击创建文档库';

$lang->tutorial->docManage->step8 = new stdClass();
$lang->tutorial->docManage->step8->name = '填写表单';

$lang->tutorial->docManage->step9 = new stdClass();
$lang->tutorial->docManage->step9->name = '保存表单';
$lang->tutorial->docManage->step9->desc = '保存后在左侧目录树中查看';

$lang->tutorial->docManage->step10 = new stdClass();
$lang->tutorial->docManage->step10->name = '鼠标移入，点击更多按钮';

$lang->tutorial->docManage->step11 = new stdClass();
$lang->tutorial->docManage->step11->name = '点击添加目录';
$lang->tutorial->docManage->step11->desc = '点击给文档库添加目录';

$lang->tutorial->docManage->step12 = new stdClass();
$lang->tutorial->docManage->step12->name = '填写目录名称';

$lang->tutorial->docManage->step13 = new stdClass();
$lang->tutorial->docManage->step13->name = '点击创建文档';
$lang->tutorial->docManage->step13->desc = '点击创建文档';

$lang->tutorial->docManage->step14 = new stdClass();
$lang->tutorial->docManage->step14->name = '填写表单';

$lang->tutorial->docManage->step15 = new stdClass();
$lang->tutorial->docManage->step15->name = '点击发布';

$lang->tutorial->docManage->step16 = new stdClass();
$lang->tutorial->docManage->step16->name = '填写表单';

$lang->tutorial->docManage->step17 = new stdClass();
$lang->tutorial->docManage->step17->name = '保存发布';
$lang->tutorial->docManage->step17->desc = '保存后在文档列表中查看';

$lang->tutorial->docManage->step18 = new stdClass();
$lang->tutorial->docManage->step18->name = '点击文档标题';
$lang->tutorial->docManage->step18->desc = '点击查看文档详情，支持收藏、编辑、导出文档，支持查看文档的历史记录、更新信息。';

$lang->tutorial->docManage->step19 = new stdClass();
$lang->tutorial->docManage->step19->name = '点击编辑按钮';
$lang->tutorial->docManage->step19->desc = '点击修改文档内容';

$lang->tutorial->docManage->step20 = new stdClass();
$lang->tutorial->docManage->step20->name = '修改文档';

$lang->tutorial->docManage->step21 = new stdClass();
$lang->tutorial->docManage->step21->name = '点击发布';
$lang->tutorial->docManage->step21->desc = '点击保存修改的内容';

$lang->tutorial->docManage->step22 = new stdClass();
$lang->tutorial->docManage->step22->name = '点击版本';
$lang->tutorial->docManage->step22->desc = '可以在这里切换文档版本查看历史版本记录';

$lang->tutorial->docManage->step23 = new stdClass();
$lang->tutorial->docManage->step23->name = '点击版本#1';
$lang->tutorial->docManage->step23->desc = '查看版本#1的文档内容';

$lang->tutorial->orTutorial = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->title = '需求池管理教程';

$lang->tutorial->orTutorial->demandpoolManage->demandManage = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->title = '需求管理';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step1 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step1->name = '点击创建需求';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step1->desc = '点击创建需求';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step2 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step2->name = '填写表单';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step3 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step3->name = '保存表单';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step3->desc = '保存后在需求列表查看';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step4 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step4->name = '点击评审按钮';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step4->desc = '点击对需求进行评审';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step5 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step5->name = '填写表单';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step6 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step6->name = '保存表单';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step6->desc = '保存后需求的状态根据评审结果变动';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step7 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step7->name = '点击变更按钮';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step7->desc = '点击对需求进行变更';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step8 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step8->name = '填写表单';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step9 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step9->name = '保存表单';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step9->desc = '保存后，需求变更完成';

$lang->tutorial->orTutorial->demandpoolManage->demandManage->step10 = new stdClass();
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step10->name = '点击矩阵';
$lang->tutorial->orTutorial->demandpoolManage->demandManage->step10->desc = '您可以在这里跟进需求的进展情况';

$lang->tutorial->orTutorial->marketManage = new stdClass();
$lang->tutorial->orTutorial->marketManage->title = '市场管理教程';

$lang->tutorial->orTutorial->marketManage->researchManage = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->title = '调研管理';

$lang->tutorial->orTutorial->marketManage->researchManage->step1 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step1->name = '点击市场';
$lang->tutorial->orTutorial->marketManage->researchManage->step1->desc = '您在这里可以管理调研活动';

$lang->tutorial->orTutorial->marketManage->researchManage->step2 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step2->name = '点击调研';
$lang->tutorial->orTutorial->marketManage->researchManage->step2->desc = '您在这里可以管理调研活动';

$lang->tutorial->orTutorial->marketManage->researchManage->step3 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step3->name = '点击发起调研';
$lang->tutorial->orTutorial->marketManage->researchManage->step3->desc = '点击发起调研活动';

$lang->tutorial->orTutorial->marketManage->researchManage->step4 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step4->name = '填写表单';

$lang->tutorial->orTutorial->marketManage->researchManage->step5 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step5->name = '保存表单';
$lang->tutorial->orTutorial->marketManage->researchManage->step5->desc = '保存后在调研列表查看';

$lang->tutorial->orTutorial->marketManage->researchManage->step6 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step6->name = '点击调研名称';
$lang->tutorial->orTutorial->marketManage->researchManage->step6->desc = '点击管理调研活动';

$lang->tutorial->orTutorial->marketManage->researchManage->step7 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step7->name = '点击设置阶段';
$lang->tutorial->orTutorial->marketManage->researchManage->step7->desc = '点击设置调研活动的阶段';

$lang->tutorial->orTutorial->marketManage->researchManage->step8 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step8->name = '填写表单';

$lang->tutorial->orTutorial->marketManage->researchManage->step9 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step9->name = '保存表单';
$lang->tutorial->orTutorial->marketManage->researchManage->step9->desc = '保存后在调研任务列表查看';

$lang->tutorial->orTutorial->marketManage->researchManage->step10 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step10->name = '点击建任务';
$lang->tutorial->orTutorial->marketManage->researchManage->step10->desc = '点击创建调研活动的任务';

$lang->tutorial->orTutorial->marketManage->researchManage->step11 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step11->name = '填写表单';

$lang->tutorial->orTutorial->marketManage->researchManage->step12 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step12->name = '保存表单';
$lang->tutorial->orTutorial->marketManage->researchManage->step12->desc = '保存后在调研任务列表查看';

$lang->tutorial->orTutorial->marketManage->researchManage->step13 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step13->name = '开始任务';
$lang->tutorial->orTutorial->marketManage->researchManage->step13->desc = '您可以在这里开始任务，并记录消耗和剩余工时';

$lang->tutorial->orTutorial->marketManage->researchManage->step14 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step14->name = '填写表单';

$lang->tutorial->orTutorial->marketManage->researchManage->step15 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step15->name = '保存表单';
$lang->tutorial->orTutorial->marketManage->researchManage->step15->desc = '保存后任务状态变为进行中';

$lang->tutorial->orTutorial->marketManage->researchManage->step16 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step16->name = '点击日志';
$lang->tutorial->orTutorial->marketManage->researchManage->step16->desc = '点击为任务记录工时日志';

$lang->tutorial->orTutorial->marketManage->researchManage->step17 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step17->name = '填写表单';

$lang->tutorial->orTutorial->marketManage->researchManage->step18 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step18->name = '保存表单';
$lang->tutorial->orTutorial->marketManage->researchManage->step18->desc = '保存后任务工时会根据日志更新';

$lang->tutorial->orTutorial->marketManage->researchManage->step19 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step19->name = '完成任务';
$lang->tutorial->orTutorial->marketManage->researchManage->step19->desc = '点击完成任务';

$lang->tutorial->orTutorial->marketManage->researchManage->step20 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step20->name = '填写表单';

$lang->tutorial->orTutorial->marketManage->researchManage->step21 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step21->name = '保存表单';
$lang->tutorial->orTutorial->marketManage->researchManage->step21->desc = '保存后任务状态更改为已完成';

$lang->tutorial->orTutorial->marketManage->researchManage->step22 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step22->name = '关闭任务';
$lang->tutorial->orTutorial->marketManage->researchManage->step22->desc = '点击将完成的任务关闭';

$lang->tutorial->orTutorial->marketManage->researchManage->step23 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step23->name = '填写表单';

$lang->tutorial->orTutorial->marketManage->researchManage->step24 = new stdClass();
$lang->tutorial->orTutorial->marketManage->researchManage->step24->name = '保存表单';
$lang->tutorial->orTutorial->marketManage->researchManage->step24->desc = '保存后任务状态更改为已关闭';

$lang->tutorial->orTutorial->roadmapManage = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->title = '产品规划管理教程';

$lang->tutorial->orTutorial->roadmapManage->lineManage = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->lineManage = clone $lang->tutorial->productManage->lineManage;

$lang->tutorial->orTutorial->roadmapManage->addProduct = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->addProduct = clone $lang->tutorial->productManage->addProduct;

$lang->tutorial->orTutorial->roadmapManage->moduleManage = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->moduleManage = clone $lang->tutorial->productManage->moduleManage;

$lang->tutorial->orTutorial->roadmapManage->storyManage = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->storyManage = clone $lang->tutorial->productManage->storyManage;

$lang->tutorial->orTutorial->roadmapManage->branchManage = new stdClass();
$lang->tutorial->orTutorial->roadmapManage->branchManage = clone $lang->tutorial->productManage->branchManage;

$lang->tutorial->orTutorial->charterManage = new stdClass();
$lang->tutorial->orTutorial->charterManage->title = 'Charter立项教程';

$lang->tutorial->orTutorial->charterManage->step1 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step1->name = "点击立项";
$lang->tutorial->orTutorial->charterManage->step1->desc = "您可以在这里管理Charter立项";

$lang->tutorial->orTutorial->charterManage->step2 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step2->name = "点击提交立项";
$lang->tutorial->orTutorial->charterManage->step2->desc = "点击提交Charter立项的申请";

$lang->tutorial->orTutorial->charterManage->step3 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step3->name = "填写表单";

$lang->tutorial->orTutorial->charterManage->step4 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step4->name = "保存表单";
$lang->tutorial->orTutorial->charterManage->step4->desc = "保存后在立项列表中跟进申请进度";

$lang->tutorial->orTutorial->charterManage->step5 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step5->name = "点击评审结果";
$lang->tutorial->orTutorial->charterManage->step5->desc = "点击评审立项申请";

$lang->tutorial->orTutorial->charterManage->step6 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step6->name = "填写表单";

$lang->tutorial->orTutorial->charterManage->step7 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step7->name = "保存表单";
$lang->tutorial->orTutorial->charterManage->step7->desc = "保存后根据评审结果，立项状态修改";

$lang->tutorial->orTutorial->charterManage->step8 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step8->name = "点击关闭";
$lang->tutorial->orTutorial->charterManage->step8->desc = "Charter完成后点击关闭按钮进行关闭";

$lang->tutorial->orTutorial->charterManage->step9 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step9->name = "填写表单";

$lang->tutorial->orTutorial->charterManage->step10 = new stdClass();
$lang->tutorial->orTutorial->charterManage->step10->name = "保存表单";
$lang->tutorial->orTutorial->charterManage->step10->desc = "保存后，立项状态变为已关闭";
