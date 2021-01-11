<?php
/* Fields */
$lang->risk->common            = '風險';
$lang->risk->source            = '來源';
$lang->risk->id                = '編號';
$lang->risk->name              = '風險名稱';
$lang->risk->category          = '類型';
$lang->risk->strategy          = '策略';
$lang->risk->status            = '狀態';
$lang->risk->impact            = '影響程度';
$lang->risk->probability       = '發生概率';
$lang->risk->rate              = '風險係數';
$lang->risk->pri               = '優先順序';
$lang->risk->prevention        = '預防措施';
$lang->risk->remedy            = '應急措施';
$lang->risk->identifiedDate    = '識別日期';
$lang->risk->plannedClosedDate = '計劃關閉日期';
$lang->risk->assignedTo        = '指派給';
$lang->risk->assignedDate      = '指派日期';
$lang->risk->createdBy         = '由誰創建';
$lang->risk->createdDate       = '創建日期';
$lang->risk->noAssigned        = '未指派';
$lang->risk->cancelBy          = '由誰取消';
$lang->risk->cancelDate        = '取消日期';
$lang->risk->cancelReason      = '取消原因';
$lang->risk->resolvedBy        = '解決者';
$lang->risk->closedDate        = '關閉日期';
$lang->risk->actualClosedDate  = '實際關閉日期';
$lang->risk->resolution        = '解決措施';
$lang->risk->hangupBy          = '由誰掛起';
$lang->risk->hangupDate        = '掛起日期';
$lang->risk->activateBy        = '由誰激活';
$lang->risk->activateDate      = '激活日期';
$lang->risk->isChange          = '風險是否變化';
$lang->risk->trackedBy         = '由誰跟蹤';
$lang->risk->trackedDate       = '跟蹤日期';
$lang->risk->editedBy          = '由誰編輯';
$lang->risk->editedDate        = '編輯日期';
$lang->risk->legendBasicInfo   = '基本信息';
$lang->risk->legendLifeTime    = '風險的一生';
$lang->risk->confirmDelete     = '您確認刪除該風險嗎？';
$lang->risk->deleted           = '已刪除';

/* Actions */
$lang->risk->batchCreate = '批量添加';
$lang->risk->create      = '添加風險';
$lang->risk->edit        = '編輯風險';
$lang->risk->browse      = '瀏覽列表';
$lang->risk->view        = '風險詳情';
$lang->risk->activate    = '激活';
$lang->risk->hangup      = '掛起';
$lang->risk->close       = '關閉';
$lang->risk->cancel      = '取消';
$lang->risk->track       = '跟蹤';
$lang->risk->assignTo    = '指派';
$lang->risk->delete      = '刪除';
$lang->risk->byQuery     = '搜索';

$lang->risk->action = new stdclass();
$lang->risk->action->hangup  = '$date, 由 <strong>$actor</strong> 掛起。' . "\n";
$lang->risk->action->tracked = '$date, 由 <strong>$actor</strong> 跟蹤。' . "\n";

$lang->risk->sourceList[''] = '';
$lang->risk->sourceList['business']    = '業務部門';
$lang->risk->sourceList['team']        = '項目組';
$lang->risk->sourceList['logistic']    = '項目保障科室';
$lang->risk->sourceList['manage']      = '管理層';
$lang->risk->sourceList['sourcing']    = '供應商-採購';
$lang->risk->sourceList['outsourcing'] = '供應商-外包';
$lang->risk->sourceList['customer']    = '外部客戶';
$lang->risk->sourceList['others']      = '其他';

$lang->risk->categoryList[''] = '';
$lang->risk->categoryList['technical']   = '技術類';
$lang->risk->categoryList['manage']      = '管理類';
$lang->risk->categoryList['business']    = '業務類';
$lang->risk->categoryList['requirement'] = '需求類';
$lang->risk->categoryList['resource']    = '資源類';
$lang->risk->categoryList['others']      = '其他';

$lang->risk->impactList[1] = 1;
$lang->risk->impactList[2] = 2;
$lang->risk->impactList[3] = 3;
$lang->risk->impactList[4] = 4;
$lang->risk->impactList[5] = 5;

$lang->risk->probabilityList[1] = 1;
$lang->risk->probabilityList[2] = 2;
$lang->risk->probabilityList[3] = 3;
$lang->risk->probabilityList[4] = 4;
$lang->risk->probabilityList[5] = 5;

$lang->risk->priList['high']   = '高';
$lang->risk->priList['middle'] = '中';
$lang->risk->priList['low']    = '低';

$lang->risk->statusList[''] = '';
$lang->risk->statusList['active']   = '開放';
$lang->risk->statusList['closed']   = '關閉';
$lang->risk->statusList['hangup']   = '掛起';
$lang->risk->statusList['canceled'] = '取消';

$lang->risk->strategyList[''] = '';
$lang->risk->strategyList['avoidance']    = '規避';
$lang->risk->strategyList['mitigation']   = '緩解';
$lang->risk->strategyList['transference'] = '轉移';
$lang->risk->strategyList['acceptance']   = '接受';

$lang->risk->isChangeList[0] = '否';
$lang->risk->isChangeList[1] = '是';

$lang->risk->cancelReasonList[''] = '';
$lang->risk->cancelReasonList['disappeared'] = '風險自行消失';
$lang->risk->cancelReasonList['mistake']     = '識別錯誤';

$lang->risk->featureBar['browse']['all']      = '所有';
$lang->risk->featureBar['browse']['active']   = '開放';
$lang->risk->featureBar['browse']['assignTo'] = '指派給我';
$lang->risk->featureBar['browse']['closed']   = '已關閉';
$lang->risk->featureBar['browse']['hangup']   = '已掛起';
$lang->risk->featureBar['browse']['canceled'] = '已取消';
