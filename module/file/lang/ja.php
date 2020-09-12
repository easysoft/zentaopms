<?php
/**
 * The file module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      wangguannan zengqingyang admin wuhongjie
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->file = new stdclass();
$lang->file->common = '添付';
$lang->file->uploadImages = '複数画像アップロード';
$lang->file->download = '添付ファイルダウンロード';
$lang->file->uploadDate = 'アップロード時間：';
$lang->file->edit = '名前変更';
$lang->file->inputFileName = '添付ファイル名を入力してください';
$lang->file->delete = '添付削除';
$lang->file->label = 'タイトル：';
$lang->file->maxUploadSize = '（%s以下）';
$lang->file->applyTemplate = 'アプリケーションテンプレート';
$lang->file->tplTitle = 'テンプレート名';
$lang->file->tplTitleAB = 'テンプレート名';
$lang->file->setPublic = 'パブリックテンプレート設定';
$lang->file->exportFields = 'エクスポートフィールド';
$lang->file->exportRange = 'エクスポートデータ';
$lang->file->defaultTPL = 'デフォルトテンプレート';
$lang->file->setExportTPL = '設定';
$lang->file->preview = 'プレビュー';
$lang->file->addFile = 'ファイル追加';
$lang->file->beginUpload = 'アップロード開始';
$lang->file->uploadSuccess = 'アップロード成功';

$lang->file->pathname = 'パス';
$lang->file->title = 'タイトル';
$lang->file->fileName = 'ファイル名';
$lang->file->untitled = '名なし';
$lang->file->extension = 'ファイルタイプ';
$lang->file->size = 'サイズ';
$lang->file->encoding = 'エンコーディング';
$lang->file->addedBy = '追加者';
$lang->file->addedDate = '追加時間';
$lang->file->downloads = 'ダウンロード回数';
$lang->file->extra = '備考';

$lang->file->dragFile = 'ファイルをここにドラッグしてください';
$lang->file->childTaskTips = "タスク名の前に'>'がつけるのはサブタスクです";
$lang->file->uploadImagesExplain = '説明：jpg、jpeg、gif、pngフォーマットの画像をアップロードしてください。プログラムはファイル名をタイトルとして、画像を内容とします。';
$lang->file->saveAndNext         = '保存并跳转下一页';
$lang->file->importPager         = '共有<strong>%s</strong>条记录，当前第<strong>%s</strong>页，共有<strong>%s</strong>页';
$lang->file->importSummary       = "本次导入共有<strong id='allCount'>%s</strong>条记录，每页导入%s条，需要导入<strong id='times'>%s</strong>次";

$lang->file->errorNotExists = "<span class='text-red'>フォルダ '%s' は存在していません</span>";
$lang->file->errorCanNotWrite = "<span class='text-red'>フォルダ '%s' は編集禁止、フォルダの権限を更新してください。linuxで下記のコマンドを入力してください: <span class='code'>sudo chmod -R 777 %s</span></span>";
$lang->file->confirmDelete = '当該添付を削除してもよろしいですか?';
$lang->file->errorFileSize = 'ファイルのサイズが%sを越えて、アップロードに成功しないの可能性があります！';
$lang->file->errorFileUpload = 'ファイルアップロード失敗、ファイルサイズが制限を越えていました';
$lang->file->errorFileFormate = 'ファイルアップロード失敗、ファイルフォーマットは規定の範囲内にいませんでした';
$lang->file->errorFileMove = 'ファイルアップロード失敗、ファイルを移動する時にエラーが発生しました';
$lang->file->dangerFile = '選択されたファイルはセキュリティリスクがあって、システムはアップロードしません。';
$lang->file->errorSuffix = '圧縮パッケージのフォーマットが間違っています、zip圧縮パッケージのみアップロードできます！';
$lang->file->errorExtract = '解凍に失敗しました！ファイルが破損し、又は圧縮パッケージに不法アップロードファイルがあった可能性があります。';
$lang->file->fileNotFound = '当該ファイルが見つかりませんでした、フィジカルファイルが削除されたかもしれません！';
$lang->file->fileContentEmpty = '上传文件内容为空，请检查后重新上传。';
