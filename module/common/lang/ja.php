<?php
/**
 * The lang file of calendar module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     business(商业软件) 
 * @author      wangguannan admin zengqingyang
 * @package     calendar 
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->arrow = '&nbsp;<i class="icon-angle-right"></i>&nbsp;';
$lang->colon = ':';
$lang->comma = '，';
$lang->dot = '。';
$lang->at = 'が';
$lang->downArrow = '↓';
$lang->null = '空';
$lang->ellipsis = '…';
$lang->percent = '%';
$lang->dash = '-';

$lang->zentaoPMS = 'Zentao';
$lang->logoImg        = 'zt-logo-en.png';
$lang->welcome = '%sプロジェクト管理システム';
$lang->logout = 'ログアウト';
$lang->login = 'ログイン';
$lang->help = 'ヘルプ';
$lang->aboutZenTao = '禅道について';
$lang->profile = '個人プロファイル';
$lang->changePassword = 'パスワード変更';
$lang->runInfo = "<div class='row'><div class='u-1 a-center' id='debugbar'>時間： ％ｓ ミリ秒、メモリ： ％ｓ　ＫＢ、 クエリ： ％ｓ 。  </div></div>";
$lang->agreement = "下記内容を同意します。<a href='http://zpl.pub/page/zplv12.html' target='_blank'>《Z PUBLIC LICENSEライセンス契約1.2》</a>。<span class='text-danger'>禅道ソフトのロゴまたはリンクを許可なく削除、非表示、または隠すことはできません。</span>";
$lang->designedByAIUX = "<a href='http://aiuxstudio.com/' class='link-aiux' target='_blank'>Designed by <strong>艾体験</strong></a>";

$lang->reset = '書き直す';
$lang->cancel = 'キャンセル';
$lang->refresh = '更新';
$lang->edit = '編集';
$lang->delete = '削除';
$lang->close = 'クローズ';
$lang->unlink = '除去';
$lang->import = 'インポート';
$lang->export = 'エクスポート';
$lang->setFileName = 'ファイル名：';
$lang->submitting = 'お待ちください...';
$lang->save = '保存';
$lang->saveSuccess = '保存成功';
$lang->confirm = '確認';
$lang->preview = 'プレビュー';
$lang->goback = '戻る';
$lang->goPC = 'PCバージョン';
$lang->more = 'その他';
$lang->day = '日';
$lang->customConfig = 'カスタマイズ';
$lang->public = 'パブリック';
$lang->trunk = 'メイン';
$lang->sort = 'ソート';
$lang->required = '必須項目';
$lang->noData = 'ありません';
$lang->fullscreen   = 'フルスクリーン';
$lang->retrack      = '折りたたむ';

$lang->actions = '';
$lang->restore = 'デフォルトに戻す';
$lang->comment = '備考';
$lang->history = '履歴';
$lang->attatch = '添付ファイル';
$lang->reverse = '順番スイッチ';
$lang->switchDisplay = '表示スイッチ';
$lang->expand = '全て展開';
$lang->collapse = '折りたたむ';
$lang->saveSuccess = '保存成功';
$lang->fail = '失敗';
$lang->addFiles = '添付ファイルをアップロードしました';
$lang->files = '添付ファイル';
$lang->pasteText = '複数入力';
$lang->uploadImages = '複数画像アップロード';
$lang->timeout = '接続がタイムアウトしました。ネットワーク環境をチェックしてから改めて接続してください。';
$lang->repairTable = 'データベース表が破損している可能性がありますので、phpmisdminまたはmyisamchkで修復してください。';
$lang->duplicate = '同じ名称の%sが既に存在しています';
$lang->ipLimited = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>申し訳ございませんが、現在のIPのログインは管理者に制限付けていますので、管理者に連絡して制限を解除してください。</body></html>";
$lang->unfold = '+';
$lang->fold = '-';
$lang->homepage = 'モジュールのトップページに設定';
$lang->noviceTutorial = '初心者向けチュートリアル';
$lang->changeLog = '更新ログ';
$lang->manual = 'マニュアル';
$lang->customMenu = 'カスタムナビゲーション';
$lang->customField = 'カスタムリスト項目';
$lang->lineNumber = '行番号';
$lang->tutorialConfirm = 'チュートリアルモードから退出していません、今退出してよろしいですか？';

$lang->preShortcutKey = '［ショ—トカットキ—：←］';
$lang->nextShortcutKey = '[ショ—トカットキ—:→]';
$lang->backShortcutKey = '[ショ—トカットキ—:Alt+↑]';

$lang->select = '選択';
$lang->selectAll = '全て選択';
$lang->selectReverse = '逆選択';
$lang->loading = 'お待ちください...';
$lang->notFound = '申し訳ありませんが、アクセスしているオブジェクトは存在しません！';
$lang->notPage = '申し訳ありませんが、アクセスの機能は開発中です！';
$lang->showAll = '[[全て表示]]';
$lang->selectedItems = '已选择 <strong>{0}</strong> 项';

$lang->future = '未来';
$lang->year = '年';
$lang->workingHour = '時間';

$lang->idAB = 'ID';
$lang->priAB = 'P';
$lang->statusAB = 'ステータス';
$lang->openedByAB = '作成者';
$lang->assignedToAB = '担当者';
$lang->typeAB = 'タイプ';

$lang->common = new stdclass();
$lang->common->common = 'パブリックモジュール';

/* 主导航菜单。*/
$lang->menu = new stdclass();
$lang->menu->my = '<span> マイページ</span>|my|index';
$lang->menu->product = $lang->productCommon . '|product|index|locate=no';
$lang->menu->project = $lang->projectCommon . '|project|index|locate=no';
$lang->menu->qa = 'テスト|qa|index';
$lang->menu->ci      = '集成|repo|browse';
$lang->menu->doc = '資料|doc|index';
$lang->menu->report = '統計|report|index';
$lang->menu->company = '組織|company|index';
$lang->menu->admin = '設定|admin|index';

$lang->dividerMenu = ',qa,report,';

/* 查询条中可以选择的对象列表。*/
$lang->searchObjects['bug'] = 'バグ';
$lang->searchObjects['story'] = $lang->storyCommon;
$lang->searchObjects['task'] = 'タスク';
$lang->searchObjects['testcase'] = 'ケース';
$lang->searchObjects['project'] = $lang->projectCommon;
$lang->searchObjects['product'] = $lang->productCommon;
$lang->searchObjects['user'] = 'ユーザ';
$lang->searchObjects['build'] = 'バージョン';
$lang->searchObjects['release'] = 'リリース';
$lang->searchObjects['productplan'] = $lang->productCommon . 'プラン';
$lang->searchObjects['testtask'] = 'テストタスク';
$lang->searchObjects['doc'] = '資料';
$lang->searchObjects['testsuite'] = 'ケースライブラリ';
$lang->searchObjects['testreport'] = 'テストレポート';
$lang->searchTips = '番号（ctrl＋g）';

/* 导入支持的编码格式。*/
$lang->importEncodeList['gbk'] = 'GBK';
$lang->importEncodeList['big5'] = 'BIG5';
$lang->importEncodeList['utf-8'] = 'UTF-8';

/* 导出文件的类型列表。*/
$lang->exportFileTypeList['csv'] = 'csv';
$lang->exportFileTypeList['xml'] = 'xml';
$lang->exportFileTypeList['html'] = 'html';

$lang->exportTypeList['all'] = '全てのレコード';
$lang->exportTypeList['selected'] = '選択したレコード';

/* 语言 */
$lang->lang = '言語';

/* 风格列表。*/
$lang->theme = 'テーマ';
$lang->themes['default'] = '禅道藍（デフォルト）';
$lang->themes['green'] = '緑';
$lang->themes['red'] = '赤';
$lang->themes['purple'] = '紫';
$lang->themes['pink'] = '桃';
$lang->themes['blackberry'] = '黒';
$lang->themes['classic'] = 'クラシックブルー';

/* 首页菜单设置。*/
$lang->index = new stdclass();
$lang->index->menu = new stdclass();

$lang->index->menu->product = "一覧{$lang->productCommon}|product|browse";
$lang->index->menu->project = "一覧{$lang->projectCommon}|project|browse";

/* 我的地盘菜单设置。*/
$lang->my = new stdclass();
$lang->my->menu = new stdclass();

$lang->my->menu->index = 'ホーム|my|index';
$lang->my->menu->calendar = array('link' => '日程|my|calendar|', 'subModule' => 'todo', 'alias' => 'todo', 'class' => 'dropdown dropdown-hover');
$lang->my->menu->task = array('link' => 'タスク|my|task|', 'subModule' => 'task');
$lang->my->menu->bug = array('link' => 'バグ|my|bug|', 'subModule' => 'bug');
$lang->my->menu->testtask = array('link' => 'テスト|my|testtask|', 'subModule' => 'testcase,testtask', 'alias' => 'testcase');
$lang->my->menu->story = array('link' => $lang->storyCommon . '|my|story|', 'subModule' => 'story');
$lang->my->menu->myProject = "{$lang->projectCommon}|my|project|";
$lang->my->menu->dynamic = '履歴|my|dynamic|';
$lang->my->menu->profile = array('link' => 'プロファイル|my|profile', 'alias' => 'editprofile');
$lang->my->menu->changePassword = 'パスワード|my|changepassword';
$lang->my->menu->manageContacts = '連絡先|my|managecontacts';
$lang->my->menu->score = array('link' => 'ポイント|my|score', 'subModule' => 'score');

$lang->my->dividerMenu = ',task,myProject,profile,';

$lang->todo = new stdclass();
$lang->todo->menu = $lang->my->menu;

$lang->score = new stdclass();
$lang->score->menu = $lang->my->menu;

/* 产品视图设置。*/
$lang->product = new stdclass();
$lang->product->menu = new stdclass();

$lang->product->menu->story = array('link' => $lang->storyCommon . '|product|browse|productID=%s', 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->menu->plan = array('link' => 'プラン|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release = array('link' => 'リリース|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap = 'ロードマップ|product|roadmap|productID=%s';
$lang->product->menu->project = "{$lang->projectCommon}|product|project|status=all&productID=%s";
$lang->product->menu->dynamic = '履歴|product|dynamic|productID=%s';
$lang->product->menu->doc = array('link' => '資料|doc|objectLibs|type=product&objectID=%s&from=product', 'subModule' => 'doc');
$lang->product->menu->branch = '@branch@|branch|manage|productID=%s';
$lang->product->menu->module = 'モジュール|tree|browse|productID=%s&view=story';
$lang->product->menu->view = array('link' => '概要|product|view|productID=%s', 'alias' => 'edit');

$lang->product->dividerMenu = ',plan,project,doc,';

$lang->story = new stdclass();
$lang->productplan = new stdclass();
$lang->release = new stdclass();
$lang->branch = new stdclass();

$lang->branch->menu = $lang->product->menu;
$lang->story->menu = $lang->product->menu;
$lang->productplan->menu = $lang->product->menu;
$lang->release->menu = $lang->product->menu;

/* 项目视图菜单设置。*/
$lang->project = new stdclass();
$lang->project->menu = new stdclass();

$lang->project->menu->task = array('link' => 'タスク|project|task|projectID=%s', 'subModule' => 'task,tree', 'alias' => 'importtask,importbug');
$lang->project->menu->kanban = array('link' => '看板|project|kanban|projectID=%s');
$lang->project->menu->burn = array('link' => 'バーンダウンチャート|project|burn|projectID=%s');
$lang->project->menu->list = array('link' => 'その他|project|grouptask|projectID=%s', 'alias' => 'grouptask,tree', 'class' => 'dropdown dropdown-hover');
$lang->project->menu->story = array('link' => $lang->storyCommon . '|project|story|projectID=%s', 'subModule' => 'story', 'alias' => 'linkstory,storykanban');
$lang->project->menu->qa = array('link' => 'テスト|project|bug|projectID=%s', 'subModule' => 'bug,build,testtask', 'alias' => 'build,testtask', 'class' => 'dropdown dropdown-hover');
$lang->project->menu->doc = array('link' => '資料|doc|objectLibs|type=project&objectID=%s&from=project', 'subModule' => 'doc');
$lang->project->menu->action = array('link' => '履歴|project|dynamic|projectID=%s', 'subModule' => 'dynamic', 'class' => 'dropdown dropdown-hover');
$lang->project->menu->product = $lang->productCommon . '|project|manageproducts|projectID=%s';
$lang->project->menu->team = array('link' => 'チーム|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->view = array('link' => '概要|project|view|projectID=%s', 'alias' => 'edit,start,suspend,putoff,close');

$lang->project->subMenu = new stdclass();
$lang->project->subMenu->list = new stdclass();
$lang->project->subMenu->list->groupTask = 'グループビュー|project|groupTask|projectID=%s';
$lang->project->subMenu->list->tree = '樹形図|project|tree|projectID=%s';

$lang->project->subMenu->qa = new stdclass();
$lang->project->subMenu->qa->bug = 'バグ|project|bug|projectID=%s';
$lang->project->subMenu->qa->build = array('link' => 'バージョン|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->subMenu->qa->testtask = array('link' => 'テストタスク|project|testtask|projectID=%s', 'subModule' => 'testreport,testtask');

$lang->project->dividerMenu = ',story,team,product,';

$lang->task = new stdclass();
$lang->build = new stdclass();
$lang->task->menu = $lang->project->menu;
$lang->build->menu = $lang->project->menu;

/* QA视图菜单设置。*/
$lang->qa = new stdclass();
$lang->qa->menu = new stdclass();

$lang->qa->menu->bug = array('link' => 'バグ|bug|browse|productID=%s');
$lang->qa->menu->testcase = array('link' => 'ケース|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->qa->menu->testtask = array('link' => 'テストタスク|testtask|browse|productID=%s');
$lang->qa->menu->testsuite = array('link' => 'スイート|testsuite|browse|productID=%s');
$lang->qa->menu->report = array('link' => 'レポート|testreport|browse|productID=%s');
$lang->qa->menu->caselib = array('link' => 'ケースライブラリ|caselib|browse');

$lang->qa->subMenu = new stdclass();
$lang->qa->subMenu->testcase = new stdclass();
$lang->qa->subMenu->testcase->feature = array('link' => '功能测试|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree,story');
$lang->qa->subMenu->testcase->unit    = array('link' => '单元测试|testtask|browseUnits|productID=%s');

$lang->bug = new stdclass();
$lang->bug->menu = new stdclass();
$lang->bug->subMenu = $lang->qa->subMenu;

$lang->bug->menu->bug = array('link' => 'バグ|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,batchactivate,confirmbug,assignto', 'subModule' => 'tree');
$lang->bug->menu->testcase = array('link' => 'ケース|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->bug->menu->testtask = array('link' => 'テストタスク|testtask|browse|productID=%s');
$lang->bug->menu->testsuite = array('link' => 'スイート|testsuite|browse|productID=%s');
$lang->bug->menu->report = array('link' => 'レポート|testreport|browse|productID=%s');
$lang->bug->menu->caselib = array('link' => 'ケースライブラリ|caselib|browse');

$lang->testcase = new stdclass();
$lang->testcase->menu = new stdclass();
$lang->testcase->subMenu = $lang->qa->subMenu;
$lang->testcase->menu->bug = array('link' => 'バグ|bug|browse|productID=%s');
$lang->testcase->menu->testcase = array('link' => 'ケース|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree,story', 'class' => 'dropdown dropdown-hover');
$lang->testcase->menu->testtask = array('link' => 'テストタスク|testtask|browse|productID=%s');
$lang->testcase->menu->testsuite = array('link' => 'スイート|testsuite|browse|productID=%s');
$lang->testcase->menu->report = array('link' => 'レポート|testreport|browse|productID=%s');
$lang->testcase->menu->caselib = array('link' => 'ケースライブラリ|caselib|browse');

$lang->testtask = new stdclass();
$lang->testtask->menu = new stdclass();
$lang->testtask->subMenu = $lang->qa->subMenu;
$lang->testtask->menu->bug = array('link' => 'バグ|bug|browse|productID=%s');
$lang->testtask->menu->testcase = array('link' => 'ケース|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testtask->menu->testtask = array('link' => 'テストタスク|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase,report');
$lang->testtask->menu->testsuite = array('link' => 'スイート|testsuite|browse|productID=%s');
$lang->testtask->menu->report = array('link' => 'レポート|testreport|browse|productID=%s');
$lang->testtask->menu->caselib = array('link' => 'ケースライブラリ|caselib|browse');

$lang->testsuite = new stdclass();
$lang->testsuite->menu = new stdclass();
$lang->testsuite->subMenu = $lang->qa->subMenu;
$lang->testsuite->menu->bug = array('link' => 'バグ|bug|browse|productID=%s');
$lang->testsuite->menu->testcase = array('link' => 'ケース|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testsuite->menu->testtask = array('link' => 'テストタスク|testtask|browse|productID=%s');
$lang->testsuite->menu->testsuite = array('link' => 'スイート|testsuite|browse|productID=%s', 'alias' => 'view,create,edit,linkcase');
$lang->testsuite->menu->report = array('link' => 'レポート|testreport|browse|productID=%s');
$lang->testsuite->menu->caselib = array('link' => 'ケースライブラリ|caselib|browse');

$lang->testreport = new stdclass();
$lang->testreport->menu = new stdclass();
$lang->testreport->subMenu = $lang->qa->subMenu;
$lang->testreport->menu->bug = array('link' => 'バグ|bug|browse|productID=%s');
$lang->testreport->menu->testcase = array('link' => 'ケース|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testreport->menu->testtask = array('link' => 'テストタスク|testtask|browse|productID=%s');
$lang->testreport->menu->testsuite = array('link' => 'スイート|testsuite|browse|productID=%s');
$lang->testreport->menu->report = array('link' => 'レポート|testreport|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->testreport->menu->caselib = array('link' => 'ケースライブラリ|caselib|browse');

$lang->caselib = new stdclass();
$lang->caselib->menu = new stdclass();
$lang->caselib->menu->bug = array('link' => 'バグ|bug|browse|');
$lang->caselib->menu->testcase = array('link' => 'ケース|testcase|browse|', 'class' => 'dropdown dropdown-hover');
$lang->caselib->menu->testtask = array('link' => 'テストタスク|testtask|browse|');
$lang->caselib->menu->testsuite = array('link' => 'スイート|testsuite|browse|');
$lang->caselib->menu->report = array('link' => 'レポート|testreport|browse|');
$lang->caselib->menu->caselib = array('link' => 'ケースライブラリ|caselib|browse', 'alias' => 'create,createcase,view,edit,batchcreatecase,showimport', 'subModule' => 'tree,testcase');

$lang->caselib->subMenu = new stdclass();
$lang->caselib->subMenu->testcase = new stdclass();
$lang->caselib->subMenu->testcase->feature = array('link' => '功能测试|testcase|browse|', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree,story');
$lang->caselib->subMenu->testcase->unit    = array('link' => '单元测试|testtask|browseUnits|');

$lang->ci = new stdclass();
$lang->ci->menu = new stdclass();
$lang->ci->menu->code     = array('link' => '代码|repo|browse|repoID=%s', 'alias' => 'diff,view,revision,log,blame,showsynccomment');
$lang->ci->menu->build    = array('link' => '构建|job|browse', 'subModule' => 'compile,job');
$lang->ci->menu->jenkins  = array('link' => 'Jenkins|jenkins|browse', 'alias' => 'create,edit');
$lang->ci->menu->maintain = array('link' => '版本库|repo|maintain', 'alias' => 'create,edit');
$lang->ci->menu->rules    = array('link' => '指令|repo|setrules');

$lang->repo          = new stdclass();
$lang->jenkins       = new stdclass();
$lang->compile       = new stdclass();
$lang->job           = new stdclass();
$lang->repo->menu    = $lang->ci->menu;
$lang->jenkins->menu = $lang->ci->menu;
$lang->compile->menu = $lang->ci->menu;
$lang->job->menu     = $lang->ci->menu;

/* 文档视图菜单设置。*/
$lang->doc = new stdclass();
$lang->doc->menu = new stdclass();
//$lang->doc->menu->createLib = array('link' => '<i class="icon icon-folder-plus"></i>&nbsp;添加文件夹|doc|createLib', 'float' => 'right');

$lang->svn = new stdclass();
$lang->git = new stdclass();

/* 统计视图菜单设置。*/
$lang->report = new stdclass();
$lang->report->menu = new stdclass();

$lang->report->menu->annual  = array('link' => '年度总结|report|annualData', 'target' => '_blank');
$lang->report->menu->product = array('link' => $lang->productCommon . '|report|productsummary');
$lang->report->menu->prj = array('link' => $lang->projectCommon . '|report|projectdeviation');
$lang->report->menu->test = array('link' => 'テスト|report|bugcreate', 'alias' => 'bugassign');
$lang->report->menu->staff = array('link' => '組織|report|workload');

$lang->report->notice = new stdclass();
$lang->report->notice->help = '説明：統計レポートのデータはリストページの検索結果から取得します。統計レポートを生成する前に、リストページで検索してください。例えば、リストで%tab%を検索する場合、レポートは%tab%の検索結果によって統計されます。';

/* 组织结构视图菜单设置。*/
$lang->company = new stdclass();
$lang->company->menu = new stdclass();
$lang->company->menu->browseUser = array('link' => 'ユーザ|company|browse', 'subModule' => 'user');
$lang->company->menu->dept = array('link' => '部門|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup = array('link' => '権限|group|browse', 'subModule' => 'group');
$lang->company->menu->dynamic = '履歴|company|dynamic|';
$lang->company->menu->view = array('link' => '企業|company|view');

$lang->dept = new stdclass();
$lang->group = new stdclass();
$lang->user = new stdclass();

$lang->dept->menu = $lang->company->menu;
$lang->group->menu = $lang->company->menu;
$lang->user->menu = $lang->company->menu;

/* 后台管理菜单设置。*/
$lang->admin = new stdclass();
$lang->admin->menu = new stdclass();
$lang->admin->menu->index = array('link' => 'ホーム|admin|index', 'alias' => 'register,certifytemail,certifyztmobile,ztcompany');
$lang->admin->menu->message = array('link' => 'メッセージ|message|index', 'subModule' => 'message,mail,webhook');
$lang->admin->menu->custom = array('link' => 'カスタマイズ|custom|set', 'subModule' => 'custom');
$lang->admin->menu->sso = array('link' => 'インテグレーション|admin|sso');
$lang->admin->menu->extension = array('link' => 'プラグイン|extension|browse', 'subModule' => 'extension');
$lang->admin->menu->dev = array('link' => '二次開発|dev|api', 'alias' => 'db', 'subModule' => 'dev,editor,entry');
$lang->admin->menu->translate = array('link' => '翻訳|translate|index', 'subModule' => 'translate');
$lang->admin->menu->data = array('link' => 'データ|backup|index', 'subModule' => 'backup,action');
$lang->admin->menu->safe = array('link' => 'セキュリティ|admin|safe', 'alias' => 'checkweak');
$lang->admin->menu->system = array('link' => 'システム|cron|index', 'subModule' => 'cron');

$lang->admin->subMenu = new stdclass();
$lang->admin->subMenu->message = new stdclass();
$lang->admin->subMenu->message->mail = array('link' => 'メール|mail|index', 'subModule' => 'mail');
$lang->admin->subMenu->message->webhook = array('link' => 'Webhook|webhook|browse', 'subModule' => 'webhook');
$lang->admin->subMenu->message->browser = array('link' => '浏览器|message|browser');
$lang->admin->subMenu->message->setting = array('link' => '設定|message|setting', 'subModule' => 'message');

$lang->admin->subMenu->sso = new stdclass();
$lang->admin->subMenu->sso->ranzhi = '然之協同|admin|sso';

$lang->admin->subMenu->dev = new stdclass();
$lang->admin->subMenu->dev->api = array('link' => 'API|dev|api');
$lang->admin->subMenu->dev->db = array('link' => 'データベース|dev|db');
$lang->admin->subMenu->dev->editor = array('link' => 'エディター|dev|editor');
$lang->admin->subMenu->dev->entry = array('link' => 'アプリケーション|entry|browse', 'subModule' => 'entry');

$lang->admin->subMenu->data = new stdclass();
$lang->admin->subMenu->data->backup = array('link' => 'バックアップ|backup|index', 'subModule' => 'backup');
$lang->admin->subMenu->data->trash = 'ごみ箱|action|trash';

$lang->admin->subMenu->system = new stdclass();
$lang->admin->subMenu->system->cron = array('link' => '定時|cron|index', 'subModule' => 'cron');
$lang->admin->subMenu->system->timezone = array('link' => 'タイムゾーン|custom|timezone', 'subModule' => 'custom');

$lang->convert = new stdclass();
$lang->upgrade = new stdclass();
$lang->action = new stdclass();
$lang->backup = new stdclass();
$lang->extension = new stdclass();
$lang->custom = new stdclass();
$lang->mail = new stdclass();
$lang->cron = new stdclass();
$lang->dev = new stdclass();
$lang->entry = new stdclass();
$lang->webhook = new stdclass();
$lang->message = new stdclass();
$lang->search = new stdclass();

$lang->convert->menu = $lang->admin->menu;
$lang->upgrade->menu = $lang->admin->menu;
$lang->action->menu = $lang->admin->menu;
$lang->backup->menu = $lang->admin->menu;
$lang->cron->menu = $lang->admin->menu;
$lang->extension->menu = $lang->admin->menu;
$lang->custom->menu = $lang->admin->menu;
$lang->mail->menu = $lang->admin->menu;
$lang->dev->menu = $lang->admin->menu;
$lang->entry->menu = $lang->admin->menu;
$lang->webhook->menu = $lang->admin->menu;
$lang->message->menu = $lang->admin->menu;

/* 菜单分组。*/
$lang->menugroup = new stdclass();
$lang->menugroup->release = 'product';
$lang->menugroup->story = 'product';
$lang->menugroup->branch = 'product';
$lang->menugroup->productplan = 'product';
$lang->menugroup->task = 'project';
$lang->menugroup->build = 'project';
$lang->menugroup->convert = 'admin';
$lang->menugroup->upgrade = 'admin';
$lang->menugroup->user = 'company';
$lang->menugroup->group = 'company';
$lang->menugroup->bug = 'qa';
$lang->menugroup->testcase = 'qa';
$lang->menugroup->case = 'qa';
$lang->menugroup->testtask = 'qa';
$lang->menugroup->testsuite = 'qa';
$lang->menugroup->caselib = 'qa';
$lang->menugroup->testreport = 'qa';
$lang->menugroup->doclib = 'doc';
$lang->menugroup->people = 'company';
$lang->menugroup->dept = 'company';
$lang->menugroup->todo = 'my';
$lang->menugroup->score = 'my';
$lang->menugroup->action = 'admin';
$lang->menugroup->backup = 'admin';
$lang->menugroup->cron = 'admin';
$lang->menugroup->extension = 'admin';
$lang->menugroup->custom = 'admin';
$lang->menugroup->mail = 'admin';
$lang->menugroup->dev = 'admin';
$lang->menugroup->entry = 'admin';
$lang->menugroup->webhook = 'admin';
$lang->menugroup->message = 'admin';

$lang->menugroup->repo    = 'ci';
$lang->menugroup->jenkins = 'ci';
$lang->menugroup->compile = 'ci';
$lang->menugroup->job     = 'ci';

/* 错误提示信息。*/
$lang->error = new stdclass();
$lang->error->companyNotFound = 'ごアクセスのドメイン名%sは相応な企業がありません。';
$lang->error->length = array("『%s』の長さはエラーがあります、『%s』でなければなりません", "『%s』の長さが『%s』を超えず、それに『%s』より大きくなってください。");
$lang->error->reg = '「%s」はフォーマットに準拠していません。「%s」になってください。';
$lang->error->unique = '「%s」には「%s」というレコードがあります。レコードが削除されたと判断した場合、設定-ごみ箱で復元してください。';
$lang->error->gt = '「%s」には、「開始時間」より後の時間を指定してください。';
$lang->error->ge = '「%s」には、「開始時間」より後の時間を指定してください。';
$lang->error->notempty = '「%s」を入力してください。';
$lang->error->empty = '「%s」を入力してください。';
$lang->error->equal = '「%s」は「%s」でなければなりません。';
$lang->error->int = array("数字を入力してください。");
$lang->error->float = '「%s」は数字（小数含む）で入力してください。';
$lang->error->email = '「%s」は正しいEMAILでなければなりません。';
$lang->error->URL = '「%s」应当为合法的URL。';
$lang->error->date = '「%s」はyyyy-mm-ddで入力してください。';
$lang->error->datetime = '「%s」はyyyy-mm-ddで入力してください。';
$lang->error->code = '「%s」はアルファベットまたは数字の組み合わせでなければなりません。';
$lang->error->account = '「%s」は半角英数字で3文字以上入力してください。';
$lang->error->passwordsame = 'パスワードとパスワード（確認）が一致しません。';
$lang->error->passwordrule = 'パスワードはルールに準拠してください。長さは6文字以上でなければなりません。';
$lang->error->accessDenied = 'アクセス権限がありません。';
$lang->error->pasteImg = 'ごブラウザは画像の貼り付けをサポートしていません！';
$lang->error->noData = 'データがありません。';
$lang->error->editedByOther = '当該レコードは変更されている可能性があります。ページを更新して再編集してください！';
$lang->error->tutorialData = '初心者モードではデータが挿入できません、初心者モードを退出して操作してください';
$lang->error->noCurlExt       = '服务器未安装Curl模块。';

/* 分页信息。*/
$lang->pager = new stdclass();
$lang->pager->noRecord = 'レコードがありません';
$lang->pager->digest = '合計<strong>%s</strong> のレコード、%s <strong>%s/%s</strong> &nbsp;';
$lang->pager->recPerPage = '１ページに <strong>%s</strong> 項目';
$lang->pager->first = "<i class='icon-step-backward' title='ホーム'></i>";
$lang->pager->pre = "<i class='icon-play icon-flip-horizontal' title='前のページ'></i>";
$lang->pager->next = "<i class='icon-play' title='次のページ'></i>";
$lang->pager->last = "<i class='icon-step-forward' title='最後のページ'></i>";
$lang->pager->locate = 'GO!';
$lang->pager->previousPage = '前のページ';
$lang->pager->nextPage = '次のページ';
$lang->pager->summery = '第 <strong>%s-%s</strong> 項目、合計<strong>%s</strong> 項目';
$lang->pager->pageOfText = '第 {0} ページ';
$lang->pager->firstPage = '1ページ';
$lang->pager->lastPage = 'ラストページ';
$lang->pager->goto = 'ジャンプ';
$lang->pager->pageOf = '第 <strong>{page}</strong> ページ';
$lang->pager->totalPage = '合計 <strong>{totalPage}</strong> ページ';
$lang->pager->totalCount = '合計 <strong>{recTotal}</strong> 項目';
$lang->pager->pageSize = '1ページ <strong>{recPerPage}</strong> 項目';
$lang->pager->itemsRange = '第 <strong>{start}</strong> ~ <strong>{end}</strong> 項目';
$lang->pager->pageOfTotal = '第 <strong>{page}</strong>/<strong>{totalPage}</strong> ページ';

$lang->colorPicker = new stdclass();
$lang->colorPicker->errorTip = '有効な値ではありません';

$lang->proVersion = "<a href='https://www.zentao.jp?item=proversion&from=footer' target='_blank' id='proLink' class='text-important'>プロ版 <i class='text-danger icon-pro-version'></i></a> &nbsp;";
$lang->downNotify = 'デスクトップリマインダーをダウンロード';
$lang->downloadClient = 'クライアントダウンロード';
$lang->clientHelp = 'クライアント使用説明';
$lang->clientHelpLink = 'http://www.zentao.net/book/zentaopmshelp/302.html#2';
$lang->website = 'https://www.zentao.jp';

$lang->suhosinInfo = '注意：データが多すぎで、php.iniで<font color=red>sohusin.post.max_vars</font>と<font color=red>sohusin.request.max_vars</font>（%sより大きい数）を修正してください。保存してからapache或いはphp-fpmを再起動、そうしないと一部分のデータが保存できません。';
$lang->maxVarsInfo = '注意：データが多すぎで、php.iniで<font color=red>max_input_vars</font>（%sより大きい数）を修正してください。保存してからapache或いはphp-fpmを再起動、そうしないと一部分のデータが保存できません。';
$lang->pasteTextInfo = 'テキストエリアにテキストを貼り付ける場合、各行の文字が一つのデータ名になります。';
$lang->noticeImport = 'おインポートのデータは既にシステムに保存しているデータがありますので、これらのデータをオーバーライド或いは改めて挿入することを選択してください。';
$lang->importConfirm = 'インポート確認';
$lang->importAndCover = 'オーバーライド';
$lang->importAndInsert = '改めて挿入';

$lang->noResultsMatch = 'マッチング結果がありません';
$lang->searchMore = '当該キーワードについてもっと多く結果を検索：';
$lang->chooseUsersToMail = 'メールで知らせるユーザを選択…';
$lang->noticePasteImg = '直接画像を貼り付けられます。';
$lang->pasteImgFail = '失敗しました、もう一度試してください。';
$lang->pasteImgUploading = '画像アップロード中、お待ちください…';

/* 时间格式设置。*/
if(!defined('DT_DATETIME1'))  define('DT_DATETIME1',  'Y-m-d H:i:s');
if(!defined('DT_DATETIME2'))  define('DT_DATETIME2',  'y-m-d H:i');
if(!defined('DT_MONTHTIME1')) define('DT_MONTHTIME1', 'n/d H:i');
if(!defined('DT_MONTHTIME2')) define('DT_MONTHTIME2', 'n月d日 H:i');
if(!defined('DT_DATE1'))      define('DT_DATE1',     'Y-m-d');
if(!defined('DT_DATE2'))      define('DT_DATE2',     'Ymd');
if(!defined('DT_DATE3'))      define('DT_DATE3',     'Y年m月d日');
if(!defined('DT_DATE4'))      define('DT_DATE4',     'n月j日');
if(!defined('DT_DATE5'))      define('DT_DATE5',     'j/n');
if(!defined('DT_TIME1'))      define('DT_TIME1',     'H:i:s');
if(!defined('DT_TIME2'))      define('DT_TIME2',     'H:i');

/* datepicker 时间*/
$lang->datepicker = new stdclass();

$lang->datepicker->dpText = new stdclass();
$lang->datepicker->dpText->TEXT_OR = '或いは';
$lang->datepicker->dpText->TEXT_PREV_YEAR = '去年';
$lang->datepicker->dpText->TEXT_PREV_MONTH = '先月';
$lang->datepicker->dpText->TEXT_PREV_WEEK = '先週';
$lang->datepicker->dpText->TEXT_YESTERDAY = '昨日';
$lang->datepicker->dpText->TEXT_THIS_MONTH = '本月';
$lang->datepicker->dpText->TEXT_THIS_WEEK = '今週';
$lang->datepicker->dpText->TEXT_TODAY = '今日';
$lang->datepicker->dpText->TEXT_NEXT_YEAR = '来年';
$lang->datepicker->dpText->TEXT_NEXT_MONTH = '来月';
$lang->datepicker->dpText->TEXT_CLOSE = 'クローズ';
$lang->datepicker->dpText->TEXT_DATE = '時間帯選択';
$lang->datepicker->dpText->TEXT_CHOOSE_DATE = '日付け選択';

$lang->datepicker->dayNames = array('日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日');
$lang->datepicker->abbrDayNames = array('日', '月', '火', '水', '木', '金', '土');
$lang->datepicker->monthNames = array('1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月');

/* Common action icons 通用动作图标 */
$lang->icons['todo'] = 'check';
$lang->icons['product'] = 'cube';
$lang->icons['bug'] = 'bug';
$lang->icons['task'] = 'check-sign';
$lang->icons['tasks'] = 'tasks';
$lang->icons['project'] = 'stack';
$lang->icons['doc'] = 'file-text';
$lang->icons['doclib'] = 'folder-close';
$lang->icons['story'] = 'lightbulb';
$lang->icons['release'] = 'tags';
$lang->icons['roadmap'] = 'code-fork';
$lang->icons['plan'] = 'flag';
$lang->icons['dynamic'] = 'volume-up';
$lang->icons['build'] = 'tag';
$lang->icons['test'] = 'check';
$lang->icons['testtask'] = 'check';
$lang->icons['group'] = 'group';
$lang->icons['team'] = 'group';
$lang->icons['company'] = 'sitemap';
$lang->icons['user'] = 'user';
$lang->icons['dept'] = 'sitemap';
$lang->icons['tree'] = 'sitemap';
$lang->icons['usecase'] = 'sitemap';
$lang->icons['testcase'] = 'sitemap';
$lang->icons['result'] = 'list-alt';
$lang->icons['mail'] = 'envelope';
$lang->icons['trash'] = 'trash';
$lang->icons['extension'] = 'th-large';
$lang->icons['app'] = 'th-large';

$lang->icons['results'] = 'list-alt';
$lang->icons['create'] = 'plus';
$lang->icons['post'] = 'edit';
$lang->icons['batchCreate'] = 'plus-sign';
$lang->icons['batchEdit'] = 'edit-sign';
$lang->icons['batchClose'] = 'off';
$lang->icons['edit'] = 'edit';
$lang->icons['delete'] = 'close';
$lang->icons['copy'] = 'copy';
$lang->icons['report'] = 'bar-chart';
$lang->icons['export'] = 'export';
$lang->icons['report-file'] = 'file-powerpoint';
$lang->icons['import'] = 'import';
$lang->icons['finish'] = 'checked';
$lang->icons['resolve'] = 'check';
$lang->icons['start'] = 'play';
$lang->icons['restart'] = 'play';
$lang->icons['run'] = 'play';
$lang->icons['runCase'] = 'play';
$lang->icons['batchRun'] = 'play-sign';
$lang->icons['assign'] = 'hand-right';
$lang->icons['assignTo'] = 'hand-right';
$lang->icons['change'] = 'fork';
$lang->icons['link'] = 'link';
$lang->icons['close'] = 'off';
$lang->icons['activate'] = 'magic';
$lang->icons['review'] = 'glasses';
$lang->icons['confirm'] = 'search';
$lang->icons['confirmBug'] = 'search';
$lang->icons['putoff'] = 'calendar';
$lang->icons['suspend'] = 'pause';
$lang->icons['pause'] = 'pause';
$lang->icons['cancel'] = 'ban-circle';
$lang->icons['recordEstimate'] = 'time';
$lang->icons['customFields'] = 'cogs';
$lang->icons['manage'] = 'cog';
$lang->icons['unlock'] = 'unlock-alt';
$lang->icons['confirmStoryChange'] = 'search';
$lang->icons['score'] = 'tint';

include (dirname(__FILE__) . '/menuOrder.php');

global $config;
if(isset($config->global->flow) and $config->global->flow == 'onlyStory')
{
unset($lang->menu->project);
unset($lang->menu->report);
unset($lang->menu->qa);

unset($lang->menuOrder[15]);
unset($lang->menuOrder[20]);
unset($lang->menuOrder[30]);

unset($lang->my->menu->bug);
unset($lang->my->menu->testtask);
unset($lang->my->menu->task);
unset($lang->my->menu->myProject);

unset($lang->product->menu->project);
unset($lang->product->menu->doc);

$lang->menu->product = "{$lang->productCommon}|product|index";

unset($lang->searchObjects['bug']);
unset($lang->searchObjects['task']);
unset($lang->searchObjects['testcase']);
unset($lang->searchObjects['project']);
unset($lang->searchObjects['build']);
unset($lang->searchObjects['testtask']);
unset($lang->searchObjects['testsuite']);
unset($lang->searchObjects['testreport']);
}

if(isset($config->global->flow) and $config->global->flow == 'onlyTask')
{
    /* Remove project and test module. */
    unset($lang->menu->project);
    unset($lang->menu->qa);
    unset($lang->menu->report);

    unset($lang->menuOrder[15]);
    unset($lang->menuOrder[20]);
    unset($lang->menuOrder[35]);

    /* Rename product module. */
    $lang->menu->product = "{$lang->productCommon}|product|index";

    /* Adjust sub menu of my dashboard. */
    unset($lang->my->menu->task);
    unset($lang->my->menu->myProject);
    unset($lang->my->menu->story);

    /* Remove sub menu of project module. */
    unset($lang->project->menu);
    unset($lang->project->menuOrder);
    $lang->project->menu = new stdclass();
    $lang->project->menu->list = array('alias' => '');

    /* Add bug, testcase and testtask module. */
    $lang->menu->bug       = 'Bug|bug|index';
    $lang->menu->testcase  = '功能测试|testcase|browse';
    $lang->menu->unit      = '单元测试|testtask|browseUnits';
    $lang->menu->testsuite = '套件|testsuite|index';
    $lang->menu->testtask  = '测试单|testtask|index';
    $lang->menu->caselib   = '用例库|caselib|browse';

    $lang->menuOrder[6]  = 'bug';
    $lang->menuOrder[7]  = 'testcase';
    $lang->menuOrder[8]  = 'unit';
    $lang->menuOrder[9]  = 'testsuite';
    $lang->menuOrder[10] = 'testtask';
    $lang->menuOrder[11] = 'caselib';
    $lang->menuOrder[12] = 'product';

    /* Adjust sub menu of bug module. */
    $lang->bug->menu = new stdclass();
    $lang->bug->menu->all           = '所有|bug|browse|productID=%s&branch=%s&browseType=all&param=%s';
    $lang->bug->menu->unclosed      = '未关闭|bug|browse|productID=%s&branch=%s&browseType=unclosed&param=%s';
    $lang->bug->menu->openedbyme    = '由我创建|bug|browse|productID=%s&branch=%s&browseType=openedbyme&param=%s';
    $lang->bug->menu->assigntome    = '指派给我|bug|browse|productID=%s&branch=%s&browseType=assigntome&param=%s';
    $lang->bug->menu->resolvedbyme  = '由我解决|bug|browse|productID=%s&branch=%s&browseType=resolvedbyme&param=%s';
    $lang->bug->menu->toclosed      = '待关闭|bug|browse|productID=%s&branch=%s&browseType=toclosed&param=%s';
    $lang->bug->menu->unresolved    = '未解决|bug|browse|productID=%s&branch=%s&browseType=unresolved&param=%s';
    $lang->bug->menu->more          = array('link' => '更多|bug|browse|productID=%s&branch=%s&browseType=unconfirmed&param=%s', 'class' => 'dropdown dropdown-hover');

    $lang->bug->subMenu = new stdclass();
    $lang->bug->subMenu->more = new stdclass();
    $lang->bug->subMenu->more->unconfirmed   = '未确认|bug|browse|productID=%s&branch=%s&browseType=unconfirmed&param=%s';
    $lang->bug->subMenu->more->assigntonull  = '未指派|bug|browse|productID=%s&branch=%s&browseType=assigntonull&param=%s';
    $lang->bug->subMenu->more->longlifebugs  = '久未处理|bug|browse|productID=%s&branch=%s&browseType=longlifebugs&param=%s';
    $lang->bug->subMenu->more->postponedbugs = '被延期|bug|browse|productID=%s&branch=%s&browseType=postponedbugs&param=%s';
    $lang->bug->subMenu->more->overduebugs   = '过期Bug|bug|browse|productID=%s&branch=%s&browseType=overduebugs&param=%s';
    $lang->bug->subMenu->more->needconfirm   = "{$lang->storyCommon}变动|bug|browse|productID=%s&branch=%s&browseType=needconfirm&param=%s";

    $lang->bug->menuOrder[5]  = 'product';
    $lang->bug->menuOrder[10] = 'all';
    $lang->bug->menuOrder[15] = 'unclosed';
    $lang->bug->menuOrder[20] = 'openedbyme';
    $lang->bug->menuOrder[25] = 'assigntome';
    $lang->bug->menuOrder[30] = 'resolvedbyme';
    $lang->bug->menuOrder[35] = 'toclosed';
    $lang->bug->menuOrder[40] = 'unresolved';
    $lang->bug->menuOrder[45] = 'unconfirmed';
    $lang->bug->menuOrder[50] = 'assigntonull';
    $lang->bug->menuOrder[55] = 'longlifebugs';
    $lang->bug->menuOrder[60] = 'postponedbugs';
    $lang->bug->menuOrder[65] = 'overduebugs';
    $lang->bug->menuOrder[70] = 'needconfirm';

    /* Adjust sub menu of testcase. */
    $lang->testcase->menu = new stdclass();
    $lang->testcase->menu->all     = '所有|testcase|browse|productID=%s&branch=%s&browseType=all';
    $lang->testcase->menu->wait    = '待评审|testcase|browse|productID=%s&branch=%s&browseType=wait';
    $lang->testcase->menu->bysuite = array('link' => '套件|testsuite|create|productID=%s', 'class' => 'dropdown dropdown-hover');

    $lang->testcase->subMenu = new stdclass();
    $lang->testcase->subMenu->bysuite = new stdclass();
    $lang->testcase->subMenu->bysuite->create = '建套件|testsuite|create|productID=%s';

    $lang->testcase->menuOrder[5]  = 'product';
    $lang->testcase->menuOrder[10] = 'all';
    $lang->testcase->menuOrder[15] = 'wait';
    $lang->testcase->menuOrder[20] = 'suite';

    /* Adjust sub menu of bug module. */
    $lang->testsuite->menu = new stdclass();

    $lang->testsuite->menuOrder[5]  = 'product';

    /* Adjust sub menu of testtask. */
    $lang->testtask->menu = new stdclass();
    $lang->testtask->menu->totalStatus = '所有|testtask|browse|productID=%s&branch=%s&type=%s,totalStatus';
    $lang->testtask->menu->wait        = '待测版本|testtask|browse|productID=%s&branch=%s&type=%s,wait';
    $lang->testtask->menu->doing       = '测试中版本|testtask|browse|productID=%s&branch=%s&type=%s,doing';
    $lang->testtask->menu->blocked     = '被阻塞版本|testtask|browse|productID=%s&branch=%s&type=%s,blocked';
    $lang->testtask->menu->done        = '已测版本|testtask|browse|productID=%s&branch=%s&type=%s,done';
    $lang->testtask->menu->report      = array('link' => '报告|testreport|browse', 'alias' => 'view,create,edit');

    $lang->testtask->menuOrder[5]  = 'product';
    $lang->testtask->menuOrder[10] = 'scope';
    $lang->testtask->menuOrder[15] = 'totalStatus';
    $lang->testtask->menuOrder[20] = 'wait';
    $lang->testtask->menuOrder[25] = 'doing';
    $lang->testtask->menuOrder[30] = 'blocked';
    $lang->testtask->menuOrder[35] = 'done';
    $lang->testtask->menuOrder[40] = 'report';

    $lang->testreport->menu      = $lang->testtask->menu;
    $lang->testreport->menuOrder = $lang->testtask->menuOrder;

    /* Adjust sub menu of caselib module. */
    $lang->caselib->menu = new stdclass();
    $lang->caselib->menu->all  = '所有|caselib|browse|libID=%s&browseType=all';
    $lang->caselib->menu->wait = '待评审|caselib|browse|libID=%s&browseType=wait';
    $lang->caselib->menu->view = '概况|caselib|view|libID=%s';

    $lang->caselib->menuOrder[5]  = 'lib';
    $lang->caselib->menuOrder[10] = 'all';
    $lang->caselib->menuOrder[15] = 'wait';
    $lang->caselib->menuOrder[20] = 'view';

    /* Adjust sub menu of product module. */
    unset($lang->product->menu->story);
    unset($lang->product->menu->project);
    unset($lang->product->menu->release);
    unset($lang->product->menu->dynamic);
    unset($lang->product->menu->plan);
    unset($lang->product->menu->roadmap);
    unset($lang->product->menu->doc);
    unset($lang->product->menu->module);
    unset($lang->product->menu->index);

    $lang->product->menu->build = array('link' => '版本|product|build', 'subModule' => 'build');

    $lang->product->menuOrder[5]  = 'build';
    $lang->product->menuOrder[10] = 'view';
    $lang->product->menuOrder[15] = 'order';

    $lang->build->menu      = $lang->product->menu;
    $lang->build->menuOrder = $lang->product->menuOrder;

    /* Adjust menu group. */
    $lang->menugroup->bug        = 'bug';
    $lang->menugroup->testcase   = 'testcase';
    $lang->menugroup->case       = 'testcase';
    $lang->menugroup->testtask   = 'testtask';
    $lang->menugroup->testsuite  = 'testsuite';
    $lang->menugroup->caselib    = 'caselib';
    $lang->menugroup->testreport = 'testtask';
    $lang->menugroup->build      = 'product';

    /* Adjust search objects. */
    unset($lang->searchObjects['story']);
    unset($lang->searchObjects['task']);
    unset($lang->searchObjects['release']);
    unset($lang->searchObjects['project']);
    unset($lang->searchObjects['productplan']);
}
