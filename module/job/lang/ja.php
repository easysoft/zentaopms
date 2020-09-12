<?php
$lang->job->common        = '任務';
$lang->job->browse        = '閲覧任務';
$lang->job->create        = '新規任務';
$lang->job->edit          = '編集任務';
$lang->job->exec          = '実行';
$lang->job->view          = '実行詳細';
$lang->job->delete        = '削除任務';
$lang->job->confirmDelete = 'この任務の削除を確認します.';
$lang->job->dirChange     = 'カタログ変更';
$lang->job->buildTag      = '新規ラベル';

$lang->job->id          = 'ID';
$lang->job->name        = '名称';
$lang->job->repo        = 'コードベース.';
$lang->job->product     = '連関' . $lang->productCommon;
$lang->job->svnDir      = 'SVN監視パス';
$lang->job->jenkins     = 'Jenkins';
$lang->job->jkHost      = 'Jenkinsサーバ';
$lang->job->buildType   = '構築されたタイプ';
$lang->job->jkJob       = 'Jenkins任務';
$lang->job->frame       = 'ツール/フレーム';
$lang->job->triggerType = 'トリガ方式';
$lang->job->atDay       = 'カスタム日付.';
$lang->job->atTime      = '実行時間';
$lang->job->lastStatus  = '最後の実行状態';
$lang->job->lastExec    = '最後の実行時間';
$lang->job->comment     = 'キーワードにマッチします';

$lang->job->lblBasic = '基本情報';

$lang->job->example    = '例';
$lang->job->commitEx   = "マッチング作成構築タスクのためのキーワードは、複数のキーワードを','で分割します.";
$lang->job->cronSample = '0 0 2 * * 2-6/1 のように、平日午前2時を表します';
$lang->job->sendExec   = '送信実行要求成功！実行結果：%s';

$lang->job->buildTypeList['build']          = '構築するだけで';
$lang->job->buildTypeList['buildAndDeploy'] = '構築し配置します';
$lang->job->buildTypeList['buildAndTest']   = '構築しテストします';

$lang->job->triggerTypeList['tag']      = 'ラベル';
$lang->job->triggerTypeList['commit']   = 'キーワードを提出します';
$lang->job->triggerTypeList['schedule'] = '計画';

$lang->job->frameList['']        = '';
$lang->job->frameList['junit']   = 'JUnit';
$lang->job->frameList['testng']  = 'TestNG';
$lang->job->frameList['phpunit'] = 'PHPUnit';
$lang->job->frameList['pytest']  = 'Pytest';
$lang->job->frameList['jtest']   = 'JTest';
$lang->job->frameList['cppunit'] = 'CppUnit';
$lang->job->frameList['gtest']   = 'GTest';
$lang->job->frameList['qtest']   = 'QTest';
