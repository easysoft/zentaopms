<?php
$lang->my->common = '我的地盤';

/* 方法列表。*/
$lang->my->index           = '首頁';
$lang->my->todo            = '我的待辦';
$lang->my->calendar        = '日程';
$lang->my->work            = '待處理';
$lang->my->contribute      = '貢獻';
$lang->my->task            = '我的任務';
$lang->my->bug             = '我的Bug';
$lang->my->testTask        = '我的版本';
$lang->my->testCase        = '我的用例';
$lang->my->story           = "我的{$lang->SRCommon}";
$lang->my->createProgram   = '添加項目';
$lang->my->project         = "我的項目";
$lang->my->execution       = "我的{$lang->execution->common}";
$lang->my->issue           = '我的問題';
$lang->my->risk            = '我的風險';
$lang->my->profile         = '我的檔案';
$lang->my->dynamic         = '我的動態';
$lang->my->team            = '團隊';
$lang->my->editProfile     = '修改檔案';
$lang->my->changePassword  = '修改密碼';
$lang->my->preference      = '個性化設置';
$lang->my->unbind          = '解除ZDOO綁定';
$lang->my->manageContacts  = '維護聯繫人';
$lang->my->deleteContacts  = '刪除聯繫人';
$lang->my->shareContacts   = '共享聯繫人列表';
$lang->my->limited         = '受限操作(只能編輯與自己相關的內容)';
$lang->my->storyConcept    = '預設需求概念組合';
$lang->my->score           = '我的積分';
$lang->my->scoreRule       = '積分規則';
$lang->my->noTodo          = '暫時沒有待辦。';
$lang->my->noData          = "暫時沒有%s。";
$lang->my->storyChanged    = "需求變更";
$lang->my->hours           = '工時/天';
$lang->my->uploadAvatar    = '更換頭像';

$lang->my->myExecutions = "我參與的階段/衝刺/迭代";
$lang->my->name         = '名稱';
$lang->my->code         = '代號';
$lang->my->projects     = '所屬項目';
$lang->my->executions   = '所屬' . $lang->execution->common;

$lang->my->executionMenu = new stdclass();
$lang->my->executionMenu->undone = '未結束';
$lang->my->executionMenu->done   = '已完成';

$lang->my->taskMenu = new stdclass();
$lang->my->taskMenu->assignedToMe = '指派給我';
$lang->my->taskMenu->openedByMe   = '由我創建';
$lang->my->taskMenu->finishedByMe = '由我完成';
$lang->my->taskMenu->closedByMe   = '由我關閉';
$lang->my->taskMenu->canceledByMe = '由我取消';

$lang->my->storyMenu = new stdclass();
$lang->my->storyMenu->assignedToMe = '指派給我';
$lang->my->storyMenu->openedByMe   = '由我創建';
$lang->my->storyMenu->reviewedByMe = '由我評審';
$lang->my->storyMenu->closedByMe   = '由我關閉';

$lang->my->projectMenu = new stdclass();
$lang->my->projectMenu->doing      = '進行中';
$lang->my->projectMenu->wait       = '未開始';
$lang->my->projectMenu->suspended  = '已掛起';
$lang->my->projectMenu->closed     = '已關閉';
$lang->my->projectMenu->openedbyme = '由我創建';

$lang->my->home = new stdclass();
$lang->my->home->latest        = '最新動態';
$lang->my->home->action        = "%s, %s <em>%s</em> %s <a href='%s'>%s</a>。";
$lang->my->home->projects      = $lang->executionCommon;
$lang->my->home->products      = $lang->productCommon;
$lang->my->home->createProject = "添加{$lang->executionCommon}";
$lang->my->home->createProduct = "添加{$lang->productCommon}";
$lang->my->home->help          = "<a href='https://www.zentao.net/help-read-79236.html' target='_blank'>幫助文檔</a>";
$lang->my->home->noProductsTip = "這裡還沒有{$lang->productCommon}。";

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = '基本信息';
$lang->my->form->lblContact = '聯繫信息';
$lang->my->form->lblAccount = '帳號信息';

$lang->my->programLink = '項目集預設着陸頁';
$lang->my->productLink = '產品預設着陸頁'; 
$lang->my->projectLink = '項目預設着陸頁';

$lang->my->programLinkList = array();
//$lang->my->programLinkList['program-home']     = '預設進入項目集主頁，可以瞭解公司整體的戰略規劃狀況';
$lang->my->programLinkList['program-pgmbrowse']  = '預設進入項目集列表，可以查看所有的項目集';
//$lang->my->programLinkList['program-pgmindex'] = '預設進入最近一個項目集儀表盤，可以查看當前項目集概況';
$lang->my->programLinkList['program-pgmproject'] = '預設進入最近一個項目集的項目列表，可以查看當前項目集下所有項目';

$lang->my->productLinkList = array();
$lang->my->productLinkList['product-index']     = '預設進入產品主頁，可以瞭解公司整體的產品狀況';
$lang->my->productLinkList['product-all']       = '預設進入產品列表，可以查看所有的產品';
$lang->my->productLinkList['product-dashboard'] = '預設進入最近一個產品儀表盤，可以查看當前產品概況';
$lang->my->productLinkList['product-browse']    = '預設進入最近一個產品的需求列表，可以查看當前產品下的需求信息';

$lang->my->projectLinkList = array();
//$lang->my->projectLinkList['program-home']    = '預設進入項目主頁，可以瞭解公司整體的項目狀況';
$lang->my->projectLinkList['program-prjbrowse'] = '預設進入項目列表，可以查看所有的項目';
$lang->my->projectLinkList['program-index']     = '預設進入最近一個項目儀表盤，可以查看當前項目概況';
$lang->my->projectLinkList['project-task']      = '預設進入最近一個項目迭代的任務列表，可以查看當前迭代下的任務信息';
