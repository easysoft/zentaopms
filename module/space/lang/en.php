<?php
$lang->space->browse     = 'Space List';
$lang->space->create     = 'Create Space';
$lang->space->edit       = 'Edit Space';
$lang->space->view       = 'Space Detail';
$lang->space->delete     = 'Delete Space';
$lang->space->members    = 'Members';
$lang->space->memberList = 'Member List';

$lang->space->group             = 'Permission';
$lang->space->groupList         = 'Permission List';
$lang->space->createGroup       = 'Add Permission Group';
$lang->space->managePriv        = 'Assign Permission';
$lang->space->importGroup       = 'Import Permission Group';
$lang->space->editGroup         = 'Edit Permission Group';
$lang->space->deleteGroup       = 'Delete Permission Group';
$lang->space->manageMembers     = 'Manage Members';
$lang->space->removeMember      = 'Unbind Member';
$lang->space->manageGroupMember = 'Manage Permission Group Member';

$lang->space->name         = 'Name';
$lang->space->manager      = 'Manager';
$lang->space->createdDate  = 'Created Date';
$lang->space->desc         = 'Description';
$lang->space->repo         = 'Repository';
$lang->space->artifactrepo = 'Artifact Repository';
$lang->space->pipeline     = 'Pipeline';
$lang->space->system       = 'Application';
$lang->space->acl          = 'Access Control';
$lang->space->deleted      = 'Deleted';
$lang->space->account      = 'Name';
$lang->space->team         = 'Team';
$lang->space->auth         = 'Access Control';
$lang->space->role         = 'Role';

$lang->space->memberGroup    = 'Permission Group';
$lang->space->accessRepo     = 'Access Repositories';
$lang->space->accessArtifact = 'Access Artifact Repositories';
$lang->space->sourceSpace    = 'Source Group Space';
$lang->space->sourceGroup    = 'Source Group';

$lang->space->aclList = array();
$lang->space->aclList['open']    = 'Open';
$lang->space->aclList['private'] = 'Private';

$lang->space->aclNoticeList = array();
$lang->space->aclNoticeList['open']    = 'Public (Anyone with space view permission can access the space)';
$lang->space->aclNoticeList['private'] = 'Private (Only members and space administrators can access the space)';

$lang->space->authList = array();
$lang->space->authList['extend'] = 'Extend';
$lang->space->authList['reset']  = 'Reset';

$lang->space->authNoticeList = array();
$lang->space->authNoticeList['extend'] = 'Extend (Take the system permission and space permission together)';
$lang->space->authNoticeList['reset']  = 'Reset (Only take space permission)';

$lang->space->roleList = array();
$lang->space->roleList['manager'] = 'Manager';
$lang->space->roleList['member']  = 'Member';

$lang->space->notice = new stdclass();
$lang->space->notice->noSpaces              = 'No space exists';
$lang->space->notice->confirmDeleteSpace    = 'Are you sure to delete this space?';
$lang->space->notice->deleteFail            = 'The space exists repositories or artifact repositories, cannot be deleted.';
$lang->space->notice->apiCreateFail         = 'Create space failed.';
$lang->space->notice->accessRepo            = 'Only display users who can access private repositories';
$lang->space->notice->accessArtifact        = 'Only display users who can access private artifact repositories';
$lang->space->notice->confirmRemoveMember   = 'Are you sure to remove this user from this space?';
$lang->space->notice->confirmDelete         = 'Are you sure delete %s permission group?';
$lang->space->notice->managerMemberConflict = '%s is a space user. To configure them as an administrator, you can remove the user first.';

$lang->space->placeholder = new stdclass();
$lang->space->placeholder->desc = 'Description of this space';

$lang->space->tips      = 'Note';
$lang->space->afterInfo = "Space is created. Next you can ";
$lang->space->setMember = 'Set Member';
$lang->space->setACL    = 'Set ACL';
$lang->space->goback    = 'Go Back Space List';
