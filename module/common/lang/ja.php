<?php
/**
 * The common simplified chinese file of ZenTaoMS.
 *
 * This file should be UTF-8 encoded.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoMS
 * @version     $Id: en.php 1015 2010-08-03 05:50:35Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->arrow        = '»';
$lang->colon        = '：：';
$lang->comma        = '，';
$lang->dot          = '。';
$lang->at           = 'に';
$lang->downArrow    = '↓';

$lang->zentaoMS     = 'ZenTaoPMS';
$lang->welcome      = "Welcome to『%s』{$lang->colon} {$lang->zentaoMS}";
$lang->myControl    = "ダッシュボード";
$lang->currentPos   = '現在の';
$lang->logout       = 'ログアウト';
$lang->login        = 'ログイン';
$lang->aboutZenTao  = '約';
$lang->todayIs      = 'Today is %s，';

$lang->reset        = 'リセット';
$lang->edit         = '[編集]';
$lang->copy         = 'コピー';
$lang->delete       = '削除';
$lang->close        = 'クローズ';
$lang->link         = 'リンク';
$lang->unlink       = '解除';
$lang->import       = 'インポート';
$lang->exportCSV    = 'CSV';
$lang->setFileName  = '入力してくださいファイル名を：';
$lang->activate     = 'アクティブ';
$lang->save         = '保存';
$lang->confirm      = '確認';
$lang->preview      = 'プレビュー';
$lang->goback       = 'バック';
$lang->go           = 'でGO！';
$lang->more         = 'もっと';

$lang->actions      = 'アクション';
$lang->comment      = 'コメント';
$lang->history      = '歴史';
$lang->attatch      = 'Attatch';
$lang->reverse      = '（逆）';
$lang->switchDisplay= '[switch display]';
$lang->switchHelp   = 'Toggle Help';
$lang->addFiles     = 'ファイルを追加する';
$lang->files        = 'ファイル';

$lang->selectAll    = 'すべて選択';
$lang->notFound     = '申し訳ありませんが、オブジェクトが見つかりません。';
$lang->showAll      = '+ +すべての+ +を表示する';
$lang->hideClosed   = '- クローズド隠す -';

$lang->feature      = '機能';
$lang->year         = '年';
$lang->workingHour  = '時間';

$lang->idAB         = 'IDは';
$lang->priAB        = 'P';
$lang->statusAB     = 'ステータス';
$lang->openedByAB   = 'オープン';
$lang->assignedToAB = 'に';
$lang->typeAB       = 'タイプ';

/* 主导航菜单。*/
$lang->menu->index   = 'インデックス|index|index';
$lang->menu->my      = 'ダッシュボード|my|index';
$lang->menu->product = '製品|product|index';
$lang->menu->project = 'プロジェクト|project|index';
$lang->menu->qa      = '品質保証|qa|index';
$lang->menu->doc     = 'ドック|doc|index';
//$lang->menu->forum   = '讨论视图|doc|index';
$lang->menu->company = '会社|company|index';
$lang->menu->admin   = '管理|admin|index';

/* 查询条中可以选择的对象列表。*/
$lang->searchObjects['bug']         = 'バグ';
$lang->searchObjects['story']       = 'ストーリー';
$lang->searchObjects['task']        = 'タスク';
$lang->searchObjects['testcase']    = 'ケース';
$lang->searchObjects['project']     = 'プロジェクト';
$lang->searchObjects['product']     = '製品';
$lang->searchObjects['user']        = 'ユーザー';
$lang->searchObjects['build']       = 'ビルド';
$lang->searchObjects['release']     = 'リリース';
$lang->searchObjects['productplan'] = '計画';
$lang->searchObjects['testtask']    = 'テストタスク';
$lang->searchTips                   = 'idはここ';

/* 首页菜单设置。*/
$lang->index->menu->product = '製品情報|product|browse';
$lang->index->menu->project = 'プロジェクト|project|browse';

/* 我的地盘菜单设置。*/
$lang->my->menu->account  = '%s' . $lang->arrow;
$lang->my->menu->todo     = array('link' => '藤堂|my|todo|', 'subModule' => 'todo');
$lang->my->menu->task     = 'タスク|my|task|';
$lang->my->menu->bug      = 'バグ|my|bug|';
$lang->my->menu->story    = 'ストーリー|my|story|';
$lang->my->menu->project  = 'プロジェクト|my|project|';
$lang->my->menu->profile  = array('link' => 'プロフィール|my|profile|', 'alias' => 'editprofile');
$lang->todo->menu         = $lang->my->menu;

/* 产品视图设置。*/
$lang->product->menu->list   = '%s';
$lang->product->menu->story  = array('link' => 'ストーリー|product|browse|productID=%s',     'subModule' => 'story');
$lang->product->menu->plan   = array('link' => '計画|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release= array('link' => 'リリース|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap= 'ロードマップ|product|roadmap|productID=%s';
$lang->product->menu->doc    = array('link' => 'ドク|product|doc|productID=%s', 'subModule' => 'doc');
$lang->product->menu->view   = '情報|product|view|productID=%s';
$lang->product->menu->edit   = '[編集]|product|edit|productID=%s';
$lang->product->menu->module = 'モジュール|tree|browse|productID=%s&view=story';
$lang->product->menu->delete = array('link' => '削除|product|delete|productID=%s', 'target' => 'hiddenwin');
$lang->product->menu->create = array('link' => '新製品|product|create', 'float' => 'right');
$lang->story->menu           = $lang->product->menu;
$lang->productplan->menu     = $lang->product->menu;
$lang->release->menu         = $lang->product->menu;

/* 项目视图菜单设置。*/
$lang->project->menu->list      = '%s';
$lang->project->menu->task      = array('link' => 'タスク|project|task|projectID=%s', 'subModule' => 'task', 'alias' => 'grouptask,importtask');
$lang->project->menu->story     = array('link' => 'ストーリー|project|story|projectID=%s');
$lang->project->menu->bug       = 'バグ|project|bug|projectID=%s';
$lang->project->menu->build     = array('link' => 'ビルド|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->menu->burn      = 'バーン|project|burn|projectID=%s';
$lang->project->menu->team      = array('link' => 'チーム|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->doc       = array('link' => 'ドク|project|doc|porjectID=%s', 'subModule' => 'doc');
$lang->project->menu->product   = 'リンク製品|project|manageproducts|projectID=%s';
$lang->project->menu->linkstory = array('link' => 'リンクストーリー|project|linkstory|projectID=%s');
$lang->project->menu->view      = '情報|project|view|projectID=%s';
$lang->project->menu->edit      = '[編集]|project|edit|projectID=%s';
$lang->project->menu->delete    = array('link' => '削除|project|delete|projectID=%s', 'target' => 'hiddenwin');

$lang->project->menu->create = array('link' => 'New Project|project|create', 'float' => 'right');
$lang->task->menu            = $lang->project->menu;
$lang->build->menu           = $lang->project->menu;

/* QA视图菜单设置。*/
$lang->bug->menu->product  = '%s';
$lang->bug->menu->bug      = array('link' => 'バグ|bug|browse|productID=%s', 'alias' => 'view,create,edit,resolve,close,activate,report', 'subModule' => 'tree');
$lang->bug->menu->testcase = array('link' => 'ケース|testcase|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->bug->menu->testtask = array('link' => 'タスク|testtask|browse|productID=%s');

$lang->testcase->menu->product  = '%s';
$lang->testcase->menu->bug      = array('link' => 'バグ|bug|browse|productID=%s');
$lang->testcase->menu->testcase = array('link' => 'ケース|testcase|browse|productID=%s', 'alias' => 'view,create,edit', 'subModule' => 'tree');
$lang->testcase->menu->testtask = array('link' => 'タスク|testtask|browse|productID=%s');

$lang->testtask->menu->product  = '%s';
$lang->testtask->menu->bug      = array('link' => 'バグ|bug|browse|productID=%s');
$lang->testtask->menu->testcase = array('link' => 'ケース|testcase|browse|productID=%s');
$lang->testtask->menu->testtask = array('link' => 'タスク|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases');

/* 文档视图菜单设置。*/
$lang->doc->menu->list    = '%s';
$lang->doc->menu->browse  = array('link' => 'ドク|doc|browse|libID=%s');
$lang->doc->menu->edit    = '[編集]ライブラリ|doc|editLib|libID=%s';
$lang->doc->menu->module  = 'モジュール|tree|browse|libID=%s&viewType=doc';
$lang->doc->menu->delete  = array('link' => '削除ライブラリ|doc|deleteLib|libID=%s', 'target' => 'hiddenwin');
$lang->doc->menu->create  = array('link' => '新しいライブラリ|doc|createLib', 'float' => 'right');

/* 组织结构视图菜单设置。*/
$lang->company->menu->name        = '%s' . $lang->arrow;
$lang->company->menu->browseUser  = array('link' => 'ユーザー|company|browse', 'subModule' => 'user');
$lang->company->menu->dept        = array('link' => '部門|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup = array('link' => 'グループ|group|browse', 'subModule' => 'group');
$lang->company->menu->edit        = array('link' => '会社|company|edit');
$lang->company->menu->addGroup    = array('link' => 'グループを追加|group|create', 'float' => 'right');
$lang->company->menu->addUser     = array('link' => 'ユーザーを追加する|user|create|dept=%s&from=company', 'subModule' => 'user', 'float' => 'right');
$lang->dept->menu            = $lang->company->menu;
$lang->group->menu           = $lang->company->menu;

/* 用户信息菜单设置。*/
$lang->user->menu->account  = '%s' . $lang->arrow;
$lang->user->menu->todo     = array('link' => '藤堂|user|todo|account=%s', 'subModule' => 'todo');
$lang->user->menu->task     = 'タスク|user|task|account=%s';
$lang->user->menu->bug      = 'バグ列表|user|bug|account=%s';
$lang->user->menu->project  = 'プロジェクト|user|project|account=%s';
$lang->user->menu->profile  = array('link' => 'プロフィール|user|profile|account=%s', 'alias' => 'edit');
$lang->user->menu->browse   = array('link' => '用户管理|company|browse|', 'float' => 'right');

/* 后台管理菜单设置。*/
$lang->admin->menu->trashes = array('link' => 'ごみ|action|trash', 'subModule' => 'action');
$lang->admin->menu->convert = array('link' => 'インポート|convert|index', 'subModule' => 'convert');
$lang->convert->menu        = $lang->admin->menu;
$lang->upgrade->menu        = $lang->admin->menu;
$lang->action->menu         = $lang->admin->menu;

/*菜单设置：分组设置。*/
$lang->menugroup->release     = 'product';
$lang->menugroup->story       = 'product';
$lang->menugroup->productplan = 'product';
$lang->menugroup->task        = 'project';
$lang->menugroup->build       = 'project';
$lang->menugroup->convert     = 'admin';
$lang->menugroup->upgrade     = 'admin';
$lang->menugroup->user        = 'company';
$lang->menugroup->group       = 'company';
$lang->menugroup->bug         = 'qa';
$lang->menugroup->testcase    = 'qa';
$lang->menugroup->testtask    = 'qa';
$lang->menugroup->people      = 'company';
$lang->menugroup->dept        = 'company';
$lang->menugroup->todo        = 'my';
$lang->menugroup->action      = 'admin';

/* 错误提示信息。*/
$lang->error->companyNotFound = "The domain %s does not exist.";
$lang->error->length          = array("『%s』length should be『%s』", "『%s』length should between『%s』and 『%s』.");
$lang->error->reg             = "『%s』should like『%s』";
$lang->error->unique          = "『%s』has『%s』already.";
$lang->error->notempty        = "『%s』can not be empty.";
$lang->error->equal           = "『%s』must be『%s』。";
$lang->error->int             = array("『%s』should be interger", "『%s』should between『%s-%s』.");
$lang->error->float           = "『%s』should be a interger or float.";
$lang->error->email           = "『%s』should be email.";
$lang->error->date            = "『%s』should be date";
$lang->error->account         = "『%s』should be a valid account.";
$lang->error->passwordsame    = "2つのパスワードが同じでなければなりません";
$lang->error->passwordrule    = "パスワード必要以上の6文字。";

/* 分页信息。*/
$lang->pager->noRecord  = "レコードはまだありません。";
$lang->pager->digest    = "<strong>%s</strong> records, <strong>%s</strong> per page, <strong>%s/%s</strong> ";
$lang->pager->first     = "最初の";
$lang->pager->pre       = "前";
$lang->pager->next      = "次の";
$lang->pager->last      = "最後に";
$lang->pager->locate    = "でGO！";

$lang->zentaoSite     = "公式サイト";
$lang->sponser        = "<a href='http://www.pujia.com' target='_blank'>PuJia Sponsed</a>";
$lang->zentaoKeywords = "オープンソースのプロジェクト管理システム";
$lang->zentaoDESC     = "ZenTaoPMSは、オープンソースのプロジェクト管理システムです。"; 



/* 时间格式设置。*/
define('DT_DATETIME1',  'Y-m-d H:i:s');
define('DT_DATETIME2',  'y-m-d H:i');
define('DT_MONTHTIME1', 'n/d H:i');
define('DT_MONTHTIME2', 'F j, H:i');
define('DT_DATE1',     'Y-m-d');
define('DT_DATE2',     'Ymd');
define('DT_DATE3',     'F j, Y ');
define('DT_TIME1',     'H:i:s');
define('DT_TIME2',     'H:i');

/* 表情。*/
$lang->smilies->smile       = '笑顔';
$lang->smilies->sad         = '悲しい';
$lang->smilies->wink        = 'ウインク';
$lang->smilies->tongue      = '舌';
$lang->smilies->shocked     = '衝撃';
$lang->smilies->eyesdown    = '失望';
$lang->smilies->angry       = '怒って';
$lang->smilies->cool        = 'クール';
$lang->smilies->indifferent = '無関心な';
$lang->smilies->sick        = '病気の';
$lang->smilies->blush       = '顔を赤らめる';
$lang->smilies->angel       = 'エンジェル';
$lang->smilies->confused    = '混乱';
$lang->smilies->cry         = '叫び';
$lang->smilies->footinmouth = '秘密';
$lang->smilies->biggrin     = '笑い';
$lang->smilies->nerd        = 'オタク';
$lang->smilies->tired       = '疲れた';
$lang->smilies->rose        = 'ローズ';
$lang->smilies->kiss        = 'キス';
$lang->smilies->heart       = 'ラブ';
$lang->smilies->hug         = 'ハグ';
$lang->smilies->dog         = '犬';
$lang->smilies->deadrose    = 'デッドローズ';
$lang->smilies->clock       = '時計';
$lang->smilies->brokenheart = '失恋';
$lang->smilies->coffee      = 'コーヒー';
$lang->smilies->computer    = 'コンピュータ';
$lang->smilies->devil       = '悪魔';
$lang->smilies->thumbsup    = '親指を立ててサインする';
$lang->smilies->thumbsdown  = 'Hhumbダウン';
$lang->smilies->mail        = 'Eメール';
