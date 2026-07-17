<?php
/**
 * The programplan module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: zh-cn.php 4729 2013-05-03 07:53:55Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->programplan->common        = $lang->projectCommon . '阶段';
$lang->programplan->browse        = '浏览甘特图';
$lang->programplan->gantt         = '甘特图';
$lang->programplan->ganttEdit     = '甘特图编辑';
$lang->programplan->ganttExport   = '甘特图导出';
$lang->programplan->list          = '阶段列表';
$lang->programplan->create        = '设置阶段';
$lang->programplan->edit          = '编辑阶段';
$lang->programplan->delete        = '删除阶段';
$lang->programplan->close         = '关闭阶段';
$lang->programplan->activate      = '激活阶段';
$lang->programplan->createSubPlan = '创建子阶段';
$lang->programplan->subPlanManage = '子阶段的管理方法';
$lang->programplan->submit        = '提交评审';
$lang->programplan->idAB          = '序号';

$lang->programplan->parent           = '父阶段';
$lang->programplan->emptyParent      = '无';
$lang->programplan->name             = '阶段名称';
$lang->programplan->code             = '代号';
$lang->programplan->status           = '阶段进度';
$lang->programplan->PM               = '阶段负责人';
$lang->programplan->PMAB             = '负责人';
$lang->programplan->acl              = '访问控制';
$lang->programplan->subStageName     = '子阶段名称';
$lang->programplan->percent          = '工作量占比';
$lang->programplan->percentAB        = '工作量占比';
$lang->programplan->planPercent      = '工作量';
$lang->programplan->attribute        = '阶段类型';
$lang->programplan->milestone        = '里程碑';
$lang->programplan->taskProgress     = '任务进度';
$lang->programplan->task             = '任务';
$lang->programplan->begin            = '计划开始';
$lang->programplan->end              = '计划完成';
$lang->programplan->realBegan        = '实际开始';
$lang->programplan->realEnd          = '实际完成';
$lang->programplan->ac               = '实际花费';
$lang->programplan->sv               = '进度偏差率';
$lang->programplan->cv               = '成本偏差率';
$lang->programplan->planDateRange    = '计划起始日期';
$lang->programplan->realDateRange    = '实际起始日期';
$lang->programplan->output           = '输出';
$lang->programplan->openedBy         = '由谁创建';
$lang->programplan->openedDate       = '创建日期';
$lang->programplan->editedBy         = '由谁编辑';
$lang->programplan->editedDate       = '编辑日期';
$lang->programplan->duration         = '可用工日';
$lang->programplan->estimate         = '工时';
$lang->programplan->consumed         = '消耗工时';
$lang->programplan->version          = '版本号';
$lang->programplan->createVersion    = '创建版本';
$lang->programplan->editVersion      = '编辑版本';
$lang->programplan->full             = '全屏';
$lang->programplan->today            = '今天';
$lang->programplan->exporting        = '导出';
$lang->programplan->exportFail       = '导出失败';
$lang->programplan->hideCriticalPath = '隐藏关键路径';
$lang->programplan->showCriticalPath = '显示关键路径';
$lang->programplan->delay            = '是否延期';
$lang->programplan->delayDays        = '延期天数';
$lang->programplan->settingGantt     = '设置甘特图';
$lang->programplan->viewSetting      = '显示设置';
$lang->programplan->desc             = '描述';
$lang->programplan->wait             = '待提交';
$lang->programplan->enabled          = '启用阶段';
$lang->programplan->point            = '评审点';
$lang->programplan->progress         = '进度';

$lang->programplan->relation             = '维护任务关系';
$lang->programplan->setTaskRelation      = '维护任务关系';
$lang->programplan->viewTaskRelation     = '浏览任务关系';
$lang->programplan->createRelation       = '添加任务关系';
$lang->programplan->editRelation         = '维护任务关系';
$lang->programplan->batchEditRelation    = '批量维护任务关系';
$lang->programplan->deleteRelation       = '删除任务关系';
$lang->programplan->batchDeleteRelation  = '批量删除任务关系';
$lang->programplan->createGanttVersion   = '新增版本';
$lang->programplan->editGanttVersion     = '编辑版本';
$lang->programplan->deleteGanttVersion   = '删除版本';
$lang->programplan->diffGanttVersion     = '版本对比';
$lang->programplan->rollbackGanttVersion = '版本回滚';
$lang->programplan->deliverableVersion   = '交付物&基线版本';
$lang->programplan->ganttVersion         = '计划版本';
$lang->programplan->tmpGanttVersion      = '临时版本';
$lang->programplan->versionDisplay       = '版本显示';

$lang->programplan->errorBegin       = "阶段的开始时间不能小于所属{$lang->projectCommon}的开始时间%s";
$lang->programplan->errorEnd         = "阶段的结束时间不能大于所属{$lang->projectCommon}的结束时间%s";
$lang->programplan->emptyBegin       = '『计划开始』日期不能为空';
$lang->programplan->emptyEnd         = '『计划完成』日期不能为空';
$lang->programplan->checkBegin       = '『计划开始』应当为合法的日期';
$lang->programplan->checkEnd         = '『计划完成』应当为合法的日期';
$lang->programplan->methodTip        = "您可以在该阶段下选择继续创建阶段或创建{$lang->executionCommon}/看板进行工作。{$lang->executionCommon}/看板不支持继续拆分。";
$lang->programplan->cropStageTip     = "已经开始了的阶段不能再裁剪";
$lang->programplan->childEnabledTip  = "子阶段启用状态跟随父阶段";
$lang->programplan->reviewedPointTip = "该评审点已提交评审不能再操作";
$lang->programplan->typeTip          = "第一层级仅支持创建阶段，同一阶段下可以创建阶段或创建迭代/看板。迭代/看板不支持继续拆分。";
$lang->programplan->rollbackTip      = '新增的执行和任务将被删除，已删除的将恢复，并仅回滚部分字段信息。该操作将覆盖当前排期，且不可恢复，请谨慎操作。是否继续？';
$lang->programplan->rollbackTip4IPD  = '新增的执行和任务将被删除，已删除的将恢复，并仅回滚部分字段信息，部分TR和DCP评审点需重新提交。该操作将覆盖当前排期，且不可恢复，请谨慎操作。是否继续？';
$lang->programplan->canNotCallback   = '无法回滚，回滚后执行计划起止日期超出项目计划起止日期，请先调整项目计划日期。';
$lang->programplan->frozenCallback   = '阶段打基线后不允许回滚版本';

$lang->programplan->milestoneList[1] = '是';
$lang->programplan->milestoneList[0] = '否';

$lang->programplan->delayList = array();
$lang->programplan->delayList[1] = '是';
$lang->programplan->delayList[0] = '否';

$lang->programplan->enabledList = array();
$lang->programplan->enabledList['on']  = '启用';
$lang->programplan->enabledList['off'] = '停用';

$lang->programplan->typeList = array();
$lang->programplan->typeList['stage']     = '阶段';
$lang->programplan->typeList['agileplus'] = $lang->executionCommon . '/看板';

$lang->programplan->noData            = '暂无数据。';
$lang->programplan->children          = '二级计划';
$lang->programplan->childrenAB        = '子';
$lang->programplan->confirmDelete     = '确定要删除当前计划吗？';
$lang->programplan->confirmChangeAttr = '修改后子阶段的类型将根据父阶段类型同步调整为“%s”，是否保存？';
$lang->programplan->noticeChangeAttr  = '修改后子阶段的类型将根据父阶段类型同步调整为“%s”';
$lang->programplan->noticeDiffVersion = '对比时，左侧版本以正常颜色显示，右侧版本以深灰色显示。';
$lang->programplan->workloadTips      = '子阶段工作量占比按百分百的比例进行拆分';
$lang->programplan->emptyStageTip     = '请联系管理员，在后台的“项目流程配置”中设置IPD阶段列表。';

$lang->programplan->stageCustom['date'] = '显示日期';
$lang->programplan->stageCustom['task'] = '显示任务';

$lang->programplan->ganttCustom['ownerID']        = '负责人';
$lang->programplan->ganttCustom['status']         = '状态';
$lang->programplan->ganttCustom['begin']          = '计划开始';
$lang->programplan->ganttCustom['deadline']       = '计划完成';
$lang->programplan->ganttCustom['realBegan']      = '实际开始';
$lang->programplan->ganttCustom['realEnd']        = '实际完成';
$lang->programplan->ganttCustom['duration']       = '可用工日';
$lang->programplan->ganttCustom['progress']       = '工作量占比';
$lang->programplan->ganttCustom['taskProgress']   = '进度';
$lang->programplan->ganttCustom['estimate']       = '预计';
$lang->programplan->ganttCustom['consumed']       = '消耗';
$lang->programplan->ganttCustom['left']           = '剩余';
$lang->programplan->ganttCustom['delay']          = '是否延期';
$lang->programplan->ganttCustom['delayDays']      = '延期天数';
$lang->programplan->ganttCustom['taskType']       = '类型';
$lang->programplan->ganttCustom['openedBy']       = '创建者';
$lang->programplan->ganttCustom['openedDate']     = '创建日期';
$lang->programplan->ganttCustom['assignedDate']   = '指派日期';
$lang->programplan->ganttCustom['finishedBy']     = '完成者';
$lang->programplan->ganttCustom['closedBy']       = '由谁关闭';
$lang->programplan->ganttCustom['closedDate']     = '关闭时间';
$lang->programplan->ganttCustom['closedReason']   = '关闭原因';
$lang->programplan->ganttCustom['canceledBy']     = '由谁取消';
$lang->programplan->ganttCustom['canceledDate']   = '取消时间';
$lang->programplan->ganttCustom['lastEditedBy']   = '最后修改';
$lang->programplan->ganttCustom['lastEditedDate'] = '最后修改日期';
$lang->programplan->ganttCustom['activatedDate']  = '激活日期';
$lang->programplan->ganttCustom['story']          = '研发需求';
$lang->programplan->ganttCustom['keywords']       = '关键词';
$lang->programplan->ganttCustom['mailto']         = '抄送给';

$lang->programplan->error                  = new stdclass();
$lang->programplan->error->percentNumber   = '"工作量占比"必须为非负数';
$lang->programplan->error->planFinishSmall = '"计划完成时间"必须大于"计划开始时间"';
$lang->programplan->error->percentOver     = '相同父阶段的子阶段工作量占比之和不超过100%';
$lang->programplan->error->createdTask     = '已分解任务，不可添加子阶段';
$lang->programplan->error->parentWorkload  = '子阶段的工作量之和不能大于父阶段的工作量:%s';
$lang->programplan->error->letterParent    = "子阶段计划开始不能小于父阶段的计划开始时间 %s";
$lang->programplan->error->greaterParent   = "子阶段计划完成不能超过父阶段的计划完成时间 %s";
$lang->programplan->error->sameName        = '阶段名称不能相同！';
$lang->programplan->error->sameCode        = '阶段代号不能相同！';
$lang->programplan->error->taskDrag        = '%s的任务不可以拖动';
$lang->programplan->error->planDrag        = '%s的阶段不可以拖动';
$lang->programplan->error->notStage        = $lang->executionCommon . '/看板不支持创建子阶段';
$lang->programplan->error->sameType        = '父阶段类型为"%s"，阶段类型需与父阶段一致';
$lang->programplan->error->emptyParentName = "包含子阶段，阶段名称不能为空。";
$lang->programplan->error->noProject       = "系统中没有瀑布、融合瀑布{$lang->projectCommon}时，无法添加甘特图。";
$lang->programplan->error->noProject4IPD   = "系统中没有瀑布、融合瀑布、ipd{$lang->projectCommon}时，无法添加甘特图。";

$lang->programplan->ganttBrowseType['gantt']      = '按阶段分组';
$lang->programplan->ganttBrowseType['assignedTo'] = '按指派给分组';
$lang->programplan->ganttBrowseType['type']       = '按任务类型分组';
$lang->programplan->ganttBrowseType['module']     = '按模块分组';
$lang->programplan->ganttBrowseType['story']      = "按{$lang->SRCommon}分组";
$lang->programplan->ganttBrowseType['status']     = '按状态分组';
$lang->programplan->ganttBrowseType['pri']        = '按优先级分组';
$lang->programplan->ganttBrowseType['finishedBy'] = '按完成者分组';
$lang->programplan->ganttBrowseType['closedBy']   = '按关闭者分组';

$lang->programplan->reviewColorList['draft']     = '#FC913F';
$lang->programplan->reviewColorList['reviewing'] = '#CD6F27';
$lang->programplan->reviewColorList['pass']      = '#0DBB7D';
$lang->programplan->reviewColorList['fail']      = '#FB2B2B';
