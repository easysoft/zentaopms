<?php
$lang->kanban->type = array();
$lang->kanban->type['all']   = "綜合看板";
$lang->kanban->type['story'] = "{$lang->SRCommon}看板";
$lang->kanban->type['task']  = "任務看板";
$lang->kanban->type['bug']   = "Bug看板";

$lang->kanban->group = new stdClass();

$lang->kanban->group->all = array();
$lang->kanban->group->story = array();
$lang->kanban->group->story['default']    = "預設方式";
$lang->kanban->group->story['pri']        = "需求優先順序";
$lang->kanban->group->story['category']   = "需求類別";
$lang->kanban->group->story['module']     = "需求模組";
$lang->kanban->group->story['source']     = "需求來源";
$lang->kanban->group->story['assignedTo'] = "指派人員";

$lang->kanban->group->task = array();
$lang->kanban->group->task['default']    = "預設方式";
$lang->kanban->group->task['pri']        = "任務優先順序";
$lang->kanban->group->task['type']       = "任務類型";
$lang->kanban->group->task['module']     = "任務所屬模組";
$lang->kanban->group->task['assignedTo'] = "指派人員";
$lang->kanban->group->task['story']      = "{$lang->SRCommon}";

$lang->kanban->group->bug = array();
$lang->kanban->group->bug['default']    = "預設方式";
$lang->kanban->group->bug['pri']        = "Bug優先順序";
$lang->kanban->group->bug['severity']   = "Bug嚴重程度";
$lang->kanban->group->bug['module']     = "Bug模組";
$lang->kanban->group->bug['type']       = "Bug類型";
$lang->kanban->group->bug['assignedTo'] = "指派人員";

$lang->kanban->WIP                = 'WIP';
$lang->kanban->setWIP             = '在製品設置';
$lang->kanban->WIPStatus          = '在製品狀態';
$lang->kanban->WIPStage           = '在製品階段';
$lang->kanban->WIPType            = '在製品類型';
$lang->kanban->WIPCount           = '在製品數量';
$lang->kanban->noLimit            = '不限制∞';
$lang->kanban->setLane            = '泳道設置';
$lang->kanban->laneName           = '泳道名稱';
$lang->kanban->laneColor          = '泳道顏色';
$lang->kanban->setColumn          = '看板列設置';
$lang->kanban->columnName         = '看板列名稱';
$lang->kanban->columnColor        = '看板列顏色';
$lang->kanban->noColumnUniqueName = '看板列名稱已存在';
$lang->kanban->moveUp             = '泳道上移';
$lang->kanban->moveDown           = '泳道下移';
$lang->kanban->laneMove           = '泳道排序';
$lang->kanban->laneGroup          = '泳道分組';
$lang->kanban->cardsSort          = '卡片排序';
$lang->kanban->moreAction         = '更多操作';
$lang->kanban->noGroup            = '無';

$lang->kanban->error = new stdclass();
$lang->kanban->error->mustBeInt       = '在製品數量必須是正整數。';
$lang->kanban->error->parentLimitNote = '父列的在製品數量不能小於子列在製品數量之和';
$lang->kanban->error->childLimitNote  = '子列在製品數量之和不能大於父列的在製品數量';

$this->lang->kanban->laneTypeList = array();
$this->lang->kanban->laneTypeList['story'] = $lang->SRCommon;
$this->lang->kanban->laneTypeList['bug']   = 'Bug';
$this->lang->kanban->laneTypeList['task']  = '任務';

$lang->kanban->storyColumn = array();
$lang->kanban->storyColumn['backlog']    = 'Backlog';
$lang->kanban->storyColumn['ready']      = '準備好';
$lang->kanban->storyColumn['develop']    = '開發';
$lang->kanban->storyColumn['developing'] = '進行中';
$lang->kanban->storyColumn['developed']  = '完成';
$lang->kanban->storyColumn['test']       = '測試';
$lang->kanban->storyColumn['testing']    = '進行中';
$lang->kanban->storyColumn['tested']     = '完成';
$lang->kanban->storyColumn['verified']   = '已驗收';
$lang->kanban->storyColumn['released']   = '已發佈';
$lang->kanban->storyColumn['closed']     = '已關閉';

$lang->kanban->bugColumn = array();
$lang->kanban->bugColumn['unconfirmed'] = '待確認';
$lang->kanban->bugColumn['confirmed']   = '已確認';
$lang->kanban->bugColumn['resolving']   = '解決中';
$lang->kanban->bugColumn['fixing']      = '進行中';
$lang->kanban->bugColumn['fixed']       = '完成';
$lang->kanban->bugColumn['test']        = '測試';
$lang->kanban->bugColumn['testing']     = '測試中';
$lang->kanban->bugColumn['tested']      = '測試完畢';
$lang->kanban->bugColumn['closed']      = '已關閉';

$lang->kanban->taskColumn = array();
$lang->kanban->taskColumn['wait']       = '未開始';
$lang->kanban->taskColumn['develop']    = '開發';
$lang->kanban->taskColumn['developing'] = '研發中';
$lang->kanban->taskColumn['developed']  = '研發完畢';
$lang->kanban->taskColumn['pause']      = '已暫停';
$lang->kanban->taskColumn['canceled']   = '已取消';
$lang->kanban->taskColumn['closed']     = '已關閉';

$lang->kanbancolumn = new stdclass();
$lang->kanbancolumn->name  = $lang->kanban->columnName;
$lang->kanbancolumn->limit = $lang->kanban->WIPCount;

$lang->kanbanlane = new stdclass();
$lang->kanbanlane->name = $lang->kanban->laneName;
