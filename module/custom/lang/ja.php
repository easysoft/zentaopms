<?php
$lang->custom->common = 'カスタマイズ';
$lang->custom->index = 'ホーム';
$lang->custom->set = 'カスタム配置';
$lang->custom->restore = 'デフォルトに戻す';
$lang->custom->key = 'キー';
$lang->custom->value = '値';
$lang->custom->flow = 'フロー';
$lang->custom->working = '作業方式';
$lang->custom->select = 'フローを選択してください：';
$lang->custom->branch = '多ブランチ';
$lang->custom->owner = '所有者';
$lang->custom->module = 'モジュール';
$lang->custom->section = '添付部分';
$lang->custom->lang = '所属言語';
$lang->custom->setPublic = 'パブリックに設定';
$lang->custom->required = '必須項目';
$lang->custom->score = 'ポイント';
$lang->custom->timezone = 'タイムゾーン';
$lang->custom->scoreReset = 'ポイントリセット';
$lang->custom->scoreTitle = 'ポイント機能';

$lang->custom->object['story'] = $lang->storyCommon;
$lang->custom->object['task'] = 'タスク';
$lang->custom->object['bug'] = 'バグ';
$lang->custom->object['testcase'] = 'ケース';
$lang->custom->object['testtask'] = 'バージョン';
$lang->custom->object['todo'] = 'ToDo';
$lang->custom->object['user'] = 'ユーザ';
$lang->custom->object['block'] = 'ユニット';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['priList'] = '優先度';
$lang->custom->story->fields['sourceList'] = 'ソース';
$lang->custom->story->fields['reasonList'] = 'クローズ原因';
$lang->custom->story->fields['stageList'] = 'フェーズ';
$lang->custom->story->fields['statusList'] = 'ステータス';
$lang->custom->story->fields['reviewResultList'] = '評価結果';
$lang->custom->story->fields['review'] = '評価フロー';

$lang->custom->task = new stdClass();
$lang->custom->task->fields['priList'] = '優先度';
$lang->custom->task->fields['typeList'] = 'タイプ';
$lang->custom->task->fields['reasonList'] = 'クローズ原因';
$lang->custom->task->fields['statusList'] = 'ステータス';
$lang->custom->task->fields['hours'] = '時間';

$lang->custom->bug = new stdClass();
$lang->custom->bug->fields['priList'] = '優先度';
$lang->custom->bug->fields['severityList'] = '重大度レベル';
$lang->custom->bug->fields['osList'] = 'OS';
$lang->custom->bug->fields['browserList'] = 'ブラウザ';
$lang->custom->bug->fields['typeList'] = 'タイプ';
$lang->custom->bug->fields['resolutionList'] = '解決策';
$lang->custom->bug->fields['statusList'] = 'ステータス';
$lang->custom->bug->fields['longlife'] = '長時間未処理日数';

$lang->custom->testcase = new stdClass();
$lang->custom->testcase->fields['priList'] = '優先度';
$lang->custom->testcase->fields['typeList'] = 'タイプ';
$lang->custom->testcase->fields['stageList'] = 'フェーズ';
$lang->custom->testcase->fields['resultList'] = '実行結果';
$lang->custom->testcase->fields['statusList'] = 'ステータス';
$lang->custom->testcase->fields['review'] = '評価フロー';

$lang->custom->testtask = new stdClass();
$lang->custom->testtask->fields['priList'] = '優先度';
$lang->custom->testtask->fields['statusList'] = 'ステータス';

$lang->custom->todo = new stdClass();
$lang->custom->todo->fields['priList'] = '優先度';
$lang->custom->todo->fields['typeList'] = 'タイプ';
$lang->custom->todo->fields['statusList'] = 'ステータス';

$lang->custom->user = new stdClass();
$lang->custom->user->fields['roleList'] = '職位';
$lang->custom->user->fields['statusList'] = 'ステータス';
$lang->custom->user->fields['contactField'] = 'アクティブ連絡先';
$lang->custom->user->fields['deleted'] = '削除済ユーザの表示';

$lang->custom->system = array('flow', 'working', 'required', 'score');

$lang->custom->block->fields['closed'] = 'クローズ済';

$lang->custom->currentLang = '現在の言語に適用';
$lang->custom->allLang = '全ての言語に適用';

$lang->custom->confirmRestore = 'デフォルト配置に戻してもよろしいですか？';

$lang->custom->notice = new stdClass();
$lang->custom->notice->userFieldNotice = '上記のフィールドがユーザの関連ページに表示するかどうかことが制御できます、空白にするなら全部表示します';
$lang->custom->notice->canNotAdd = 'この項目は演算で利用され、カスタム追加機能が提供していません';
$lang->custom->notice->forceReview = '指名されたユーザが提出した%sはレビュー必須となります。';
$lang->custom->notice->forceNotReview = '指名されたユーザが提出した%sはレビュー不要となります。';
$lang->custom->notice->longlife = 'バグリストページの長時間未処理タグで、設定日数前の未処理バグを表示します。';
$lang->custom->notice->invalidNumberKey = 'キー値は255以下の数字となります';
$lang->custom->notice->invalidStringKey = '「キー」に半角英数字、“_”のみセット可能';
$lang->custom->notice->cannotSetTimezone = 'date_default_timezone_setメソッドは存在しません、或いは無効されます。タイムゾーンが設定できません。';
$lang->custom->notice->noClosedBlock = 'クローズ済ユニットがありません';
$lang->custom->notice->required = 'こちらの必須項目を選択してください。';
$lang->custom->notice->conceptResult               = '我们已经根据您的选择为您设置了<b> %s-%s </b>模式，使用<b>%s</b> + <b> %s</b>。';
$lang->custom->notice->conceptPath                 = '您可以在：后台 -> 自定义 -> 流程页面修改。';

$lang->custom->notice->indexPage['product'] = '8.2バージョンからプロダクトホームビューを追加しました、デフォルトでプロダクトホームページにアクセスしてもよろしいですか？';
$lang->custom->notice->indexPage['project'] = '8.2バージョンからプロジェクトホームビューを追加しました、デフォルトでプロジェクトホームページにアクセスしてもよろしいですか？';
$lang->custom->notice->indexPage['qa'] = '8.2バージョンからテストホームビューを追加しました、デフォルトでテストホームページにアクセスしてもよろしいですか？';

$lang->custom->notice->invalidStrlen['ten'] = 'キーの長さは10文字以下にしてください。';
$lang->custom->notice->invalidStrlen['twenty'] = 'キーの長さは20文字以下にしてください。';
$lang->custom->notice->invalidStrlen['thirty'] = 'キーの長さは30文字以下にしてください。';
$lang->custom->notice->invalidStrlen['twoHundred'] = 'キーの長さは2２5文字以下にしてください。';

$lang->custom->storyReview = '評価フロー';
$lang->custom->forceReview = '強制評価';
$lang->custom->forceNotReview = '評価不要';
$lang->custom->reviewList[1] = 'オン';
$lang->custom->reviewList[0] = 'オフ';

$lang->custom->deletedList[1] = '表示';
$lang->custom->deletedList[0] = '非表示';

$lang->custom->workingHours = '毎日利用可能時間';
$lang->custom->weekend = '休日';
$lang->custom->weekendList[2] = '週休二日';
$lang->custom->weekendList[1] = '週休一日';

$lang->custom->productProject = new stdclass();
$lang->custom->productProject->relation['0_0'] = 'プロダクト - プロジェクト';
$lang->custom->productProject->relation['0_1'] = 'プロダクト - イテレート';
$lang->custom->productProject->relation['1_1'] = 'プロジェクト - イテレート';
$lang->custom->productProject->relation['0_2'] = '产品 - 冲刺';
$lang->custom->productProject->relation['1_2'] = '项目 - 冲刺';

$lang->custom->productProject->notice = '実際状況によって自分のチームに合わせる概念を選んでください。';

$lang->custom->workingList['full'] = '開発管理ツール';
$lang->custom->workingList['onlyTest'] = 'テスト管理ツール';
$lang->custom->workingList['onlyStory'] = $lang->storyCommon . '管理ツール';
$lang->custom->workingList['onlyTask'] = 'タスク管理ツール';

$lang->custom->menuTip = 'クリックでナビゲーション項目の表示・非表示を切り替え、ドラッグで順番変更';
$lang->custom->saveFail = '保存失敗！';
$lang->custom->page = 'ページ';

$lang->custom->scoreStatus[1] = 'オン';
$lang->custom->scoreStatus[0] = 'オフ';

$lang->custom->moduleName['product'] = $lang->productCommon;
$lang->custom->moduleName['productplan'] = 'プラン';
$lang->custom->moduleName['project'] = $lang->productCommon;

$lang->custom->conceptQuestions['overview']         = "1. 下述哪种组合方式更适合您公司的管理现状？";
$lang->custom->conceptQuestions['story']            = "2. 您公司是在使用需求概念还是用户故事概念？";
$lang->custom->conceptQuestions['requirementpoint'] = "3. 您公司是在使用工时还是功能点来做规模估算？";
$lang->custom->conceptQuestions['storypoint']       = "3. 您公司是在使用工时还是故事点来做规模估算？";

$lang->custom->conceptOptions = new stdclass;

$lang->custom->conceptOptions->story = array();
$lang->custom->conceptOptions->story['0'] = '需求';
$lang->custom->conceptOptions->story['1'] = '故事';

$lang->custom->conceptOptions->hourPoint = array();
$lang->custom->conceptOptions->hourPoint['0'] = '工时';
$lang->custom->conceptOptions->hourPoint['1'] = '故事点';
$lang->custom->conceptOptions->hourPoint['2'] = '功能点';
