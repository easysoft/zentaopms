<?php
/**
 * The programplan module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: zh-cn.php 4729 2013-05-03 07:53:55Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->programplan->common = '项目计划';

$lang->programplan->browse        = '浏览阶段计划';
$lang->programplan->gantt         = '甘特图';
$lang->programplan->list          = '阶段列表';
$lang->programplan->create        = '设置阶段';
$lang->programplan->edit          = '编辑';
$lang->programplan->delete        = '删除';
$lang->programplan->createSubPlan = '创建二级阶段';

$lang->programplan->parent           = '父阶段';
$lang->programplan->emptyParent      = '无';
$lang->programplan->name             = '名称';
$lang->programplan->percent          = '计划工作量';
$lang->programplan->percentAB        = '计划工作量';
$lang->programplan->planPercent      = '工作量';
$lang->programplan->attribute        = '阶段';
$lang->programplan->milestone        = '里程碑';
$lang->programplan->taskProgress     = '任务进度';
$lang->programplan->task             = '任务';
$lang->programplan->begin            = '计划开始';
$lang->programplan->end              = '计划完成';
$lang->programplan->realBegan        = '实际开始';
$lang->programplan->realEnd          = '实际完成';
$lang->programplan->output           = '输出';
$lang->programplan->openedBy         = '由谁创建';
$lang->programplan->openedDate       = '创建日期';
$lang->programplan->editedBy         = '由谁编辑';
$lang->programplan->editedDate       = '编辑日期';
$lang->programplan->duration         = '计划工期';
$lang->programplan->version          = '版本号';
$lang->programplan->full             = '全屏';
$lang->programplan->today            = '今天';
$lang->programplan->exporting        = '导出';
$lang->programplan->exportFail       = '导出失败';
$lang->programplan->hideCriticalPath = '隐藏关键路径';
$lang->programplan->showCriticalPath = '显示关键路径';

$lang->programplan->milestoneList[1] = '是';
$lang->programplan->milestoneList[0] = '否';

$lang->programplan->noData        = '暂无数据。';
$lang->programplan->children      = '二级计划';
$lang->programplan->childrenAB    = '子';
$lang->programplan->confirmDelete = '确定要删除当前计划吗？';

$lang->programplan->stageCustom = new stdClass();
$lang->programplan->stageCustom->date = '显示日期';
$lang->programplan->stageCustom->task = '显示任务';

$lang->programplan->error                  = new stdclass();
$lang->programplan->error->percentNumber   = '"工作量比例"必须为数字';
$lang->programplan->error->planFinishSmall = '"计划完成时间"必须大于"计划开始时间"';
$lang->programplan->error->percentOver     = '"工作量比例"累计不应当超过100%';
$lang->programplan->error->createdTask     = '已分解任务,不可添加子阶段';
