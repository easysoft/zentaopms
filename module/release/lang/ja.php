<?php
/**
 * The release module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: zh-cn.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->release->common = 'リリース';
$lang->release->create = '新規';
$lang->release->edit = '編集';
$lang->release->linkStory = $lang->storyCommon . '紐付け';
$lang->release->linkBug = 'バグ紐付け';
$lang->release->delete = 'リリース削除';
$lang->release->deleted = '削除';
$lang->release->view = 'リリース詳細';
$lang->release->browse = 'リリース閲覧';
$lang->release->changeStatus = 'ステータス更新';
$lang->release->batchUnlink = '一括除去';
$lang->release->batchUnlinkStory = $lang->storyCommon . '一括除去';
$lang->release->batchUnlinkBug = 'バグ一括除去';

$lang->release->confirmDelete = '当該リリースを削除してもよろしいですか？';
$lang->release->confirmUnlinkStory = "当該{$lang->storyCommon}を除去してもよろしいですか？";
$lang->release->confirmUnlinkBug = '当該バグを除去してもよろしいですか？';
$lang->release->existBuild = '「バージョン」は既に「%s」のレコードがあります。「リリース名」を更新または一つ「バージョン」を選択することができます。';
$lang->release->noRelease = 'リリースがありません。';
$lang->release->errorDate = '发布日期不能大于今天。';

$lang->release->basicInfo = '基本情報';

$lang->release->id = 'ID';
$lang->release->product = $lang->productCommon;
$lang->release->branch = 'プラットフォーム/ブランチ';
$lang->release->build = 'バージョン';
$lang->release->name = 'リリース名';
$lang->release->marker = 'マイルストーン';
$lang->release->date = 'リリース日';
$lang->release->desc = '説明';
$lang->release->status = 'ステータス';
$lang->release->subStatus = '子状态';
$lang->release->last = '前のリリース';
$lang->release->unlinkStory = $lang->storyCommon . '除去';
$lang->release->unlinkBug = 'バグ除去';
$lang->release->stories = '完了した' . $lang->storyCommon;
$lang->release->bugs = '処理済バグ';
$lang->release->leftBugs = '残りのバグ';
$lang->release->generatedBugs = '処理待ちバグ';
$lang->release->finishStories = "今回は %s 個{$lang->storyCommon}を完了しました";
$lang->release->resolvedBugs = '今回は %s 個バグを処理しました';
$lang->release->createdBugs = '今回は %s 個バグを残しました';
$lang->release->export = 'HTMLエクスポート';
$lang->release->yesterday = '昨日のリリース';
$lang->release->all = '所有';

$lang->release->filePath = 'ダウンロードアドレス：';
$lang->release->scmPath = 'バージョンライブラリアドレス：';

$lang->release->exportTypeList['all'] = '全て';
$lang->release->exportTypeList['story'] = $lang->storyCommon;
$lang->release->exportTypeList['bug'] = 'バグ';
$lang->release->exportTypeList['leftbug'] = '処理待ちバグ';

$lang->release->statusList[''] = '';
$lang->release->statusList['normal'] = '正常';
$lang->release->statusList['terminate'] = '保守停止';

$lang->release->changeStatusList['normal'] = 'アクティブ';
$lang->release->changeStatusList['terminate'] = '保守停止';

$lang->release->action = new stdclass();
$lang->release->action->changestatus = array('main' => '$date、 <strong>$actor</strong> より $extra。', 'extra' => 'changeStatusList');
