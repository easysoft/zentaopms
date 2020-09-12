<?php
/**
 * The tutorial lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      wuhongjie wangguannan
 * @package     ZenTaoPMS
 * @version     $Id: zh-cn.php 5116 2013-07-12 06:37:48Z sunhao@cnezsoft.com $
 * @link        http://www.zentao.net
 */
$lang->tutorial = new stdclass();
$lang->tutorial->common = 'チュートリアル';
$lang->tutorial->desc = 'シリーズタスクを完了することによって、禅道の基本使用方法を速やかに理解できます。10分くらいかかりますが、いつでも終了できます。';
$lang->tutorial->start = 'すぐ開始';
$lang->tutorial->exit = 'チュートリアル終了';
$lang->tutorial->congratulation = 'おめでとうございます、全てタスクを完了しました！';
$lang->tutorial->restart = 'リスタート';
$lang->tutorial->currentTask = '現在タスク';
$lang->tutorial->allTasks = '全てタスク';
$lang->tutorial->previous = '前タスク';
$lang->tutorial->nextTask = '次タスク';
$lang->tutorial->openTargetPage = ' <strong class="task-page-name">目標</strong> ページを開きます';
$lang->tutorial->atTargetPage = '<strong class="task-page-name">目標</strong> ページにいます';
$lang->tutorial->reloadTargetPage = 'リロード';
$lang->tutorial->target = '目標';
$lang->tutorial->targetPageTip = '【%s】ページを開いてください';
$lang->tutorial->requiredTip = '【%s】は必須項目';
$lang->tutorial->congratulateTask = 'おめでとうございます、タスク 【<span class="task-name-current"></span>】を完了しました！';
$lang->tutorial->serverErrorTip = 'エラーが発生しました。';
$lang->tutorial->ajaxSetError = '完了したタスクを指定してください、タスクをリセットしたいなら、値を空に設定してください。';
$lang->tutorial->novice = '初めて禅道をご利用しますので、初心者向けチュートリアルに入りませんか';
$lang->tutorial->dataNotSave = 'チュートリアル中、データが保存しません';

$lang->tutorial->tasks = array();

$lang->tutorial->tasks['createAccount'] = array('title' => 'アカウント作成');
$lang->tutorial->tasks['createAccount']['nav'] = array('module' => 'user', 'method' => 'create', 'menuModule' => 'company', 'menu' => 'browseUser', 'form' => '#createForm', 'submit' => '#submit', 'target' => '.create-user-btn', 'targetPageName' => 'ユーザ追加');
$lang->tutorial->tasks['createAccount']['desc'] = "<p>新規ユーザアカウントを作成します：</p><ul><li data-target='nav'> <span class='task-nav'>組織 <i class='icon icon-angle-right'></i> ユーザ <i class='icon icon-angle-right'></i> ユーザ追加</span> ページを開く；</li><li data-target='form'>新規ユーザ情報を入力します；</li><li data-target='submit'>ユーザ情報を保存します。</li></ul>";

global $config;
if($config->global->flow == 'full' or $config->global->flow != 'onlyTask')
{
    $lang->tutorial->tasks['createProduct'] = array('title' => $lang->productCommon.'作成');
    $lang->tutorial->tasks['createProduct']['nav'] = array('module' => 'product', 'method' => 'create', 'menu' => '#pageNav', 'form' => '#createForm', 'submit' => '#submit', 'target' => '.create-product-btn', 'targetPageName' =>  $lang->productCommon.'追加' );
    $lang->tutorial->tasks['createProduct']['desc'] = "<p>新規プロダクトを作成します：</p><ul><li data-target='nav'> <span class='task-nav'>プロダクト <i class='icon icon-angle-right'></i> プロダクト追加</span> ページを開きます；</li><li data-target='form'>新規プロダクト情報を入力します；</li><li data-target='submit'>プロダクト情報を保存します。</li></ul>";
}

if($config->global->flow == 'full' or $config->global->flow == 'onlyStory')
{
    $lang->tutorial->tasks['createStory'] = array('title' => $lang->storyCommon . '作成');
    $lang->tutorial->tasks['createStory']['nav'] = array('module' => 'story', 'method' => 'create', 'menuModule' => 'product', 'menu' => 'story', 'target' => '.create-story-btn', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => $lang->storyCommon . '提出');
    $lang->tutorial->tasks['createStory']['desc'] = "<p>新規{$lang->storyCommon}を作成します：</p><ul><li data-target='nav'><span class='task-nav'>プロダクト <i class='icon icon-angle-right'></i> {$lang->storyCommon} <i class='icon icon-angle-right'></i> {$lang->storyCommon}追加</span> ページを開きます；</li><li data-target='form'>{$lang->storyCommon}情報を入力します；</li><li data-target='submit'>{$lang->storyCommon}情報を保存します。</li></ul>";
}

if($config->global->flow == 'full' or $config->global->flow == 'onlyTask')
{
    $lang->tutorial->tasks['createProject'] = array('title' => $lang->projectCommon.'作成');
    $lang->tutorial->tasks['createProject']['nav'] = array('module' => 'project', 'method' => 'create', 'menu' => '#pageNav', 'form' => '#dataform', 'submit' => '#submit', 'target' => '.create-project-btn', 'targetPageName' => '追加' . $lang->projectCommon);
    $lang->tutorial->tasks['createProject']['desc'] = "<p>新規プロジェクトを作成します：</p><ul><li data-target='nav'> <span class='task-nav'> プロジェクト <i class='icon icon-angle-right'></i> プロジェクト追加</span> ページを開きます；</li><li data-target='form'>プロジェクト情報を入力します；</li><li data-target='submit'>プロジェクト情報を保存します。</li></ul>";

    $lang->tutorial->tasks['manageTeam'] = array('title' => 'チーム管理');
    $lang->tutorial->tasks['manageTeam']['nav'] = array('module' => 'project', 'method' => 'managemembers', 'menu' => 'team', 'target' => '.manage-team-btn', 'form' => '#teamForm', 'requiredFields' => 'account1', 'submit' => '#submit', 'targetPageName' => 'チーム管理');
    $lang->tutorial->tasks['manageTeam']['desc'] = "<p>プロジェクトチームメンバーを管理します：</p><ul><li data-target='nav'> <span class='task-nav'> プロジェクト <i class='icon icon-angle-right'></i> チーム <i class='icon icon-angle-right'></i> チーム管理</span> ページを開きます；</li><li data-target='form'>チームメンバーを選択します；</li><li data-target='submit'>チームメンバー情報を保存します。</li></ul>";

    if($config->global->flow == 'full')
    {
        $lang->tutorial->tasks['linkStory'] = array('title' => $lang->storyCommon . 'と関連付け');
        $lang->tutorial->tasks['linkStory']['nav'] = array('module' => 'project', 'method' => 'linkStory', 'menu' => 'story', 'target' => '.link-story-btn', 'form' => '#linkStoryForm', 'formType' => 'table', 'submit' => '#submit', 'targetPageName' => $lang->storyCommon . 'と関連付け');
        $lang->tutorial->tasks['linkStory']['desc'] = "<p>{$lang->storyCommon}をプロジェクトと関連付けます：</p><ul><li data-target='nav'> <span class='task-nav'> プロジェクト <i class='icon icon-angle-right'></i> {$lang->storyCommon} <i class='icon icon-angle-right'></i> {$lang->storyCommon}関連付け</span> ページを開きます；</li><li data-target='form'>関連付けたい{$lang->storyCommon}を選択します；</li><li data-target='submit'>関連付けた{$lang->storyCommon}情報を保存します。</li></ul>";
    }

    $lang->tutorial->tasks['createTask'] = array('title' => 'タスク分解');
    $lang->tutorial->tasks['createTask']['nav'] = array('module' => 'task', 'method' => 'create', 'menuModule' => 'project', 'menu' => 'story', 'target' => '.btn-task-create', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'タスク作成');
    $lang->tutorial->tasks['createTask']['desc'] = "<p>{$lang->storyCommon}をタスクに分解します：</p><ul><li data-target='nav'> <span class='task-nav'> プロジェクト <i class='icon icon-angle-right'></i> {$lang->storyCommon} <i class='icon icon-angle-right'></i> タスク分解</span> ページを開きます；</li><li data-target='form'>タスク情報を入力します；</li><li data-target='submit'>タスク情報を保存します。</li></ul>";
}

if($config->global->flow == 'full' or $config->global->flow == 'onlyTest')
{
    $lang->tutorial->tasks['createBug'] = array('title' => 'バグ提出');
    $lang->tutorial->tasks['createBug']['nav'] = array('module' => 'bug', 'method' => 'create', 'menuModule' => 'qa', 'menu' => 'bug', 'target' => '.btn-bug-create', 'form' => '#dataform', 'submit' => '#submit', 'targetPageName' => 'バグ提出');
    $lang->tutorial->tasks['createBug']['desc'] = "<p>バグを提出します：</p><ul><li data-target='nav'> <span class='task-nav'> テスト <i class='icon icon-angle-right'></i> バグ <i class='icon icon-angle-right'></i> バグ提出</span>ページを開きます；</li><li data-target='form'>バグ情報を入力します；</li><li data-target='submit'>バグ情報を保存します。</li></ul>";
}
