<?php
global $lang,$config;

$waterfallProjectManage = new stdClass();
$waterfallProjectManage->name    = 'waterfallProjectManage';
$waterfallProjectManage->title   = $lang->tutorial->waterfallProjectManage->title;
$waterfallProjectManage->icon    = 'waterfall text-special';
$waterfallProjectManage->type    = 'basic';
$waterfallProjectManage->modules = 'project,execution,build,task,bug,testreport,issue,risk';
$waterfallProjectManage->app     = 'project';
$waterfallProjectManage->tasks   = array();

$waterfallProjectManage->tasks['manageProject'] = array();
$waterfallProjectManage->tasks['manageProject']['name']     = 'manageProject';
$waterfallProjectManage->tasks['manageProject']['title']    = $lang->tutorial->waterfallProjectManage->manageProject->title;
$waterfallProjectManage->tasks['manageProject']['startUrl'] = array('project', 'browse');
$waterfallProjectManage->tasks['manageProject']['steps']    = array();

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'project',
    'title' => $lang->tutorial->waterfallProjectManage->manageProject->step1->name,
    'desc'  => $lang->tutorial->waterfallProjectManage->manageProject->step1->desc
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar .create-project-btn',
    'page'   => 'project-browse',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step2->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step2->desc
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#modelList div.waterfall div.model-item',
    'page'   => 'project-browse',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step3->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step3->desc
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'form',
    'target' => '#form-project-create',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step4->name
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#form-project-create .form-actions button[type="submit"]',
    'page'   => 'project-create',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step5->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step5->desc
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#table-project-browse .dtable-body div[data-row="2"] a',
    'page'   => 'project-browse',
    'url'    => array('project', 'browse'),
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step6->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step6->desc
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'settings',
    'page'   => 'project-index',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step7->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step7->desc
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'clickMainNavbar',
    'target' => 'members',
    'page'   => 'project-view',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step8->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step8->desc
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'click',
    'target' => '#mainContainer #mainMenu #actionBar a',
    'page'   => 'project-team',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step9->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step9->desc
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'form',
    'target' => '#teamForm table',
    'page'   => 'project-manageMembers',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step10->name,
);

$waterfallProjectManage->tasks['manageProject']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#teamForm #saveButton',
    'page'   => 'project-manageMembers',
    'title'  => $lang->tutorial->waterfallProjectManage->manageProject->step11->name,
    'desc'   => $lang->tutorial->waterfallProjectManage->manageProject->step11->desc
);

$config->tutorial->guides[$waterfallProjectManage->name] = $waterfallProjectManage;
