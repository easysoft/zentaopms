<?php
$lang->my->common = 'マイページ';

/* 方法列表。*/
$lang->my->index = 'ホーム';
$lang->my->todo = 'ToDo';
$lang->my->calendar = 'カレンダー';
$lang->my->task = 'タスク';
$lang->my->bug = 'バグ';
$lang->my->testTask = 'バージョン';
$lang->my->testCase = 'ケース';
$lang->my->story = $lang->storyCommon;
$lang->my->myProject = "{$lang->projectCommon}";
$lang->my->profile = 'プロファイル';
$lang->my->dynamic = '活動';
$lang->my->editProfile = 'プロファイル更新';
$lang->my->changePassword = 'パスワード変更';
$lang->my->unbind = '然之バインド解除';
$lang->my->manageContacts = '連絡先管理';
$lang->my->deleteContacts = '連絡先削除';
$lang->my->shareContacts = '連絡先リストシェア';
$lang->my->limited = '制限操作（個人関連資料のみ編集可能）';
$lang->my->score = 'マイポイント';
$lang->my->scoreRule = 'ポイントルール';
$lang->my->noTodo = 'ToDoがありません。';

$lang->my->taskMenu = new stdclass();
$lang->my->taskMenu->assignedToMe = 'マイアサイン';
$lang->my->taskMenu->openedByMe = '未完了';
$lang->my->taskMenu->finishedByMe = '完了';
$lang->my->taskMenu->closedByMe = 'クローズ';
$lang->my->taskMenu->canceledByMe = 'キャンセル';

$lang->my->storyMenu = new stdclass();
$lang->my->storyMenu->assignedToMe = 'マイアサイン';
$lang->my->storyMenu->openedByMe = '未完了';
$lang->my->storyMenu->reviewedByMe = '承認';
$lang->my->storyMenu->closedByMe = 'クローズ';

$lang->my->home = new stdclass();
$lang->my->home->latest = '最新アクティビティ';
$lang->my->home->action = "%s, %s <em>%s</em> %s <a href='%s'>%s</a>。";
$lang->my->home->projects = $lang->projectCommon;
$lang->my->home->products = $lang->productCommon;
$lang->my->home->createProject = '新規';
$lang->my->home->createProduct = "{$lang->productCommon}追加";
$lang->my->home->help = "<a href='https://www.zentao.net/help-read-79236.html' target='_blank'>ヘルプファイル</a>";
$lang->my->home->noProductsTip = "こちらは{$lang->productCommon}がありません。";

$lang->my->form = new stdclass();
$lang->my->form->lblBasic = '基本情報';
$lang->my->form->lblContact = '連絡情報';
$lang->my->form->lblAccount = 'アカウント情報';
