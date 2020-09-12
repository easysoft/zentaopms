<?php
$lang->backup->common = 'バックアップ';
$lang->backup->index = 'バックアップトップページ';
$lang->backup->history = 'バックアップ履歴';
$lang->backup->delete = 'バックアップ削除';
$lang->backup->backup = 'バックアップ開始';
$lang->backup->restore = '復元';
$lang->backup->change = '時間保持';
$lang->backup->changeAB = '更新';
$lang->backup->rmPHPHeader = 'セキュリティ設定除去';

$lang->backup->time = 'バックアップ時間';
$lang->backup->files = 'バックアップファイル';
$lang->backup->allCount = '总文件数';
$lang->backup->count    = '备份文件数';
$lang->backup->size = 'サイズ';
$lang->backup->status   = '状态';

$lang->backup->statusList['success'] = '成功';
$lang->backup->statusList['fail']    = '失败';

$lang->backup->setting = '設定';
$lang->backup->settingDir = 'バックアップディレクトリー';
$lang->backup->settingList['nofile'] = '添付ファイルとコードをバックアップしない';
$lang->backup->settingList['nosafe'] = 'PHPファイルヘッダーのダウンロードの防備が要りません';

$lang->backup->waitting = '<span id="backupType"></span>今処理中、少し待ってください…';
$lang->backup->progressSQL = '<p>SQLバックアップ中、バックアップした内容：%s</p>';
$lang->backup->progressAttach = '<p>SQLバックアップ終了しました。</p><p>添付ファイルバックアップ中、バックアップした内容：%s</p>';
$lang->backup->progressCode = '<p>SQLバックアップ終了しました。</p><p>添付ファイルバックアップ終了しました。</p><p>コードバックアップ中、バックアップした内容：%s</p>';
$lang->backup->confirmDelete = 'バックアップを削除しますか？';
$lang->backup->confirmRestore = '当該バックアップを復元しますか？';
$lang->backup->holdDays = '最近 %s 天のバックアップを保持します';
$lang->backup->copiedFail      = '复制失败的文件：';
$lang->backup->restoreTip = '復元機能は添付ファイルとデータベースのみ復元できます。もしコードの復元が必要としたら、手動で復元してください。';

$lang->backup->success = new stdclass();
$lang->backup->success->backup = 'バックアップ成功！';
$lang->backup->success->restore = '復元成功！';

$lang->backup->error = new stdclass();
$lang->backup->error->noCreateDir = 'バックアップディレクトリーが存在していません、このディレクトリーの作成もできません';
$lang->backup->error->noWritable = '<code>%s</code> は編集できません！このディレクトリーの権限をチェックしてください、そうしないとバックアップできません。';
$lang->backup->error->noDelete = 'ファイル %s は削除できません、権限更新あるいは手動で削除してください。';
$lang->backup->error->restoreSQL = 'データベース復元に失敗しました、エラー：%s';
$lang->backup->error->restoreFile = '添付ファイル復元に失敗しました、エラー：%s';
$lang->backup->error->backupFile = '添付ファイルバックアップに失敗しました、エラー：%s';
$lang->backup->error->backupCode = 'コードバックアップに失敗しました、エラー：%s';
