<?php
/**
 * The user module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      admin
 * @package     user
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->user->common = 'ユーザ';
$lang->user->id = 'ユーザ番号';
$lang->user->company = '所属会社';
$lang->user->dept = '所属部門';
$lang->user->account = 'ユーザID';
$lang->user->password = 'パスワード';
$lang->user->password2 = 'パスワード（確認）';
$lang->user->role = '職位';
$lang->user->group = 'グルーピング';
$lang->user->realname = '氏名';
$lang->user->nickname = 'ニックネ—ム';
$lang->user->commiter = 'ソースコード';
$lang->user->birthyear = '生年月日';
$lang->user->gender = '性別';
$lang->user->email = 'メール';
$lang->user->basicInfo = '基本情報';
$lang->user->accountInfo = 'アカウント情報';
$lang->user->verify = 'セキュリティ検証';
$lang->user->contactInfo = '連絡情報';
$lang->user->skype = 'Skype';
$lang->user->qq = 'Line';
$lang->user->mobile = '携帯';
$lang->user->phone = '電話';
$lang->user->weixin = 'Wechat';
$lang->user->dingding = 'DingTalk';
$lang->user->slack = 'Slack';
$lang->user->whatsapp = 'WhatsApp';
$lang->user->address = 'アドレス';
$lang->user->zipcode = '郵便番号';
$lang->user->join = '入社日';
$lang->user->visits = 'アクセス回数';
$lang->user->ip = '最終ログインIP';
$lang->user->last = '最終ログイン';
$lang->user->ranzhi = '然之アカウント';
$lang->user->ditto = '同上';
$lang->user->originalPassword = '旧パスワード';
$lang->user->newPassword = '新規パスワード';
$lang->user->verifyPassword = '登録者パスワード';
$lang->user->resetPassword = 'パスワードを忘れた方';
$lang->user->score = 'ポイント';

$lang->user->legendBasic = '基本情報';
$lang->user->legendContribution = '個人情報';

$lang->user->index = 'ユーザビューページ';
$lang->user->view = 'ユーザ詳細';
$lang->user->create = 'ユーザ追加';
$lang->user->batchCreate = 'ユーザ一括追加';
$lang->user->edit = 'ユーザ編集';
$lang->user->batchEdit = '一括編集';
$lang->user->unlock = 'ユーザロック解除';
$lang->user->delete = 'ユーザ削除';
$lang->user->unbind = '然之バインド解除';
$lang->user->login = 'ユーザログイン';
$lang->user->mobileLogin = '携帯電話でアクセス';
$lang->user->editProfile = 'プロファイル更新';
$lang->user->deny = 'アクセス制限付き';
$lang->user->confirmDelete = '当該ユーザを削除してもよろしいですか？';
$lang->user->confirmUnlock = '当該ユーザのロックステータスを解除してもよろしいですか？';
$lang->user->confirmUnbind = '当該ユーザと然之のバインドを解除してもよろしいですか？';
$lang->user->relogin = '再ログイン';
$lang->user->asGuest = 'ゲストアクセス';
$lang->user->goback = '前のページに戻ります';
$lang->user->deleted = '（削除した）';
$lang->user->search = '検索';

$lang->user->saveTemplate = 'テンプレート保存';
$lang->user->setPublic = 'パブリックテンプレートに設定';
$lang->user->deleteTemplate = 'テンプレート削除';
$lang->user->setTemplateTitle = 'テンプレートタイトルを入力してください';
$lang->user->applyTemplate = 'アプリケーションテンプレート';
$lang->user->confirmDeleteTemplate = '当該テンプレートを削除してもよろしいですか？';
$lang->user->setPublicTemplate = 'パブリックテンプレートに設定';
$lang->user->tplContentNotEmpty = '模板内容不能为空!';

$lang->user->profile = 'プロファイル';
$lang->user->project = $lang->projectCommon;
$lang->user->task = 'タスク';
$lang->user->bug = 'バグ';
$lang->user->test = 'テスト';
$lang->user->testTask = 'テストタスク';
$lang->user->testCase = 'テストケース';
$lang->user->schedule = '日程';
$lang->user->todo = 'ToDo';
$lang->user->story = $lang->storyCommon;
$lang->user->dynamic = 'アクティビティ';

$lang->user->openedBy = '%sが作成';
$lang->user->assignedTo = '%sにアサイン';
$lang->user->finishedBy = '%sが完了';
$lang->user->resolvedBy = '%sが処理';
$lang->user->closedBy = '%sがクローズ';
$lang->user->reviewedBy = '%sがレビュー';
$lang->user->canceledBy = '%sがキャンセル';

$lang->user->testTask2Him = '担当バージョン';
$lang->user->case2Him = '%sへのケース';
$lang->user->caseByHim = '%sが作成したケース';

$lang->user->errorDeny = '申し訳ございませんが、『<b>%s</b>』モジュールの『<b>%s</b>』機能にアクセスできません。管理者に連絡して権限を取得してください。後退をクリックして前のページに戻ります。';
$lang->user->errorView = '抱歉，您无权访问『<b>%s</b>』视图。请联系管理员获取权限。点击后退返回上页。';
$lang->user->loginFailed = 'ログインに失敗しました。ユーザIDもしくはパスワードが正しくありません。';
$lang->user->lockWarning = 'また%s回入力できます。';
$lang->user->loginLocked = 'パスワードの失敗の回数多すぎます。管理者に連絡してロックを解除、或いは%s分後に再度入力してください。';
$lang->user->weakPassword = 'パスワードの強度が足りません。';
$lang->user->errorWeak = '密码不能使用【%s】这些常用弱口令。';

$lang->user->roleList[''] = '';
$lang->user->roleList['dev'] = '開発';
$lang->user->roleList['qa'] = 'テスト';
$lang->user->roleList['pm'] = 'プロジェクトマネージャ';
$lang->user->roleList['po'] = 'プロダクトマネージャ';
$lang->user->roleList['td'] = '研究開発主管';
$lang->user->roleList['pd'] = 'プロダクト主管';
$lang->user->roleList['qd'] = 'テスト主管';
$lang->user->roleList['top'] = '上級管理';
$lang->user->roleList['others'] = 'その他';

$lang->user->genderList['m'] = '男';
$lang->user->genderList['f'] = '女';

$lang->user->thirdPerson['m'] = '彼';
$lang->user->thirdPerson['f'] = '彼';

$lang->user->passwordStrengthList[0] = '<span>弱</span>';
$lang->user->passwordStrengthList[1] = '<span>中</span>';
$lang->user->passwordStrengthList[2] = '<span>强</span>';

$lang->user->statusList['active'] = '正常';
$lang->user->statusList['delete'] = '削除';

$lang->user->personalData['createdTodo'] = 'ToDo';
$lang->user->personalData['createdStory'] = '作成済' . $lang->storyCommon;
$lang->user->personalData['finishedTask'] = '完了タスク';
$lang->user->personalData['resolvedBug'] = '処理済バグ';
$lang->user->personalData['createdCase'] = 'ケース';

$lang->user->keepLogin['on'] = 'ログインステータス保持';
$lang->user->loginWithDemoUser = 'demoアカウントを使用してログイン：';

$lang->user->tpl = new stdclass();
$lang->user->tpl->type = 'タイプ';
$lang->user->tpl->title = 'テンプレート名';
$lang->user->tpl->content = '内容';
$lang->user->tpl->public = 'パブリックするかどうか';

$lang->usertpl = new stdclass();
$lang->usertpl->title = '模板名称';

$lang->user->placeholder = new stdclass();
$lang->user->placeholder->account = '英字、数字と下線の組み合わせ、３文字以上';
$lang->user->placeholder->password1 = '6文字以上';
$lang->user->placeholder->role = '職位は内容とユーザリストの順序に影響を与えます。';
$lang->user->placeholder->group = 'ユーザの権限リストをグルーピングして決定します。';
$lang->user->placeholder->commiter = 'バージョン制御システム(subversion)のアカウント';
$lang->user->placeholder->verify = '登録者パスワードを入力してください。';

$lang->user->placeholder->passwordStrength[1] = '6文字以上、アルファベット（大文字と小文字）、数字が含まれています。';
$lang->user->placeholder->passwordStrength[2] = '10文字以上、アルファベット（大文字と小文字）、数字、特殊文字が含まれています。';

$lang->user->error = new stdclass();
$lang->user->error->account = '【ID %s】のユーザIDの要求は：3文字以上の英字、数字、または下線の組み合わせ';
$lang->user->error->accountDupl = '【ID %s】のユーザIDは既に存在しています。';
$lang->user->error->realname = '【ID %s】の氏名は必ず記入してください。';
$lang->user->error->password = '【ID %s】のパスワードは6文字以上でなければなりません。';
$lang->user->error->mail = '【ID %s】のメールアドレスが正しくありません。';
$lang->user->error->reserved = '【ID %s】のユーザIDはシステムに保留されています。';
$lang->user->error->weakPassword = '【ID %s】的密码强度小于系统设定。';
$lang->user->error->dangerPassword = '【ID %s】的密码不能使用【%s】这些常用若口令。';

$lang->user->error->verifyPassword = 'セキュリティ検証に失敗しました。登録者パスワードを確認してください。';
$lang->user->error->originalPassword = '旧パスワードが正しくありません。';

$lang->user->contactFieldList['phone'] = $lang->user->phone;
$lang->user->contactFieldList['mobile'] = $lang->user->mobile;
$lang->user->contactFieldList['qq'] = $lang->user->qq;
$lang->user->contactFieldList['dingding'] = $lang->user->dingding;
$lang->user->contactFieldList['weixin'] = $lang->user->weixin;
$lang->user->contactFieldList['skype'] = $lang->user->skype;
$lang->user->contactFieldList['slack'] = $lang->user->slack;
$lang->user->contactFieldList['whatsapp'] = $lang->user->whatsapp;

$lang->user->contacts = new stdclass();
$lang->user->contacts->common = '連絡先';
$lang->user->contacts->listName = '連絡先';
$lang->user->contacts->userList = 'ユーザリスト';

$lang->user->contacts->manage = 'リスト管理';
$lang->user->contacts->contactsList = '既存のリスト';
$lang->user->contacts->selectedUsers = 'ユーザ選択';
$lang->user->contacts->selectList = '選択リスト';
$lang->user->contacts->createList = '新規連絡先';
$lang->user->contacts->noListYet = 'まだリストが作成されていません。先に連絡先リストを作成してください。';
$lang->user->contacts->confirmDelete = '当該リストを削除してもよろしいですか？';
$lang->user->contacts->or = '或いは';

$lang->user->resetFail = 'パスワードのリセットに失敗しました。ユーザIDが存在するかどうかを確認してください。';
$lang->user->resetSuccess = 'パスワードのリセットに成功しました。新しいパスワードでログインしてください。';
$lang->user->noticeResetFile = '<h5>一般ユーザは、管理者に連絡して、パスワードを再設定することができます。</h5>
    <h5>管理员请登录禅道所在的服务器，创建<span> '%s' </span>文件。</h5>
    <p>注意：</p>
    <ol>
    <li>文件内容为空。</li>
    <li>如果之前文件存在，删除之后重新创建。</li>
    </ol>'; 
