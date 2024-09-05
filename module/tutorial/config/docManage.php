<?php
global $lang,$app;

$docManage = new stdClass();
$docManage->name    = 'docManage';
$docManage->title   = $lang->tutorial->docManage->title;
$docManage->icon    = 'doc text-special';
$docManage->type    = 'advance';
$docManage->modules = 'doc';
$docManage->app     = 'doc';
$docManage->tasks   = array();

$docManage->tasks['docManage'] = array();
$docManage->tasks['docManage']['name']     = 'docManage';
$docManage->tasks['docManage']['title']    = $lang->tutorial->docManage->title;
$docManage->tasks['docManage']['startUrl'] = array('doc', 'mySpace');
$docManage->tasks['docManage']['steps']    = array();

$docManage->tasks['docManage']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'doc',
    'title' => $lang->tutorial->docManage->step1->name,
    'desc'  => $lang->tutorial->docManage->step1->desc
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'clickNavbar',
    'target' => 'team',
    'page'   => 'doc-mySpace',
    'title'  => $lang->tutorial->docManage->step2->name,
    'desc'   => $lang->tutorial->docManage->step2->desc
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar button.more-btn',
    'page'   => 'doc-tablecontents',
    'title'  => $lang->tutorial->docManage->step3->name
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'li.createSpace-btn a',
    'page'   => 'doc-tablecontents',
    'title'  => $lang->tutorial->docManage->step4->name
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'doc-tablecontents',
    'title'  => $lang->tutorial->docManage->step5->name
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'doc-tablecontents',
    'title'  => $lang->tutorial->docManage->step6->name,
    'desc'   => $lang->tutorial->docManage->step6->desc
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.createLib-btn',
    'page'   => 'doc-tablecontents',
    'url'    => array('doc', 'tablecontents'),
    'title'  => $lang->tutorial->docManage->step7->name,
    'desc'   => $lang->tutorial->docManage->step7->desc
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'doc-tablecontents',
    'title'  => $lang->tutorial->docManage->step8->name
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'doc-tablecontents',
    'title'  => $lang->tutorial->docManage->step9->name,
    'desc'   => $lang->tutorial->docManage->step9->desc
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div[data-lib="2"] nav.toolbar button',
    'page'   => 'doc-tablecontents',
    'url'    => array('doc', 'tablecontents'),
    'title'  => $lang->tutorial->docManage->step10->name
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'li.menu-item[z-key="adddirectory"] a',
    'page'   => 'doc-tablecontents',
    'url'    => array('doc', 'tablecontents'),
    'title'  => $lang->tutorial->docManage->step11->name,
    'desc'   => $lang->tutorial->docManage->step11->desc
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'li.tree-item menu.tree',
    'page'   => 'doc-tablecontents',
    'url'    => array('doc', 'tablecontents'),
    'title'  => $lang->tutorial->docManage->step12->name
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.doc-create-btn',
    'page'   => 'doc-tablecontents',
    'url'    => array('doc', 'tablecontents'),
    'title'  => $lang->tutorial->docManage->step13->name,
    'desc'   => $lang->tutorial->docManage->step13->desc
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'form',
    'target' => '#docForm',
    'page'   => 'doc-create',
    'title'  => $lang->tutorial->docManage->step14->name
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.panel-actions a.release-btn',
    'page'   => 'doc-create',
    'title'  => $lang->tutorial->docManage->step15->name
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'form',
    'target' => '#modalBasicInfo div.modal-body',
    'page'   => 'doc-create',
    'title'  => $lang->tutorial->docManage->step16->name
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#modalBasicInfo button.saveBasicInfoBtn',
    'page'   => 'doc-create',
    'title'  => $lang->tutorial->docManage->step17->name,
    'desc'   => $lang->tutorial->docManage->step17->desc
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-col="title"][data-row="1"] a',
    'page'   => 'doc-tablecontents',
    'url'    => array('doc', 'tablecontents'),
    'title'  => $lang->tutorial->docManage->step18->name,
    'desc'   => $lang->tutorial->docManage->step18->desc
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#docToolbar a.doc-edit-btn',
    'page'   => 'doc-view',
    'title'  => $lang->tutorial->docManage->step19->name,
    'desc'   => $lang->tutorial->docManage->step19->desc
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'form',
    'target' => '#docForm div.panel-body',
    'page'   => 'doc-edit',
    'title'  => $lang->tutorial->docManage->step20->name
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'saveForm',
    'target' => '#docForm button[type="submit"]',
    'page'   => 'doc-edit',
    'title'  => $lang->tutorial->docManage->step21->name,
    'desc'   => $lang->tutorial->docManage->step21->desc
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'click',
    'target' => '#docBody #versionDropdown-toggle',
    'page'   => 'doc-view',
    'url'    => array('doc', 'view', 'docID=1'),
    'title'  => $lang->tutorial->docManage->step22->name,
    'desc'   => $lang->tutorial->docManage->step22->desc
);

$docManage->tasks['docManage']['steps'][] = array(
    'type'   => 'click',
    'target' => 'menu.is-contextmenu menu.dropdown-menu li.menu-item[z-key="1"]',
    'page'   => 'doc-view',
    'title'  => $lang->tutorial->docManage->step23->name,
    'desc'   => $lang->tutorial->docManage->step23->desc
);
