<?php
/**
 * The project module zh-cn file of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
/* 字段列表。*/
$lang->project->common       = '项目视图';
$lang->project->id           = '项目编号';
$lang->project->company      = '所属公司';
$lang->project->iscat        = '作为目录';
$lang->project->type         = '项目类型';
$lang->project->parent       = '上级项目';
$lang->project->name         = '项目名称';
$lang->project->code         = '项目代号';
$lang->project->begin        = '开始日期';
$lang->project->end          = '结束日期';
$lang->project->status       = '项目状态';
$lang->project->statge       = '所处阶段';
$lang->project->pri          = '优先级';
$lang->project->desc         = '项目描述';
$lang->project->goal         = '项目目标';
$lang->project->openedBy     = '由谁创建';
$lang->project->openedDate   = '创建日期';
$lang->project->closedBy     = '由谁关闭';
$lang->project->closedDate   = '关闭日期';
$lang->project->canceledBy   = '由谁取消';
$lang->project->canceledDate = '取消日期';
$lang->project->PO           = '产品负责人';
$lang->project->PM           = '项目负责人';
$lang->project->QM           = '测试负责人';
$lang->project->RM           = '发布负责人';
$lang->project->acl          = '访问控制';
$lang->project->teamname     = '团队名称';
$lang->project->products     = '相关产品';
$lang->project->childProjects= '子项目';
$lang->project->whitelist    = '分组白名单';
$lang->project->totalEstimate= '总预计';
$lang->project->totalConsumed= '总消耗';
$lang->project->totalLeft    = '总剩余';
$lang->project->progess      = '进度';
$lang->project->noProduct    = '无产品项目';
$lang->project->select       = '--请选择项目--';

$lang->team->account     = '用户';
$lang->team->role        = '角色';
$lang->team->joinDate    = '加盟日';
$lang->team->workingHour = '工时/天';

/* 字段取值列表。*/
$lang->project->statusList['']      = '';
$lang->project->statusList['wait']  = '未开始';
$lang->project->statusList['doing'] = '进行中';
$lang->project->statusList['done']  = '已完成';

$lang->project->aclList['open']    = '默认设置(有项目视图权限，即可访问)';
$lang->project->aclList['private'] = '私有项目(只有项目团队成员才能访问)';
$lang->project->aclList['custom']  = '自定义白名单(团队成员和白名单的成员可以访问)';

/* 方法列表。*/
$lang->project->index          = "项目首页";
$lang->project->task           = '任务列表';
$lang->project->groupTask      = '分组浏览任务';
$lang->project->story          = '需求列表';
$lang->project->bug            = 'Bug列表';
$lang->project->dynamic        = '动态';
$lang->project->build          = 'Build列表';
$lang->project->burn           = '燃尽图';
$lang->project->computeBurn    = '更新燃尽图';
$lang->project->burnData       = '燃尽图数据';
$lang->project->team           = '团队成员';
$lang->project->doc            = '文档列表';
$lang->project->manageProducts = '关联产品';
$lang->project->linkStory      = '关联需求';
$lang->project->view           = "基本信息";
$lang->project->create         = "添加项目";
$lang->project->delete         = "删除项目";
$lang->project->browse         = "浏览项目";
$lang->project->edit           = "编辑项目";
$lang->project->manageMembers  = '团队管理';
$lang->project->unlinkMember   = '移除成员';
$lang->project->unlinkStory    = '移除需求';
$lang->project->importTask     = '导入任务';
$lang->project->ajaxGetProducts= '接口：获得项目产品列表';

/* 分组浏览。*/
$lang->project->allTasks            = '所有任务';
$lang->project->assignedToMe        = '指派给我';
$lang->project->finishedByMe        = '由我完成';
$lang->project->statusWait          = '未开始';
$lang->project->statusDoing         = '进行中';
$lang->project->statusDone          = '已完成';
$lang->project->statusClosed        = '已关闭';
$lang->project->delayed             = '已延期';
$lang->project->groups['']          = '分组查看';
$lang->project->groups['story']     = '需求分组';
$lang->project->groups['status']    = '状态分组';
$lang->project->groups['pri']       = '优先级分组';
$lang->project->groups['openedby']  = '创建者分组';
$lang->project->groups['assignedTo']= '指派给分组';
$lang->project->groups['finishedby']= '完成者分组';
$lang->project->groups['closedby']  = '关闭者分组';
$lang->project->groups['estimate']  = '预计分组';
$lang->project->groups['consumed']  = '已消耗分组';
$lang->project->groups['left']      = '剩余分组';
$lang->project->groups['type']      = '类型分组';
$lang->project->groups['deadline']  = '截止分组';
$lang->project->listTaskNeedConfrim = '需求变动';
$lang->project->byQuery             = '搜索';

/* 查询条件列表。*/
$lang->project->allProject          = '所有项目';

/* 页面提示。*/
$lang->project->selectProject   = "请选择项目";
$lang->project->beginAndEnd     = '起止时间';
$lang->project->lblStats        = '工时统计';
$lang->project->stats           = '总共预计<strong>%s</strong>工时<br />已经消耗<strong>%s</strong>工时<br />预计剩余<strong>%s</strong>工时';
$lang->project->oneLineStats    = "项目<strong>%s</strong>, 代号为<strong>%s</strong>, 相关产品为<strong>%s</strong>，<strong>%s</strong>开始，<strong>%s</strong>结束，总预计<strong>%s</strong>工时，已消耗<strong>%s</strong>工时，预计剩余<strong>%s</strong>工时。";
$lang->project->taskSummary     = "本页共 <strong>%s</strong> 个任务，未开始<strong>%s</strong>，进行中<strong>%s</strong>，总预计<strong>%s</strong>工时，已消耗<strong>%s</strong>工时，剩余<strong>%s</strong>工时。";
$lang->project->groupSummary    = "本组共 <strong>%s</strong> 个任务，未开始<strong>%s</strong>，进行中<strong>%s</strong>，总预计<strong>%s</strong>工时，已消耗<strong>%s</strong>工时，剩余<strong>%s</strong>工时。";
$lang->project->wbs             = "分解任务";
$lang->project->largeBurnChart  = '点击查看大图';
$lang->project->howToUpdateBurn = "<a href='%s' class='helplink'><i>如何更新?</i></a>";
$lang->project->whyNoStories    = "看起来没有需求可以关联。请检查下项目关联的产品中有没有需求，而且要确保它们已经审核通过。";
$lang->project->doneProjects    = '已结束';
$lang->project->unDoneProjects  = '未结束';

/* 交互提示。*/
$lang->project->confirmDelete         = '您确定删除项目[%s]吗？';
$lang->project->confirmUnlinkMember   = '您确定从该项目中移除该用户吗？';
$lang->project->confirmUnlinkStory    = '您确定从该项目中移除该需求吗？';
$lang->project->errorNoLinkedProducts = '该项目没有关联的产品，系统将转到产品关联页面';
$lang->project->accessDenied          = '您无权访问该项目！';
$lang->project->tips                  = '提示';
$lang->project->afterInfo             = '项目添加成功，您现在可以进行以下操作：';
$lang->project->setTeam               = '设置团队';
$lang->project->linkStory             = '关联需求';
$lang->project->createTask            = '添加任务';
$lang->project->goback                = '返回项目首页（5秒后将自动跳转）';

/* 统计。*/
$lang->project->charts->burn->graph->caption      = "燃尽图";
$lang->project->charts->burn->graph->xAxisName    = "日期";
$lang->project->charts->burn->graph->yAxisName    = "HOUR";
$lang->project->charts->burn->graph->baseFontSize = 12;
$lang->project->charts->burn->graph->formatNumber = 0;
$lang->project->charts->burn->graph->animation    = 0;
$lang->project->charts->burn->graph->rotateNames  = 1;
$lang->project->charts->burn->graph->showValues   = 0;
