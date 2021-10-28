<?php
$lang->kanban = new stdClass();

$lang->kanban->WIP                = 'WIP';
$lang->kanban->setWIP             = 'WIP Settings';
$lang->kanban->WIPStatus          = 'WIP Status';
$lang->kanban->WIPStage           = 'WIP Stage';
$lang->kanban->WIPType            = 'WIP Type';
$lang->kanban->WIPCount           = 'WIP Count';
$lang->kanban->noLimit            = 'No Limit ∞';
$lang->kanban->setLane            = 'Lane Settings';
$lang->kanban->laneName           = 'Lane Name';
$lang->kanban->laneColor          = 'Lane Color';
$lang->kanban->setLaneColumn      = 'Column Settings';
$lang->kanban->columnName         = 'Column Name';
$lang->kanban->columnColor        = 'Column Color';
$lang->kanban->noColumnUniqueName = 'The Kanban column name already exists.';

$lang->kanban->error = new stdclass();
$lang->kanban->error->parentLimitNote = 'The WIPs in the parent column cannot be < the sum of the WIPs in the child column.';
$lang->kanban->error->childLimitNote  = 'The sum of products in the child column cannot be > the number of products in the parent column.';

$this->lang->kanban->laneTypeList = array();
$this->lang->kanban->laneTypeList['story'] = $lang->SRCommon;
$this->lang->kanban->laneTypeList['bug']   = 'Bug';
$this->lang->kanban->laneTypeList['task']  = 'Task';

$lang->kanban->storyColumn = array();
$lang->kanban->storyColumn['backlog']    = 'Backlog';
$lang->kanban->storyColumn['ready']      = 'Ready';
$lang->kanban->storyColumn['develop']    = 'Development';
$lang->kanban->storyColumn['developing'] = 'Doing';
$lang->kanban->storyColumn['developed']  = 'Done';
$lang->kanban->storyColumn['test']       = 'Testing';
$lang->kanban->storyColumn['testing']    = 'Doing';
$lang->kanban->storyColumn['tested']     = 'Done';
$lang->kanban->storyColumn['verified']   = 'Verified';
$lang->kanban->storyColumn['released']   = 'Released';
$lang->kanban->storyColumn['closed']     = 'Closed';

$lang->kanban->bugColumn = array();
$lang->kanban->bugColumn['unconfirmed'] = 'Unconfirmed';
$lang->kanban->bugColumn['confirmed']   = 'Confirmed';
$lang->kanban->bugColumn['resolving']   = 'Resolving';
$lang->kanban->bugColumn['fixing']      = 'Doing';
$lang->kanban->bugColumn['fixed']       = 'Done';
$lang->kanban->bugColumn['test']        = 'Test';
$lang->kanban->bugColumn['testing']     = 'Doing';
$lang->kanban->bugColumn['tested']      = 'Done';
$lang->kanban->bugColumn['closed']      = 'Closed';

$lang->kanban->taskColumn = array();
$lang->kanban->taskColumn['wait']       = 'Wait';
$lang->kanban->taskColumn['develop']    = 'Develop';
$lang->kanban->taskColumn['developing'] = 'Developing';
$lang->kanban->taskColumn['developed']  = 'Developed';
$lang->kanban->taskColumn['pause']      = 'Pause';
$lang->kanban->taskColumn['canceled']   = 'Canceled';
$lang->kanban->taskColumn['closed']     = 'Closed';

$lang->kanbancolumn = new stdclass();
$lang->kanbancolumn->limit = $lang->kanban->WIPCount;

$lang->kanbanlane = new stdclass();
$lang->kanbanlane->name = $lang->kanban->laneName;
