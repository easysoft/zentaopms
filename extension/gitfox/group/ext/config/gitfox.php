<?php
$config->group->subset = new stdclass();

$config->group->subset->my = new stdclass();
$config->group->subset->my->order = 20;
$config->group->subset->my->nav = 'my';

$config->group->subset->personalsettings = new stdclass();
$config->group->subset->personalsettings->order = 50;
$config->group->subset->personalsettings->nav = 'my';

$config->group->subset->effort = new stdclass();
$config->group->subset->effort->order = 60;
$config->group->subset->effort->nav = 'my';

$config->group->subset->admin = new stdclass();
$config->group->subset->admin->order = 1010;
$config->group->subset->admin->nav = 'admin';

$config->group->subset->systemsetting = new stdclass();
$config->group->subset->systemsetting->order = 1180;
$config->group->subset->systemsetting->nav = 'admin';

$config->group->subset->staffmanage = new stdclass();
$config->group->subset->staffmanage->order = 1190;
$config->group->subset->staffmanage->nav = 'admin';

$config->group->subset->notifysetting = new stdclass();
$config->group->subset->notifysetting->order = 1230;
$config->group->subset->notifysetting->nav = 'admin';

$config->group->subset->search = new stdclass();
$config->group->subset->search->order = 1570;

$config->group->subset->file = new stdclass();
$config->group->subset->file->order = 1590;

$config->group->subset->generalcomment = new stdclass();
$config->group->subset->generalcomment->order = 1750;

$config->group->subset->generalping = new stdclass();
$config->group->subset->generalping->order = 1760;

$config->group->subset->generaltemplate = new stdclass();
$config->group->subset->generaltemplate->order = 1770;

$config->group->subset->generaleffort = new stdclass();
$config->group->subset->generaleffort->order = 1780;

$config->group->subset->space = new stdclass();
$config->group->subset->space->order = 2300;
$config->group->subset->space->nav = 'space';

$config->group->subset->repo = new stdclass();
$config->group->subset->repo->order = 2310;
$config->group->subset->repo->nav = 'repo';

$config->group->subset->repocode = new stdclass();
$config->group->subset->repocode->order = 2320;
$config->group->subset->repocode->nav = 'repo';

$config->group->subset->codeReview = new stdclass();
$config->group->subset->codeReview->order = 2330;
$config->group->subset->codeReview->nav = 'ppm';

$config->group->subset->pipeline = new stdclass();
$config->group->subset->pipeline->order = 2350;
$config->group->subset->pipeline->nav = 'pipeline';

$config->group->subset->codeScan = new stdclass();
$config->group->subset->codeScan->order = 2420;
$config->group->subset->codeScan->nav = 'codescan';

$config->group->subset->codereview = new stdclass();
$config->group->subset->codereview->order = 2450;
$config->group->subset->codereview->nav = 'issue';

$config->group->subset->artifact = new stdclass();
$config->group->subset->artifact->order = 2460;
$config->group->subset->artifact->nav = 'artifact';

$config->group->subset->systemManage = new stdclass();
$config->group->subset->systemManage->order = 2470;
$config->group->subset->systemManage->nav = 'system';

$config->group->subset->configure = new stdclass();
$config->group->subset->configure->order = 2900;
$config->group->subset->configure->nav = 'repo';

$config->group->package = new stdclass();

$config->group->package->browseSystem = new stdclass();
$config->group->package->browseSystem->order  = 2380;
$config->group->package->browseSystem->subset = 'systemManage';
$config->group->package->browseSystem->privs  = array();
$config->group->package->browseSystem->privs['product-browse'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(), 'recommend' => array());

$config->group->package->application = new stdclass();
$config->group->package->application->order  = 2390;
$config->group->package->application->subset = 'systemManage';
$config->group->package->application->privs  = array();
$config->group->package->application->privs['product-create'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'product-browse'), 'recommend' => array(0 => 'product-edit', 1 => 'product-delete', 2 => 'product-close', 3 => 'product-activate'));
$config->group->package->application->privs['product-edit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'product-browse'), 'recommend' => array(0 => 'product-create', 1 => 'product-delete', 2 => 'product-close', 3 => 'product-activate'));
$config->group->package->application->privs['product-delete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 30, 'depend' => array(0 => 'product-browse'), 'recommend' => array(0 => 'product-create', 1 => 'product-edit', 2 => 'product-close', 3 => 'product-activate'));
$config->group->package->application->privs['product-close'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 40, 'depend' => array(0 => 'product-browse'), 'recommend' => array(0 => 'product-create', 1 => 'product-edit', 2 => 'product-delete', 3 => 'product-activate'));
$config->group->package->application->privs['product-activate'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 50, 'depend' => array(0 => 'product-browse'), 'recommend' => array(0 => 'product-create', 1 => 'product-edit', 2 => 'product-delete', 3 => 'product-close'));

$config->group->package->space = new stdclass();
$config->group->package->space->order  = 2230;
$config->group->package->space->subset = 'space';
$config->group->package->space->privs  = array();
$config->group->package->space->privs['space-browse'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(), 'recommend' => array(0 => 'space-create', 1 => 'space-edit', 2 => 'space-delete', 3 => 'space-view'));
$config->group->package->space->privs['space-view'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'space-browse'), 'recommend' => array(0 => 'space-group'));

$config->group->package->manageSpace = new stdclass();
$config->group->package->manageSpace->order  = 2235;
$config->group->package->manageSpace->subset = 'space';
$config->group->package->manageSpace->privs  = array();
$config->group->package->manageSpace->privs['space-create'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'space-browse'), 'recommend' => array(0 => 'space-edit', 1 => 'space-view', 2 => 'space-delete'));
$config->group->package->manageSpace->privs['space-edit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(0 => 'space-browse'), 'recommend' => array(0 => 'space-create', 1 => 'space-view', 2 => 'space-delete'));
$config->group->package->manageSpace->privs['space-delete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 25, 'depend' => array(0 => 'space-browse'), 'recommend' => array(0 => 'space-create', 1 => 'space-edit', 2 => 'space-view'));

$config->group->package->manageSpacePrivs = new stdclass();
$config->group->package->manageSpacePrivs->order  = 2240;
$config->group->package->manageSpacePrivs->subset = 'space';
$config->group->package->manageSpacePrivs->privs  = array();
$config->group->package->manageSpacePrivs->privs['space-members'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'space-view'), 'recommend' => array(0 => 'space-manageMembers', 1 => 'space-removeMember'));
$config->group->package->manageSpacePrivs->privs['space-manageMembers'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(0 => 'space-members'), 'recommend' => array(0 => 'space-removeMember'));
$config->group->package->manageSpacePrivs->privs['space-removeMember'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'space-members'), 'recommend' => array(0 => 'space-manageMembers'));
$config->group->package->manageSpacePrivs->privs['space-group'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 25, 'depend' => array(0 => 'space-view'), 'recommend' => array(0 => 'space-createGroup', 1 => 'space-editGroup', 2 => 'space-deleteGroup', 3 => 'space-managePriv', 4 => 'space-manageGroupMember'));
$config->group->package->manageSpacePrivs->privs['space-createGroup'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 30, 'depend' => array(0 => 'space-group'), 'recommend' => array(0 => 'space-editGroup', 1 => 'space-deleteGroup', 2 => 'space-managePriv', 3 => 'space-manageGroupMember'));
$config->group->package->manageSpacePrivs->privs['space-editGroup'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 35, 'depend' => array(0 => 'space-group'), 'recommend' => array(0 => 'space-createGroup', 1 => 'space-deleteGroup', 2 => 'space-managePriv', 3 => 'space-manageGroupMember'));
$config->group->package->manageSpacePrivs->privs['space-deleteGroup'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 36, 'depend' => array(0 => 'space-group'), 'recommend' => array(0 => 'space-createGroup', 1 => 'space-editGroup', 2 => 'space-managePriv', 3 => 'space-manageGroupMember'));
$config->group->package->manageSpacePrivs->privs['space-importGroup'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 40, 'depend' => array(0 => 'space-group'), 'recommend' => array(0 => 'space-createGroup', 1 => 'space-editGroup', 2 => 'space-deleteGroup', 3 => 'space-managePriv'));
$config->group->package->manageSpacePrivs->privs['space-managePriv'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 45, 'depend' => array(0 => 'space-group'), 'recommend' => array(0 => 'space-createGroup', 1 => 'space-editGroup', 2 => 'space-deleteGroup', 3 => 'space-manageGroupMember'));
$config->group->package->manageSpacePrivs->privs['space-manageGroupMember'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 50, 'depend' => array(0 => 'space-group'), 'recommend' => array(0 => 'space-createGroup', 1 => 'space-editGroup', 2 => 'space-deleteGroup', 3 => 'space-managePriv'));

$config->group->package->browseRepo = new stdclass();
$config->group->package->browseRepo->order  = 2420;
$config->group->package->browseRepo->subset = 'repo';
$config->group->package->browseRepo->privs  = array();
$config->group->package->browseRepo->privs['repo-maintain'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 0, 'depend' => array(), 'recommend' => array(0 => 'repo-browse', 1 => 'repo-create', 2 => 'repo-edit'));
$config->group->package->browseRepo->privs['repo-browse'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 30, 'depend' => array(0 => 'repo-maintain'), 'recommend' => array(0 => 'repo-create', 1 => 'repo-edit'));

$config->group->package->browseBranchSettings = new stdclass();
$config->group->package->browseBranchSettings->order  = 2425;
$config->group->package->browseBranchSettings->subset = 'repo';
$config->group->package->browseBranchSettings->privs  = array();
$config->group->package->browseBranchSettings->privs['repobranchtype-browse'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'repo-browse'), 'recommend' => array(0 => 'repobranchtype-create', 1 => 'repobranchtype-edit', 2 => 'repobranchtype-delete', 3 => 'repobranchtype-import'));

$config->group->package->browseWebhooks = new stdclass();
$config->group->package->browseWebhooks->order  = 2425;
$config->group->package->browseWebhooks->subset = 'repo';
$config->group->package->browseWebhooks->privs  = array();
$config->group->package->browseWebhooks->privs['repo-browseWebhooks'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'repo-browse'), 'recommend' => array());
$config->group->package->browseWebhooks->privs['repo-logWebhook'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 25, 'depend' => array(0 => 'repo-browseWebhooks'), 'recommend' => array());

$config->group->package->manageRepo = new stdclass();
$config->group->package->manageRepo->order  = 2440;
$config->group->package->manageRepo->subset = 'repo';
$config->group->package->manageRepo->privs  = array();
$config->group->package->manageRepo->privs['repo-createRepo'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'repo-maintain'), 'recommend' => array(0 => 'repo-edit'));
$config->group->package->manageRepo->privs['repo-create'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'repo-maintain'), 'recommend' => array(0 => 'repo-edit'));
$config->group->package->manageRepo->privs['repo-edit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'repo-maintain'), 'recommend' => array(0 => 'repo-create'));
$config->group->package->manageRepo->privs['repo-delete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 25, 'depend' => array(0 => 'repo-maintain'), 'recommend' => array(0 => 'repo-create', 1 => 'repo-edit'));

$config->group->package->setBranchType = new stdclass();
$config->group->package->setBranchType->order  = 2450;
$config->group->package->setBranchType->subset = 'repo';
$config->group->package->setBranchType->privs  = array();
$config->group->package->setBranchType->privs['repobranchtype-create'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'repobranchtype-browse'), 'recommend' => array(0 => 'repobranchtype-edit', 1 => 'repobranchtype-delete', 2 => 'repobranchtype-import'));
$config->group->package->setBranchType->privs['repobranchtype-import'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(0 => 'repobranchtype-browse'), 'recommend' => array(0 => 'repobranchtype-create', 1 => 'repobranchtype-edit', 2 => 'repobranchtype-delete'));
$config->group->package->setBranchType->privs['repobranchtype-edit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'repobranchtype-browse'), 'recommend' => array(0 => 'repobranchtype-create', 1 => 'repobranchtype-delete', 2 => 'repobranchtype-import'));
$config->group->package->setBranchType->privs['repobranchtype-delete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 25, 'depend' => array(0 => 'repobranchtype-browse'), 'recommend' => array(0 => 'repobranchtype-create', 1 => 'repobranchtype-edit', 2 => 'repobranchtype-import'));

$config->group->package->setBranchRule = new stdclass();
$config->group->package->setBranchRule->order  = 2455;
$config->group->package->setBranchRule->subset = 'repo';
$config->group->package->setBranchRule->privs  = array();
$config->group->package->setBranchRule->privs['repobranchrule-setBranchRule'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'repobranchtype-browse', 1 => 'repo-browseBranch'), 'recommend' => array());

$config->group->package->setReviewFlow = new stdclass();
$config->group->package->setReviewFlow->order  = 2460;
$config->group->package->setReviewFlow->subset = 'repo';
$config->group->package->setReviewFlow->privs  = array();
$config->group->package->setReviewFlow->privs['reporeviewflow-browse'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'repobranchtype-browse'), 'recommend' => array(0 => 'reporeviewflow-create', 1 => 'reporeviewflow-edit', 2 => 'reporeviewflow-changeStatus', 3 => 'reporeviewflow-delete'));
$config->group->package->setReviewFlow->privs['reporeviewflow-create'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'reporeviewflow-browse'), 'recommend' => array(0 => 'reporeviewflow-edit', 1 => 'reporeviewflow-changeStatus', 2 => 'reporeviewflow-delete'));
$config->group->package->setReviewFlow->privs['reporeviewflow-edit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(0 => 'reporeviewflow-browse'), 'recommend' => array(0 => 'reporeviewflow-create', 1 => 'reporeviewflow-changeStatus', 2 => 'reporeviewflow-delete'));
$config->group->package->setReviewFlow->privs['reporeviewflow-changeStatus'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'reporeviewflow-browse'), 'recommend' => array(0 => 'reporeviewflow-create', 1 => 'reporeviewflow-edit', 2 => 'reporeviewflow-delete'));
$config->group->package->setReviewFlow->privs['reporeviewflow-delete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'reporeviewflow-browse'), 'recommend' => array(0 => 'reporeviewflow-create', 1 => 'reporeviewflow-edit', 2 => 'reporeviewflow-changeStatus'));

$config->group->package->manageWebhooks = new stdclass();
$config->group->package->manageWebhooks->order  = 2470;
$config->group->package->manageWebhooks->subset = 'repo';
$config->group->package->manageWebhooks->privs  = array();
$config->group->package->manageWebhooks->privs['repo-createWebhook'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'repo-browseWebhooks'), 'recommend' => array());
$config->group->package->manageWebhooks->privs['repo-editWebhook'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(0 => 'repo-browseWebhooks'), 'recommend' => array());
$config->group->package->manageWebhooks->privs['repo-enableWebhook'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'repo-browseWebhooks'), 'recommend' => array());
$config->group->package->manageWebhooks->privs['repo-deleteWebhook'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 30, 'depend' => array(0 => 'repo-browseWebhooks'), 'recommend' => array());

$config->group->package->browseCode = new stdclass();
$config->group->package->browseCode->order  = 2540;
$config->group->package->browseCode->subset = 'repocode';
$config->group->package->browseCode->privs  = array();
$config->group->package->browseCode->privs['repo-log'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'repo-maintain'), 'recommend' => array(0 => 'repo-blame', 1 => 'repo-revision', 2 => 'repo-view'));
$config->group->package->browseCode->privs['repo-revision'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(0 => 'repo-maintain'), 'recommend' => array(0 => 'repo-blame', 1 => 'repo-log', 2 => 'repo-view'));
$config->group->package->browseCode->privs['repo-diff'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'repo-maintain'), 'recommend' => array(0 => 'repo-addBug', 1 => 'repo-editBug', 2 => 'repo-review'));
$config->group->package->browseCode->privs['repo-view'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 25, 'depend' => array(0 => 'repo-maintain'), 'recommend' => array(0 => 'repo-blame', 1 => 'repo-log', 2 => 'repo-revision'));
$config->group->package->browseCode->privs['repo-blame'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 30, 'depend' => array(0 => 'repo-maintain'), 'recommend' => array(0 => 'repo-log', 1 => 'repo-revision', 2 => 'repo-view'));
$config->group->package->browseCode->privs['repo-browseBranch'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 35, 'depend' => array(0 => 'repo-browse'), 'recommend' => array(0 => 'repo-log', 1 => 'repo-revision', 2 => 'repo-maintain'));
$config->group->package->browseCode->privs['repo-browseTag'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 40, 'depend' => array(0 => 'repo-browse'), 'recommend' => array(0 => 'repo-log', 1 => 'repo-revision', 2 => 'repo-maintain'));

$config->group->package->downloadCode = new stdclass();
$config->group->package->downloadCode->order  = 2550;
$config->group->package->downloadCode->subset = 'repocode';
$config->group->package->downloadCode->privs  = array();
$config->group->package->downloadCode->privs['repo-download'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'repo-maintain'), 'recommend' => array(0 => 'repo-downloadCode'));
$config->group->package->downloadCode->privs['repo-downloadCode'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'repo-maintain'), 'recommend' => array(0 => 'repo-download'));

$config->group->package->createBranchAndTag = new stdclass();
$config->group->package->createBranchAndTag->order  = 2555;
$config->group->package->createBranchAndTag->subset = 'repocode';
$config->group->package->createBranchAndTag->privs  = array();
$config->group->package->createBranchAndTag->privs['repo-createBranch'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'repo-browse', 1 => 'repo-browseBranch'), 'recommend' => array(0 => 'repo-maintain'));
$config->group->package->createBranchAndTag->privs['repo-createTag'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'repo-browse', 1 => 'repo-browseTag'), 'recommend' => array(0 => 'repo-maintain'));

$config->group->package->archiveBranch = new stdclass();
$config->group->package->archiveBranch->order  = 2560;
$config->group->package->archiveBranch->subset = 'repocode';
$config->group->package->archiveBranch->privs  = array();
$config->group->package->archiveBranch->privs['repo-archiveBranch'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 50, 'depend' => array(0 => 'repo-browse', 1 => 'repo-browseBranch', 2 => 'repo-activeBranch'), 'recommend' => array(0 => 'repo-maintain'));
$config->group->package->archiveBranch->privs['repo-activeBranch'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 55, 'depend' => array(0 => 'repo-browse', 1 => 'repo-browseBranch', 2 => 'repo-archiveBranch'), 'recommend' => array(0 => 'repo-maintain'));

$config->group->package->deleteBranchAndTag = new stdclass();
$config->group->package->deleteBranchAndTag->order  = 2565;
$config->group->package->deleteBranchAndTag->subset = 'repocode';
$config->group->package->deleteBranchAndTag->privs  = array();
$config->group->package->deleteBranchAndTag->privs['repo-deleteBranch'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 35, 'depend' => array(0 => 'repo-browse', 1 => 'repo-browseBranch'), 'recommend' => array(0 => 'repo-maintain'));
$config->group->package->deleteBranchAndTag->privs['repo-deleteTag'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 45, 'depend' => array(0 => 'repo-browse', 1 => 'repo-browseTag'), 'recommend' => array(0 => 'repo-maintain'));

$config->group->package->provider = new stdclass();
$config->group->package->provider->order  = 2385;
$config->group->package->provider->subset = 'configure';
$config->group->package->provider->privs  = array();
$config->group->package->provider->privs['provider-browse'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(), 'recommend' => array(0 => 'provider-create', 1 => 'provider-edit', 2 => 'provider-delete'));
$config->group->package->provider->privs['provider-create'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(), 'recommend' => array(0 => 'provider-edit', 1 => 'provider-delete'));
$config->group->package->provider->privs['provider-edit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'provider-browse'), 'recommend' => array(0 => 'provider-create', 1 => 'provider-delete'));
$config->group->package->provider->privs['provider-delete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'provider-browse'), 'recommend' => array(0 => 'provider-create', 1 => 'provider-edit'));

$config->group->package->browseRunner = new stdclass();
$config->group->package->browseRunner->order  = 2390;
$config->group->package->browseRunner->subset = 'configure';
$config->group->package->browseRunner->privs  = array();
$config->group->package->browseRunner->privs['runner-browse'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(), 'recommend' => array(0 => 'runner-create', 1 => 'runner-changeState', 2 => 'runner-edit', 3 => 'runner-delete'));

$config->group->package->manageRunner = new stdclass();
$config->group->package->manageRunner->order  = 2395;
$config->group->package->manageRunner->subset = 'configure';
$config->group->package->manageRunner->privs  = array();
$config->group->package->manageRunner->privs['runner-create'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'runner-browse'), 'recommend' => array(0 => 'runner-edit', 1 => 'runner-changeState', 2 => 'runner-delete'));
$config->group->package->manageRunner->privs['runner-changeState'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(0 => 'runner-browse'), 'recommend' => array(0 => 'runner-create', 1 => 'runner-edit', 2 => 'runner-delete'));
$config->group->package->manageRunner->privs['runner-edit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'runner-browse'), 'recommend' => array(0 => 'runner-create', 1 => 'runner-changeState', 2 => 'runner-delete'));
$config->group->package->manageRunner->privs['runner-delete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 25, 'depend' => array(0 => 'runner-browse'), 'recommend' => array(0 => 'runner-create', 1 => 'runner-edit', 2 => 'runner-changeState'));

$config->group->package->browseReviewRequest = new stdclass();
$config->group->package->browseReviewRequest->order  = 2580;
$config->group->package->browseReviewRequest->subset = 'codeReview';
$config->group->package->browseReviewRequest->privs  = array();
$config->group->package->browseReviewRequest->privs['ppm-browse'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(0 => 'repo-maintain', 1 => 'repo-browse'), 'recommend' => array(0 => 'ppm-close', 1 => 'ppm-create', 2 => 'ppm-edit', 3 => 'ppm-reopen', 4 => 'ppm-view'));
$config->group->package->browseReviewRequest->privs['ppm-view'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'ppm-browse'), 'recommend' => array());

$config->group->package->submitReviewRequest = new stdclass();
$config->group->package->submitReviewRequest->order  = 2585;
$config->group->package->submitReviewRequest->subset = 'codeReview';
$config->group->package->submitReviewRequest->privs  = array();
$config->group->package->submitReviewRequest->privs['ppm-create'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'ppm-browse'), 'recommend' => array(0 => 'ppm-edit'));
$config->group->package->submitReviewRequest->privs['ppm-edit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 25, 'depend' => array(0 => 'ppm-browse'), 'recommend' => array(0 => 'ppm-create'));
$config->group->package->submitReviewRequest->privs['ppm-delete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 30, 'depend' => array(0 => 'ppm-browse'), 'recommend' => array(0 => 'ppm-create', 1 => 'ppm-edit'));
$config->group->package->submitReviewRequest->privs['ppm-merge'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 35, 'depend' => array(0 => 'ppm-browse', 1 => 'ppm-view'), 'recommend' => array(0 => 'ppm-browse', 1 => 'ppm-create', 2 => 'ppm-edit'));

$config->group->package->codeReview = new stdclass();
$config->group->package->codeReview->order  = 2590;
$config->group->package->codeReview->subset = 'codeReview';
$config->group->package->codeReview->privs  = array();
$config->group->package->codeReview->privs['ppm-review'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'ppm-browse'), 'recommend' => array(0 => 'ppm-close', 1 => 'ppm-reopen'));
$config->group->package->codeReview->privs['ppm-close'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 25, 'depend' => array(0 => 'ppm-browse'), 'recommend' => array(0 => 'ppm-review', 1 => 'ppm-reopen'));
$config->group->package->codeReview->privs['ppm-reopen'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 30, 'depend' => array(0 => 'ppm-browse'), 'recommend' => array(0 => 'ppm-review', 1 => 'ppm-close'));

$config->group->package->browseScanInfo = new stdclass();
$config->group->package->browseScanInfo->order  = 5;
$config->group->package->browseScanInfo->subset = 'codeScan';
$config->group->package->browseScanInfo->privs  = array();
$config->group->package->browseScanInfo->privs['codescan-overview'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'codescan-task'), 'recommend' => array());
$config->group->package->browseScanInfo->privs['codescan-task'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'codescan-overview'), 'recommend' => array());
$config->group->package->browseScanInfo->privs['codescan-taskView'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 25, 'depend' => array(0 => 'codescan-task'), 'recommend' => array());
$config->group->package->browseScanInfo->privs['codescan-plan'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 30, 'depend' => array(0 => 'codescan-overview'), 'recommend' => array());
$config->group->package->browseScanInfo->privs['codescan-planView'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 35, 'depend' => array(0 => 'codescan-plan'), 'recommend' => array());
$config->group->package->browseScanInfo->privs['codescan-solution'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 40, 'depend' => array(0 => 'codescan-plan'), 'recommend' => array());
$config->group->package->browseScanInfo->privs['codescan-solutionview'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 45, 'depend' => array(0 => 'codescan-solution'), 'recommend' => array());
$config->group->package->browseScanInfo->privs['codescan-browse'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 50, 'depend' => array(0 => 'codescan-ruleset'), 'recommend' => array(0 => 'codescan-view'));
$config->group->package->browseScanInfo->privs['codescan-view'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 55, 'depend' => array(0 => 'codescan-browse'), 'recommend' => array());
$config->group->package->browseScanInfo->privs['codescan-ruleset'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 60, 'depend' => array(0 => 'codescan-solution'), 'recommend' => array());
$config->group->package->browseScanInfo->privs['codescan-rulesetview'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 65, 'depend' => array(0 => 'codescan-ruleset'), 'recommend' => array());
$config->group->package->browseScanInfo->privs['codescan-taskLog'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 70, 'depend' => array(0 => 'codescan-task'), 'recommend' => array());

$config->group->package->execScan = new stdclass();
$config->group->package->execScan->order  = 10;
$config->group->package->execScan->subset = 'codeScan';
$config->group->package->execScan->privs  = array();
$config->group->package->execScan->privs['codescan-exec'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'codescan-plan'), 'recommend' => array(0 => 'codescan-createPlan', 1 => 'codescan-editPlan', 2 => 'codescan-deletePlan', 3 => 'codescan-task'));
$config->group->package->execScan->privs['codescan-resend'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'codescan-task'), 'recommend' => array());

$config->group->package->codeScanMaintain = new stdclass();
$config->group->package->codeScanMaintain->order  = 20;
$config->group->package->codeScanMaintain->subset = 'codeScan';
$config->group->package->codeScanMaintain->privs  = array();
$config->group->package->codeScanMaintain->privs['codescan-createPlan'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'codescan-plan'), 'recommend' => array(0 => 'codescan-editPlan', 1 => 'codescan-deletePlan', 2 => 'codescan-task', 3 => 'codescan-exec'));
$config->group->package->codeScanMaintain->privs['codescan-editPlan'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'codescan-plan'), 'recommend' => array(0 => 'codescan-createPlan', 1 => 'codescan-deletePlan', 2 => 'codescan-task', 3 => 'codescan-exec'));
$config->group->package->codeScanMaintain->privs['codescan-createTrigger'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(0 => 'codescan-plan'), 'recommend' => array(0 => 'codescan-editTrigger', 1 => 'codescan-deleteTrigger'));
$config->group->package->codeScanMaintain->privs['codescan-editTrigger'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'codescan-plan'), 'recommend' => array(0 => 'codescan-createTrigger', 1 => 'codescan-deleteTrigger'));
$config->group->package->codeScanMaintain->privs['codescan-createsolution'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 25, 'depend' => array(0 => 'codescan-solution'), 'recommend' => array(0 => 'codescan-editsolution', 1 => 'codescan-deletesolution'));
$config->group->package->codeScanMaintain->privs['codescan-editsolution'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 30, 'depend' => array(0 => 'codescan-solution'), 'recommend' => array(0 => 'codescan-createsolution', 1 => 'codescan-deletesolution', 2 => 'codescan-changesolutionstate'));
$config->group->package->codeScanMaintain->privs['codescan-changesolutionstate'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 35, 'depend' => array(0 => 'codescan-solution'), 'recommend' => array(0 => 'codescan-createsolution', 1 => 'codescan-editsolution', 2 => 'codescan-deletesolution'));
$config->group->package->codeScanMaintain->privs['codescan-linkset'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 40, 'depend' => array(0 => 'codescan-solution'), 'recommend' => array(0 => 'codescan-unlinkset'));
$config->group->package->codeScanMaintain->privs['codescan-createruleset'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 45, 'depend' => array(0 => 'codescan-ruleset'), 'recommend' => array(0 => 'codescan-editruleset', 1 => 'codescan-deleteruleset'));
$config->group->package->codeScanMaintain->privs['codescan-editruleset'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 50, 'depend' => array(0 => 'codescan-ruleset'), 'recommend' => array(0 => 'codescan-createruleset', 1 => 'codescan-deleteruleset', 2 => 'codescan-changerulesetstate'));
$config->group->package->codeScanMaintain->privs['codescan-changerulesetstate'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 55, 'depend' => array(0 => 'codescan-ruleset'), 'recommend' => array(0 => 'codescan-createruleset', 1 => 'codescan-editruleset', 2 => 'codescan-deleteruleset'));
$config->group->package->codeScanMaintain->privs['codescan-changeState'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 60, 'depend' => array(0 => 'codescan-browse'), 'recommend' => array(0 => 'codescan-view'));
$config->group->package->codeScanMaintain->privs['codescan-linkrule'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 65, 'depend' => array(0 => 'codescan-ruleset'), 'recommend' => array(0 => 'codescan-batchunlinkrule', 1 => 'codescan-unlinkrule'));

$config->group->package->codeScanDelete = new stdclass();
$config->group->package->codeScanDelete->order  = 20;
$config->group->package->codeScanDelete->subset = 'codeScan';
$config->group->package->codeScanDelete->privs  = array();
$config->group->package->codeScanDelete->privs['codescan-deletePlan'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'codescan-plan'), 'recommend' => array(0 => 'codescan-createPlan', 1 => 'codescan-editPlan', 2 => 'codescan-task', 3 => 'codescan-exec'));
$config->group->package->codeScanDelete->privs['codescan-deleteTrigger'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'codescan-plan'), 'recommend' => array(0 => 'codescan-createTrigger', 1 => 'codescan-editTrigger'));
$config->group->package->codeScanDelete->privs['codescan-deletesolution'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'codescan-solution'), 'recommend' => array(0 => 'codescan-createsolution', 1 => 'codescan-editsolution'));
$config->group->package->codeScanDelete->privs['codescan-unlinkSolution'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 25, 'depend' => array(0 => 'codescan-plan'), 'recommend' => array(0 => 'codescan-createPlan', 1 => 'codescan-editPlan', 2 => 'codescan-deletePlan', 3 => 'codescan-task'));
$config->group->package->codeScanDelete->privs['codescan-unlinkset'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 30, 'depend' => array(0 => 'codescan-solution'), 'recommend' => array(0 => 'codescan-linkset'));
$config->group->package->codeScanDelete->privs['codescan-deleteruleset'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 35, 'depend' => array(0 => 'codescan-ruleset'), 'recommend' => array(0 => 'codescan-createruleset', 1 => 'codescan-editruleset'));
$config->group->package->codeScanDelete->privs['codescan-unlinkrule'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 40, 'depend' => array(0 => 'codescan-ruleset'), 'recommend' => array(0 => 'codescan-batchunlinkrule', 1 => 'codescan-linkrule'));
$config->group->package->codeScanDelete->privs['codescan-batchunlinkrule'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 45, 'depend' => array(0 => 'codescan-ruleset'), 'recommend' => array(0 => 'codescan-unlinkrule', 1 => 'codescan-linkrule'));

$config->group->package->browseCodeIssue = new stdclass();
$config->group->package->browseCodeIssue->order  = 2600;
$config->group->package->browseCodeIssue->subset = 'codereview';
$config->group->package->browseCodeIssue->privs  = array();
$config->group->package->browseCodeIssue->privs['repo-review'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 0, 'depend' => array(0 => 'repo-diff'), 'recommend' => array(0 => 'repo-addBug', 1 => 'repo-editBug'));
$config->group->package->browseCodeIssue->privs['codescan-issue'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'repo-maintain'), 'recommend' => array());
$config->group->package->browseCodeIssue->privs['codescan-issueView'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(0 => 'codescan-issue'), 'recommend' => array());
$config->group->package->browseCodeIssue->privs['repo-addComment'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'repo-diff'), 'recommend' => array(0 => 'repo-addBug', 1 => 'repo-editBug', 2 => 'repo-editComment', 3 => 'repo-review'));

$config->group->package->reviewCodeIssue = new stdclass();
$config->group->package->reviewCodeIssue->order  = 2605;
$config->group->package->reviewCodeIssue->subset = 'codereview';
$config->group->package->reviewCodeIssue->privs  = array();
$config->group->package->reviewCodeIssue->privs['repo-addBug'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'repo-diff'), 'recommend' => array(0 => 'repo-addComment', 1 => 'repo-editBug', 2 => 'repo-editComment', 3 => 'repo-review'));
$config->group->package->reviewCodeIssue->privs['repo-editBug'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(0 => 'repo-diff'), 'recommend' => array(0 => 'repo-addBug', 1 => 'repo-addComment', 2 => 'repo-editComment', 3 => 'repo-review'));
$config->group->package->reviewCodeIssue->privs['repo-editComment'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'repo-diff'), 'recommend' => array(0 => 'repo-addBug', 1 => 'repo-addComment', 2 => 'repo-editBug', 3 => 'repo-review'));
$config->group->package->reviewCodeIssue->privs['repo-deleteComment'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 25, 'depend' => array(0 => 'repo-diff'), 'recommend' => array(0 => 'repo-addBug', 1 => 'repo-addComment', 2 => 'repo-editBug', 3 => 'repo-editComment', 4 => 'repo-review'));

$config->group->package->processScanIssues = new stdclass();
$config->group->package->processScanIssues->order  = 2610;
$config->group->package->processScanIssues->subset = 'codereview';
$config->group->package->processScanIssues->privs  = array();
$config->group->package->processScanIssues->privs['codescan-ignoreIssue'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'codescan-issue'), 'recommend' => array(0 => 'codescan-issueView', 1 => 'codescan-confirmIssue', 2 => 'codescan-activeIssue'));
$config->group->package->processScanIssues->privs['codescan-confirmIssue'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'codescan-issue'), 'recommend' => array(0 => 'codescan-issueView', 1 => 'codescan-ignoreIssue', 2 => 'codescan-activeIssue'));
$config->group->package->processScanIssues->privs['codescan-activeIssue'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(0 => 'codescan-issue'), 'recommend' => array(0 => 'codescan-issueView', 1 => 'codescan-ignoreIssue', 2 => 'codescan-confirmIssue'));

$config->group->package->browsePipeline = new stdclass();
$config->group->package->browsePipeline->order  = 5;
$config->group->package->browsePipeline->subset = 'pipeline';
$config->group->package->browsePipeline->privs  = array();
$config->group->package->browsePipeline->privs['pipeline-browse'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'repo-maintain', 1 => 'repo-browse'), 'recommend' => array(0 => 'pipeline-create', 1 => 'pipeline-edit', 2 => 'pipeline-arrange'));
$config->group->package->browsePipeline->privs['pipeline-execution'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'pipeline-browse'), 'recommend' => array(0 => 'pipeline-exec', 1 => 'pipeline-execView'));
$config->group->package->browsePipeline->privs['pipeline-execView'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(0 => 'pipeline-execution'), 'recommend' => array(0 => 'pipeline-execution', 1 => 'pipeline-exec'));

$config->group->package->pipelineActions = new stdclass();
$config->group->package->pipelineActions->order  = 10;
$config->group->package->pipelineActions->subset = 'pipeline';
$config->group->package->pipelineActions->privs  = array();
$config->group->package->pipelineActions->privs['pipeline-exec'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'pipeline-browse'), 'recommend' => array(0 => 'pipeline-execution'));
$config->group->package->pipelineActions->privs['pipeline-cancel'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'pipeline-execution'), 'recommend' => array(0 => 'pipeline-exec'));

$config->group->package->managePipeline = new stdclass();
$config->group->package->managePipeline->order  = 15;
$config->group->package->managePipeline->subset = 'pipeline';
$config->group->package->managePipeline->privs  = array();
$config->group->package->managePipeline->privs['pipeline-create'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'pipeline-browse'), 'recommend' => array(0 => 'pipeline-edit', 1 => 'pipeline-arrange'));
$config->group->package->managePipeline->privs['pipeline-edit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'pipeline-browse'), 'recommend' => array(0 => 'pipeline-create', 1 => 'pipeline-arrange'));
$config->group->package->managePipeline->privs['pipeline-arrange'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(0 => 'pipeline-browse'), 'recommend' => array(0 => 'pipeline-create', 1 => 'pipeline-edit'));
$config->group->package->managePipeline->privs['pipeline-delete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'pipeline-browse'), 'recommend' => array(0 => 'pipeline-create', 1 => 'pipeline-edit', 2 => 'pipeline-arrange'));

$config->group->package->browseArtifactrepo = new stdclass();
$config->group->package->browseArtifactrepo->order  = 2800;
$config->group->package->browseArtifactrepo->subset = 'artifact';
$config->group->package->browseArtifactrepo->privs  = array();
$config->group->package->browseArtifactrepo->privs['artifact-browse'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'space-browse', 1 => 'repo-browse'), 'recommend' => array());

$config->group->package->manageArtifactrepo = new stdclass();
$config->group->package->manageArtifactrepo->order  = 2810;
$config->group->package->manageArtifactrepo->subset = 'artifact';
$config->group->package->manageArtifactrepo->privs  = array();
$config->group->package->manageArtifactrepo->privs['artifact-create'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'space-browse', 1 => 'repo-browse', 2 => 'artifact-browse'), 'recommend' => array());
$config->group->package->manageArtifactrepo->privs['artifact-edit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'space-browse', 1 => 'repo-browse', 2 => 'artifact-browse'), 'recommend' => array());

$config->group->package->deleteArtifactrepo = new stdclass();
$config->group->package->deleteArtifactrepo->order  = 2820;
$config->group->package->deleteArtifactrepo->subset = 'artifact';
$config->group->package->deleteArtifactrepo->privs  = array();
$config->group->package->deleteArtifactrepo->privs['artifact-delete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'space-browse', 1 => 'repo-browse', 2 => 'artifact-browse'), 'recommend' => array());

$config->group->package->manageArtifactDir = new stdclass();
$config->group->package->manageArtifactDir->order  = 2830;
$config->group->package->manageArtifactDir->subset = 'artifact';
$config->group->package->manageArtifactDir->privs  = array();
$config->group->package->manageArtifactDir->privs['artifact-createDir'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'space-browse', 1 => 'repo-browse', 2 => 'artifact-browse'), 'recommend' => array());
$config->group->package->manageArtifactDir->privs['artifact-editDir'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'space-browse', 1 => 'repo-browse', 2 => 'artifact-browse'), 'recommend' => array());

$config->group->package->deleteArtifactDir = new stdclass();
$config->group->package->deleteArtifactDir->order  = 2840;
$config->group->package->deleteArtifactDir->subset = 'artifact';
$config->group->package->deleteArtifactDir->privs  = array();
$config->group->package->deleteArtifactDir->privs['artifact-deleteDir'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'space-browse', 1 => 'repo-browse', 2 => 'artifact-browse'), 'recommend' => array());

$config->group->package->uploadArtifact = new stdclass();
$config->group->package->uploadArtifact->order  = 2850;
$config->group->package->uploadArtifact->subset = 'artifact';
$config->group->package->uploadArtifact->privs  = array();
$config->group->package->uploadArtifact->privs['artifact-uploadArtifact'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'space-browse', 1 => 'repo-browse', 2 => 'artifact-browse'), 'recommend' => array());

$config->group->package->deleteArtifact = new stdclass();
$config->group->package->deleteArtifact->order  = 2860;
$config->group->package->deleteArtifact->subset = 'artifact';
$config->group->package->deleteArtifact->privs  = array();
$config->group->package->deleteArtifact->privs['artifact-deleteArtifact'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'space-browse', 1 => 'repo-browse'), 'recommend' => array());

$config->group->package->manageArtifact = new stdclass();
$config->group->package->manageArtifact->order  = 2870;
$config->group->package->manageArtifact->subset = 'artifact';
$config->group->package->manageArtifact->privs  = array();
$config->group->package->manageArtifact->privs['artifact-editArtifact'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'space-browse', 1 => 'repo-browse'), 'recommend' => array());

$config->group->package->admin = new stdclass();
$config->group->package->admin->order  = 5;
$config->group->package->admin->subset = 'admin';
$config->group->package->admin->privs  = array();
$config->group->package->admin->privs['admin-index'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(), 'recommend' => array());

$config->group->package->backup = new stdclass();
$config->group->package->backup->order  = 5;
$config->group->package->backup->subset = 'admin';
$config->group->package->backup->privs  = array();
$config->group->package->backup->privs['backup-index'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 0, 'depend' => array(0 => 'admin-index'), 'recommend' => array());
$config->group->package->backup->privs['backup-backup'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 2, 'depend' => array(0 => 'backup-index'), 'recommend' => array());
$config->group->package->backup->privs['backup-restore'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 3, 'depend' => array(0 => 'backup-index'), 'recommend' => array());
$config->group->package->backup->privs['backup-delete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 4, 'depend' => array(0 => 'backup-index'), 'recommend' => array());
$config->group->package->backup->privs['backup-setting'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 1, 'depend' => array(0 => 'backup-index'), 'recommend' => array(0 => 'backup-change'));
$config->group->package->backup->privs['backup-rmPHPHeader'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'backup-index'), 'recommend' => array());
$config->group->package->backup->privs['backup-change'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 6, 'depend' => array(0 => 'backup-setting'), 'recommend' => array());

$config->group->package->trash = new stdclass();
$config->group->package->trash->order  = 15;
$config->group->package->trash->subset = 'systemsetting';
$config->group->package->trash->privs  = array();
$config->group->package->trash->privs['action-trash'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 0, 'depend' => array(0 => 'admin-index'), 'recommend' => array());
$config->group->package->trash->privs['action-undelete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 1, 'depend' => array(0 => 'action-trash'), 'recommend' => array());
$config->group->package->trash->privs['action-hideOne'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 2, 'depend' => array(0 => 'action-trash'), 'recommend' => array());

$config->group->package->security = new stdclass();
$config->group->package->security->order  = 20;
$config->group->package->security->subset = 'systemsetting';
$config->group->package->security->privs  = array();
$config->group->package->security->privs['admin-checkWeak'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 1, 'depend' => array(0 => 'admin-index'), 'recommend' => array());
$config->group->package->security->privs['admin-resetPWDSetting'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 2, 'depend' => array(0 => 'admin-index'), 'recommend' => array());
$config->group->package->security->privs['admin-safe'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 0, 'depend' => array(0 => 'admin-index'), 'recommend' => array());

$config->group->package->ldap = new stdclass();
$config->group->package->ldap->order  = 30;
$config->group->package->ldap->subset = 'systemsetting';
$config->group->package->ldap->privs  = array();
$config->group->package->ldap->privs['user-importldap'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(0 => 'ldap-set'), 'recommend' => array());
$config->group->package->ldap->privs['ldap-set'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'admin-index'), 'recommend' => array());

$config->group->package->systemSetting = new stdclass();
$config->group->package->systemSetting->order  = 5;
$config->group->package->systemSetting->subset = 'systemsetting';
$config->group->package->systemSetting->privs  = array();
$config->group->package->systemSetting->privs['cache-setting'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 45, 'depend' => array(0 => 'admin-index'), 'recommend' => array());
$config->group->package->systemSetting->privs['cache-flush'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 45, 'depend' => array(0 => 'admin-index', 1 => 'cache-setting'), 'recommend' => array());
$config->group->package->systemSetting->privs['admin-tableEngine'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 45, 'depend' => array(0 => 'admin-index'), 'recommend' => array());
$config->group->package->systemSetting->privs['custom-timezone'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 45, 'depend' => array(0 => 'admin-index'), 'recommend' => array());
$config->group->package->systemSetting->privs['search-buildIndex'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 35, 'depend' => array(0 => 'admin-index'), 'recommend' => array());

$config->group->package->search = new stdclass();
$config->group->package->search->order  = 5;
$config->group->package->search->subset = 'search';
$config->group->package->search->privs  = array();
$config->group->package->search->privs['search-buildForm']   = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 0, 'depend' => array(), 'recommend' => array());
$config->group->package->search->privs['search-buildQuery']  = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 1, 'depend' => array('search-buildForm'), 'recommend' => array());
$config->group->package->search->privs['search-saveQuery']   = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 2, 'depend' => array('search-buildForm'), 'recommend' => array('search-deleteQuery'));
$config->group->package->search->privs['search-deleteQuery'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 3, 'depend' => array('search-buildForm'), 'recommend' => array('search-saveQuery'));

$config->group->package->managePriv = new stdclass();
$config->group->package->managePriv->order  = 20;
$config->group->package->managePriv->subset = 'staffmanage';
$config->group->package->managePriv->privs  = array();
$config->group->package->managePriv->privs['group-managePriv'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 0, 'depend' => array(0 => 'admin-index', 1 => 'group-browse'), 'recommend' => array());

$config->group->package->dept = new stdclass();
$config->group->package->dept->order  = 5;
$config->group->package->dept->subset = 'staffmanage';
$config->group->package->dept->privs  = array();
$config->group->package->dept->privs['dept-browse'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'admin-index'), 'recommend' => array(0 => 'dept-delete', 1 => 'dept-manageChild', 2 => 'dept-updateOrder'));
$config->group->package->dept->privs['dept-updateOrder'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'admin-index', 1 => 'dept-browse'), 'recommend' => array(0 => 'dept-delete', 1 => 'dept-edit', 2 => 'dept-manageChild'));
$config->group->package->dept->privs['dept-manageChild'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(0 => 'admin-index', 1 => 'dept-browse'), 'recommend' => array(0 => 'dept-delete', 1 => 'dept-edit'));
$config->group->package->dept->privs['dept-edit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 20, 'depend' => array(0 => 'admin-index', 1 => 'dept-browse'), 'recommend' => array(0 => 'dept-delete', 1 => 'dept-manageChild'));
$config->group->package->dept->privs['dept-delete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 25, 'depend' => array(0 => 'admin-index', 1 => 'dept-browse'), 'recommend' => array(0 => 'dept-edit', 1 => 'dept-manageChild'));

$config->group->package->group = new stdclass();
$config->group->package->group->order  = 15;
$config->group->package->group->subset = 'staffmanage';
$config->group->package->group->privs  = array();
$config->group->package->group->privs['group-browse'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 0, 'depend' => array(0 => 'admin-index'), 'recommend' => array());
$config->group->package->group->privs['group-create'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 1, 'depend' => array(0 => 'admin-index', 1 => 'group-browse'), 'recommend' => array(0 => 'group-copy', 1 => 'group-delete', 2 => 'group-edit', 3 => 'group-manageMember', 4 => 'group-manageProjectAdmin', 5 => 'group-manageView'));
$config->group->package->group->privs['group-edit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 2, 'depend' => array(0 => 'admin-index', 1 => 'group-browse'), 'recommend' => array(0 => 'group-copy', 1 => 'group-create', 2 => 'group-delete', 3 => 'group-manageMember', 4 => 'group-manageProjectAdmin', 5 => 'group-manageView'));
$config->group->package->group->privs['group-copy'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 3, 'depend' => array(0 => 'admin-index', 1 => 'group-browse'), 'recommend' => array(0 => 'group-create', 1 => 'group-delete', 2 => 'group-edit', 3 => 'group-manageMember', 4 => 'group-manageProjectAdmin', 5 => 'group-manageView'));
$config->group->package->group->privs['group-delete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 4, 'depend' => array(0 => 'admin-index', 1 => 'group-browse'), 'recommend' => array(0 => 'group-copy', 1 => 'group-create', 2 => 'group-edit', 3 => 'group-manageMember', 4 => 'group-manageProjectAdmin', 5 => 'group-manageView'));
$config->group->package->group->privs['group-manageMember'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 6, 'depend' => array(0 => 'admin-index', 1 => 'group-browse'), 'recommend' => array(0 => 'group-copy', 1 => 'group-create', 2 => 'group-delete', 3 => 'group-edit', 4 => 'group-manageProjectAdmin', 5 => 'group-manageView'));
$config->group->package->group->privs['group-manageProjectAdmin'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 8, 'depend' => array(0 => 'admin-index', 1 => 'group-browse'), 'recommend' => array(0 => 'group-copy', 1 => 'group-create', 2 => 'group-delete', 3 => 'group-edit', 4 => 'group-manageMember', 5 => 'group-manageView'));
$config->group->package->group->privs['group-manageView'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'admin-index', 1 => 'group-browse'), 'recommend' => array(0 => 'group-copy', 1 => 'group-create', 2 => 'group-delete', 3 => 'group-edit', 4 => 'group-manageMember', 5 => 'group-manageProjectAdmin'));

$config->group->package->user = new stdclass();
$config->group->package->user->order  = 10;
$config->group->package->user->subset = 'staffmanage';
$config->group->package->user->privs  = array();
$config->group->package->user->privs['company-browse'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 0, 'depend' => array(0 => 'admin-index'), 'recommend' => array(0 => 'user-view'));
$config->group->package->user->privs['user-create'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 2, 'depend' => array(0 => 'admin-index', 1 => 'company-browse'), 'recommend' => array(0 => 'user-batchCreate', 1 => 'user-delete', 2 => 'user-edit', 3 => 'user-unlock'));
$config->group->package->user->privs['user-batchCreate'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 3, 'depend' => array(0 => 'admin-index', 1 => 'company-browse'), 'recommend' => array(0 => 'user-create'));
$config->group->package->user->privs['user-view'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 1, 'depend' => array(0 => 'admin-index', 1 => 'company-browse'), 'recommend' => array());
$config->group->package->user->privs['user-edit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 4, 'depend' => array(0 => 'admin-index', 1 => 'company-browse', 2 => 'user-view'), 'recommend' => array(0 => 'user-batchEdit', 1 => 'user-create', 2 => 'user-delete', 3 => 'user-unlock'));
$config->group->package->user->privs['user-unlock'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 6, 'depend' => array(0 => 'admin-index', 1 => 'company-browse', 2 => 'user-view'), 'recommend' => array(0 => 'user-create', 1 => 'user-delete', 2 => 'user-edit'));
$config->group->package->user->privs['user-delete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 7, 'depend' => array(0 => 'admin-index', 1 => 'company-browse', 2 => 'user-view'), 'recommend' => array(0 => 'user-create', 1 => 'user-edit', 2 => 'user-unlock'));
$config->group->package->user->privs['user-batchEdit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'admin-index', 1 => 'company-browse', 2 => 'user-view'), 'recommend' => array(0 => 'user-edit'));
$config->group->package->user->privs['user-unbind'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 8, 'depend' => array(0 => 'admin-index'), 'recommend' => array());
$config->group->package->user->privs['user-export'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 9, 'depend' => array(0 => 'company-browse'), 'recommend' => array());
$config->group->package->user->privs['user-exportTemplate'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'company-browse', 1 => 'user-import'), 'recommend' => array());
$config->group->package->user->privs['user-import'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 11, 'depend' => array(0 => 'company-browse', 1 => 'user-exportTemplate'), 'recommend' => array());
$config->group->package->user->privs['admin-sso'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 65, 'depend' => array(0 => 'admin-index'), 'recommend' => array());

$config->group->package->message = new stdclass();
$config->group->package->message->order  = 5;
$config->group->package->message->subset = 'notifysetting';
$config->group->package->message->privs  = array();
$config->group->package->message->privs['message-index'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(), 'recommend' => array());
$config->group->package->message->privs['message-browser'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(), 'recommend' => array());
$config->group->package->message->privs['message-setting'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(), 'recommend' => array());

$config->group->package->mail = new stdclass();
$config->group->package->mail->order  = 10;
$config->group->package->mail->subset = 'notifysetting';
$config->group->package->mail->privs  = array();
$config->group->package->mail->privs['mail-detect'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'admin-index'), 'recommend' => array());
$config->group->package->mail->privs['mail-index'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 0, 'depend' => array(0 => 'mail-detect'), 'recommend' => array());
$config->group->package->mail->privs['mail-edit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 1, 'depend' => array(0 => 'mail-detect', 1 => 'message-index'), 'recommend' => array());
$config->group->package->mail->privs['mail-save'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 3, 'depend' => array(0 => 'mail-edit'), 'recommend' => array(0 => 'mail-reset'));
$config->group->package->mail->privs['mail-test'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 2, 'depend' => array(0 => 'mail-edit'), 'recommend' => array());
$config->group->package->mail->privs['mail-reset'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 4, 'depend' => array(0 => 'mail-edit'), 'recommend' => array(0 => 'mail-save'));
$config->group->package->mail->privs['mail-browse'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 6, 'depend' => array(0 => 'mail-edit'), 'recommend' => array());
$config->group->package->mail->privs['mail-delete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 7, 'depend' => array(0 => 'mail-browse'), 'recommend' => array());
$config->group->package->mail->privs['mail-batchDelete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 8, 'depend' => array(0 => 'mail-browse'), 'recommend' => array(0 => 'mail-delete'));
$config->group->package->mail->privs['mail-resend'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 9, 'depend' => array(0 => 'mail-browse'), 'recommend' => array());

$config->group->package->sms = new stdclass();
$config->group->package->sms->order  = 20;
$config->group->package->sms->subset = 'notifysetting';
$config->group->package->sms->privs  = array();
$config->group->package->sms->privs['sms-index'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(), 'recommend' => array());
$config->group->package->sms->privs['sms-test'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'sms-index'), 'recommend' => array());
$config->group->package->sms->privs['sms-reset'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 15, 'depend' => array(0 => 'sms-index'), 'recommend' => array());

$config->group->package->my = new stdclass();
$config->group->package->my->order  = 5;
$config->group->package->my->subset = 'my';
$config->group->package->my->privs  = array();
$config->group->package->my->privs['my-index'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 0, 'depend' => array(), 'recommend' => array());
$config->group->package->my->privs['my-work'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 2, 'depend' => array(0 => 'my-index'), 'recommend' => array());
$config->group->package->my->privs['my-dynamic'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 6, 'depend' => array(0 => 'my-index'), 'recommend' => array());
$config->group->package->my->privs['my-audit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 7, 'depend' => array(0 => 'my-index'), 'recommend' => array());

$config->group->package->profile = new stdclass();
$config->group->package->profile->order  = 5;
$config->group->package->profile->subset = 'personalsettings';
$config->group->package->profile->privs  = array();
$config->group->package->profile->privs['my-uploadAvatar'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 2, 'depend' => array(0 => 'my-index'), 'recommend' => array());
$config->group->package->profile->privs['my-editProfile'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 1, 'depend' => array(0 => 'my-index'), 'recommend' => array());

$config->group->package->manageEffort = new stdclass();
$config->group->package->manageEffort->order  = 5;
$config->group->package->manageEffort->subset = 'effort';
$config->group->package->manageEffort->privs  = array();
$config->group->package->manageEffort->privs['my-effort'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 1, 'depend' => array(0 => 'effort-calendar'), 'recommend' => array());
$config->group->package->manageEffort->privs['effort-calendar'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 0, 'depend' => array(), 'recommend' => array());
$config->group->package->manageEffort->privs['effort-batchCreate'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 3, 'depend' => array(0 => 'effort-calendar'), 'recommend' => array(0 => 'effort-delete', 1 => 'effort-edit', 2 => 'effort-view'));
$config->group->package->manageEffort->privs['effort-edit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 4, 'depend' => array(), 'recommend' => array(0 => 'effort-batchCreate', 1 => 'effort-batchEdit', 2 => 'effort-createForObject', 3 => 'effort-delete', 4 => 'effort-export', 5 => 'effort-view'));
$config->group->package->manageEffort->privs['effort-batchEdit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(0 => 'my-effort'), 'recommend' => array(0 => 'effort-edit'));
$config->group->package->manageEffort->privs['effort-view'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 2, 'depend' => array(0 => 'effort-calendar'), 'recommend' => array(0 => 'effort-delete', 1 => 'effort-edit', 2 => 'my-effort'));
$config->group->package->manageEffort->privs['effort-delete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 6, 'depend' => array(), 'recommend' => array(0 => 'effort-batchCreate', 1 => 'effort-batchEdit', 2 => 'effort-createForObject', 3 => 'effort-edit', 4 => 'effort-export', 5 => 'effort-view'));
$config->group->package->manageEffort->privs['effort-export'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 7, 'depend' => array(0 => 'effort-calendar', 1 => 'my-effort'), 'recommend' => array(0 => 'effort-view'));

$config->group->package->file = new stdclass();
$config->group->package->file->order  = 5;
$config->group->package->file->subset = 'file';
$config->group->package->file->privs  = array();
$config->group->package->file->privs['file-download'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 1, 'depend' => array(), 'recommend' => array());
$config->group->package->file->privs['file-edit'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 0, 'depend' => array(), 'recommend' => array());
$config->group->package->file->privs['file-delete'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 2, 'depend' => array(), 'recommend' => array(0 => 'file-edit'));
$config->group->package->file->privs['file-uploadImages'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 3, 'depend' => array(), 'recommend' => array());
$config->group->package->file->privs['file-setPublic'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 25, 'depend' => array(), 'recommend' => array());
$config->group->package->file->privs['file-preview'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 26, 'depend' => array(), 'recommend' => array());

$config->group->package->comment = new stdclass();
$config->group->package->comment->order  = 5;
$config->group->package->comment->subset = 'generalcomment';
$config->group->package->comment->privs  = array();
$config->group->package->comment->privs['action-comment'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(), 'recommend' => array());
$config->group->package->comment->privs['action-editComment'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(0 => 'action-comment'), 'recommend' => array());

$config->group->package->ping = new stdclass();
$config->group->package->ping->order  = 5;
$config->group->package->ping->subset = 'generalping';
$config->group->package->ping->privs  = array();
$config->group->package->ping->privs['misc-ping'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 5, 'depend' => array(), 'recommend' => array());

$config->group->package->template = new stdclass();
$config->group->package->template->order  = 5;
$config->group->package->template->subset = 'generaltemplate';
$config->group->package->template->privs  = array();
$config->group->package->template->privs['user-setPublicTemplate'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 10, 'depend' => array(), 'recommend' => array());

$config->group->package->commonEffort = new stdclass();
$config->group->package->commonEffort->order  = 5;
$config->group->package->commonEffort->subset = 'generaleffort';
$config->group->package->commonEffort->privs  = array();
$config->group->package->commonEffort->privs['effort-createForObject'] = array('edition' => 'gitfox', 'vision' => 'gitfox', 'order' => 3, 'depend' => array(), 'recommend' => array(0 => 'effort-delete', 1 => 'effort-edit'));
