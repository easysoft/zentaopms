<?php
/**
 * The build module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      admin
 * @package     build
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->build->common = 'バージョン';
$lang->build->create = 'バージョン作成';
$lang->build->edit = 'バージョン編集';
$lang->build->linkStory = $lang->storyCommon . '紐付け';
$lang->build->linkBug = 'バグ紐付け';
$lang->build->delete = 'バージョン削除';
$lang->build->deleted = '削除';
$lang->build->view = 'バージョン詳細';
$lang->build->batchUnlink = '一括除去';
$lang->build->batchUnlinkStory = $lang->storyCommon . '一括除去';
$lang->build->batchUnlinkBug = 'バグ一括除去';

$lang->build->confirmDelete = '当該バージョンを削除してもよろしいですか？';
$lang->build->confirmUnlinkStory = "当該{$lang->storyCommon}を除去してもよろしいですか？";
$lang->build->confirmUnlinkBug = '当該バグを除去してもよろしいですか？';

$lang->build->basicInfo = '基本情報';

$lang->build->id = 'ID';
$lang->build->product = $lang->productCommon;
$lang->build->branch = 'プラットフォーム/ブランチ';
$lang->build->project = '所属' . $lang->projectCommon;
$lang->build->name = 'バージョン';
$lang->build->date = '圧縮日';
$lang->build->builder = '作成者';
$lang->build->scmPath = 'ソースコードアドレス';
$lang->build->filePath = 'ダウンロードアドレス';
$lang->build->desc = '説明';
$lang->build->files = 'パッケージアップロード';
$lang->build->last = '前バージョン';
$lang->build->packageType = 'パッケージタイプ';
$lang->build->unlinkStory = $lang->storyCommon . '除去';
$lang->build->unlinkBug = 'バグ除去';
$lang->build->stories = '完了の' . $lang->storyCommon;
$lang->build->bugs = '処理済バグ';
$lang->build->generatedBugs = '発生バグ';
$lang->build->noProduct = "<span style='color:red'>当該{$lang->projectCommon}が{$lang->productCommon}と紐付けていませんので、バージョンが作成できません。先に<a href='%s'>{$lang->productCommon}と紐付けてください。</a></span>";
$lang->build->noBuild = 'バージョンがありません';

$lang->build->notice = new stdclass();
$lang->build->notice->changeProduct = '已经关联{$lang->storyCommon}或Bug的版本，不能修改其所属产品';

$lang->build->finishStories = "今回は%s個の{$lang->storyCommon}を完了しました";
$lang->build->resolvedBugs = '今回は%s個のバグを処理しました';
$lang->build->createdBugs = '今回は%s個のバグが発生しました';

$lang->build->placeholder = new stdclass();
$lang->build->placeholder->scmPath = 'ソフトウェアソースコードベース、例えばSubversion、Gitベースアドレスなど';
$lang->build->placeholder->filePath = '当該バージョンのソフトウェアパッケージのダウンロードとストレージアドレス';

$lang->build->action = new stdclass();
$lang->build->action->buildopened = '$date、 <strong>$actor</strong> よりバージョン <strong>$extra</strong>を作成しました。' . "\n";
$lang->backhome = '戻る';
