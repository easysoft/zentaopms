<?php
$lang->testreport->common = 'テストレポート';
$lang->testreport->browse = 'レポートリスト';
$lang->testreport->create = 'レポート作成';
$lang->testreport->edit = 'レポート編集';
$lang->testreport->delete = 'レポート削除';
$lang->testreport->export = 'エクスポート';
$lang->testreport->exportAction = 'レポートエクスポート';
$lang->testreport->view = 'レポート詳細';
$lang->testreport->recreate = 'レポート再生成';

$lang->testreport->title = 'レポート名';
$lang->testreport->product     = "所属{$lang->productCommon}";
$lang->testreport->bugTitle = 'バグ名';
$lang->testreport->storyTitle = $lang->storyCommon . '名';
$lang->testreport->project = 'プロジェクト';
$lang->testreport->testtask = 'テストバージョン';
$lang->testreport->tasks = $lang->testreport->testtask;
$lang->testreport->startEnd = '開始、終了時間';
$lang->testreport->owner = '担当者';
$lang->testreport->members = 'メンバー';
$lang->testreport->begin = '開始時間';
$lang->testreport->end = '終了時間';
$lang->testreport->stories = 'テストの' . $lang->storyCommon;
$lang->testreport->bugs = 'テストのバグ';
$lang->testreport->builds = 'バージョン情報';
$lang->testreport->goal = 'プロジェクト目標';
$lang->testreport->cases = 'ケース';
$lang->testreport->bugInfo = 'バグ分布';
$lang->testreport->report = '総括';
$lang->testreport->legacyBugs = '処理待ちバグ';
$lang->testreport->createdBy   = '由谁创建';
$lang->testreport->createdDate = '作成時間';
$lang->testreport->objectID = 'オブジェクト';
$lang->testreport->objectType  = '对象类型';
$lang->testreport->profile = '概略';
$lang->testreport->value = '値';
$lang->testreport->none = 'なし';
$lang->testreport->all = '全てのレポート';
$lang->testreport->deleted = '削除';
$lang->testreport->selectTask = 'テストタスクによってレポート作成';

$lang->testreport->legendBasic = '基本情報';
$lang->testreport->legendStoryAndBug = 'テスト範囲';
$lang->testreport->legendBuild = 'テスト順番';
$lang->testreport->legendCase = '関連ケース';
$lang->testreport->legendLegacyBugs = '処理待ちバグ';
$lang->testreport->legendReport = 'レポート';
$lang->testreport->legendComment = '総括';
$lang->testreport->legendMore = 'その他の機能';

$lang->testreport->bugSeverityGroups = 'バグ重大度レベル別で分布';
$lang->testreport->bugTypeGroups = 'バグタイプ別で分布';
$lang->testreport->bugStatusGroups = 'バグステータス別で分布';
$lang->testreport->bugOpenedByGroups = 'バグ作成者別で分布';
$lang->testreport->bugResolvedByGroups = 'バグ処理者別で分布';
$lang->testreport->bugResolutionGroups = 'バグ解決策別で分布';
$lang->testreport->bugModuleGroups = 'バグモジュール別で分布';
$lang->testreport->legacyBugs = '処理待ちバグ';
$lang->testreport->bugConfirmedRate = '有効バグ率 (施策は処理済或いは延期/ステータスは処理或いはクローズ)';
$lang->testreport->bugCreateByCaseRate = 'ケースより発見バグ率(ケースより生成バグ/時間区間内で新規のバグ)';

$lang->testreport->caseSummary = '共に<strong>%s</strong>個のケースがあり、<strong>%s</strong>個のケースが実行され、<strong>%s</strong>個の結果が生じ、<strong>%s</strong>個のケースに失敗しました。';
$lang->testreport->buildSummary = '<strong>%s</strong>個のバージョンがテストされました。';
$lang->testreport->confirmDelete = '当該レポートを削除しますか？';
$lang->testreport->moreNotice = 'その他の機能は禅道の拡張メカニズムを参考して拡張できます。それ以外、弊社に連絡してカスタマイズすることができます。';
$lang->testreport->exportNotice = "<a href='https://www.zentao.net' target='_blank' style='color:grey'>禅道プロジェクト管理ソフト</a>からエクスポートしました";
$lang->testreport->noReport = 'レポートがありません。';
$lang->testreport->foundBugTip = 'テストに関連バージョンを含め、テスト時間範囲に発生のバグ数。';
$lang->testreport->legacyBugTip = 'バグステータスがアクティブ、或いはバグの処理済時間がテスト終了時間以後。';
$lang->testreport->fromCaseBugTip = 'テスト時間範囲に、ケース実行失敗した後で作成のバグ。';
$lang->testreport->errorTrunk = 'メインバーションはテストレポートが作成できませんので、関連バージョンを更新してください。';
$lang->testreport->noTestTask = "当該{$lang->productCommon}は非Trunkテストタスクと紐付けませんでしたから、レポートが作成できません。先にテストタスクを作成してください。";
$lang->testreport->noObjectID = "テストタスク或いは{$lang->projectCommon}を選択していませんから、レポートが作成できません。";
$lang->testreport->moreProduct = "テストレポートの作成は一つの{$lang->productCommon}のみ。";
$lang->testreport->hiddenCase     = "隐藏 %s 个用例";

$lang->testreport->bugSummary = <<<EOD
<strong>%s</strong>個のバグを発見 <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->foundBugTip}'><i class='icon-help'></i></a>、
<strong>%s</strong>個のバグを残りました <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->legacyBugTip}'><i class='icon-help'></i></a>。
ケースを実行して<strong>%s</strong>個のバグを生成しました <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->fromCaseBugTip}'><i class='icon-help'></i></a>。
有効バグ率（策は処理或いは延期/ステータスは処理或いはクローズ）：<strong>%s</strong>、ケース発見のバグ率（ケース作成のバグ/発見されたバグ数）：<strong>%s</strong>
EOD;
