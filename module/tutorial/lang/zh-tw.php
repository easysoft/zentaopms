<?php
/**
 * The tutorial lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: zh-tw.php 5116 2013-07-12 06:37:48Z sunhao@cnezsoft.com $
 * @link        http://www.zentao.net
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
$lang->tutorial->requiredTip      = '【%s】為必填項';
$lang->tutorial->congratulateTask = '恭喜，你完成了任務 【<span class="task-name-current"></span>】！';
$lang->tutorial->serverErrorTip   = '發生了一些錯誤。';
$lang->tutorial->ajaxSetError     = '必須指定已完成的任務，如果要重置任務，請設置值為空。';
$lang->tutorial->novice           = "你可能初次使用禪道，是否進入新手教程";
$lang->tutorial->dataNotSave      = "教程任務中，數據不會保存。";

$lang->tutorial->tasks = array();

$lang->tutorial->tasks['createAccount']         = array('title' => '創建帳號');
$lang->tutorial->tasks['createAccount']['nav']  = array('module' => 'user', 'method' => 'create', 'menuModule' => 'company', 'menu' => 'browseUser', 'form' => '#createForm', 'submit' => '#submit', 'target' => '.create-user-btn', 'targetPageName' => '添加用戶');
$lang->tutorial->tasks['createAccount']['desc'] = "<p>在系統創建一個新的用戶帳號：</p><ul><li data-target='nav'>打開 <span class='task-nav'>組織 <i class='icon icon-angle-right'></i> 用戶 <i class='icon icon-angle-right'></i> 添加用戶</span> 頁面；</li><li data-target='form'>在添加用戶表單中填寫新用戶信息；</li><li data-target='submit'>保存用戶信息。</li></ul>";

global $config;
if($config->global->flow == 'full' or $config->global->flow != 'onlyTask')
{
    $lang->tutorial->tasks['createProduct']         = array('title' => '創建' . $lang->productCommon);
    $lang->tutorial->tasks['createProduct']['nav']  = array('module' => 'product', 'method' => 'create', 'menu' => '#pageNav', 'form' => '#createForm', 'submit' => '#submit', 'target' => '.create-product-btn', 'targetPageName' => '添加' . $lang->productCommon);
    $lang->tutorial->tasks['createProduct']['desc'] = "<p>在系統創建一個新的{$lang->productCommon}：</p><ul><li data-target='nav'>打開 <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i> 添加{$lang->productCommon}</span> 頁面；</li><li data-target='form'>在表單中填寫要創建的{$lang->productCommon}信息；</li><li data-target='submit'>保存{$lang->productCommon}信息。</li></ul>";
}

if($config->global->flow == 'full' or $config->global->flow == 'onlyStory')
{
    $lang->tutorial->tasks['createStory']         = array('title' => "創建{$lang->SRCommon}");
    $lang->tutorial->tasks['createStory']['nav']  = array('module' => 'story', 'method' => 'create', 'menuModule' => 'product', 'menu' => 'story', 'target' => '.create-story-btn', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => "提{$lang->SRCommon}");
    $lang->tutorial->tasks['createStory']['desc'] = "<p>在系統創建一個新的{$lang->SRCommon}：</p><ul><li data-target='nav'>打開 <span class='task-nav'>{$lang->productCommon} <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 添加{$lang->SRCommon}</span> 頁面；</li><li data-target='form'>在表單中填寫要創建的{$lang->SRCommon}信息；</li><li data-target='submit'>保存{$lang->SRCommon}信息。</li></ul>";
}

if($config->global->flow == 'full' or $config->global->flow == 'onlyTask')
{
    $lang->tutorial->tasks['createProject']         = array('title' => '創建' . $lang->executionCommon);
    $lang->tutorial->tasks['createProject']['nav']  = array('module' => 'execution', 'method' => 'create', 'menu' => '#pageNav', 'form' => '#dataform', 'submit' => '#submit', 'target' => '.create-project-btn', 'targetPageName' => '添加' . $lang->executionCommon);
    $lang->tutorial->tasks['createProject']['desc'] = "<p>在系統創建一個新的{$lang->executionCommon}：</p><ul><li data-target='nav'>打開 <span class='task-nav'> {$lang->executionCommon} <i class='icon icon-angle-right'></i> 添加{$lang->executionCommon}</span> 頁面；</li><li data-target='form'>在表單中填寫要創建的{$lang->executionCommon}信息；</li><li data-target='submit'>保存{$lang->executionCommon}信息。</li></ul>";

    $lang->tutorial->tasks['manageTeam']         = array('title' => '管理團隊');
    $lang->tutorial->tasks['manageTeam']['nav']  = array('module' => 'execution', 'method' => 'managemembers', 'menu' => 'team', 'target' => '.manage-team-btn', 'form' => '#teamForm', 'requiredFields' => 'account1', 'submit' => '#submit', 'targetPageName' => '團隊管理');
    $lang->tutorial->tasks['manageTeam']['desc'] = "<p>管理{$lang->executionCommon}團隊成員：</p><ul><li data-target='nav'>打開 <span class='task-nav'> {$lang->executionCommon} <i class='icon icon-angle-right'></i> 團隊 <i class='icon icon-angle-right'></i> 團隊管理</span> 頁面；</li><li data-target='form'>選擇要加入團隊的成員；</li><li data-target='submit'>保存團隊成員信息。</li></ul>";

    if($config->global->flow == 'full')
    {
        $lang->tutorial->tasks['linkStory']         = array('title' => "關聯{$lang->SRCommon}");
        $lang->tutorial->tasks['linkStory']['nav']  = array('module' => 'execution', 'method' => 'linkStory', 'menu' => 'story', 'target' => '.link-story-btn', 'form' => '#linkStoryForm', 'formType' => 'table', 'submit' => '#submit', 'targetPageName' => "關聯{$lang->SRCommon}");
        $lang->tutorial->tasks['linkStory']['desc'] = "<p>將{$lang->SRCommon}關聯到{$lang->executionCommon}：</p><ul><li data-target='nav'>打開 <span class='task-nav'> {$lang->executionCommon} <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 關聯{$lang->SRCommon}</span> 頁面；</li><li data-target='form'>在{$lang->SRCommon}列表中勾選要關聯的{$lang->SRCommon}；</li><li data-target='submit'>保存關聯的{$lang->SRCommon}信息。</li></ul>";
    }

    $lang->tutorial->tasks['createTask']         = array('title' => '分解任務');
    $lang->tutorial->tasks['createTask']['nav']  = array('module' => 'task', 'method' => 'create', 'menuModule' => 'execution', 'menu' => 'story', 'target' => '.btn-task-create', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => '建任務');
    $lang->tutorial->tasks['createTask']['desc'] = "<p>將{$lang->executionCommon}{$lang->SRCommon}分解為任務：</p><ul><li data-target='nav'>打開 <span class='task-nav'> {$lang->executionCommon} <i class='icon icon-angle-right'></i> {$lang->SRCommon} <i class='icon icon-angle-right'></i> 分解任務</span> 頁面；</li><li data-target='form'>在表單中填寫任務信息；</li><li data-target='submit'>保存任務信息。</li></ul>";
}

if($config->global->flow == 'full' or $config->global->flow == 'onlyTest')
{
    $lang->tutorial->tasks['createBug']         = array('title' => '提Bug');
    $lang->tutorial->tasks['createBug']['nav']  = array('module' => 'bug', 'method' => 'create', 'menuModule' => 'qa', 'menu' => 'bug', 'target' => '.btn-bug-create', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => '提Bug');
    $lang->tutorial->tasks['createBug']['desc'] = "<p>在系統中提交一個Bug：</p><ul><li data-target='nav'>打開 <span class='task-nav'> 測試 <i class='icon icon-angle-right'></i> Bug <i class='icon icon-angle-right'></i> 提Bug</span>；</li><li data-target='form'>在表單中填寫Bug信息；</li><li data-target='submit'>保存Bug信息。</li></ul>";
}
