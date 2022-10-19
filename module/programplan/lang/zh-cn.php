<?php
/**
 * The programplan module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: zh-cn.php 4729 2013-05-03 07:53:55Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->programplan->common        = '项目阶段';
$lang->programplan->browse        = '浏览甘特图';
$lang->programplan->gantt         = '甘特图';
$lang->programplan->ganttEdit     = '甘特图编辑';
$lang->programplan->list          = '阶段列表';
$lang->programplan->create        = '设置阶段';
$lang->programplan->edit          = '编辑';
$lang->programplan->delete        = '删除阶段';
$lang->programplan->close         = '关闭阶段';
$lang->programplan->activate      = '激活阶段';
$lang->programplan->createSubPlan = '创建二级阶段';

$lang->programplan->parent           = '父阶段';
$lang->programplan->emptyParent      = '无';
$lang->programplan->name             = '阶段名称';
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
$lang->programplan->duration         = '工期';
$lang->programplan->estimate         = '工时';
$lang->programplan->consumed         = '消耗工时';
$lang->programplan->version          = '版本号';
$lang->programplan->full             = '全屏';
$lang->programplan->today            = '今天';
$lang->programplan->exporting        = '导出';
$lang->programplan->exportFail       = '导出失败';
$lang->programplan->hideCriticalPath = '隐藏关键路径';
$lang->programplan->showCriticalPath = '显示关键路径';
$lang->programplan->delay            = '是否延期';
$lang->programplan->delayDays        = '延期天数';

$lang->programplan->errorBegin       = '阶段的开始时间不能小于所属项目的开始时间%s';
$lang->programplan->errorEnd         = '阶段的结束时间不能大于所属项目的结束时间%s';
$lang->programplan->emptyBegin       = '『计划开始』日期不能为空';
$lang->programplan->emptyEnd         = '『计划完成』日期不能为空';
$lang->programplan->checkBegin       = '『计划开始』应当为合法的日期';
$lang->programplan->checkEnd         = '『计划完成』应当为合法的日期';

$lang->programplan->milestoneList[1] = '是';
$lang->programplan->milestoneList[0] = '否';

$lang->programplan->delayList = array();
$lang->programplan->delayList[1] = '是';
$lang->programplan->delayList[0] = '否';

$lang->programplan->noData        = '暂无数据。';
$lang->programplan->children      = '二级计划';
$lang->programplan->childrenAB    = '子';
$lang->programplan->confirmDelete = '确定要删除当前计划吗？';
$lang->programplan->workloadTips  = '子阶段工作量占比按百分百的比例进行拆分';

$lang->programplan->stageCustom = new stdClass();
$lang->programplan->stageCustom->date = '显示日期';
$lang->programplan->stageCustom->task = '显示任务';

$lang->programplan->ganttCustom['PM']           ='负责人';
$lang->programplan->ganttCustom['deadline']     ='计划完成';
$lang->programplan->ganttCustom['status']       ='状态';
$lang->programplan->ganttCustom['realBegan']    ='实际开始';
$lang->programplan->ganttCustom['realEnd']      ='实际完成';
$lang->programplan->ganttCustom['progress']     ='工作量占比';
$lang->programplan->ganttCustom['taskProgress'] ='任务进度';
$lang->programplan->ganttCustom['estimate']     ='工时';
$lang->programplan->ganttCustom['consumed']     ='消耗工时';
$lang->programplan->ganttCustom['delay']        = '是否延期';
$lang->programplan->ganttCustom['delayDays']    = '延期天数';

$lang->programplan->error                  = new stdclass();
$lang->programplan->error->percentNumber   = '"工作量比例"必须为数字';
$lang->programplan->error->planFinishSmall = '"计划完成时间"必须大于"计划开始时间"';
$lang->programplan->error->percentOver     = '工作量占比累计不应当超过100%';
$lang->programplan->error->createdTask     = '已分解任务，不可添加子阶段';
$lang->programplan->error->parentWorkload  = '子阶段的工作量之和不能大于父阶段的工作量:%s';
$lang->programplan->error->parentDuration  = '子阶段计划开始、计划完成不能超过父阶段';
$lang->programplan->error->sameName        = '阶段名称不能相同！';
$lang->programplan->error->taskDrag        = '%s的任务不可以拖动';
$lang->programplan->error->planDrag        = '%s的阶段不可以拖动';

$lang->programplan->ganttBrowseType['gantt']       = '按阶段分组';
$lang->programplan->ganttBrowseType['assignedTo']  = '按指派给分组';
