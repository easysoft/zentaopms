<?php
/* Action. */
$lang->stakeholder->common       = '干係人';
$lang->stakeholder->browse       = '干係人列表';
$lang->stakeholder->batchCreate  = '批量添加';
$lang->stakeholder->create       = '添加干係人';
$lang->stakeholder->edit         = '編輯干係人';
$lang->stakeholder->view         = '干係人詳情';
$lang->stakeholder->delete       = '移除干係人';
$lang->stakeholder->createdBy    = '創建者';
$lang->stakeholder->createdDate  = '創建時間';
$lang->stakeholder->search       = '搜索';
$lang->stakeholder->browse       = '瀏覽列表';
$lang->stakeholder->view         = '用戶信息';
$lang->stakeholder->basicInfo    = '基本信息';
$lang->stakeholder->add          = '新建';
$lang->stakeholder->communicate  = '溝通記錄';
$lang->stakeholder->expect       = '期望內容';
$lang->stakeholder->progress     = '達成進展';
$lang->stakeholder->expectation  = '期望管理';
$lang->stakeholder->createExpect = '添加期望';
$lang->stakeholder->deleteExpect = '刪除期望';
$lang->stakeholder->editExpect   = '編輯期望';
$lang->stakeholder->viewExpect   = '期望信息';
$lang->stakeholder->issue        = '問題管理';
$lang->stakeholder->viewIssue    = '活動問題列表';
$lang->stakeholder->userIssue    = '干係人問題列表';

$lang->stakeholder->viewAction      = '干係人詳情';
$lang->stakeholder->viewIssueAction = '問題列表';

/* Fields. */
$lang->stakeholder->id          = '編號';
$lang->stakeholder->user        = '用戶';
$lang->stakeholder->type        = '類型';
$lang->stakeholder->name        = '姓名';
$lang->stakeholder->phone       = '手機';
$lang->stakeholder->qq          = 'QQ';
$lang->stakeholder->weixin      = '微信';
$lang->stakeholder->email       = '郵箱';
$lang->stakeholder->isKey       = '關鍵干係人';
$lang->stakeholder->inside      = '內部干係人';
$lang->stakeholder->outside     = '外部干係人';
$lang->stakeholder->from        = '類型';
$lang->stakeholder->company     = '所屬公司';
$lang->stakeholder->nature      = '性格特徵';
$lang->stakeholder->analysis    = '影響分析';
$lang->stakeholder->strategy    = '應對策略';
$lang->stakeholder->expect      = '期望內容';
$lang->stakeholder->progress    = '達成進展';
$lang->stakeholder->createdBy   = '創建者';
$lang->stakeholder->createdDate = '創建日期';
$lang->stakeholder->emptyTip    = '暫無問題。';

$lang->stakeholder->keyList[0] = '否';
$lang->stakeholder->keyList[1] = '是';

$lang->stakeholder->typeList['inside']  = '內部';
$lang->stakeholder->typeList['outside'] = '外部';

$lang->stakeholder->fromList['team']    = '項目團隊成員';
$lang->stakeholder->fromList['company'] = '公司同事';
$lang->stakeholder->fromList['outside'] = '外部人員';

$lang->stakeholder->userEmpty           = '用戶不能為空！';
$lang->stakeholder->nameEmpty           = '姓名不能為空！';
$lang->stakeholder->companyEmpty        = '所屬公司不能為空！';
$lang->stakeholder->confirmDelete       = "您確定刪除該干係人嗎？";
$lang->stakeholder->confirmDeleteExpect = "您確定刪除該干係人期望嗎？";
$lang->stakeholder->createCommunicate   = '<i class="icon icon-chat-line"></i>添加溝通記錄';

$lang->stakeholder->action = new stdclass();
$lang->stakeholder->action->communicate = array('main' => '$date, 由 <strong>$actor</strong> 溝通。');
