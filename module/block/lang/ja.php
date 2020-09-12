<?php
/**
 * The ja file of crm block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      admin wangguannan
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->block = new stdclass();
$lang->block->common = 'ユニット';
$lang->block->name = 'ユニット名';
$lang->block->style = '外観';
$lang->block->grid = '位置';
$lang->block->color = '色';
$lang->block->reset = 'デフォルト';

$lang->block->account = 'ユーザ';
$lang->block->module = 'モジュール';
$lang->block->title = 'ユニット名';
$lang->block->source = 'ソースモジュール';
$lang->block->block = 'ソースユニット';
$lang->block->order = 'ソート';
$lang->block->height = '高度';
$lang->block->role = '役割';

$lang->block->lblModule = 'モジュール';
$lang->block->lblBlock = 'ユニット';
$lang->block->lblNum = '件数';
$lang->block->lblHtml = 'HTML内容';
$lang->block->dynamic = 'アクティビティ';
$lang->block->assignToMe = '自分担当';
$lang->block->lblFlowchart = 'フロー';
$lang->block->welcome = 'ようこそ';
$lang->block->lblTesttask = 'テスト詳細表示';

$lang->block->leftToday = '状況';
$lang->block->myTask = 'タスク';
$lang->block->myStory = $lang->storyCommon;
$lang->block->myBug = 'バグ';
$lang->block->myProject = " $lang->projectCommon";
$lang->block->myProduct = $lang->productCommon;
$lang->block->delayed = '延期';
$lang->block->noData = '情報がありません。';
$lang->block->emptyTip = '情報がありません。';

$lang->block->params = new stdclass();
$lang->block->params->name = 'パラメータ名';
$lang->block->params->value = 'パラメータ値';

$lang->block->createBlock = '追加';
$lang->block->editBlock = '編集';
$lang->block->ordersSaved = 'ソート保存済';
$lang->block->confirmRemoveBlock = 'ユニットを非表示してもよろしいですか？';
$lang->block->noticeNewBlock = '10.0バージョン後で各ビューのホームページには新しいビューを提供しました、新しいビューレイアウトを表示しますか？';
$lang->block->confirmReset = 'デフォルトのレイアウトに戻しますか？';
$lang->block->closeForever = 'クローズ';
$lang->block->confirmClose = 'ユニットをクローズしてもよろしいですか？クローズ済みのユニットが使用できません。設定⇒カスタマイズ⇒ユニットで復元できます。';
$lang->block->remove = '除去';
$lang->block->refresh = '更新';
$lang->block->nbsp = '';
$lang->block->hidden = '非表示';
$lang->block->dynamicInfo = "<span class='timeline-tag'>%s</span> <span class='timeline-text'>%s <em>%s</em> %s <a href='%s' title='%s'>%s</a></span>";

$lang->block->default['product']['1']['title'] = $lang->productCommon . '状況';
$lang->block->default['product']['1']['block'] = 'statistic';
$lang->block->default['product']['1']['grid'] = '8';

$lang->block->default['product']['1']['params']['type'] = 'all';
$lang->block->default['product']['1']['params']['num'] = '20';

$lang->block->default['product']['2']['title'] = '全' . $lang->productCommon;
$lang->block->default['product']['2']['block'] = 'overview';
$lang->block->default['product']['2']['grid'] = '4';

$lang->block->default['product']['3']['title'] = '進行中' . $lang->productCommon;
$lang->block->default['product']['3']['block'] = 'list';
$lang->block->default['product']['3']['grid'] = '8';

$lang->block->default['product']['3']['params']['num'] = '15';
$lang->block->default['product']['3']['params']['type'] = 'noclosed';

$lang->block->default['product']['4']['title'] = '担当' . $lang->storyCommon;
$lang->block->default['product']['4']['block'] = 'story';
$lang->block->default['product']['4']['grid'] = '4';

$lang->block->default['product']['4']['params']['num'] = '15';
$lang->block->default['product']['4']['params']['orderBy'] = 'id_desc';
$lang->block->default['product']['4']['params']['type'] = 'assignedTo';

$lang->block->default['project']['1']['title'] = $lang->projectCommon . '状況';
$lang->block->default['project']['1']['block'] = 'statistic';
$lang->block->default['project']['1']['grid'] = '8';

$lang->block->default['project']['1']['params']['type'] = 'all';
$lang->block->default['project']['1']['params']['num'] = '20';

$lang->block->default['project']['2']['title'] = '全' . $lang->projectCommon;
$lang->block->default['project']['2']['block'] = 'overview';
$lang->block->default['project']['2']['grid'] = '4';

$lang->block->default['project']['3']['title'] = '進行中' . $lang->projectCommon;
$lang->block->default['project']['3']['block'] = 'list';
$lang->block->default['project']['3']['grid'] = '8';

$lang->block->default['project']['3']['params']['num'] = '15';
$lang->block->default['project']['3']['params']['orderBy'] = 'id_desc';
$lang->block->default['project']['3']['params']['type'] = 'undone';

$lang->block->default['project']['4']['title'] = '担当タスク';
$lang->block->default['project']['4']['block'] = 'task';
$lang->block->default['project']['4']['grid'] = '4';

$lang->block->default['project']['4']['params']['num'] = '15';
$lang->block->default['project']['4']['params']['orderBy'] = 'id_desc';
$lang->block->default['project']['4']['params']['type'] = 'assignedTo';

$lang->block->default['qa']['1']['title'] = 'テスト統計';
$lang->block->default['qa']['1']['block'] = 'statistic';
$lang->block->default['qa']['1']['grid'] = '8';

$lang->block->default['qa']['1']['params']['type'] = 'noclosed';
$lang->block->default['qa']['1']['params']['num'] = '20';

//$lang->block->default['qa']['2']['title'] = '测试用例总览';
//$lang->block->default['qa']['2']['block'] = 'overview';
//$lang->block->default['qa']['2']['grid']  = 4;

$lang->block->default['qa']['2']['title'] = 'バグ';
$lang->block->default['qa']['2']['block'] = 'bug';
$lang->block->default['qa']['2']['grid'] = '4';

$lang->block->default['qa']['2']['params']['num'] = '15';
$lang->block->default['qa']['2']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['2']['params']['type'] = 'assignedTo';

$lang->block->default['qa']['3']['title'] = '担当ケース';
$lang->block->default['qa']['3']['block'] = 'case';
$lang->block->default['qa']['3']['grid'] = '4';

$lang->block->default['qa']['3']['params']['num'] = '15';
$lang->block->default['qa']['3']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['3']['params']['type'] = 'assigntome';

$lang->block->default['qa']['4']['title'] = 'テスト待ちバージョンリスト';
$lang->block->default['qa']['4']['block'] = 'testtask';
$lang->block->default['qa']['4']['grid'] = '8';

$lang->block->default['qa']['4']['params']['num'] = '15';
$lang->block->default['qa']['4']['params']['orderBy'] = 'id_desc';
$lang->block->default['qa']['4']['params']['type'] = 'wait';

$lang->block->default['full']['my']['1']['title'] = 'ようこそ';
$lang->block->default['full']['my']['1']['block'] = 'welcome';
$lang->block->default['full']['my']['1']['grid'] = '8';
$lang->block->default['full']['my']['1']['source'] = '';
$lang->block->default['full']['my']['2']['title'] = '最新アクティビティ';
$lang->block->default['full']['my']['2']['block'] = 'dynamic';
$lang->block->default['full']['my']['2']['grid'] = '4';
$lang->block->default['full']['my']['2']['source'] = '';
$lang->block->default['full']['my']['3']['title'] = 'フロー';
$lang->block->default['full']['my']['3']['block'] = 'flowchart';
$lang->block->default['full']['my']['3']['grid'] = '8';
$lang->block->default['full']['my']['3']['source'] = '';
$lang->block->default['full']['my']['4']['title'] = 'ToDo';
$lang->block->default['full']['my']['4']['block'] = 'list';
$lang->block->default['full']['my']['4']['grid'] = '4';
$lang->block->default['full']['my']['4']['source'] = 'todo';
$lang->block->default['full']['my']['4']['params']['num'] = '20';
$lang->block->default['full']['my']['5'] = $lang->block->default['project']['1'];
$lang->block->default['full']['my']['5']['source'] = 'project';
$lang->block->default['full']['my']['6'] = $lang->block->default['project']['2'];
$lang->block->default['full']['my']['6']['source'] = 'project';
$lang->block->default['full']['my']['7'] = $lang->block->default['product']['1'];
$lang->block->default['full']['my']['7']['source'] = 'product';
$lang->block->default['full']['my']['8'] = $lang->block->default['product']['2'];
$lang->block->default['full']['my']['8']['source'] = 'product';
$lang->block->default['full']['my']['9'] = $lang->block->default['qa']['2'];
$lang->block->default['full']['my']['9']['source'] = 'qa';

$lang->block->default['onlyTest']['my']['1'] = $lang->block->default['qa']['1'];
$lang->block->default['onlyTest']['my']['1']['source'] = 'qa';
$lang->block->default['onlyTest']['my']['1']['grid'] = '8';
$lang->block->default['onlyTest']['my']['2']['title'] = '最新アクティビティ';
$lang->block->default['onlyTest']['my']['2']['block'] = 'dynamic';
$lang->block->default['onlyTest']['my']['2']['grid'] = '4';
$lang->block->default['onlyTest']['my']['2']['source'] = '';
$lang->block->default['onlyTest']['my']['3']['title'] = 'ToDo';
$lang->block->default['onlyTest']['my']['3']['block'] = 'list';
$lang->block->default['onlyTest']['my']['3']['grid'] = '6';
$lang->block->default['onlyTest']['my']['3']['source'] = 'todo';
$lang->block->default['onlyTest']['my']['3']['params']['num'] = '20';
$lang->block->default['onlyTest']['my']['4'] = $lang->block->default['qa']['2'];
$lang->block->default['onlyTest']['my']['4']['source'] = 'qa';
$lang->block->default['onlyTest']['my']['4']['grid'] = '6';

$lang->block->default['onlyStory']['my']['1'] = $lang->block->default['product']['1'];
$lang->block->default['onlyStory']['my']['1']['source'] = 'product';
$lang->block->default['onlyStory']['my']['1']['grid'] = '8';
$lang->block->default['onlyStory']['my']['2']['title'] = '最新アクティビティ';
$lang->block->default['onlyStory']['my']['2']['block'] = 'dynamic';
$lang->block->default['onlyStory']['my']['2']['grid'] = '4';
$lang->block->default['onlyStory']['my']['2']['source'] = '';
$lang->block->default['onlyStory']['my']['3']['title'] = 'ToDo';
$lang->block->default['onlyStory']['my']['3']['block'] = 'list';
$lang->block->default['onlyStory']['my']['3']['grid'] = '6';
$lang->block->default['onlyStory']['my']['3']['source'] = 'todo';
$lang->block->default['onlyStory']['my']['3']['params']['num'] = '20';
$lang->block->default['onlyStory']['my']['4'] = $lang->block->default['product']['2'];
$lang->block->default['onlyStory']['my']['4']['source'] = 'product';
$lang->block->default['onlyStory']['my']['4']['grid'] = '4';

$lang->block->default['onlyTask']['my']['1'] = $lang->block->default['project']['1'];
$lang->block->default['onlyTask']['my']['1']['source'] = 'project';
$lang->block->default['onlyTask']['my']['1']['grid'] = '8';
$lang->block->default['onlyTask']['my']['2']['title'] = '最新アクティビティ';
$lang->block->default['onlyTask']['my']['2']['block'] = 'dynamic';
$lang->block->default['onlyTask']['my']['2']['grid'] = '4';
$lang->block->default['onlyTask']['my']['2']['source'] = '';
$lang->block->default['onlyTask']['my']['3']['title'] = 'ToDo';
$lang->block->default['onlyTask']['my']['3']['block'] = 'list';
$lang->block->default['onlyTask']['my']['3']['grid'] = '6';
$lang->block->default['onlyTask']['my']['3']['source'] = 'todo';
$lang->block->default['onlyTask']['my']['3']['params']['num'] = '20';
$lang->block->default['onlyTask']['my']['4'] = $lang->block->default['project']['2'];
$lang->block->default['onlyTask']['my']['4']['source'] = 'project';
$lang->block->default['onlyTask']['my']['4']['grid'] = '6';

$lang->block->num = '数';
$lang->block->type = 'タイプ';
$lang->block->orderBy = 'ソート';

$lang->block->availableBlocks = new stdclass();

$lang->block->availableBlocks->todo = 'ToDo';
$lang->block->availableBlocks->task = 'タスク';
$lang->block->availableBlocks->bug = 'バグ';
$lang->block->availableBlocks->case = 'ケース';
$lang->block->availableBlocks->story = $lang->storyCommon;
$lang->block->availableBlocks->product = $lang->productCommon . 'リスト';
$lang->block->availableBlocks->project = $lang->projectCommon . 'リスト';
$lang->block->availableBlocks->plan = 'プランリスト';
$lang->block->availableBlocks->release = 'リリースリスト';
$lang->block->availableBlocks->build = 'バージョンリスト';
$lang->block->availableBlocks->testtask = 'テストバージョンリスト';

$lang->block->moduleList['product'] = $lang->productCommon;
$lang->block->moduleList['project'] = $lang->projectCommon;
$lang->block->moduleList['qa'] = 'テスト';
$lang->block->moduleList['todo'] = 'ToDo';

$lang->block->modules['product'] = new stdclass();
$lang->block->modules['product']->availableBlocks = new stdclass();
$lang->block->modules['product']->availableBlocks->statistic = $lang->productCommon . '状況';
$lang->block->modules['product']->availableBlocks->overview = '全' . $lang->productCommon;
$lang->block->modules['product']->availableBlocks->list = $lang->productCommon . 'リスト';
$lang->block->modules['product']->availableBlocks->story = $lang->storyCommon . 'リスト';
$lang->block->modules['product']->availableBlocks->plan = 'プランリスト';
$lang->block->modules['product']->availableBlocks->release = 'リリースリスト';
$lang->block->modules['project'] = new stdclass();
$lang->block->modules['project']->availableBlocks = new stdclass();
$lang->block->modules['project']->availableBlocks->statistic = $lang->productCommon . '状況';
$lang->block->modules['project']->availableBlocks->overview = '全' . $lang->productCommon;
$lang->block->modules['project']->availableBlocks->list = $lang->productCommon . 'リスト';
$lang->block->modules['project']->availableBlocks->task = 'タスクリスト';
$lang->block->modules['project']->availableBlocks->build = 'ビルドリスト';
$lang->block->modules['qa'] = new stdclass();
$lang->block->modules['qa']->availableBlocks = new stdclass();
$lang->block->modules['qa']->availableBlocks->statistic = 'テスト統計';
//$lang->block->modules['qa']->availableBlocks->overview  = '测试用例总览';
$lang->block->modules['qa']->availableBlocks->bug = 'バグリスト';
$lang->block->modules['qa']->availableBlocks->case = 'ケースリスト';
$lang->block->modules['qa']->availableBlocks->testtask = 'バージョンリスト';
$lang->block->modules['todo'] = new stdclass();
$lang->block->modules['todo']->availableBlocks = new stdclass();
$lang->block->modules['todo']->availableBlocks->list = 'ToDoリスト';

$lang->block->orderByList = new stdclass();

$lang->block->orderByList->product = array();
$lang->block->orderByList->product['id_asc'] = 'ID 昇順';
$lang->block->orderByList->product['id_desc'] = 'IＤ 降順';
$lang->block->orderByList->product['status_asc'] = 'ステータス昇順';
$lang->block->orderByList->product['status_desc'] = 'ステータス降順';

$lang->block->orderByList->project = array();
$lang->block->orderByList->project['id_asc'] = 'ID 昇順';
$lang->block->orderByList->project['id_desc'] = 'IＤ 降順';
$lang->block->orderByList->project['status_asc'] = 'ステータス昇順';
$lang->block->orderByList->project['status_desc'] = 'ステータス降順';

$lang->block->orderByList->task = array();
$lang->block->orderByList->task['id_asc'] = 'ID 昇順';
$lang->block->orderByList->task['id_desc'] = 'IＤ 降順';
$lang->block->orderByList->task['pri_asc'] = '優先度昇順';
$lang->block->orderByList->task['pri_desc'] = '優先度降順';
$lang->block->orderByList->task['estimate_asc'] = '予定時間昇順';
$lang->block->orderByList->task['estimate_desc'] = '予定時間降順';
$lang->block->orderByList->task['status_asc'] = 'ステータス昇順';
$lang->block->orderByList->task['status_desc'] = 'ステータス降順';
$lang->block->orderByList->task['deadline_asc'] = '締切日昇順';
$lang->block->orderByList->task['deadline_desc'] = '締切日降順';

$lang->block->orderByList->bug = array();
$lang->block->orderByList->bug['id_asc'] = 'ID 昇順';
$lang->block->orderByList->bug['id_desc'] = 'IＤ 降順';
$lang->block->orderByList->bug['pri_asc'] = '優先度昇順';
$lang->block->orderByList->bug['pri_desc'] = '優先度递减';
$lang->block->orderByList->bug['severity_asc'] = 'レベル昇順';
$lang->block->orderByList->bug['severity_desc'] = 'レベル降順';

$lang->block->orderByList->case = array();
$lang->block->orderByList->case['id_asc'] = 'ID 昇順';
$lang->block->orderByList->case['id_desc'] = 'IＤ 递减';
$lang->block->orderByList->case['pri_asc'] = '優先度昇順';
$lang->block->orderByList->case['pri_desc'] = '優先度递减';

$lang->block->orderByList->story = array();
$lang->block->orderByList->story['id_asc'] = 'ID 昇順';
$lang->block->orderByList->story['id_desc'] = 'IＤ降順';
$lang->block->orderByList->story['pri_asc'] = '優先度昇順';
$lang->block->orderByList->story['pri_desc'] = '優先度递减';
$lang->block->orderByList->story['status_asc'] = 'ステータス昇順';
$lang->block->orderByList->story['status_desc'] = 'ステータス递减';
$lang->block->orderByList->story['stage_asc'] = 'フェーズ昇順';
$lang->block->orderByList->story['stage_desc'] = 'フェーズ递减';

$lang->block->todoNum = 'ToDo数';
$lang->block->taskNum = 'タスク数';
$lang->block->bugNum = 'バグ数';

$lang->block->typeList = new stdclass();

$lang->block->typeList->task['assignedTo'] = '担当タスク';
$lang->block->typeList->task['openedBy'] = '新規';
$lang->block->typeList->task['finishedBy'] = '完了';
$lang->block->typeList->task['closedBy'] = 'クローズ';
$lang->block->typeList->task['canceledBy'] = 'キャンセル';

$lang->block->typeList->bug['assignedTo'] = '担当バグ';
$lang->block->typeList->bug['openedBy'] = '新規';
$lang->block->typeList->bug['resolvedBy'] = '処理';
$lang->block->typeList->bug['closedBy'] = 'クローズ';

$lang->block->typeList->case['assigntome'] = '担当ケース';
$lang->block->typeList->case['openedbyme'] = '新規';

$lang->block->typeList->story['assignedTo'] = '担当' . $lang->storyCommon;
$lang->block->typeList->story['openedBy'] = '新規';
$lang->block->typeList->story['reviewedBy'] = '承認';
$lang->block->typeList->story['closedBy'] = 'クローズ';

$lang->block->typeList->product['noclosed'] = '進行中';
$lang->block->typeList->product['closed'] = 'クローズ済';
$lang->block->typeList->product['all'] = '全部';
$lang->block->typeList->product['involved'] = '私参加の';

$lang->block->typeList->project['undone'] = '未完了';
$lang->block->typeList->project['doing'] = '進行中';
$lang->block->typeList->project['all'] = '全部';
$lang->block->typeList->project['involved'] = '参加';

$lang->block->typeList->testtask['wait'] = 'テスト待ちバージョン';
$lang->block->typeList->testtask['doing'] = 'テスト中バージョン';
$lang->block->typeList->testtask['blocked'] = 'ブロックバージョン';
$lang->block->typeList->testtask['done'] = 'テスト済バージョン';
$lang->block->typeList->testtask['all'] = '全部';

$lang->block->modules['product']->moreLinkList = new stdclass();
$lang->block->modules['product']->moreLinkList->list = 'product|all|product=&line=0&status=%s';
$lang->block->modules['product']->moreLinkList->story = 'my|story|type=%s';

$lang->block->modules['project']->moreLinkList = new stdclass();
$lang->block->modules['project']->moreLinkList->list = 'project|all|status=%s&project=';
$lang->block->modules['project']->moreLinkList->task = 'my|task|type=%s';

$lang->block->modules['qa']->moreLinkList = new stdclass();
$lang->block->modules['qa']->moreLinkList->bug = 'my|bug|type=%s';
$lang->block->modules['qa']->moreLinkList->case = 'my|testcase|type=%s';
$lang->block->modules['qa']->moreLinkList->testtask = 'testtask|browse|type=%s';

$lang->block->modules['todo']->moreLinkList = new stdclass();
$lang->block->modules['todo']->moreLinkList->list = 'my|todo|type=all';

$lang->block->modules['common'] = new stdclass();
$lang->block->modules['common']->moreLinkList = new stdclass();
$lang->block->modules['common']->moreLinkList->dynamic = 'company|dynamic|';

$lang->block->welcomeList['06:00'] = 'Hello  %s';
$lang->block->welcomeList['11:30'] = 'Hello  %s';
$lang->block->welcomeList['13:30'] = 'Hello  %s';
$lang->block->welcomeList['19:00'] = 'Hello  %s';

$lang->block->gridOptions[8] = '左側';
$lang->block->gridOptions[4] = '右側';

$lang->block->flowchart = array();
$lang->block->flowchart['admin'] = array('管理者', '会社情報管理', 'ユーザ追加', '権限管理');
$lang->block->flowchart['product'] = array('プロダクト担当', 'プロダクト作成', 'モジュール管理', '計画管理', $lang->storyCommon . '管理', 'リリース');
$lang->block->flowchart['project'] = array('プロジェクト担当', 'プロジェクト作成', 'チーム管理', 'プロダクト紐付け', $lang->storyCommon . '紐付け', 'タスク振分');
$lang->block->flowchart['dev'] = array('開発担当', 'タスク、バグ取得', 'ステータス更新', 'タスク、バグ完成');
$lang->block->flowchart['tester'] = array('テスト担当', 'ケース入力', 'ケース実行', 'バグ提出', 'バグ検証', 'バグクローズ');
