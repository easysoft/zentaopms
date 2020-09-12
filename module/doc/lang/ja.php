<?php
/**
 * The doc module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      wangguannan zengqingyang
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
/* 字段列表。*/
$lang->doc->common = '資料';
$lang->doc->id = '番号';
$lang->doc->product = $lang->productCommon;
$lang->doc->project = $lang->projectCommon;
$lang->doc->lib = '資料ライブラリ';
$lang->doc->module = '分類';
$lang->doc->title = 'タイトル';
$lang->doc->digest = '概要';
$lang->doc->comment = '備考';
$lang->doc->type = 'タイプ';
$lang->doc->content = '本文';
$lang->doc->keywords = 'キーワード';
$lang->doc->url = 'URL';
$lang->doc->files = '添付ファイル';
$lang->doc->addedBy = '作成者';
$lang->doc->addedDate = '追加時間';
$lang->doc->editedBy = '編集者';
$lang->doc->editedDate = '編集時間';
$lang->doc->version = 'バージョン';
$lang->doc->basicInfo = '基本情報';
$lang->doc->deleted = '削除済';
$lang->doc->fileObject = '所属オブジェクト';
$lang->doc->whiteList = 'ホワイトリスト';
$lang->doc->contentType = 'フォーマット';
$lang->doc->separator = "<i class='icon-angle-right'></i>";
$lang->doc->fileTitle = '添付ファイル名';
$lang->doc->filePath = 'アドレス';
$lang->doc->extension = 'タイプ';
$lang->doc->size = 'サイズ';
$lang->doc->download = 'ダウンロード';
$lang->doc->acl = '権限';
$lang->doc->fileName = '添付ファイル';
$lang->doc->groups = 'グルーピング';
$lang->doc->users = 'ユーザ';
$lang->doc->item = '項';
$lang->doc->num = '資料数';
$lang->doc->searchResult = '検索結果';

$lang->doc->moduleDoc = 'モジュール毎に閲覧';
$lang->doc->searchDoc = '検索';
$lang->doc->fast = 'クイックアクセス';
$lang->doc->allDoc = '全資料';
$lang->doc->openedByMe = '新規';
$lang->doc->orderByOpen = '最近追加';
$lang->doc->orderByEdit = '最近更新';
$lang->doc->orderByVisit = '最近アクセス';
$lang->doc->todayEdited = '本日更新';
$lang->doc->pastEdited = '前の資料';
$lang->doc->myDoc = '資料';
$lang->doc->myCollection = 'お気に入り';

/* 方法列表。*/
$lang->doc->index = 'ホーム';
$lang->doc->create = '新規';
$lang->doc->edit = '編集';
$lang->doc->delete = '削除';
$lang->doc->browse = 'リスト';
$lang->doc->view = '詳細';
$lang->doc->diff = '比較';
$lang->doc->diffAction = '資料比較';
$lang->doc->sort = 'ソート';
$lang->doc->manageType = '分類管理';
$lang->doc->editType = '分類編集';
$lang->doc->deleteType = '分類削除';
$lang->doc->addType = '分類追加';
$lang->doc->childType = 'サブ分類';
$lang->doc->collect = 'お気に入り';
$lang->doc->cancelCollection = 'キャンセル';
$lang->doc->deleteFile = '削除';

$lang->doc->libName = 'ライブラリ名';
$lang->doc->libType = 'タイプ';
$lang->doc->custom = 'カスタマイズ';
$lang->doc->customAB = 'ライブラリカスタマイズ';
$lang->doc->createLib = 'ライブラリ新規作成';
$lang->doc->allLibs = 'ライブラリリスト';
$lang->doc->objectLibs = "{$lang->productCommon}/{$lang->projectCommon}ライブラリリスト";
$lang->doc->showFiles = '添付ファイルライブラリ';
$lang->doc->editLib = '編集';
$lang->doc->deleteLib = '削除';
$lang->doc->fixedMenu = 'メニューバーに固定';
$lang->doc->removeMenu = 'メニューバーから除去';
$lang->doc->search = '検索';

/* 查询条件列表 */
$lang->doc->allProduct = '全て' . $lang->productCommon;
$lang->doc->allProject = '全て' . $lang->productCommon;

$lang->doc->libTypeList['product'] = $lang->productCommon;
$lang->doc->libTypeList['project'] = $lang->projectCommon;
$lang->doc->libTypeList['custom'] = 'カスタマイズ';

$lang->doc->libIconList['product'] = 'icon-cube';
$lang->doc->libIconList['project'] = 'icon-stack';
$lang->doc->libIconList['custom'] = 'icon-folder-o';

$lang->doc->systemLibs['product'] = $lang->productCommon;
$lang->doc->systemLibs['project'] = $lang->projectCommon;

global $config;
if($config->global->flow == 'onlyStory' or $config->global->flow == 'onlyTest') unset($lang->doc->systemLibs['project']);
if($config->global->flow == 'onlyStory' or $config->global->flow == 'onlyTest') unset($lang->doc->libTypeList['project']);
if($config->global->flow == 'onlyTask')  unset($lang->doc->systemLibs['product']);
if($config->global->flow == 'onlyTask')  unset($lang->doc->libTypeList['product']);

$lang->doc->aclList['open'] = 'パブリック';
$lang->doc->aclList['custom'] = 'カスタマイズ';
$lang->doc->aclList['private'] = 'プライベート';

$lang->doc->typeList['html'] = 'リッチテキスト';
$lang->doc->typeList['markdown'] = 'Markdown';
$lang->doc->typeList['url'] = 'リンク';
$lang->doc->typeList['word'] = 'Word';
$lang->doc->typeList['ppt'] = 'PPT';
$lang->doc->typeList['excel'] = 'Excel';

$lang->doc->types['text'] = '資料';
$lang->doc->types['url'] = 'リンク';

$lang->doc->contentTypeList['html'] = 'HTML';
$lang->doc->contentTypeList['markdown'] = 'MarkDown';

$lang->doc->browseType = '閲覧方式';
$lang->doc->browseTypeList['list'] = 'リスト';
$lang->doc->browseTypeList['grid'] = 'ディレクトリー';

$lang->doc->fastMenuList['byediteddate'] = '最近更新';
//$lang->doc->fastMenuList['visiteddate']   = '最近访问';
$lang->doc->fastMenuList['openedbyme'] = 'マイ資料';
$lang->doc->fastMenuList['collectedbyme'] = 'お気に入り';

$lang->doc->fastMenuIconList['byediteddate'] = 'icon-folder-upload';
//$lang->doc->fastMenuIconList['visiteddate']   = 'icon-folder-move';
$lang->doc->fastMenuIconList['openedbyme'] = 'icon-folder-account';
$lang->doc->fastMenuIconList['collectedbyme'] = 'icon-folder-star';

$lang->doc->customObjectLibs['files'] = '添付ファイルライブラリ表示';
$lang->doc->customObjectLibs['customFiles'] = 'カスタマイズ資料ライブラリ表示';

$lang->doc->orderLib = '資料ライブラリソート';
$lang->doc->customShowLibs = '表示設定';
$lang->doc->customShowLibsList['zero'] = '空き資料ライブラリ表示';
$lang->doc->customShowLibsList['children'] = 'サブ分類資料表示';
$lang->doc->customShowLibsList['unclosed'] = '未クローズプロジェクトのみ表示';

$lang->doc->confirmDelete = '当該資料を削除してもよろしいですか？';
$lang->doc->confirmDeleteLib = '当該資料ライブラリを削除してもよろしいですか？';
$lang->doc->errorEditSystemDoc = 'システム資料ライブラリ更新不要';
$lang->doc->errorEmptyProduct = "{$lang->productCommon}がありません、資料が作成できません";
$lang->doc->errorEmptyProject = "{$lang->projectCommon}がありません、資料が作成できません";
$lang->doc->errorMainSysLib = '当該システム資料ライブラリが削除できません！';
$lang->doc->accessDenied = 'アクセス権限がありません！';
$lang->doc->versionNotFount = '当該バージョンに資料がありません';
$lang->doc->noDoc = '資料がありません。';
$lang->doc->cannotCreateOffice = '<p>申し訳ございません、企業版のみ%s資料が作成できます。<p><p>企業版をお试したら、弊社へご連絡下さい：03-3537-9700（代表）。</p>';
$lang->doc->notSetOffice       = "<p>创建%s文档，需要配置<a href='%s' target='_parent'>Office转换设置</a>。<p>";
$lang->doc->noSearchedDoc = '資料を検索できませんでした。';
$lang->doc->noEditedDoc = '資料を編集していません。';
$lang->doc->noOpenedDoc = '資料を作成していません。';
$lang->doc->noCollectedDoc = 'お気に入り資料がありません。';

$lang->doc->noticeAcl['lib']['product']['default'] = '選択されたプロダクトのアクセス権限を持ちユーザがアクセスできます。';
$lang->doc->noticeAcl['lib']['product']['custom'] = '選択されたプロダクトのアクセス権限を持ち、或いはホワイトリストのユーザがアクセスできます。';
$lang->doc->noticeAcl['lib']['project']['default'] = '選択されたプロジェクトのアクセス権限を持ちユーザがアクセスできます。';
$lang->doc->noticeAcl['lib']['project']['custom'] = '選択されたプロジェクトのアクセス権限を持ち、或いはホワイトリストのユーザがアクセスできます。';
$lang->doc->noticeAcl['lib']['custom']['open'] = '全てのユーザがアクセスできます。';
$lang->doc->noticeAcl['lib']['custom']['custom'] = 'ホワイトリストのユーザがアクセスできます。';
$lang->doc->noticeAcl['lib']['custom']['private'] = '作成者のみアクセスできます。';

$lang->doc->noticeAcl['doc']['open'] = '当該ファイル所属した資料ライブラリのアクセス権限を持ちユーザがアクセスできます。';
$lang->doc->noticeAcl['doc']['custom'] = 'ホワイトリストのユーザがアクセスできます。';
$lang->doc->noticeAcl['doc']['private'] = '作成者のみアクセスできます。';

$lang->doc->placeholder = new stdclass();
$lang->doc->placeholder->url = '相応なリンクアドレス';

$lang->doclib = new stdclass();
$lang->doclib->name = 'ライブラリ名';
$lang->doclib->control = 'アクセス制御';
$lang->doclib->group = 'グルーピング';
$lang->doclib->user = 'ユーザ';
$lang->doclib->files = '添付ファイルライブラリ';
$lang->doclib->all = '全て資料ライブラリ';
$lang->doclib->select = '資料ライブラリ選択';
$lang->doclib->project = $lang->projectCommon . 'ライブラリ';
$lang->doclib->product = $lang->productCommon . 'ライブラリ';

$lang->doclib->aclListA['default'] = 'デフォルト';
$lang->doclib->aclListA['custom'] = 'カスタマイズ';

$lang->doclib->aclListB['open'] = 'パブリック';
$lang->doclib->aclListB['custom'] = 'カスタマイズ';
$lang->doclib->aclListB['private'] = 'プライベート';

$lang->doclib->create['product'] = '作成' . $lang->productCommon . '資料ライブラリ';
$lang->doclib->create['project'] = '作成' . $lang->projectCommon . '資料ライブラリ';
$lang->doclib->create['custom'] = 'カスタマイズライブラリ新規作成';

$lang->doclib->main['product'] = 'メインライブラリ';
$lang->doclib->main['project'] = 'メインライブラリ';

$lang->doclib->tabList['product'] = $lang->productCommon;
$lang->doclib->tabList['project'] = $lang->projectCommon;
$lang->doclib->tabList['custom'] = 'カスタマイズ';

$lang->doclib->nameList['custom'] = 'カスタマイズライブラリ名';
