<?php
global $lang;

$scrumProjectManage = new stdClass();
$scrumProjectManage->name    = 'scrumProjectManage';
$scrumProjectManage->title   = $lang->tutorial->scrumProjectManage->title;
$scrumProjectManage->icon    = 'project text-special';
$scrumProjectManage->type    = 'basic';
$scrumProjectManage->modules = 'project';
$scrumProjectManage->tasks   = array();

$scrumProjectManage->tasks['manageProject'] = array();
$scrumProjectManage->tasks['manageProject']['name']     = 'manageProject';
$scrumProjectManage->tasks['manageProject']['title']    = $lang->tutorial->scrumProjectManage->manageProject->title;
$scrumProjectManage->tasks['manageProject']['startUrl'] = array('project', 'browse');
$scrumProjectManage->tasks['manageProject']['steps']   = array();

$scrumProjectManage->tasks['manageProject']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'project',
    'title' => $lang->tutorial->scrumProjectManage->manageProject->step1->name,
    'desc'  => $lang->tutorial->scrumProjectManage->manageProject->step1->desc
);

$scrumProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-project-btn',
    'page'   => 'project-createGuide',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step2->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageProject->step2->desc
);

$scrumProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#modelList div.scrum div.model-item',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step3->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageProject->step3->desc
);

$scrumProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'form',
    'target' => '#form-project-create',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step4->name
);

$scrumProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#form-project-create .form-actions button[type="submit"]',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step5->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageProject->step5->desc
);

$scrumProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-project-browse .dtable-body data-col=name data-row=1 a',
    'page'   => 'project-browse',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step6->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageProject->step6->desc
);

$scrumProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'settings',
    'page'   => 'project-view',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step7->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageProject->step7->desc
);

$scrumProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'members',
    'page'   => 'project-team',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step8->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageProject->step8->desc
);

$scrumProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#mainContainer #mainMenu #actionBar a',
    'page'   => 'project-manageMembers',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step9->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageProject->step9->desc
);

$scrumProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'form',
    'target' => '#teamForm',
    'page'   => 'project-manageMembers',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step10->name,
);

$scrumProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#teamForm #saveButton',
    'page'   => 'project-manageMembers',
    'title'  => $lang->tutorial->scrumProjectManage->manageProject->step11->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageProject->step11->desc
);

$scrumProjectManage->tasks['manageExecution'] = array();
$scrumProjectManage->tasks['manageExecution']['name']     = 'manageExecution';
$scrumProjectManage->tasks['manageExecution']['title']    = $lang->tutorial->scrumProjectManage->manageExecution->title;
$scrumProjectManage->tasks['manageExecution']['startUrl'] = array('execution', 'all');
$scrumProjectManage->tasks['manageExecution']['steps']    = array();

$scrumProjectManage->tasks['manageExecution']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'execution',
    'page'   => 'project-execution',
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step1->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageExecution->step1->desc
);

$scrumProjectManage->tasks['manageExecution']['steps'][] = array(
    'type'   => 'click',
    'target' => '#mainMenu #actionBar a.create-execution-btn',
    'page'   => 'execution-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step2->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageExecution->step2->desc
);

$scrumProjectManage->tasks['manageExecution']['steps'][] = array(
    'type'   => 'form',
    'target' => '#form-execution-create',
    'page'   => 'execution-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step3->name
);

$scrumProjectManage->tasks['manageExecution']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#form-execution-create .form-actions button[type="submit"]',
    'page'   => 'execution-create',
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step4->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageExecution->step4->desc
);

$scrumProjectManage->tasks['manageExecution']['steps'][] = array(
    'type'   => 'click',
    'target' => '#tipsModal div.panel-body button.linkstory-btn',
    'page'   => 'execution-create', //注意：需要验证一下，写execution-create正确还是execution-tips正确
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step5->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageExecution->step5->desc
);

$scrumProjectManage->tasks['manageExecution']['steps'][] = array(
    'type'   => 'selectRow',
    'target' => '#table-execution-linkstory',
    'page'   => 'execution-linkstory',
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step6->name
);

$scrumProjectManage->tasks['manageExecution']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-execution-linkstory .dtable-footer .link-story-btn',
    'page'   => 'execution-linkstory',
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step7->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageExecution->step7->desc
);

$scrumProjectManage->tasks['manageExecution']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'burn',
    'page'   => 'execution-story',
    'title'  => $lang->tutorial->scrumProjectManage->manageExecution->step8->name,
    'desc'   => $lang->tutorial->scrumProjectManage->manageExecution->step8->desc
);

$config->tutorial->guides[$scrumProjectManage->name] = $scrumProjectManage;
