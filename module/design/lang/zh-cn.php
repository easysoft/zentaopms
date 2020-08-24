<?php
$lang->design->common        = '设计';
$lang->design->browse        = '浏览列表';
$lang->design->commit        = '关联提交';
$lang->design->submission    = '相关提交';
$lang->design->version       = '版本号';
$lang->design->assignedTo    = '指派给';
$lang->design->actions       = '操作';
$lang->design->delete        = '删除';
$lang->design->confirmDelete = '您确定要删除这个设计吗？';
$lang->design->edit          = '变更';
$lang->design->byQuery       = '搜索';
$lang->design->products      = '所属产品';
$lang->design->story         = '相关需求';
$lang->design->file          = '附件';
$lang->design->id            = '编号';
$lang->design->story         = '需求';
$lang->design->ditto         = '同上';
$lang->design->type          = '设计类型';
$lang->design->name          = '设计名称';
$lang->design->create        = '创建设计';
$lang->design->batchCreate   = '批量创建';
$lang->design->view          = '查看设计';
$lang->design->desc          = '设计描述';
$lang->design->product       = '所属产品';
$lang->design->basicInfo     = '基础信息';
$lang->design->commitBy      = '由谁提交';
$lang->design->commitDate    = '提交时间';
$lang->design->affectedStory = "影响{$lang->storyCommon}";
$lang->design->affectedTasks = '影响任务';
$lang->design->submit        = '提交评审';
$lang->design->revision      = '查看关联代码';
$lang->design->designView    = '查看详情';
$lang->design->reviewObject  = '评审对象';
$lang->design->createdBy     = '由谁创建';
$lang->design->createdDate   = '创建时间';
$lang->design->basicInfo     = '基本信息';

$lang->design->typeList         = array();
$lang->design->typeList['']     = '';
$lang->design->typeList['HLDS'] = '概要设计';
$lang->design->typeList['DDS']  = '详细设计';
$lang->design->typeList['DBDS'] = '数据库设计';
$lang->design->typeList['ADS']  = '接口设计';

$lang->design->range          = '影响范围';
$lang->design->errorSelection = '还没有选中记录!';
$lang->design->noDesign       = '暂时没有记录';

$lang->design->rangeList           = array();
$lang->design->rangeList['all']    = '全部记录';
$lang->design->rangeList['assign'] = '选中记录';

$lang->design->featureBar['all'] = '所有';
$lang->design->featureBar += $lang->design->typeList;
