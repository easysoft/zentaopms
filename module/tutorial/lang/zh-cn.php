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
$lang->tutorial->desc             = '通过完成一系列任务，快速了解禅道的基本使用方法。这可能会花费你10分钟，你可以随时退出任务。';
$lang->tutorial->start            = '开始';
$lang->tutorial->continue         = '继续';
$lang->tutorial->exit             = '退出教程';
$lang->tutorial->congratulation   = '恭喜，你已完成了所有任务！';
$lang->tutorial->restart          = '重新开始';
$lang->tutorial->currentTask      = '当前任务';
$lang->tutorial->allTasks         = '所有任务';
$lang->tutorial->previous         = '上一个';
$lang->tutorial->nextTask         = '下一个任务';
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
$lang->tutorial->scrumProjectManage->manageExecution->step6->desc = '点击需求查看已关联的需求';

$lang->tutorial->scrumProjectManage->manageExecution->step7 = new stdClass();
$lang->tutorial->scrumProjectManage->manageExecution->step7->name = '点击关联需求';
$lang->tutorial->scrumProjectManage->manageExecution->step7->desc = '点击关联需求进入关联需求列表';

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
$lang->tutorial->scrumProjectManage->manageTask->step1->desc = '进入需求列表，您可以在这里看到之前关联的需求需求';

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
$lang->tutorial->scrumProjectManage->manageTask->step18->name = '点击版本';
$lang->tutorial->scrumProjectManage->manageTask->step18->desc = '进入版本模块中可以创建版本';

$lang->tutorial->scrumProjectManage->manageTask->step19 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step19->name = '点击创建版本';
$lang->tutorial->scrumProjectManage->manageTask->step19->desc = '可以在这里创建新的版本';

$lang->tutorial->scrumProjectManage->manageTask->step20 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step20->name = '填写表单';

$lang->tutorial->scrumProjectManage->manageTask->step21 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step21->name = '保存表单';
$lang->tutorial->scrumProjectManage->manageTask->step21->desc = '保存后进入版本详情';

$lang->tutorial->scrumProjectManage->manageTask->step22 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step22->name = '关联需求';
$lang->tutorial->scrumProjectManage->manageTask->step22->desc = '可以将完成的研发需求关联在版本中';

$lang->tutorial->scrumProjectManage->manageTask->step23 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step23->name = '选择需求';
$lang->tutorial->scrumProjectManage->manageTask->step23->desc = '在这里可以选择勾选需要关联的需求';

$lang->tutorial->scrumProjectManage->manageTask->step24 = new stdClass();
$lang->tutorial->scrumProjectManage->manageTask->step24->name = '保存关联的需求';
$lang->tutorial->scrumProjectManage->manageTask->step24->desc = '您可以将完成的需求关联在当前版本中';

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
$lang->tutorial->scrumProjectManage->manageTest->step11->name = '点击转bug';
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
$lang->tutorial->scrumProjectManage->manageTest->step21->desc = '您可以将用例关联在测试单中，在这里可以查看全部已关联的用例';

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
$lang->tutorial->scrumProjectManage->manageBug->step2->name = '点击提bug';
$lang->tutorial->scrumProjectManage->manageBug->step2->desc = '可以在这里创建bug';

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
$lang->tutorial->scrumProjectManage->manageBug->step10->desc = '保存后可以将解决完的bug进行验证';

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
$lang->tutorial->waterfallProjectManage->setStage->step5->desc = '在这里可以切换为甘特图视图查看阶段';

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
$lang->tutorial->waterfallProjectManage->design->step2->desc = '您可以在这里创建版本';

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
$lang->tutorial->waterfallProjectManage->design->step7->name = '填写表单';
$lang->tutorial->waterfallProjectManage->design->step7->desc = '空';

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
$lang->tutorial->kanbanProjectManage->manageProject = $lang->tutorial->scrumProjectManage->manageProject;

$lang->tutorial->kanbanProjectManage->manageProject->step3 = new stdClass();
$lang->tutorial->kanbanProjectManage->manageProject->step3->name = '点击项目看板';
$lang->tutorial->kanbanProjectManage->manageProject->step3->desc = '可以在这里创建看板项目';
