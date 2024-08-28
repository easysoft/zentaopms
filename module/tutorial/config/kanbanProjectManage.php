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
    'target' => '#modelList div.scrum div.model-item',
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
