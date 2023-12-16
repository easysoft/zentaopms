<?php
/**
 * The user module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: en.php 5053 2013-07-06 08:17:37Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->user->common           = 'User';
$lang->user->id               = 'ID';
$lang->user->inside           = 'Internal';
$lang->user->outside          = 'Outsiders';
$lang->user->company          = 'Company';
$lang->user->dept             = 'Department';
$lang->user->account          = 'Account';
$lang->user->password         = 'Password';
$lang->user->password1        = 'Password';
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
$lang->user->priv             = 'Privilege';
$lang->user->visits           = 'Visits';
$lang->user->visions          = 'Version Type';
$lang->user->ip               = 'Last IP';
$lang->user->last             = 'Last Login';
$lang->user->ranzhi           = 'Zdoo Account';
$lang->user->ditto            = 'Ditto';
$lang->user->originalPassword = 'Old Password';
$lang->user->newPassword      = 'New Password';
$lang->user->verifyPassword   = 'Password';
$lang->user->forgetPassword   = 'Forgotten Password?';
$lang->user->score            = 'Score';
$lang->user->name             = 'Name';
$lang->user->type             = 'User Type';
$lang->user->cropAvatar       = 'Crop Avatar';
$lang->user->cropAvatarTip    = 'Drag and drop the box to crop the image.';
$lang->user->cropImageTip     = 'The image is too small. The recommended image size is at least 48x48. The current image size is %s';
$lang->user->captcha          = 'Captcha';
$lang->user->avatar           = 'Avatar';
$lang->user->birthday         = 'Birthday';
$lang->user->nature           = 'Nature';
$lang->user->analysis         = 'Analysis';
$lang->user->strategy         = 'Strategy';
$lang->user->fails            = 'number of failures';
$lang->user->locked           = 'Lock Time';
$lang->user->scoreLevel       = 'Score Level';
$lang->user->clientStatus     = 'Client Status';
$lang->user->clientLang       = 'Client Language';
$lang->user->programs         = 'Program';
$lang->user->products         = $lang->productCommon;
$lang->user->projects         = $lang->projectCommon;
$lang->user->sprints          = $lang->execution->common;
$lang->user->identity         = 'Identity';
$lang->user->switchVision     = 'Switch to %s';
$lang->user->submit           = 'Submit';
$lang->user->resetPWD         = 'Reset Password';
$lang->user->resetPwdByAdmin  = 'Reset password via admin';
$lang->user->resetPwdByMail   = 'Reset password via email';

$lang->user->abbr = new stdclass();
$lang->user->abbr->id        = 'ID';
$lang->user->abbr->password2 = 'Repeat Password';
$lang->user->abbr->address   = 'Address';
$lang->user->abbr->join      = 'Joined';

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
$lang->user->bind          = "Bind User";
$lang->user->oauthRegister = "Register a new account";
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
$lang->user->else          = 'Else';

$lang->user->saveTemplate          = 'Save as Template';
$lang->user->setPublic             = 'Set as Public Template';
$lang->user->deleteTemplate        = 'Delete Template';
$lang->user->setTemplateTitle      = 'Please enter the title of the template.';
$lang->user->applyTemplate         = 'Templates';
$lang->user->confirmDeleteTemplate = 'Do you want to delete this template?';
$lang->user->setPublicTemplate     = 'Set as Public Template';
$lang->user->tplContentNotEmpty    = 'The template content cannot be empty!';
$lang->user->sendEmailSuccess      = 'An email has been sent to your mailbox. Please check it.';
$lang->user->linkExpired           = 'The link has expired, please apply again.';

$lang->user->profile   = 'Profile';
$lang->user->project   = $lang->executionCommon . 's';
$lang->user->execution = $lang->execution->common;
$lang->user->task      = 'Tasks';
$lang->user->bug       = 'Bugs';
$lang->user->test      = 'Test';
$lang->user->testTask  = 'Requests';
$lang->user->testCase  = 'Cases';
$lang->user->issue     = 'Issue';
$lang->user->risk      = 'Risk';
$lang->user->schedule  = 'Schedule';
$lang->user->todo      = 'Todos';
$lang->user->story     = 'Stories';
$lang->user->dynamic   = 'Dynamics';

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

$lang->user->errorDeny    = "Sorry, your access to <b>%2\$s</b> of <b>%1\$s</b> is denied. Please contact your Admin to get privileges. Return to home page or login again.";
$lang->user->errorView    = "Sorry, your access view <b>%s</b> is denied. Please contact your Admin to get privileges. Return to home page or login again.";
$lang->user->loginFailed  = "Login failed. Please check your account and password.";
$lang->user->lockWarning  = "You can try %s times.";
$lang->user->loginLocked  = "Please contact the administrator to unlock your account or try %s minutes later.";
$lang->user->weakPassword = "Your password does not meet the requirements.";
$lang->user->errorWeak    = "Passwords cannot use [%s] weak passwords.";
$lang->user->errorCaptcha = "Captcha Error";
$lang->user->loginExpired = 'System login has expired, please log in again :)';

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

$lang->user->typeList['inside']  = $lang->user->inside;
$lang->user->typeList['outside'] = $lang->user->outside;

$lang->user->passwordStrengthList[0] = "<span style='color:red'>Weak</span>";
$lang->user->passwordStrengthList[1] = "<span style='color:#000'>Good</span>";
$lang->user->passwordStrengthList[2] = "<span style='color:green'>Strong</span>";

$lang->user->statusList['active'] = 'Active';
$lang->user->statusList['delete'] = 'Deleted';

$lang->user->personalData['createdTodos']        = 'Todos Created';
$lang->user->personalData['createdRequirements'] = "Requirements Created";
$lang->user->personalData['createdStories']      = "Stories Created";
$lang->user->personalData['finishedTasks']       = 'Tasks Finished';
$lang->user->personalData['createdBugs']         = 'Bugs Created';
$lang->user->personalData['resolvedBugs']        = 'Bugs Resolved';
$lang->user->personalData['createdCases']        = 'Cases Created';
$lang->user->personalData['createdRisks']        = 'Risks Created';
$lang->user->personalData['resolvedRisks']       = 'Risks Resolved';
$lang->user->personalData['createdIssues']       = 'Issues Created';
$lang->user->personalData['resolvedIssues']      = 'Issues Resolved';
$lang->user->personalData['createdDocs']         = 'Docs Created';

$lang->user->keepLogin['on']   = 'Keep Login';
$lang->user->loginWithDemoUser = 'Login as Demo User:';
$lang->user->scanToLogin       = 'Scan QR Code';

$lang->user->tpl = new stdclass();
$lang->user->tpl->type    = 'Type';
$lang->user->tpl->title   = 'TPL Title';
$lang->user->tpl->content = 'Content';
$lang->user->tpl->public  = 'Public';

$lang->usertpl = new stdclass();
$lang->usertpl->account = 'Creator';
$lang->usertpl->type    = 'Template Type';
$lang->usertpl->title   = 'Template Name';
$lang->usertpl->content = 'Template Content';
$lang->usertpl->public  = 'Public';

$lang->user->placeholder = new stdclass();
$lang->user->placeholder->account   = '>= 3 letters, underline and numbers';
$lang->user->placeholder->password1 = '>= 6 characters';
$lang->user->placeholder->role      = "Role is related to content and user listing order.";
$lang->user->placeholder->group     = "Group is related to user privileges.";
$lang->user->placeholder->commiter  = 'SVN/Git account';
$lang->user->placeholder->verify    = 'Please enter your ZenTao login password to verify..';

$lang->user->placeholder->loginPassword = 'Enter your password';
$lang->user->placeholder->loginAccount  = 'Enter your account';
$lang->user->placeholder->loginUrl      = 'Enter your ZenTao address';
$lang->user->placeholder->email         = 'Enter your email';

$lang->user->placeholder->passwordStrength[0] = '≥6 letters.';
$lang->user->placeholder->passwordStrength[1] = '>= 6 letters and numbers';
$lang->user->placeholder->passwordStrength[2] = '>= 10 letters, numbers and special characters';

$lang->user->placeholder->passwordStrengthCheck[0] = 'The password should be ≥ 6 letters.';
$lang->user->placeholder->passwordStrengthCheck[1] = 'The password should be ≥ 6 letters, combination of uppercase, lowercase letters and numbers.';
$lang->user->placeholder->passwordStrengthCheck[2] = 'The password should be ≥ 10 letters, combination of uppercase, lowercase letters, numbers, and special symbols.';

$lang->user->error = new stdclass();
$lang->user->error->account        = 'account must be >= 3 letters, underline or numbers';
$lang->user->error->accountDupl    = 'account is used.';
$lang->user->error->realname       = 'must be real name';
$lang->user->error->visions        = 'must be version type';
$lang->user->error->password       = 'password must be >= 6 characters.';
$lang->user->error->mail           = 'enter valid Email address';
$lang->user->error->reserved       = 'account is reserved.';
$lang->user->error->weakPassword   = 'the password strength is less than the system setting.';
$lang->user->error->dangerPassword = "passwords cannot be used with [%s] these commonly used if-passwords.";

$lang->user->error->url              = "Invalid address. Please contact your ZenTao Admin.";
$lang->user->error->verify           = "Wrong account or password.";
$lang->user->error->verifyPassword   = "Verification failed. Please enter your Login Password.";
$lang->user->error->originalPassword = "Old password is incorrect.";
$lang->user->error->companyEmpty     = "Company name must be not empty.";
$lang->user->error->noAccess         = "This user is not from your department. You have no access to this user information.";
$lang->user->error->accountEmpty     = 'Account must be not empty !';
$lang->user->error->emailEmpty       = 'Email must be not empty !';
$lang->user->error->noUser           = 'Invalid account.';
$lang->user->error->noEmail          = 'The user does not register email. Please get in touch with the administrator to reset the password.';
$lang->user->error->errorEmail       = 'The account does not match the email. Please enter a new one.';
$lang->user->error->emailSetting     = 'No email is configured in the system. Contact the admin to reset the email.';
$lang->user->error->sendMailFail     = 'Message sending failed, please try again!';
$lang->user->error->loginTimeoutTip  = 'Login failed, please check if the proxy service is normal.';

$lang->user->contactFieldList['phone']    = $lang->user->phone;
$lang->user->contactFieldList['mobile']   = $lang->user->mobile;
$lang->user->contactFieldList['qq']       = $lang->user->qq;
$lang->user->contactFieldList['dingding'] = $lang->user->dingding;
$lang->user->contactFieldList['weixin']   = $lang->user->weixin;
$lang->user->contactFieldList['skype']    = $lang->user->skype;
$lang->user->contactFieldList['slack']    = $lang->user->slack;
$lang->user->contactFieldList['whatsapp'] = $lang->user->whatsapp;

$lang->user->executionTypeList['stage']  = 'Stage';
$lang->user->executionTypeList['sprint'] = $lang->iterationCommon;

$lang->user->contacts = new stdclass();
$lang->user->contacts->common   = 'Contacts';
$lang->user->contacts->listName = 'List Name';
$lang->user->contacts->userList = 'User List';

$lang->usercontact = new stdclass;
$lang->usercontact->account  = 'Creator';
$lang->usercontact->listName = 'List Name';
$lang->usercontact->userList = 'User List';
$lang->usercontact->public   = 'Public';

$lang->user->contacts->manage        = 'Manage List';
$lang->user->contacts->contactsList  = 'Contact List';
$lang->user->contacts->selectedUsers = 'Users';
$lang->user->contacts->selectList    = 'List';
$lang->user->contacts->createList    = 'Create List';
$lang->user->contacts->noListYet     = 'No contact list exists. Please create one first.';
$lang->user->contacts->confirmDelete = 'Do you want to delete this list?';
$lang->user->contacts->or            = ' or ';

$lang->user->resetFail        = "Failed. Please check the account.";
$lang->user->resetSuccess     = "Reset! Please use your new password to login.";
$lang->user->noticeDelete     = 'Do you want to delete "%s" from ZenTao?';
$lang->user->noticeHasDeleted = "This user has been deleted. If you want to view it, please go to the Admin-System-Data-Recycle to restore it.";
$lang->user->noticeResetFile  = "<h5>Contact the Administrator to reset your password.</h5>
    <h5>If you are, please login your Zentao host and create a file named <span> %s </span>.</h5>
    <p>Note:</p>
    <ol>
    <li>Keep the file empty.</li>
    <li>If the file exists, remove it and create it again.</li>
    </ol>";
$lang->user->notice4Safe = "Warning: Weak password of one click package detected";
$lang->user->process4DIR = "It is detected that you might use the one-click installation package environment. Other sites in the environment are still using weak passwords. For security reasons, if you do not use other sites, please handle them in time. Delete or rename the %s directory. Visit: <a href='https://www.zentao.pm/book/zentaomanual/fix-weak-password-564.html' target='_blank'>https://www.zentao.pm/book/zentaomanual/fix-weak-password-564.html</a>";
$lang->user->process4DB  = "It is detected that you might use the one-click installation package environment. Other sites in the environment are still using simple passwords. For security reasons, if you do not use other sites, please handle them in time. Please login database and modify password field of zt_user table of %s database. Visit: <a href='https://www.zentao.pm/book/zentaomanual/fix-weak-password-564.html' target='_blank'>https://www.zentao.pm/book/zentaomanual/fix-weak-password-564.html</a>";
$lang->user->mkdirWin = <<<EOT
    <html><head><meta charset='utf-8'></head>
    <body><table align='center' style='width:700px; margin-top:100px; border:1px solid gray; font-size:14px;'><tr><td style='padding:8px'>
    <div style='margin-bottom:8px;'>不能创建临时目录，请确认目录<strong style='color:#ed980f'>%s</strong>是否存在并有操作权限。</div>
    <div>A tmp directory cannot be created. Make sure the directory <strong style='color:#ed980f'>%s</strong> exists and you have the right permission.</div>
    </td></tr></table></body></html>
EOT;
$lang->user->mkdirLinux = <<<EOT
    <html><head><meta charset='utf-8'></head>
    <body><table align='center' style='width:700px; margin-top:100px; border:1px solid gray; font-size:14px;'><tr><td style='padding:8px'>
    <div style='margin-bottom:8px;'>不能创建临时目录，请确认目录<strong style='color:#ed980f'>%s</strong>是否存在并有操作权限。</div>
    <div style='margin-bottom:8px;'>命令为：<strong style='color:#ed980f'>chmod 777 -R %s</strong>。</div>
    <div>A tmp directory cannot be created. Make sure the directory <strong style='color:#ed980f'>%s</strong> exists and you have the right permission.</div>
    <div style='margin-bottom:8px;'>Command: <strong style='color:#ed980f'>chmod 777 -R %s</strong>.</div>
    </td></tr></table></body></html>
EOT;

$lang->user->jumping = "This page will redirect to the previous page in <span id='time'>10</span> seconds. <a href='%s' id='redirect' class='btn primary'>Redirect Now</a>";

$lang->user->zentaoapp = new stdclass();
$lang->user->zentaoapp->logout = 'Logout';

$lang->user->featureBar['todo']['all']             = 'Assigned To Yourself';
$lang->user->featureBar['todo']['before']          = 'Unfinished';
$lang->user->featureBar['todo']['future']          = 'TBD';
$lang->user->featureBar['todo']['thisWeek']        = 'This Week';
$lang->user->featureBar['todo']['thisMonth']       = 'This Month';
$lang->user->featureBar['todo']['thisYear']        = 'This Year';
$lang->user->featureBar['todo']['assignedToOther'] = 'Assigned To Other';
$lang->user->featureBar['todo']['cycle']           = 'Recurrence';

$lang->user->featureBar['dynamic']['all']       = 'All';
$lang->user->featureBar['dynamic']['today']     = 'Today';
$lang->user->featureBar['dynamic']['yesterday'] = 'Yesterday';
$lang->user->featureBar['dynamic']['thisWeek']  = 'This Week';
$lang->user->featureBar['dynamic']['lastWeek']  = 'Last Week';
$lang->user->featureBar['dynamic']['thisMonth'] = 'This Month';
$lang->user->featureBar['dynamic']['lastMonth'] = 'Last Month';
