<?php
/**
 * The tutorial lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: zh-tw.php 5116 2013-07-12 06:37:48Z sunhao@cnezsoft.com $
 * @link        https://www.zentao.net
 */
$lang->tutorial = new stdclass();
$lang->tutorial->common           = '新手教程';
$lang->tutorial->desc             = '通過完成一系列任務，快速瞭解禪道的基本使用方法。這可能會花費你10分鐘，你可以隨時退出任務。';
$lang->tutorial->start            = '立即開始';
$lang->tutorial->exit             = '退出教程';
$lang->tutorial->congratulation   = '恭喜，你已完成了所有任務！';
$lang->tutorial->restart          = '重新開始';
$lang->tutorial->currentTask      = '當前任務';
$lang->tutorial->allTasks         = '所有任務';
$lang->tutorial->previous         = '上一個';
$lang->tutorial->nextTask         = '下一個任務';
$lang->tutorial->openTargetPage   = '打開 <strong class="task-page-name">目標</strong> 頁面';
$lang->tutorial->atTargetPage     = '已在 <strong class="task-page-name">目標</strong> 頁面';
$lang->tutorial->reloadTargetPage = '重新載入';
$lang->tutorial->target           = '目標';
$lang->tutorial->targetPageTip    = '按此指示打開【%s】頁面';
$lang->tutorial->targetAppTip     = '按此指示打開【%s】應用';
$lang->tutorial->requiredTip      = '【%s】為必填項';
$lang->tutorial->congratulateTask = '恭喜，你完成了任務 【<span class="task-name-current"></span>】！';
$lang->tutorial->serverErrorTip   = '發生了一些錯誤。';
$lang->tutorial->ajaxSetError     = '必須指定已完成的任務，如果要重置任務，請設置值為空。';
$lang->tutorial->novice           = "你可能初次使用禪道，是否進入新手教程";
$lang->tutorial->dataNotSave      = "教程任務中，數據不會保存。";

$lang->tutorial->tasks = new stdClass();
$lang->tutorial->tasks->createAccount = new stdclass();

$lang->tutorial->tasks->createAccount->title          = '創建帳號';
$lang->tutorial->tasks->createAccount->targetPageName = '添加用戶';
$lang->tutorial->tasks->createAccount->desc           = "<p>在系統創建一個新的用戶帳號：</p><ul><li data-target='nav'>打開 <span class='task-nav'>後台 <i class='icon icon-angle-right'></i> 人員 <i class='icon icon-angle-right'></i> 用戶 <i class='icon icon-angle-right'></i> 添加用戶</span> 頁面；</li><li data-target='form'>在添加用戶表單中填寫新用戶信息；</li><li data-target='submit'>保存用戶信息。</li></ul>";

$lang->tutorial->tasks->createProgram = new stdclass();
$lang->tutorial->tasks->createProgram->title          = '創建項目集';
$lang->tutorial->tasks->createProgram->targetPageName = '添加項目集';
$lang->tutorial->tasks->createProgram->desc           = "<p>在系統創建一個新的項目集：</p><ul><li data-target='nav'>打開 <span class='task-nav'>項目集 <i class='icon icon-angle-right'></i> 項目集列表 <i class='icon icon-angle-right'></i> 添加項目集</span> 頁面；</li><li data-target='form'>在添加項目集表單中填寫項目集信息；</li><li data-target='submit'>保存項目集信息。</li></ul>";

$lang->tutorial->tasks->createProduct = new stdclass();
$lang->tutorial->tasks->createProduct->title          = '創建' . $lang->productCommon;
$lang->tutorial->tasks->createProduct->targetPageName = '添加' . $lang->productCommon;
$lang->tutorial->tasks->createProduct->desc           = "<p>在系統創建一個新的{$lang->productCommon}：</p><ul><li data-target='nav'>打開 <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i> {$lang->productCommon}列表 <i class='icon icon-angle-right'></i> 添加{$lang->productCommon}</span> 頁面；</li><li data-target='form'>在添加{$lang->productCommon}表單中填寫要創建的{$lang->productCommon}信息；</li><li data-target='submit'>保存{$lang->productCommon}信息。</li></ul>";

$lang->tutorial->tasks->createStory = new stdclass();
$lang->tutorial->tasks->createStory->title          = "創建{$lang->SRCommon}";
$lang->tutorial->tasks->createStory->targetPageName = "提{$lang->SRCommon}";
$lang->tutorial->tasks->createStory->desc           = "<p>在系統創建一個新的{$lang->SRCommon}：</p><ul><li data-target='nav'>打開 <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 提{$lang->SRCommon}</span> 頁面；</li><li data-target='form'>在{$lang->productCommon}表單中填寫要創建的{$lang->SRCommon}信息；</li><li data-target='submit'>保存{$lang->SRCommon}信息。</li></ul>";

$lang->tutorial->tasks->createProject = new stdclass();
$lang->tutorial->tasks->createProject->title          = '創建項目';
$lang->tutorial->tasks->createProject->targetPageName = '添加項目';
$lang->tutorial->tasks->createProject->desc           = "<p>在系統創建一個新的項目：</p><ul><li data-target='nav'>打開 <span class='task-nav'> 項目 <i class='icon icon-angle-right'></i> 項目列表 <i class='icon icon-angle-right'></i> 創建項目</span> 頁面；</li><li data-target='form'>在項目表單中填寫要創建的項目信息；</li><li data-target='submit'>保存項目信息。</li></ul>";

$lang->tutorial->tasks->manageTeam = new stdclass();
$lang->tutorial->tasks->manageTeam->title          = '管理項目團隊';
$lang->tutorial->tasks->manageTeam->targetPageName = '團隊管理';
$lang->tutorial->tasks->manageTeam->desc           = "<p>管理項目團隊成員：</p><ul><li data-target='nav'>打開 <span class='task-nav'> 項目 <i class='icon icon-angle-right'></i> 設置 <i class='icon icon-angle-right'></i> 團隊 <i class='icon icon-angle-right'></i> 團隊管理</span> 頁面；</li><li data-target='form'>選擇要加入項目團隊的成員；</li><li data-target='submit'>保存團隊成員信息。</li></ul>";

$lang->tutorial->tasks->createProjectExecution = new stdclass();
$lang->tutorial->tasks->createProjectExecution->title          = '添加' . $lang->executionCommon;
$lang->tutorial->tasks->createProjectExecution->targetPageName = '添加' . $lang->executionCommon;
$lang->tutorial->tasks->createProjectExecution->desc           = "<p>在系統創建一個新的{$lang->executionCommon}：</p><ul><li data-target='nav'>打開 <span class='task-nav'> 項目 <i class='icon icon-angle-right'></i> {$lang->executionCommon} <i class='icon icon-angle-right'></i> 添加{$lang->executionCommon}</span> 頁面；</li><li data-target='form'>在{$lang->executionCommon}表單中填寫要創建的{$lang->executionCommon}信息；</li><li data-target='submit'>保存{$lang->executionCommon}信息。</li></ul>";

$lang->tutorial->tasks->linkStory = new stdclass();
$lang->tutorial->tasks->linkStory->title          = "關聯{$lang->SRCommon}";
$lang->tutorial->tasks->linkStory->targetPageName = "關聯{$lang->SRCommon}";
$lang->tutorial->tasks->linkStory->desc           = "<p>將{$lang->SRCommon}關聯到執行：</p><ul><li data-target='nav'>打開 <span class='task-nav'> 執行 <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 關聯{$lang->SRCommon}</span> 頁面；</li><li data-target='form'>在{$lang->SRCommon}列表中勾選要關聯的{$lang->SRCommon}；</li><li data-target='submit'>保存關聯的{$lang->SRCommon}信息。</li></ul>";

$lang->tutorial->tasks->createTask = new stdclass();
$lang->tutorial->tasks->createTask->title          = '分解任務';
$lang->tutorial->tasks->createTask->targetPageName = '建任務';
$lang->tutorial->tasks->createTask->desc           = "<p>將執行{$lang->SRCommon}分解為任務：</p><ul><li data-target='nav'>打開 <span class='task-nav'> 執行 <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 分解任務</span> 頁面；</li><li data-target='form'>在表單中填寫任務信息；</li><li data-target='submit'>保存任務信息。</li></ul>";

$lang->tutorial->tasks->createBug = new stdclass();
$lang->tutorial->tasks->createBug->title          = '提Bug';
$lang->tutorial->tasks->createBug->targetPageName = '提Bug';
$lang->tutorial->tasks->createBug->desc           = "<p>在系統中提交一個Bug：</p><ul><li data-target='nav'>打開 <span class='task-nav'> 測試 <i class='icon icon-angle-right'></i> Bug <i class='icon icon-angle-right'></i> 提Bug</span>；</li><li data-target='form'>在表單中填寫Bug信息；</li><li data-target='submit'>保存Bug信息。</li></ul>";
