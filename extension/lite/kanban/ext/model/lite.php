<?php
public function getKanbanByProject($projectID, $status = 'all')
{
    $project = $this->loadModel('project')->getById($projectID);
    $teams   = $this->project->getTeamMembers($projectID);

    $project->team  = join(',', array_keys($teams));
    $project->owner = zget($project, 'PO', 'openedBy');

    $kanbans = $this->dao->select('*')->from(TABLE_KANBAN)
        ->where('project')->eq($projectID)
        ->andWhere('deleted')->eq(0)
        ->beginIF($status != 'all')->andWhere('status')->eq($status)->fi()
        ->fetchAll('id');

    foreach($kanbans as $kanban)
    {
        if(!$this->checkKanbanPriv($kanban, 'kanban', array($project->id => $project))) unset($kanbans[$kanban->id]);
    }

    return array_values($kanbans);
}
