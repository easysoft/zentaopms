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
$lang->user->commiter    = 'SCM Account';
$lang->user->birthyear   = 'Birth Year';
$lang->user->gender      = 'Gender';
$lang->user->email       = 'Email';
$lang->user->basicInfo   = 'Basic Info';
$lang->user->accountInfo = 'Account';
$lang->user->verify      = 'Verification';
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
$lang->user->visits      = 'Visits';
$lang->user->ip          = 'Last IP';
$lang->user->last        = 'Last Login';
$lang->user->ranzhi      = 'Zdoo Account';
$lang->user->ditto       = 'Ditto';
$lang->user->originalPassword = 'Old Password';
$lang->user->verifyPassword   = 'Your Login Password';
$lang->user->resetPassword    = 'Forgot Password?';

$lang->user->index           = "Home";
$lang->user->view            = "Overview";
$lang->user->create          = "Add User";
$lang->user->batchCreate     = "Batch Add";
$lang->user->edit            = "Edit";
$lang->user->batchEdit       = "Batch Edit";
$lang->user->unlock          = "Unlock";
$lang->user->delete          = "Delete";
$lang->user->unbind          = "Unbind Zdoo";
$lang->user->login           = "Login";
$lang->user->mobileLogin     = "Mobile";
$lang->user->editProfile     = "Edit";
$lang->user->deny            = "Access is denied.";
$lang->user->confirmDelete   = "Are you sure to delete this user?";
$lang->user->confirmUnlock   = "Are you sure to unlock this user?";
$lang->user->confirmUnbind   = "Are you sure to unbind this user from Zdoo?";
$lang->user->relogin         = "Login Again";
$lang->user->asGuest         = "Guest";
$lang->user->goback          = "Back";
$lang->user->deleted         = '(Deleted)';
$lang->user->search          = 'Search';

$lang->user->profile     = 'Profile';
$lang->user->project     = $lang->projectCommon;
$lang->user->task        = 'Task';
$lang->user->bug         = 'Bug';
$lang->user->test        = 'Test';
$lang->user->testTask    = 'TestTask';
$lang->user->testCase    = 'TestCase';
$lang->user->todo        = 'Todo';
$lang->user->story       = 'Story';
$lang->user->dynamic     = 'Dynamic';

$lang->user->openedBy    = 'Opened';
$lang->user->assignedTo  = 'Assigned';
$lang->user->finishedBy  = 'Finished';
$lang->user->resolvedBy  = 'Resolved';
$lang->user->closedBy    = 'Closed';
$lang->user->reviewedBy  = 'Reviewed';
$lang->user->canceledBy  = 'Canceled';

$lang->user->testTask2Him = 'Build';
$lang->user->case2Him     = 'Case Assigned';
$lang->user->caseByHim    = 'Case Opened';

$lang->user->errorDeny    = "Sorry, your access to <b>%s</b> of <b>%s</b> has been denied. Please contact your Admin to get permissions. Click Back to return.";
$lang->user->loginFailed  = "Login failed. Please check your account and password.";
$lang->user->lockWarning  = "You can try %s times.";
$lang->user->loginLocked  = "Please contact the administrator to unlock your account or try %s mins later.";
$lang->user->weakPassword = "Your password does not meet the complexity requirements.";

$lang->user->roleList['']       = '';
$lang->user->roleList['dev']    = 'Developer';
$lang->user->roleList['qa']     = 'QA';
$lang->user->roleList['pm']     = 'Scrum Master';
$lang->user->roleList['po']     = 'Product Owner';
$lang->user->roleList['td']     = 'Technical Manager';
$lang->user->roleList['pd']     = 'Product Manager';
$lang->user->roleList['qd']     = 'QA Manager';
$lang->user->roleList['top']    = 'Senior Manager';
$lang->user->roleList['others'] = 'Other';

$lang->user->genderList['m'] = 'M';
$lang->user->genderList['f'] = 'F';

$lang->user->passwordStrengthList[0] = "<span style='color:red'>Weak</span>";
$lang->user->passwordStrengthList[1] = "<span style='color:#000'>Good</span>";
$lang->user->passwordStrengthList[2] = "<span style='color:green'>Strong</span>";

$lang->user->statusList['active'] = 'Activated';
$lang->user->statusList['delete'] = 'Deleted';

$lang->user->keepLogin['on']      = 'Remember Me';
$lang->user->loginWithDemoUser    = 'Login as Demo User:';

$lang->user->tpl = new stdclass();
$lang->user->tpl->type    = 'Type';
$lang->user->tpl->title   = 'TPL Title';
$lang->user->tpl->content = 'Content';
$lang->user->tpl->public  = 'Public';

$lang->user->placeholder = new stdclass();
$lang->user->placeholder->account   = 'Letters, Underline and Numbers, at least 3 characters';
$lang->user->placeholder->password1 = 'six characters at lease ';
$lang->user->placeholder->role      = "Role affects content and user order.";
$lang->user->placeholder->group     = "Group decides user privilege.";
$lang->user->placeholder->commiter  = 'SVN/Git account';
$lang->user->placeholder->verify    = 'Please input your login password.';

$lang->user->placeholder->passwordStrength[1] = 'Letters and Numbers, at least 6 characters';
$lang->user->placeholder->passwordStrength[2] = 'Letters, Numbers and special characters, at lease 10 characters';

$lang->user->error = new stdclass();
$lang->user->error->account       = "ID %s，account must contain letters, underline, or numbers,at lease three characters";
$lang->user->error->accountDupl   = "ID %s，account has been used.";
$lang->user->error->realname      = "ID %s，must be real name";
$lang->user->error->password      = "ID %s，password must be 6 characters at least.";
$lang->user->error->mail          = "ID %s，please enter valid Email address";
$lang->user->error->role          = "ID %s，role cannot be null.";
$lang->user->error->reserved      = "ID %s，account is reserved.";

$lang->user->error->verifyPassword   = "Verification failed. Please enter your Login Password.";
$lang->user->error->originalPassword = "Old password is incorrect.";

$lang->user->contacts = new stdclass();
$lang->user->contacts->common   = 'Contact';
$lang->user->contacts->listName = 'List Name';
$lang->user->contacts->userList = 'User List';

$lang->user->contacts->manage        = 'Manage';
$lang->user->contacts->contactsList  = 'Contacts';
$lang->user->contacts->selectedUsers = 'Select';
$lang->user->contacts->selectList    = 'List';
$lang->user->contacts->createList    = 'Create Contact List';
$lang->user->contacts->noListYet     = 'No contact list exists. Please create one first.';
$lang->user->contacts->confirmDelete = 'Do you want to delete this list?';
$lang->user->contacts->or            = ' or ';

$lang->user->resetFail       = "Failed. Please check the account";
$lang->user->resetSuccess    = "Reset! Please use your new password to login.";
$lang->user->noticeResetFile = "<h5>Contact the Administrator to reset your password.</h5>
    <h5>If you are, please login your Zentao host and create a file named <span> '%s' </span>.</h5>
    <p>Note:</p>
    <ol>
    <li>Keep the file empty.</li>
    <li>If the file exists, remove it and create it again.</li>
    </ol>";
