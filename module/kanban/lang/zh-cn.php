<?php
/* Actions. */
$lang->kanban->create       = '创建看板';
$lang->kanban->createSpace  = '创建空间';
$lang->kanban->editSpace    = '设置空间';
$lang->kanban->closeSpace   = '关闭空间';
$lang->kanban->deleteSpace  = '删除空间';
$lang->kanban->sortSpace    = '空间排序';
$lang->kanban->edit         = '编辑看板';
$lang->kanban->view         = '查看看板';
$lang->kanban->close        = '关闭看板';
$lang->kanban->delete       = '删除看板';
$lang->kanban->createRegion = '创建区域';
$lang->kanban->editRegion   = '编辑区域';
$lang->kanban->sortRegion   = '区域排序';
$lang->kanban->deleteRegion = '删除区域';
$lang->kanban->createLane   = '创建泳道';
$lang->kanban->editLane     = '删除泳道';
$lang->kanban->sortLane     = '泳道排序';
$lang->kanban->createColumn = '创建看板列';
$lang->kanban->editColumn   = '编辑看板列';
$lang->kanban->sortColumn   = '看板列排序';
$lang->kanban->deleteColumn = '删除看板列';
$lang->kanban->createCard   = '创建卡片';
$lang->kanban->editCard     = '编辑卡片';
$lang->kanban->viewCard     = '查看卡片';
$lang->kanban->sortCard     = '卡片排序';
$lang->kanban->setting      = '设置';

/* Fields. */
$lang->kanban->space          = '所属空间';
$lang->kanban->name           = '看板名称';
$lang->kanban->owner          = '负责人';
$lang->kanban->team           = '团队';
$lang->kanban->desc           = '空间描述';
$lang->kanban->acl            = '访问控制';
$lang->kanban->whitelist      = '白名单';
$lang->kanban->status         = '状态';
$lang->kanban->createdBy      = '由谁创建';
$lang->kanban->createdDate    = '创建日期';
$lang->kanban->lastEditedBy   = '最后修改';
$lang->kanban->lastEditedDate = '最后修改日期';
$lang->kanban->closedBy       = '由谁关闭';
$lang->kanban->closedDate     = '关闭日期';

$lang->kanban->aclList['open']    = '继承空间访问权限（能访问当前空间，即可访问）';
$lang->kanban->aclList['private'] = '私有（看板团队成员、白名单、空间负责人可访问）';

$lang->kanban->type = array();
$lang->kanban->type['all']   = "综合看板";
$lang->kanban->type['story'] = "{$lang->SRCommon}看板";
$lang->kanban->type['task']  = "任务看板";
$lang->kanban->type['bug']   = "Bug看板";

$lang->kanban->group = new stdClass();

$lang->kanban->group->all = array();
$lang->kanban->group->story = array();
$lang->kanban->group->story['default']    = "默认方式";
$lang->kanban->group->story['pri']        = "需求优先级";
$lang->kanban->group->story['category']   = "需求类别";
$lang->kanban->group->story['module']     = "需求模块";
$lang->kanban->group->story['source']     = "需求来源";
$lang->kanban->group->story['assignedTo'] = "指派人员";

$lang->kanban->group->task = array();
$lang->kanban->group->task['default']    = "默认方式";
$lang->kanban->group->task['pri']        = "任务优先级";
$lang->kanban->group->task['type']       = "任务类型";
$lang->kanban->group->task['module']     = "任务所属模块";
$lang->kanban->group->task['assignedTo'] = "指派人员";
$lang->kanban->group->task['story']      = "{$lang->SRCommon}";

$lang->kanban->group->bug = array();
$lang->kanban->group->bug['default']    = "默认方式";
$lang->kanban->group->bug['pri']        = "Bug优先级";
$lang->kanban->group->bug['severity']   = "Bug严重程度";
$lang->kanban->group->bug['module']     = "Bug模块";
$lang->kanban->group->bug['type']       = "Bug类型";
$lang->kanban->group->bug['assignedTo'] = "指派人员";

$lang->kanban->WIP                = 'WIP';
$lang->kanban->setWIP             = '在制品设置';
$lang->kanban->WIPStatus          = '在制品状态';
$lang->kanban->WIPStage           = '在制品阶段';
$lang->kanban->WIPType            = '在制品类型';
$lang->kanban->WIPCount           = '在制品数量';
$lang->kanban->noLimit            = '不限制∞';
$lang->kanban->setLane            = '泳道设置';
$lang->kanban->laneName           = '泳道名称';
$lang->kanban->laneColor          = '泳道颜色';
$lang->kanban->setColumn          = '看板列设置';
$lang->kanban->columnName         = '看板列名称';
$lang->kanban->columnColor        = '看板列颜色';
$lang->kanban->noColumnUniqueName = '看板列名称已存在';
$lang->kanban->moveUp             = '泳道上移';
$lang->kanban->moveDown           = '泳道下移';
$lang->kanban->laneMove           = '泳道排序';
$lang->kanban->laneGroup          = '泳道分组';
$lang->kanban->cardsSort          = '卡片排序';
$lang->kanban->moreAction         = '更多操作';
$lang->kanban->noGroup            = '无';
$lang->kanban->limitExceeded      = '超出在制品限制';
$lang->kanban->fullScreen         = '全屏';
$lang->kanban->setting            = '设置';

$lang->kanban->error = new stdclass();
$lang->kanban->error->mustBeInt       = '在制品数量必须是正整数。';
$lang->kanban->error->parentLimitNote = '父列的在制品数量不能小于子列在制品数量之和';
$lang->kanban->error->childLimitNote  = '子列在制品数量之和不能大于父列的在制品数量';

$lang->kanban->defaultColumn = array('未开始', '进行中', '已完成', '已关闭');

$this->lang->kanban->laneTypeList = array();
$this->lang->kanban->laneTypeList['story'] = $lang->SRCommon;
$this->lang->kanban->laneTypeList['bug']   = 'Bug';
$this->lang->kanban->laneTypeList['task']  = '任务';

$lang->kanban->storyColumn = array();
$lang->kanban->storyColumn['backlog']    = 'Backlog';
$lang->kanban->storyColumn['ready']      = '准备好';
$lang->kanban->storyColumn['develop']    = '开发';
$lang->kanban->storyColumn['developing'] = '进行中';
$lang->kanban->storyColumn['developed']  = '完成';
$lang->kanban->storyColumn['test']       = '测试';
$lang->kanban->storyColumn['testing']    = '进行中';
$lang->kanban->storyColumn['tested']     = '完成';
$lang->kanban->storyColumn['verified']   = '已验收';
$lang->kanban->storyColumn['released']   = '已发布';
$lang->kanban->storyColumn['closed']     = '已关闭';

$lang->kanban->bugColumn = array();
$lang->kanban->bugColumn['unconfirmed'] = '待确认';
$lang->kanban->bugColumn['confirmed']   = '已确认';
$lang->kanban->bugColumn['resolving']   = '解决中';
$lang->kanban->bugColumn['fixing']      = '进行中';
$lang->kanban->bugColumn['fixed']       = '完成';
$lang->kanban->bugColumn['test']        = '测试';
$lang->kanban->bugColumn['testing']     = '测试中';
$lang->kanban->bugColumn['tested']      = '测试完毕';
$lang->kanban->bugColumn['closed']      = '已关闭';

$lang->kanban->taskColumn = array();
$lang->kanban->taskColumn['wait']       = '未开始';
$lang->kanban->taskColumn['develop']    = '开发';
$lang->kanban->taskColumn['developing'] = '研发中';
$lang->kanban->taskColumn['developed']  = '研发完毕';
$lang->kanban->taskColumn['pause']      = '已暂停';
$lang->kanban->taskColumn['canceled']   = '已取消';
$lang->kanban->taskColumn['closed']     = '已关闭';

$lang->kanbanspace = new stdclass();
$lang->kanbanspace->common         = '看板空间';
$lang->kanbanspace->name           = '空间名称';
$lang->kanbanspace->owner          = '负责人';
$lang->kanbanspace->team           = '团队';
$lang->kanbanspace->desc           = '空间描述';
$lang->kanbanspace->acl            = '访问控制';
$lang->kanbanspace->whitelist      = '白名单';
$lang->kanbanspace->status         = '状态';
$lang->kanbanspace->createdBy      = '由谁创建';
$lang->kanbanspace->createdDate    = '创建日期';
$lang->kanbanspace->lastEditedBy   = '最后修改';
$lang->kanbanspace->lastEditedDate = '最后修改日期';
$lang->kanbanspace->closedBy       = '由谁关闭';
$lang->kanbanspace->closedDate     = '关闭日期';

$lang->kanbanspace->empty = '暂时没有空间';

$lang->kanbanspace->aclList['open']    = '公开（有看板空间视图权限即可访问）';
$lang->kanbanspace->aclList['private'] = '私有（只有看板空间负责人、团队成员、白名单可访问）';

$lang->kanbanspace->featureBar['all']    = '所有';
$lang->kanbanspace->featureBar['my']     = '我的空间';
$lang->kanbanspace->featureBar['other']  = '其他空间';
$lang->kanbanspace->featureBar['closed'] = '已关闭';

$lang->kanbancolumn = new stdclass();
$lang->kanbancolumn->name  = $lang->kanban->columnName;
$lang->kanbancolumn->limit = $lang->kanban->WIPCount;

$lang->kanbanlane = new stdclass();
$lang->kanbanlane->name    = $lang->kanban->laneName;
$lang->kanbanlane->common  = '泳道';
$lang->kanbanlane->default = '默认泳道';

$lang->kanbanregion = new stdclass();
$lang->kanbanregion->name    = '看板区域';
$lang->kanbanregion->default = '默认区域';

$lang->kanbancard = new stdclass();
