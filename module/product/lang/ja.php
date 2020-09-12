<?php
/**
 * The product module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      admin
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->product->common = $lang->productCommon . 'ビュー';
$lang->product->index = $lang->productCommon;
$lang->product->browse = $lang->storyCommon . 'リスト';
$lang->product->dynamic = '履歴';
$lang->product->view = "{$lang->productCommon}概略";
$lang->product->edit = "{$lang->productCommon}編集";
$lang->product->batchEdit = '一括編集';
$lang->product->create = '新規';
$lang->product->delete = "{$lang->productCommon}削除";
$lang->product->deleted = '削除';
$lang->product->close = 'クローズ';
$lang->product->closeAction = "{$lang->productCommon}クローズ";
$lang->product->select = "{$lang->productCommon}選択";
$lang->product->mine = '担当タスク：';
$lang->product->other = '...';
$lang->product->closed = 'クローズ';
$lang->product->updateOrder = 'ソート';
$lang->product->orderAction = "{$lang->productCommon}ソート";
$lang->product->all = "全{$lang->productCommon}";
$lang->product->export = 'データエクスポート';
$lang->product->exportAction = "{$lang->productCommon}エクスポート";

$lang->product->basicInfo = '基本情報';
$lang->product->otherInfo = '他の情報';

$lang->product->plans = 'プラン数';
$lang->product->releases = 'リリース数';
$lang->product->docs = '資料数';
$lang->product->bugs = '関連バグ';
$lang->product->projects = "{$lang->projectCommon}数";
$lang->product->cases = 'ケース数';
$lang->product->builds = 'ビルド数';
$lang->product->roadmap = 'ロードマップ';
$lang->product->doc = '資料リスト';
$lang->product->project = $lang->projectCommon . 'リスト';
$lang->product->build = 'バージョンリスト';
$lang->product->projectInfo = "当該プロダクトと紐付けた全て{$lang->projectCommon}";

$lang->product->currentProject = '現' . $lang->projectCommon;
$lang->product->activeStories = 'アクティブ';
$lang->product->activeStoriesTitle = 'アクティブ';
$lang->product->changedStories = '変更済';
$lang->product->changedStoriesTitle = '変更済';
$lang->product->draftStories = '下書き';
$lang->product->draftStoriesTitle = '下書き';
$lang->product->closedStories = 'クローズ済';
$lang->product->closedStoriesTitle = 'クローズ済';
$lang->product->unResolvedBugs = '処理待ちバグ';
$lang->product->unResolvedBugsTitle = '処理待ちバグ';
$lang->product->assignToNullBugs = '担当未定バグ';
$lang->product->assignToNullBugsTitle = '担当未定バグ';

$lang->product->confirmDelete = "当該{$lang->productCommon}を削除してもよろしいですか？";
$lang->product->errorNoProduct = "{$lang->productCommon}はまだ作成していません！";
$lang->product->accessDenied = "当該{$lang->productCommon}をアクセスする権限がありません";

$lang->product->id = '番号';
$lang->product->name = "{$lang->productCommon}名";
$lang->product->code = 'コード';
$lang->product->line = 'ライン';
$lang->product->order = 'ソート';
$lang->product->type = 'タイプ';
$lang->product->typeAB = 'タイプ';
$lang->product->status = 'ステータス';
$lang->product->subStatus = '子状态';
$lang->product->desc = '説明';
$lang->product->manager = '担当者';
$lang->product->PO = '担当者';
$lang->product->QD = 'テスト担当';
$lang->product->RD = 'リリース担当';
$lang->product->acl = 'アクセス制御';
$lang->product->whitelist = 'ホワイトリストグループ';
$lang->product->branch = '所属%s';
$lang->product->qa = 'テスト';
$lang->product->release = 'リリース';
$lang->product->allRelease = '全リリース';
$lang->product->maintain = '保守中';
$lang->product->latestDynamic = '最新履歴';
$lang->product->plan = 'プラン';
$lang->product->iteration = 'バージョンイテレーション';
$lang->product->iterationInfo = 'イテレーション %s 回';
$lang->product->iterationView = '詳細表示';
$lang->product->createdBy = '由谁创建';
$lang->product->createdDate = '创建日期';

$lang->product->searchStory = '検索';
$lang->product->assignedToMe = '担当者';
$lang->product->openedByMe = '作成';
$lang->product->reviewedByMe = '承認';
$lang->product->closedByMe = 'クローズ';
$lang->product->draftStory = '下書き';
$lang->product->activeStory = 'アクティブ';
$lang->product->changedStory = '変更';
$lang->product->willClose = 'クローズ待ち';
$lang->product->closedStory = 'クローズ';
$lang->product->unclosed = 'クローズ待ち';
$lang->product->unplan = 'プラン待ち';
$lang->product->viewByUser = 'ユーザ毎に表示';

$lang->product->allStory = '全て';
$lang->product->allProduct = '全' . $lang->productCommon;
$lang->product->allProductsOfProject = '全関連' . $lang->productCommon;

$lang->product->typeList[''] = '';
$lang->product->typeList['normal'] = '正常';
$lang->product->typeList['branch'] = '多ブランチ';
$lang->product->typeList['platform'] = '多プラットフォーム';

$lang->product->typeTips = array();
$lang->product->typeTips['branch'] = '（客先カスタムの場合に適用）';
$lang->product->typeTips['platform'] = '（クロスプラットフォームアプリケーション開発に適用、IOS、アンドロイド、PCなど）';

$lang->product->branchName['normal'] = '';
$lang->product->branchName['branch'] = 'ブランチ';
$lang->product->branchName['platform'] = 'プラットフォーム';

$lang->product->statusList[''] = '';
$lang->product->statusList['normal'] = '正常';
$lang->product->statusList['closed'] = '終了';

$lang->product->aclList['open'] = "デフォルト設定({$lang->productCommon}ビュー権限を持つメンバーのみ)";
$lang->product->aclList['private'] = "プライベートの{$lang->productCommon}({$lang->productCommon}担当および{$lang->projectCommon}メンバーのみ)";
$lang->product->aclList['custom'] = 'カスタムホワイトリスト（チームメンバーおよびホワイトリストメンバーのみ）';

$lang->product->storySummary = "共に <strong>%s</strong> 個{$lang->storyCommon}、 <strong>%s</strong>時間計画時間、 <strong>%s</strong>ケース占有率があります。";
$lang->product->checkedSummary = "<strong>%total%</strong>  個{$lang->storyCommon}、 <strong>%estimate%</strong> 時間計画時間、 <strong>%rate%</strong> ケース占有率を選択しました。";
$lang->product->noModule = "<div>モジュール情報がありません</div><div>{$lang->productCommon}モジュールを管理してください</div>";
$lang->product->noProduct = "{$lang->productCommon}がありません。";
$lang->product->noMatched = '見つかりませんでした："%s"を含めた' . $lang->productCommon;

$lang->product->featureBar['browse']['allstory'] = $lang->product->allStory;
$lang->product->featureBar['browse']['unclosed'] = $lang->product->unclosed;
$lang->product->featureBar['browse']['assignedtome'] = $lang->product->assignedToMe;
$lang->product->featureBar['browse']['openedbyme'] = $lang->product->openedByMe;
$lang->product->featureBar['browse']['reviewedbyme'] = $lang->product->reviewedByMe;
$lang->product->featureBar['browse']['draftstory'] = $lang->product->draftStory;
$lang->product->featureBar['browse']['more'] = $lang->more;

$lang->product->featureBar['all']['noclosed'] = $lang->product->unclosed;
$lang->product->featureBar['all']['closed'] = $lang->product->statusList['closed'];
$lang->product->featureBar['all']['all'] = $lang->product->allProduct;

$lang->product->moreSelects['closedbyme'] = $lang->product->closedByMe;
$lang->product->moreSelects['activestory'] = $lang->product->activeStory;
$lang->product->moreSelects['changedstory'] = $lang->product->changedStory;
$lang->product->moreSelects['willclose'] = $lang->product->willClose;
$lang->product->moreSelects['closedstory'] = $lang->product->closedStory;
