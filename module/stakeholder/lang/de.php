<?php
/* Action. */
$lang->stakeholder->common       = 'Stakeholder';
$lang->stakeholder->browse       = 'Stakeholder List';
$lang->stakeholder->batchCreate  = 'Batch Add';
$lang->stakeholder->create       = 'Add Stakeholder';
$lang->stakeholder->edit         = 'Edit Stakeholder';
$lang->stakeholder->view         = 'Stakeholder Details';
$lang->stakeholder->delete       = 'Remove Stakeholder';
$lang->stakeholder->createdBy    = 'CreatedBy';
$lang->stakeholder->createdDate  = 'CreatedDate';
$lang->stakeholder->search       = 'Search';
$lang->stakeholder->browse       = 'Stakeholder List';
$lang->stakeholder->view         = 'Stakeholder Details';
$lang->stakeholder->basicInfo    = 'Basic Info';
$lang->stakeholder->add          = 'Create';
$lang->stakeholder->communicate  = 'Communications';
$lang->stakeholder->expect       = 'Expectation';
$lang->stakeholder->progress     = 'Progress';
$lang->stakeholder->userIssue    = 'Stakeholder Issues';
$lang->stakeholder->deleted      = 'Deleted';

$lang->stakeholder->viewAction = 'View Stakeholder';

/* Fields. */
$lang->stakeholder->id          = 'ID';
$lang->stakeholder->user        = 'User';
$lang->stakeholder->type        = 'Type';
$lang->stakeholder->name        = 'Name';
$lang->stakeholder->phone       = 'Mobile';
$lang->stakeholder->qq          = 'QQ';
$lang->stakeholder->weixin      = 'WeChat';
$lang->stakeholder->email       = 'EMail';
$lang->stakeholder->isKey       = 'Key Stakeholder';
$lang->stakeholder->inside      = 'Internal Stakeholder';
$lang->stakeholder->outside     = 'External Stakeholder';
$lang->stakeholder->from        = 'Type';
$lang->stakeholder->company     = 'Company';
$lang->stakeholder->nature      = 'Personality';
$lang->stakeholder->analysis    = 'Impact Analysis';
$lang->stakeholder->strategy    = 'Response';
$lang->stakeholder->expect      = 'Expectation';
$lang->stakeholder->progress    = 'Progress';
$lang->stakeholder->createdBy   = 'CreatedBy';
$lang->stakeholder->createdDate = 'CreatedDate';
$lang->stakeholder->emptyTip    = 'No issue for now.';

$lang->stakeholder->keyList[0] = 'No';
$lang->stakeholder->keyList[1] = 'Yes';

$lang->stakeholder->typeList['inside']  = 'Internal';
$lang->stakeholder->typeList['outside'] = 'External';

$lang->stakeholder->fromList['team']    = $lang->projectCommon . ' Team';
$lang->stakeholder->fromList['company'] = 'Internal';
$lang->stakeholder->fromList['outside'] = 'External';

$lang->stakeholder->userEmpty           = 'User cannot be empty!';
$lang->stakeholder->nameEmpty           = 'Name cannot be empty!';
$lang->stakeholder->companyEmpty        = 'Company cannot be empty!';
$lang->stakeholder->confirmDelete       = "Do you want to delete the stakeholder?";
$lang->stakeholder->confirmDeleteExpect = "Do you want to remove the expectation?";
$lang->stakeholder->createCommunicate   = '<i class="icon icon-chat-line"></i>added Communication History.';

$lang->stakeholder->action = new stdclass();
$lang->stakeholder->action->communicate = array('main' => '$date, communicatedby <strong>$actor</strong>.');
