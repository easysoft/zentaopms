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
$lang->user->common           = 'User';
$lang->user->id               = 'ID';
$lang->user->company          = 'Company';
$lang->user->dept             = 'Department';
$lang->user->account          = 'Account';
$lang->user->password         = 'Password';
$lang->user->password2        = 'Repeat Password';
$lang->user->role             = 'Role';
$lang->user->group            = 'Privilege Group';
$lang->user->realname         = 'Name';
$lang->user->nickname         = 'Nickname';
$lang->user->commiter         = 'SVN/GIT Account';
$lang->user->birthyear        = 'DOB';
$lang->user->gender           = 'Gender';
$lang->user->email            = 'Email';
$lang->user->basicInfo        = 'Basic Info.';
$lang->user->accountInfo      = 'Account Info.';
$lang->user->verify           = 'Verification';
$lang->user->contactInfo      = 'Contact';
$lang->user->skype            = 'Skype';
$lang->user->qq               = 'QQ';
$lang->user->mobile           = 'Mobile';
$lang->user->phone            = 'Phone';
$lang->user->weixin           = 'WeChat';
$lang->user->dingding         = 'DingDing';
$lang->user->slack            = 'Slack';
$lang->user->whatsapp         = 'WhatsApp';
$lang->user->address          = 'Address';
$lang->user->zipcode          = 'ZipCode';
$lang->user->join             = 'Joined';
$lang->user->visits           = 'Visits';
$lang->user->ip               = 'Last IP';
$lang->user->last             = 'Last Login';
$lang->user->ranzhi           = 'Zdoo Account';
$lang->user->ditto            = 'Ditto';
$lang->user->originalPassword = 'Old Password';
$lang->user->newPassword      = 'New Password';
$lang->user->verifyPassword   = 'Password';
$lang->user->resetPassword    = 'Forgot Password?';
$lang->user->score            = 'Score';

$lang->user->legendBasic        = 'Basic Information';
$lang->user->legendContribution = 'Contribution';

$lang->user->index         = "Home";
$lang->user->view          = "User Detail";
$lang->user->create        = "Add User";
$lang->user->batchCreate   = "Batch Add";
$lang->user->edit          = "Edit User";
$lang->user->batchEdit     = "Batch Edit";
$lang->user->unlock        = "Unlock User";
$lang->user->delete        = "Delete User";
$lang->user->unbind        = "Unbind from Zdoo";
$lang->user->login         = "Login";
$lang->user->mobileLogin   = "Mobile";
$lang->user->editProfile   = "Edit Profile";
$lang->user->deny          = "Your access is denied.";
$lang->user->confirmDelete = "Do you want to delete this user?";
$lang->user->confirmUnlock = "Do you want to unlock this user?";
$lang->user->confirmUnbind = "Do you want to unbind this user from Zdoo?";
$lang->user->relogin       = "Login Again";
$lang->user->asGuest       = "Guest";
$lang->user->goback        = "Back";
$lang->user->deleted       = '(Deleted)';
$lang->user->search        = 'Search';

$lang->user->saveTemplate          = 'Save as Template';
$lang->user->setPublic             = 'Set as Public Template';
$lang->user->deleteTemplate        = 'Delete Template';
$lang->user->setTemplateTitle      = 'Please enter the title of template.';
$lang->user->applyTemplate         = 'Templates';
$lang->user->confirmDeleteTemplate = 'Do you want to delete this template?';
$lang->user->setPublicTemplate     = 'Set as Public Template';
$lang->user->tplContentNotEmpty    = 'The template content cannot be empty!';

$lang->user->profile     = 'Profile';
$lang->user->project     = $lang->projectCommon . 's';
$lang->user->task        = 'Tasks';
$lang->user->bug         = 'Bugs';
$lang->user->test        = 'Test';
$lang->user->testTask    = 'Requests';
$lang->user->testCase    = 'Cases';
$lang->user->schedule    = 'Schedule';
$lang->user->todo        = 'Todos';
$lang->user->story       = 'Stories';
$lang->user->dynamic     = 'Dynamics';

$lang->user->openedBy    = 'CreatedBy%s';
$lang->user->assignedTo  = 'AssignedTo%s';
$lang->user->finishedBy  = 'FinishedBy%s';
$lang->user->resolvedBy  = 'ResolvedBy%s';
$lang->user->closedBy    = 'ClosedBy%s';
$lang->user->reviewedBy  = 'ReviewedBy%s';
$lang->user->canceledBy  = 'CancelledBy%s';

$lang->user->testTask2Him = 'RequestsAssignedTo%s';
$lang->user->case2Him     = 'CasesAssignedTo%s';
$lang->user->caseByHim    = 'CasesCreatedBy%s';

$lang->user->errorDeny    = "Sorry, your access to <b>%s</b> of <b>%s</b> is denied. Please contact your Admin to get privileges. Click Back to return.";
$lang->user->errorView    = "Sorry, your access view <b>%s</b> is denied. Please contact your Admin to get privileges. Click Back to return.";
$lang->user->loginFailed  = "Login failed. Please check your account and password.";
$lang->user->lockWarning  = "You can try %s times.";
$lang->user->loginLocked  = "Please contact the administrator to unlock your account or try %s minutes later.";
$lang->user->weakPassword = "Your password does not meet the requirements.";
$lang->user->errorWeak    = "Passwords cannot use [%s] these commonly used weak passwords.";

$lang->user->roleList['']       = '';
$lang->user->roleList['dev']    = 'Developer';
$lang->user->roleList['qa']     = 'Tester';
$lang->user->roleList['pm']     = 'Scrum Master';
$lang->user->roleList['po']     = 'Product Owner';
$lang->user->roleList['td']     = 'Technical Manager';
$lang->user->roleList['pd']     = 'Product Manager';
$lang->user->roleList['qd']     = 'QA Manager';
$lang->user->roleList['top']    = 'Senior Manager';
$lang->user->roleList['others'] = 'Others';

$lang->user->genderList['m'] = 'Male';
$lang->user->genderList['f'] = 'Female';

$lang->user->thirdPerson['m'] = 'Him';
$lang->user->thirdPerson['f'] = 'Her';

$lang->user->passwordStrengthList[0] = "<span style='color:red'>Weak</span>";
$lang->user->passwordStrengthList[1] = "<span style='color:#000'>Good</span>";
$lang->user->passwordStrengthList[2] = "<span style='color:green'>Strong</span>";

$lang->user->statusList['active'] = 'Active';
$lang->user->statusList['delete'] = 'Deleted';

$lang->user->personalData['createdTodo']  = 'Todos Created';
$lang->user->personalData['createdStory'] = 'Stories Created';
$lang->user->personalData['finishedTask'] = 'Tasks Finished';
$lang->user->personalData['resolvedBug']  = 'Bugs Resolved';
$lang->user->personalData['createdCase']  = 'Cases Created';

$lang->user->keepLogin['on']      = 'Keep Login';
$lang->user->loginWithDemoUser    = 'Login as Demo User:';

$lang->user->tpl = new stdclass();
$lang->user->tpl->type    = 'Type';
$lang->user->tpl->title   = 'TPL Title';
$lang->user->tpl->content = 'Content';
$lang->user->tpl->public  = 'Public';

$lang->usertpl = new stdclass();
$lang->usertpl->title = 'Template Name';

$lang->user->placeholder = new stdclass();
$lang->user->placeholder->account   = '>= 3 letters, underline and numbers';
$lang->user->placeholder->password1 = '>= 6 characters';
$lang->user->placeholder->role      = "Role is related to content and user listing order.";
$lang->user->placeholder->group     = "Group is related to user privileges.";
$lang->user->placeholder->commiter  = 'SVN/Git account';
$lang->user->placeholder->verify    = 'Please enter your login password.';

$lang->user->placeholder->passwordStrength[1] = '>= 6 letters and numbers';
$lang->user->placeholder->passwordStrength[2] = '>= 10 letters, numbers and special characters';

$lang->user->error = new stdclass();
$lang->user->error->account        = "ID %s，account must be >= 3 letters, underline, or numbers";
$lang->user->error->accountDupl    = "ID %s，account is used.";
$lang->user->error->realname       = "ID %s，must be real name";
$lang->user->error->password       = "ID %s，password must be >= 6 characters.";
$lang->user->error->mail           = "ID %s，please enter valid Email address";
$lang->user->error->reserved       = "ID %s，account is reserved.";
$lang->user->error->weakPassword   = "ID %s，the password strength is less than the system setting.";
$lang->user->error->dangerPassword = "ID %s，Passwords cannot be used with [%s] these commonly used if-passwords.";

$lang->user->error->verifyPassword   = "Verification failed. Please enter your Login Password.";
$lang->user->error->originalPassword = "Old password is incorrect.";

$lang->user->contactFieldList['phone']    = $lang->user->phone;
$lang->user->contactFieldList['mobile']   = $lang->user->mobile;
$lang->user->contactFieldList['qq']       = $lang->user->qq;
$lang->user->contactFieldList['dingding'] = $lang->user->dingding;
$lang->user->contactFieldList['weixin']   = $lang->user->weixin;
$lang->user->contactFieldList['skype']    = $lang->user->skype;
$lang->user->contactFieldList['slack']    = $lang->user->slack;
$lang->user->contactFieldList['whatsapp'] = $lang->user->whatsapp;

$lang->user->contacts = new stdclass();
$lang->user->contacts->common   = 'Contacts';
$lang->user->contacts->listName = 'List Name';
$lang->user->contacts->userList = 'User List';

$lang->user->contacts->manage        = 'Manage List';
$lang->user->contacts->contactsList  = 'Contact List';
$lang->user->contacts->selectedUsers = 'Users';
$lang->user->contacts->selectList    = 'List';
$lang->user->contacts->createList    = 'Create List';
$lang->user->contacts->noListYet     = 'No contact list exists. Please create one first.';
$lang->user->contacts->confirmDelete = 'Do you want to delete this list?';
$lang->user->contacts->or            = ' or ';

$lang->user->resetFail       = "Failed. Please check the account.";
$lang->user->resetSuccess    = "Reset! Please use your new password to login.";
$lang->user->noticeResetFile = "<h5>Contact the Administrator to reset your password.</h5>
    <h5>If you are, please login your Zentao host and create a file named <span> '%s' </span>.</h5>
    <p>Note:</p>
    <ol>
    <li>Keep the file empty.</li>
    <li>If the file exists, remove it and create it again.</li>
    </ol>";
