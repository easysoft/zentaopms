<?php
$lang->my->common = '我的地盤';

/* 方法列表。*/
$lang->my->index          = '首頁';
$lang->my->todo           = '我的待辦';
$lang->my->calendar       = '日程';
$lang->my->task           = '我的任務';
$lang->my->bug            = '我的Bug';
$lang->my->testTask       = '我的版本';
$lang->my->testCase       = '我的用例';
$lang->my->story          = "我的{$lang->storyCommon}";
$lang->my->myProject      = "我的{$lang->projectCommon}";
$lang->my->profile        = '我的檔案';
$lang->my->dynamic        = '我的動態';
$lang->my->editProfile    = '修改檔案';
$lang->my->changePassword = '修改密碼';
$lang->my->unbind         = '解除ZDOO綁定';
$lang->my->manageContacts = '維護聯繫人';
$lang->my->deleteContacts = '刪除聯繫人';
$lang->my->shareContacts  = '共享聯繫人列表';
$lang->my->limited        = '受限操作(只能編輯與自己相關的內容)';
$lang->my->score          = '我的積分';
$lang->my->scoreRule      = '積分規則';
$lang->my->noTodo         = '暫時沒有待辦。';

$lang->my->taskMenu = new stdclass();
$lang->my->taskMenu->assignedToMe = '指派給我';
$lang->my->taskMenu->openedByMe   = '由我創建';
$lang->my->taskMenu->finishedByMe = '由我完成';
$lang->my->taskMenu->closedByMe   = '由我關閉';
$lang->my->taskMenu->canceledByMe = '由我取消';

$lang->my->storyMenu = new stdclass();
$lang->my->storyMenu->assignedToMe = '指給我';
$lang->my->storyMenu->openedByMe   = '我創建';
$lang->my->storyMenu->reviewedByMe = '我評審';
$lang->my->storyMenu->closedByMe   = '我關閉';

$lang->my->home = new stdclass();
$lang->my->home->latest        = '最新動態';
$lang->my->home->action        = "%s, %s <em>%s</em> %s <a href='%s'>%s</a>。";
$lang->my->home->projects      = $lang->projectCommon;
$lang->my->home->products      = $lang->productCommon;
$lang->my->home->createProject = "添加{$lang->projectCommon}";
$lang->my->home->createProduct = "添加{$lang->productCommon}";
$lang->my->home->help          = "<a href='https://www.zentao.net/help-read-79236.html' target='_blank'>幫助文檔</a>";
$lang->my->home->noProductsTip = "這裡還沒有{$lang->productCommon}。";

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = '基本信息';
$lang->my->form->lblContact = '聯繫信息';
$lang->my->form->lblAccount = '帳號信息';
