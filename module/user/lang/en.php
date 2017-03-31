<?php
/**
 * The user module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: en.php 5053 2013-07-06 08:17:37Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->user->common      = 'User';
$lang->user->id          = 'ID';
$lang->user->company     = 'Company';
$lang->user->dept        = 'Department';
$lang->user->account     = 'Account';
$lang->user->password    = 'Password';
$lang->user->password2   = 'Repeat Password';
$lang->user->role        = 'Role';
$lang->user->group       = 'Group';
$lang->user->realname    = 'Name';
$lang->user->nickname    = 'Nickname';
$lang->user->commiter    = 'Commiter';
$lang->user->birthyear   = 'Birth Year';
$lang->user->gender      = 'Gender';
$lang->user->email       = 'Email';
$lang->user->basicInfo   = 'Basic Info';
$lang->user->accountInfo = 'Account';
$lang->user->verify      = 'Acceptance';
$lang->user->contactInfo = 'Contact';
$lang->user->skype       = 'Skype';
$lang->user->qq          = 'QQ';
$lang->user->yahoo       = 'Yahoo!';
$lang->user->gtalk       = 'GTalk';
$lang->user->wangwang    = 'Wangwang';
$lang->user->mobile      = 'Mobile';
$lang->user->phone       = 'Phone';
$lang->user->address     = 'Address';
$lang->user->zipcode     = 'Zip Code';
$lang->user->join        = 'Join Date';
$lang->user->visits      = 'Visit Count';
$lang->user->ip          = 'Last IP';
$lang->user->last        = 'Last Login';
$lang->user->ranzhi      = 'Ranger account';
$lang->user->ditto       = 'Ditto';
$lang->user->originalPassword = 'Old Password';
$lang->user->verifyPassword   = 'Please enter your password';
$lang->user->resetPassword    = 'Forgot Password?';

$lang->user->index           = "Home";
$lang->user->view            = "Info";
$lang->user->create          = "Add";
$lang->user->batchCreate     = "Batch Add User";
$lang->user->edit            = "Edit";
$lang->user->batchEdit       = "Batch Edit";
$lang->user->unlock          = "Unlock";
$lang->user->delete          = "Delete";
$lang->user->unbind          = "Unbind Ranger";
$lang->user->login           = "Login";
$lang->user->mobileLogin     = "Mobile";
$lang->user->editProfile     = "Edit";
$lang->user->deny            = "Access Denied";
$lang->user->confirmDelete   = "Are you sure to delete this user?";
$lang->user->confirmUnlock   = "Are you sure to unlock this user?";
$lang->user->confirmUnbind   = "Are you sure to unbind this user from Ranger?";
$lang->user->relogin         = "Login Again";
$lang->user->asGuest         = "Guest";
$lang->user->goback          = "Back";
$lang->user->deleted         = '(Deleted)';

$lang->user->profile     = 'Profile';
$lang->user->project     = $lang->projectCommon;
$lang->user->task        = 'Task';
$lang->user->bug         = 'Bug';
$lang->user->test        = 'Testing';
$lang->user->testTask    = 'Test Task';
$lang->user->testCase    = 'Test Case';
$lang->user->todo        = 'To-Do';
$lang->user->story       = 'Story';
$lang->user->dynamic     = 'Dynamic';

$lang->user->openedBy    = 'Opened by him';
$lang->user->assignedTo  = 'Assigned to him';
$lang->user->finishedBy  = 'Finished by him';
$lang->user->resolvedBy  = 'Resolved by him';
$lang->user->closedBy    = 'Closed by him';
$lang->user->reviewedBy  = 'Reviewed by him';
$lang->user->canceledBy  = 'Cancelled by him';

$lang->user->testTask2Him = 'His Build';
$lang->user->case2Him     = 'His Case';
$lang->user->caseByHim    = 'Case Open by Him';

$lang->user->errorDeny    = "Sorry, your access to <b>%s</b> Module <b>%s</b> Feature has been denied. Please contact Admin to get permission. Click Back to return.";
$lang->user->loginFailed  = "Login failed. Please check your account and password.";
$lang->user->lockWarning  = "You can try %s times.";
$lang->user->loginLocked  = "Please contact the administrator to unlock your account or try %s mins later.";
$lang->user->weakPassword = "Your password does meet the setting requirements.";

$lang->user->roleList['']       = '';
$lang->user->roleList['dev']    = 'Developer';
$lang->user->roleList['qa']     = 'Tester';
$lang->user->roleList['pm']     = 'Project Manager';
$lang->user->roleList['po']     = 'Product Owner';
$lang->user->roleList['td']     = 'Technical Supervisor';
$lang->user->roleList['pd']     = 'Product Supervisor';
$lang->user->roleList['qd']     = 'QA Supervisor';
$lang->user->roleList['top']    = 'Senior Manager';
$lang->user->roleList['others'] = 'Other';

$lang->user->genderList['m'] = 'M';
$lang->user->genderList['f'] = 'F';

$lang->user->passwordStrengthList[0] = "<span style='color:red'>Weak</span>";
$lang->user->passwordStrengthList[1] = "<span style='color:#000'>Good</span>";
$lang->user->passwordStrengthList[2] = "<span style='color:green'>Strong</span>";

$lang->user->statusList['active'] = 'Activate';
$lang->user->statusList['delete'] = 'Deleted';

$lang->user->keepLogin['on']      = 'Keep login';
$lang->user->loginWithDemoUser    = 'Login as Demo User:';

$lang->user->placeholder = new stdclass();
$lang->user->placeholder->account   = 'must be contain letters, underline, and numbers,at lease three characters';
$lang->user->placeholder->password1 = 'must be at lease six characters';
$lang->user->placeholder->role      = "Role affects content and user's order.";
$lang->user->placeholder->group     = "Group decides user's privilege.";
$lang->user->placeholder->commiter  = 'Subversion Account';
$lang->user->placeholder->verify    = 'Password is required to verify.';

$lang->user->placeholder->passwordStrength[1] = 'must be contain letters and numbers,at lease six characters';
$lang->user->placeholder->passwordStrength[2] = 'must be contain letters, numbers and special characters ,at lease 10 characters';

$lang->user->error = new stdclass();
$lang->user->error->account       = "ID %s，must be contain letters, underline, and numbers,at lease three characters";
$lang->user->error->accountDupl   = "ID %s，account has been used";
$lang->user->error->realname      = "ID %s，must be real name";
$lang->user->error->password      = "ID %s，password must be 6 characters at least.";
$lang->user->error->mail          = "ID %s，please enter valid Email address";
$lang->user->error->role          = "ID %s，role cannot be null.";

$lang->user->error->verifyPassword   = "Wrong password. Please enter your Login Password.";
$lang->user->error->originalPassword = "Old password is incorrect.";

$lang->user->contacts = new stdclass();
$lang->user->contacts->common   = 'Contact';
$lang->user->contacts->listName = 'List Name';

$lang->user->contacts->manage        = 'Manage';
$lang->user->contacts->contactsList  = 'Contacts';
$lang->user->contacts->selectedUsers = 'Select User';
$lang->user->contacts->selectList    = 'Select List';
$lang->user->contacts->createList    = 'Create a List';
$lang->user->contacts->noListYet     = 'No contacts list exist. Please create a list first.';
$lang->user->contacts->confirmDelete = 'Do you want to delete this list?';
$lang->user->contacts->or            = ' or ';

$lang->user->resetFail       = "Failed. Please check the account";
$lang->user->resetSuccess    = "Reset! Please use your new password to login.";
$lang->user->noticeResetFile = "<h5>If you are not Administrator, please contact Administrator to reset your password.</h5>
    <h5>If you are, please login into your Zentao host and create the <span>%s</span> file.</h5>
    <p>Note:</p>
    <ol>
    <li>Keep the ok.txt empty.</li>
    <li>If ok.txt exists, remove it and create one again.</li>
    </ol>";
