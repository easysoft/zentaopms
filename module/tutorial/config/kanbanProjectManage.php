<?php
global $lang,$config;

$kanbanProjectManage = new stdClass();
$kanbanProjectManage->name    = 'kanbanProjectManage';
$kanbanProjectManage->title   = $lang->tutorial->kanbanProjectManage->title;
$kanbanProjectManage->icon    = 'kanban text-special';
$kanbanProjectManage->type    = 'basic';
$kanbanProjectManage->modules = 'project,execution,build,task,bug,testreport,issue,risk';
$kanbanProjectManage->app     = 'project';
$kanbanProjectManage->tasks   = array();

$kanbanProjectManage->tasks['manageProject'] = array();
$kanbanProjectManage->tasks['manageProject']['name']     = 'manageProject';
$kanbanProjectManage->tasks['manageProject']['title']    = $lang->tutorial->kanbanProjectManage->manageProject->title;
$kanbanProjectManage->tasks['manageProject']['startUrl'] = array('project', 'browse');
$kanbanProjectManage->tasks['manageProject']['steps']   = array();

$kanbanProjectManage->tasks['manageProject']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'project',
    'title' => $lang->tutorial->kanbanProjectManage->manageProject->step1->name,
    'desc'  => $lang->tutorial->kanbanProjectManage->manageProject->step1->desc
);

$kanbanProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar .create-project-btn',
    'page'   => 'project-browse',
    'title'  => $lang->tutorial->kanbanProjectManage->manageProject->step2->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageProject->step2->desc
);

$kanbanProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#modelList div.kanban div.model-item',
    'page'   => 'project-browse',
    'title'  => $lang->tutorial->kanbanProjectManage->manageProject->step3->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageProject->step3->desc
);

$kanbanProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'form',
    'target' => '#form-project-create',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->kanbanProjectManage->manageProject->step4->name
);

$kanbanProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#form-project-create .form-actions button[type="submit"]',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->kanbanProjectManage->manageProject->step5->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageProject->step5->desc
);

$kanbanProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-project-browse .dtable-body div[data-row="2"] a',
    'page'   => 'project-browse',
    'url'    => array('project', 'browse'),
    'title'  => $lang->tutorial->kanbanProjectManage->manageProject->step6->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageProject->step6->desc
);

$kanbanProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'settings',
    'page'   => 'project-index',
    'title'  => $lang->tutorial->kanbanProjectManage->manageProject->step7->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageProject->step7->desc
);

$kanbanProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'clickMainNavbar',
    'target' => 'members',
    'page'   => 'project-view',
    'title'  => $lang->tutorial->kanbanProjectManage->manageProject->step8->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageProject->step8->desc
);

$kanbanProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#mainContainer #mainMenu #actionBar a',
    'page'   => 'project-team',
    'title'  => $lang->tutorial->kanbanProjectManage->manageProject->step9->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageProject->step9->desc
);

$kanbanProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'form',
    'target' => '#teamForm table',
    'page'   => 'project-manageMembers',
    'title'  => $lang->tutorial->kanbanProjectManage->manageProject->step10->name,
);

$kanbanProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#teamForm #saveButton',
    'page'   => 'project-manageMembers',
    'title'  => $lang->tutorial->kanbanProjectManage->manageProject->step11->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageProject->step11->desc
);

$kanbanProjectManage->tasks['manageKanban'] = array();
$kanbanProjectManage->tasks['manageKanban']['name']     = 'manageKanban';
$kanbanProjectManage->tasks['manageKanban']['title']    = $lang->tutorial->kanbanProjectManage->manageKanban->title;
$kanbanProjectManage->tasks['manageKanban']['startUrl'] = array('project', 'browse');
$kanbanProjectManage->tasks['manageKanban']['steps']   = array();

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.execution-create-btn',
    'page'   => 'project-index',
    'url'    => array('project', 'index', 'projectID=2'),
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step1->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageKanban->step1->desc
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'execution-create',
    'app'    => 'execution',
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step2->name
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'execution-create',
    'app'    => 'execution',
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step3->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageKanban->step3->desc
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.kanban-region nav.toolbar button',
    'page'   => 'execution-kanban',
    'url'    => array('execution', 'kanban', 'executionID=3'),
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step4->name
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'click',
    'target' => '.kanban-createRegion-btn a',
    'page'   => 'execution-kanban',
    'url'    => array('execution', 'kanban', 'executionID=3'),
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step5->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageKanban->step5->desc
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'form',
    'target' => '#createRegionForm',
    'page'   => 'execution-kanban',
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step6->name
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#createRegionForm button[type="submit"]',
    'page'   => 'execution-kanban',
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step7->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageKanban->step7->desc
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'click',
    'target' => '.create-btn',
    'page'   => 'execution-kanban',
    'url'    => array('execution', 'kanban', 'executionID=3'),
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step8->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageKanban->step8->desc
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'click',
    'target' => '.linkStory-btn a',
    'page'   => 'execution-kanban',
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step9->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageKanban->step9->desc
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => '#table-execution-linkstory .dtable-body div[data-col="id"]',
    'page'   => 'execution-kanban',
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step10->name
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-execution-linkstory button.link-story-btn',
    'page'   => 'execution-kanban',
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step11->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageKanban->step11->desc
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div[z-key="group1"] div[z-lane="1"] div[z-col="1"] .card-list .card-actions button',
    'page'   => 'execution-kanban',
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step12->name
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'click',
    'target' => 'li.task-create-btn a',
    'page'   => 'execution-kanban',
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step13->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageKanban->step13->desc
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'form',
    'target' => '#form-task-create',
    'page'   => 'execution-kanban',
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step14->name
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#form-task-create button[type="submit"]',
    'page'   => 'execution-kanban',
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step15->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageKanban->step15->desc
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'click',
    'target' => '.create-btn',
    'page'   => 'execution-kanban',
    'url'    => array('execution', 'kanban', 'executionID=3'),
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step16->name
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'click',
    'target' => '.bug-create-btn a',
    'page'   => 'execution-kanban',
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step17->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageKanban->step17->desc
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'form',
    'target' => '#form-bug-create',
    'page'   => 'execution-kanban',
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step18->name
);

$kanbanProjectManage->tasks['manageKanban']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#form-bug-create button[type="submit"]',
    'page'   => 'execution-kanban',
    'title'  => $lang->tutorial->kanbanProjectManage->manageKanban->step19->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageKanban->step19->desc
);

$kanbanProjectManage->tasks['manageBuild'] = array();
$kanbanProjectManage->tasks['manageBuild']['name']     = 'manageBuild';
$kanbanProjectManage->tasks['manageBuild']['title']    = $lang->tutorial->kanbanProjectManage->manageBuild->title;
$kanbanProjectManage->tasks['manageBuild']['startUrl'] = array('execution', 'kanban', 'execution=3');
$kanbanProjectManage->tasks['manageBuild']['steps']   = array();

$kanbanProjectManage->tasks['manageBuild']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'build',
    'page'   => 'execution-kanban',
    'app'    => 'execution',
    'url'    => array('execution', 'kanban', 'executionID=3'),
    'title'  => $lang->tutorial->kanbanProjectManage->manageBuild->step1->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageBuild->step1->desc
);

$kanbanProjectManage->tasks['manageBuild']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a',
    'page'   => 'execution-build',
    'title'  => $lang->tutorial->kanbanProjectManage->manageBuild->step2->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageBuild->step2->desc
);

$kanbanProjectManage->tasks['manageBuild']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'build-create',
    'title'  => $lang->tutorial->kanbanProjectManage->manageBuild->step3->name
);

$kanbanProjectManage->tasks['manageBuild']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'build-create',
    'title'  => $lang->tutorial->kanbanProjectManage->manageBuild->step4->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageBuild->step4->desc
);

$kanbanProjectManage->tasks['manageBuild']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'CFD',
    'page'   => 'execution-build',
    'url'    => array('execution', 'build', 'executionID=3'),
    'title'  => $lang->tutorial->kanbanProjectManage->manageBuild->step5->name,
    'desc'   => $lang->tutorial->kanbanProjectManage->manageBuild->step5->desc
);
