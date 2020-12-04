<?php
/* Fields */
$lang->risk->common            = '风险';
$lang->risk->source            = '来源';
$lang->risk->id                = '编号';
$lang->risk->name              = '风险名称';
$lang->risk->category          = '类型';
$lang->risk->strategy          = '策略';
$lang->risk->status            = '状态';
$lang->risk->impact            = '影响程度';
$lang->risk->probability       = '发生概率';
$lang->risk->rate              = '风险系数';
$lang->risk->pri               = '优先级';
$lang->risk->prevention        = '处理措施';
$lang->risk->remedy            = '应急措施';
$lang->risk->identifiedDate    = '识别日期';
$lang->risk->plannedClosedDate = '计划关闭日期';
$lang->risk->assignedTo        = '指派给';
$lang->risk->assignedDate      = '指派日期';
$lang->risk->createdBy         = '由谁创建';
$lang->risk->createdDate       = '创建日期';
$lang->risk->noAssigned        = '未指派';
$lang->risk->cancelBy          = '由谁取消';
$lang->risk->cancelDate        = '取消日期';
$lang->risk->cancelReason      = '取消原因';
$lang->risk->resolvedBy        = '解决者';
$lang->risk->closedDate        = '关闭日期';
$lang->risk->actualClosedDate  = '实际关闭日期';
$lang->risk->resolution        = '解决措施';
$lang->risk->hangupBy          = '由谁挂起';
$lang->risk->hangupDate        = '挂起日期';
$lang->risk->activateBy        = '由谁激活';
$lang->risk->activateDate      = '激活日期';
$lang->risk->isChange          = '风险是否变化';
$lang->risk->trackedBy         = '由谁跟踪';
$lang->risk->trackedDate       = '跟踪日期';
$lang->risk->editedBy          = '由谁编辑';
$lang->risk->editedDate        = '编辑日期';
$lang->risk->legendBasicInfo   = '基本信息';
$lang->risk->legendLifeTime    = '风险的一生';
$lang->risk->confirmDelete     = '您确认删除该风险吗？';
$lang->risk->deleted           = '已删除';

/* Actions */
$lang->risk->batchCreate = '批量添加';
$lang->risk->create      = '添加风险';
$lang->risk->edit        = '编辑风险';
$lang->risk->browse      = '浏览列表';
$lang->risk->view        = '风险详情';
$lang->risk->activate    = '激活';
$lang->risk->hangup      = '挂起';
$lang->risk->close       = '关闭';
$lang->risk->cancel      = '取消';
$lang->risk->track       = '跟踪';
$lang->risk->assignTo    = '指派';
$lang->risk->delete      = '删除';
$lang->risk->byQuery     = '搜索';

$lang->risk->action = new stdclass();
$lang->risk->action->hangup  = '$date, 由 <strong>$actor</strong> 挂起。' . "\n";
$lang->risk->action->tracked = '$date, 由 <strong>$actor</strong> 跟踪。' . "\n";

$lang->risk->sourceList[''] = '';
$lang->risk->sourceList['business']    = '业务部门';
$lang->risk->sourceList['team']        = '项目组';
$lang->risk->sourceList['logistic']    = '项目保障科室';
$lang->risk->sourceList['manage']      = '管理层';
$lang->risk->sourceList['sourcing']    = '供应商-采购';
$lang->risk->sourceList['outsourcing'] = '供应商-外包';
$lang->risk->sourceList['customer']    = '外部客户';
$lang->risk->sourceList['others']      = '其他';

$lang->risk->categoryList[''] = '';
$lang->risk->categoryList['technical']   = '技术类';
$lang->risk->categoryList['manage']      = '管理类';
$lang->risk->categoryList['business']    = '业务类';
$lang->risk->categoryList['requirement'] = '需求类';
$lang->risk->categoryList['resource']    = '资源类';
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
$lang->risk->statusList['active']   = '开放';
$lang->risk->statusList['closed']   = '关闭';
$lang->risk->statusList['hangup']   = '挂起';
$lang->risk->statusList['canceled'] = '取消';

$lang->risk->strategyList[''] = '';
$lang->risk->strategyList['avoidance']    = '规避';
$lang->risk->strategyList['mitigation']   = '缓解';
$lang->risk->strategyList['transference'] = '转移';
$lang->risk->strategyList['acceptance']   = '接受';

$lang->risk->isChangeList[0] = '否';
$lang->risk->isChangeList[1] = '是';

$lang->risk->cancelReasonList[''] = '';
$lang->risk->cancelReasonList['disappeared'] = '风险自行消失';
$lang->risk->cancelReasonList['mistake']     = '识别错误';

$lang->risk->featureBar['browse']['all']      = '所有';
$lang->risk->featureBar['browse']['active']   = '开放';
$lang->risk->featureBar['browse']['assignTo'] = '指派给我';
$lang->risk->featureBar['browse']['closed']   = '已关闭';
$lang->risk->featureBar['browse']['hangup']   = '已挂起';
$lang->risk->featureBar['browse']['canceled'] = '已取消';
