<?php
/* Actions. */
$lang->kanban->create       = 'Create Kanban';
$lang->kanban->createSpace  = 'Create Space';
$lang->kanban->editSpace    = 'Edit Space';
$lang->kanban->closeSpace   = 'Close Space';
$lang->kanban->deleteSpace  = 'Delete Space';
$lang->kanban->sortSpace    = 'Sort Space';
$lang->kanban->edit         = 'Edit Kanban';
$lang->kanban->view         = 'View Kanban';
$lang->kanban->close        = 'Close Kanban';
$lang->kanban->delete       = 'Delete Kanban';
$lang->kanban->createRegion = 'Create Region';
$lang->kanban->editRegion   = 'Edit Region';
$lang->kanban->sortRegion   = 'Sort Region';
$lang->kanban->deleteRegion = 'Delete Region';
$lang->kanban->createLane   = 'Create Lane';
$lang->kanban->editLane     = 'Edit Lane';
$lang->kanban->sortLane     = 'Sort Lane';
$lang->kanban->createColumn = 'Create Column';
$lang->kanban->editColumn   = 'Edit Column';
$lang->kanban->sortColumn   = 'Sort Column';
$lang->kanban->deleteColumn = 'Delete Column';
$lang->kanban->createCard   = 'Create Card';
$lang->kanban->editCard     = 'Edit Card';
$lang->kanban->viewCard     = 'View Card';
$lang->kanban->sortCard     = 'Sort Card';
$lang->kanban->setting      = 'Setting';

/* Fields. */
$lang->kanban->space  = 'Space';
$lang->kanban->name   = 'Name';
$lang->kanban->owner  = 'Owner';
$lang->kanban->team   = 'Team';
$lang->kanban->desc   = 'Description';
$lang->kanban->acl    = 'ACL';
$lang->kanban->closed = 'Closed';

$lang->kanban->empty        = 'No Kanban';
$lang->kanban->teamSumCount = '%s people in total';

$lang->kanban->aclList['open']    = 'Open (Accessible with space view permissions)';
$lang->kanban->aclList['private'] = 'Private (For the kanban team, whitelist members and space owner only)';

$lang->kanban->aclGroup['open']    = 'Open';
$lang->kanban->aclGroup['private'] = 'Private';

$lang->kanban->type = array();
$lang->kanban->type['all']   = "All KanBan";
$lang->kanban->type['story'] = "Story KanBan";
$lang->kanban->type['task']  = "Task KanBan";
$lang->kanban->type['bug']   = "Bug KanBan";

$lang->kanban->group = new stdClass();

$lang->kanban->group->all = array();
$lang->kanban->group->story = array();
$lang->kanban->group->story['default']    = "Default";
$lang->kanban->group->story['pri']        = "Story Priority";
$lang->kanban->group->story['category']   = "Story Category";
$lang->kanban->group->story['module']     = "Story Module";
$lang->kanban->group->story['source']     = "Story Source";
$lang->kanban->group->story['assignedTo'] = "Assigned To";

$lang->kanban->group->task = array();
$lang->kanban->group->task['default']    = "Default";
$lang->kanban->group->task['pri']        = "Task Priority";
$lang->kanban->group->task['type']       = "Task Type";
$lang->kanban->group->task['module']     = "Task Module";
$lang->kanban->group->task['assignedTo'] = "Assigned To";
$lang->kanban->group->task['story']      = "Story";

$lang->kanban->group->bug = array();
$lang->kanban->group->bug['default']    = "Default";
$lang->kanban->group->bug['pri']        = "Bug Priority";
$lang->kanban->group->bug['severity']   = "Bug Severity";
$lang->kanban->group->bug['module']     = "Bug Module";
$lang->kanban->group->bug['type']       = "Bug Type";
$lang->kanban->group->bug['assignedTo'] = "Assigned To";

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
$lang->kanban->setColumn          = 'Column Settings';
$lang->kanban->columnName         = 'Column Name';
$lang->kanban->columnColor        = 'Column Color';
$lang->kanban->noColumnUniqueName = 'The Kanban column name already exists.';
$lang->kanban->moveUp             = 'Swimlane Up';
$lang->kanban->moveDown           = 'Swimlane Down';
$lang->kanban->laneMove           = 'Swimlane Sorting';
$lang->kanban->laneGroup          = 'Lane Group';
$lang->kanban->cardsSort          = 'Cards Sortting';
$lang->kanban->moreAction         = 'More Action';
$lang->kanban->noGroup            = 'None';

$lang->kanban->error = new stdclass();
$lang->kanban->error->mustBeInt       = 'The WIPs must be positive integer.';
$lang->kanban->error->parentLimitNote = 'The WIPs in the parent column cannot be < the sum of the WIPs in the child column.';
$lang->kanban->error->childLimitNote  = 'The sum of products in the child column cannot be > the number of products in the parent column.';

$lang->kanban->defaultColumn = array('wait', 'doing', 'done', 'close');

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

$lang->kanbanspace = new stdclass();
$lang->kanbanspace->common = 'Kanban Space';
$lang->kanbanspace->name   = 'Name';
$lang->kanbanspace->owner  = 'Owner';
$lang->kanbanspace->team   = 'Team';
$lang->kanbanspace->desc   = 'Description';
$lang->kanbanspace->acl    = 'ACL';

$lang->kanbanspace->empty = 'No Space';

$lang->kanbanspace->aclList['open']    = 'Open (Accessible with kanban view permissions)';
$lang->kanbanspace->aclList['private'] = 'Private (For the space owner, team and whitelist members only)';

$lang->kanbanspace->featureBar['all']    = 'All';
$lang->kanbanspace->featureBar['my']     = 'My';
$lang->kanbanspace->featureBar['other']  = 'Other';
$lang->kanbanspace->featureBar['closed'] = 'Closed';

$lang->kanbancolumn = new stdclass();
$lang->kanbancolumn->name  = $lang->kanban->columnName;
$lang->kanbancolumn->limit = $lang->kanban->WIPCount;

$lang->kanbanlane = new stdclass();
$lang->kanbanlane->name    = $lang->kanban->laneName;
$lang->kanbanlane->default = 'Default Lane';

$lang->kanbanregion = new stdclass();
$lang->kanbanregion->name    = 'Kanban Region';
$lang->kanbanregion->default = 'Default Region';
