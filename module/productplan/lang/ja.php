<?php
/**
 * The productplan module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      admin
 * @package     productplan
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->productplan->common = $lang->productCommon . 'プラン';
$lang->productplan->browse = 'プラン閲覧';
$lang->productplan->index = 'プランリスト';
$lang->productplan->create = '新規';
$lang->productplan->edit = 'プラン編集';
$lang->productplan->delete = 'プラン削除';
$lang->productplan->view = 'プラン詳細';
$lang->productplan->bugSummary = '共に<strong>%s</strong>個のバグがあります';
$lang->productplan->basicInfo = '基本情報';
$lang->productplan->batchEdit = '一括編集';

$lang->productplan->batchUnlink = '一括除去';
$lang->productplan->linkStory = $lang->storyCommon . '紐付け';
$lang->productplan->unlinkStory = $lang->storyCommon . '除去';
$lang->productplan->unlinkStoryAB = '除去';
$lang->productplan->batchUnlinkStory = $lang->storyCommon . '一括除去';
$lang->productplan->linkedStories = $lang->storyCommon;
$lang->productplan->unlinkedStories = $lang->storyCommon . 'と紐付けません';
$lang->productplan->updateOrder = 'ソート';
$lang->productplan->createChildren = '子プラン新規';

$lang->productplan->linkBug = 'バグ紐付け';
$lang->productplan->unlinkBug = 'バグ除去';
$lang->productplan->batchUnlinkBug = 'バグ一括除去';
$lang->productplan->linkedBugs = 'バグ';
$lang->productplan->unlinkedBugs = 'バグと紐付けません';
$lang->productplan->unexpired = '期限内プラン';
$lang->productplan->all = '全プラン';

$lang->productplan->confirmDelete = '当該プランを削除してもよろしいですか？';
$lang->productplan->confirmUnlinkStory = "当該{$lang->storyCommon}を除去してもよろしいですか？";
$lang->productplan->confirmUnlinkBug = '当該バグを除去してもよろしいですか？';
$lang->productplan->noPlan = 'プランがありません。';
$lang->productplan->cannotDeleteParent = '不能删除父计划';

$lang->productplan->id = '番号';
$lang->productplan->product = $lang->productCommon;
$lang->productplan->branch = 'プラットフォーム/ブランチ';
$lang->productplan->title = 'プラン名';
$lang->productplan->desc = '説明';
$lang->productplan->begin = '開始日';
$lang->productplan->end = '終了日';
$lang->productplan->last = '前のプラン';
$lang->productplan->future = '未定';
$lang->productplan->stories = $lang->storyCommon . '数';
$lang->productplan->bugs = 'バグ数';
$lang->productplan->hour = '時間';
$lang->productplan->project = $lang->projectCommon;
$lang->productplan->parent = '親プラン';
$lang->productplan->parentAB = '父';
$lang->productplan->children = '子プラン';
$lang->productplan->childrenAB = '子';
$lang->productplan->order = '排序';
$lang->productplan->deleted = '已删除';

$lang->productplan->endList[7] = '1 週間';
$lang->productplan->endList[14] = '2 週間';
$lang->productplan->endList[31] = '1 か月';
$lang->productplan->endList[62] = '2 か月';
$lang->productplan->endList[93] = '3 か月';
$lang->productplan->endList[186] = '半年';
$lang->productplan->endList[365] = '1 年';

$lang->productplan->errorNoTitle = 'ID %s タイトルを入力してください';
$lang->productplan->errorNoBegin = 'ID %s 開始時間を入力してください';
$lang->productplan->errorNoEnd = 'ID %s 終了時間を入力してください';
$lang->productplan->beginGeEnd = 'ID %s 開始時間は終了時間を超えることができません';

$lang->productplan->featureBar['browse']['all'] = '全て';
$lang->productplan->featureBar['browse']['unexpired'] = '期限内';
$lang->productplan->featureBar['browse']['overdue'] = '期限切れ';
