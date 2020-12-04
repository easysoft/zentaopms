<?php
/* Fields */
$lang->risk->common            = 'Risk';
$lang->risk->source            = 'Source';
$lang->risk->id                = 'ID';
$lang->risk->name              = 'Name';
$lang->risk->category          = 'Type';
$lang->risk->strategy          = 'Strategy';
$lang->risk->status            = 'Status';
$lang->risk->impact            = 'Impact';
$lang->risk->probability       = 'Probability';
$lang->risk->rate              = 'Risk Index';
$lang->risk->pri               = 'Priority';
$lang->risk->prevention        = 'Mitigation';
$lang->risk->remedy            = 'Contingency';
$lang->risk->identifiedDate    = 'Identified';
$lang->risk->plannedClosedDate = 'Deadline';
$lang->risk->assignedTo        = 'AssignedTo';
$lang->risk->assignedDate      = 'AssignedDate';
$lang->risk->createdBy         = 'CreatedBy';
$lang->risk->createdDate       = 'CreatedDate';
$lang->risk->noAssigned        = 'Unassigned';
$lang->risk->cancelBy          = 'CanceledBy';
$lang->risk->cancelDate        = 'CanceledDate';
$lang->risk->cancelReason      = 'Cancel Reason';
$lang->risk->resolvedBy        = 'ResolvedBy';
$lang->risk->closedDate        = 'ClosedDate';
$lang->risk->actualClosedDate  = 'Closed Date';
$lang->risk->resolution        = 'Resolution';
$lang->risk->hangupBy          = 'SuspendedBy';
$lang->risk->hangupDate        = 'SuspendedDate';
$lang->risk->activateBy        = 'ActivatedBy';
$lang->risk->activateDate      = 'ActivatedDate';
$lang->risk->isChange          = 'Any change?';
$lang->risk->trackedBy         = 'TrackedBy';
$lang->risk->trackedDate       = 'TrackedDate';
$lang->risk->editedBy          = 'EditedBy';
$lang->risk->editedDate        = 'EditedDate';
$lang->risk->legendBasicInfo   = 'Basic Info.';
$lang->risk->legendLifeTime    = 'About Risk';
$lang->risk->confirmDelete     = 'Do you want to delelte this risk?';
$lang->risk->deleted           = 'Deleted';

/* Actions */
$lang->risk->batchCreate = 'Batch Create';
$lang->risk->create      = 'Create Risk';
$lang->risk->edit        = 'Edit Risk';
$lang->risk->browse      = 'Risks';
$lang->risk->view        = 'Details';
$lang->risk->activate    = 'Active';
$lang->risk->hangup      = 'Suspend';
$lang->risk->close       = 'Close';
$lang->risk->cancel      = 'Cancle';
$lang->risk->track       = 'Track';
$lang->risk->assignTo    = 'Assign';
$lang->risk->delete      = 'Delete';
$lang->risk->byQuery     = 'Search';

$lang->risk->action = new stdclass();
$lang->risk->action->hangup  = '$date, suspended by <strong>$actor</strong>.' . "\n";
$lang->risk->action->tracked = '$date, tracked by <strong>$actor</strong>.' . "\n";
 
$lang->risk->sourceList[''] = '';
$lang->risk->sourceList['business']    = 'Business';
$lang->risk->sourceList['team']        = 'Project Team';
$lang->risk->sourceList['logistic']    = 'Project Logistics';
$lang->risk->sourceList['manage']      = 'Management';
$lang->risk->sourceList['sourcing']    = 'Supplier-Procurement';
$lang->risk->sourceList['outsourcing'] = 'Supplier-Outsourcing';
$lang->risk->sourceList['customer']    = 'Customer';
$lang->risk->sourceList['others']      = 'Other';

$lang->risk->categoryList[''] = '';
$lang->risk->categoryList['technical']   = 'Technology';
$lang->risk->categoryList['manage']      = 'Management';
$lang->risk->categoryList['business']    = 'Business';
$lang->risk->categoryList['requirement'] = 'Requirement';
$lang->risk->categoryList['resource']    = 'Resource';
$lang->risk->categoryList['others']      = 'Other';

$lang->risk->impactList[1] = 1;
$lang->risk->impactList[2] = 2;
$lang->risk->impactList[3] = 3;
$lang->risk->impactList[4] = 4;
$lang->risk->impactList[5] = 5;

$lang->risk->probabilityList[1] = 1;
$lang->risk->probabilityList[2] = 2;
$lang->risk->probabilityList[3] = 3;
$lang->risk->probabilityList[4] = 4;
$lang->risk->probabilityList[5] = 5;

$lang->risk->priList['high']   = 'High';
$lang->risk->priList['middle'] = 'Medium';
$lang->risk->priList['low']    = 'Low';

$lang->risk->statusList[''] = '';
$lang->risk->statusList['active']   = 'Active';
$lang->risk->statusList['closed']   = 'Closed';
$lang->risk->statusList['hangup']   = 'Suspended';
$lang->risk->statusList['canceled'] = 'Canceled';

$lang->risk->strategyList[''] = '';
$lang->risk->strategyList['avoidance']    = 'Avoidance';
$lang->risk->strategyList['mitigation']   = 'Mitigation';
$lang->risk->strategyList['transference'] = 'Transfer';
$lang->risk->strategyList['acceptance']   = 'Acceptance';

$lang->risk->isChangeList[0] = 'No';
$lang->risk->isChangeList[1] = 'Yes';

$lang->risk->cancelReasonList[''] = '';
$lang->risk->cancelReasonList['disappeared'] = 'Dispear';
$lang->risk->cancelReasonList['mistake']     = 'Mistake';

$lang->risk->featureBar['browse']['all']      = 'All';
$lang->risk->featureBar['browse']['active']   = 'Active';
$lang->risk->featureBar['browse']['assignTo'] = 'AssignToMe';
$lang->risk->featureBar['browse']['closed']   = 'Closed';
$lang->risk->featureBar['browse']['hangup']   = 'Suspended';
$lang->risk->featureBar['browse']['canceled'] = 'Canceled';
