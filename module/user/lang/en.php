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
$lang->user->inside           = 'Internal Users';
$lang->user->outside          = 'External Users';
$lang->user->company          = 'Company';
$lang->user->dept             = 'Department';
$lang->user->account          = 'Account';
$lang->user->password         = 'Password';
$lang->user->passwordfield    = 'Password';
$lang->user->password1        = 'Password';
$lang->user->password2        = 'Confirm Password';
$lang->user->role             = 'Role';
$lang->user->group            = 'Permission Group';
$lang->user->realname         = 'Name';
$lang->user->nickname         = 'Nickname';
$lang->user->commiter         = 'SVN / GIT Account';
$lang->user->birthyear        = 'DOB';
$lang->user->gender           = 'Gender';
$lang->user->email            = 'Email';
$lang->user->basicInfo        = 'Basic Info';
$lang->user->accountInfo      = 'Account Info';
$lang->user->verify           = 'Verification';
$lang->user->contactInfo      = 'Contact Info';
$lang->user->skype            = 'Skype';
$lang->user->qq               = 'QQ';
$lang->user->mobile           = 'Mobile';
$lang->user->phone            = 'Phone';
$lang->user->weixin           = 'WeChat';
$lang->user->dingding         = 'DingTalk';
$lang->user->slack            = 'Slack';
$lang->user->whatsapp         = 'WhatsApp';
$lang->user->address          = 'Mailing Address';
$lang->user->zipcode          = 'Zip Code';
$lang->user->join             = 'Join Date';
$lang->user->priv             = 'Permission';
$lang->user->visits           = 'Visit Count';
$lang->user->visions          = 'Interface Type';
$lang->user->ip               = 'Last IP';
$lang->user->last             = 'Last Login';
$lang->user->ranzhi           = 'Zdoo Account';
$lang->user->ditto            = 'Ditto';
$lang->user->originalPassword = 'Current Password';
$lang->user->newPassword      = 'New Password';
$lang->user->verifyPassword   = 'Password';
$lang->user->forgetPassword   = 'Forgot Password';
$lang->user->score            = 'Points';
$lang->user->name             = 'Name';
$lang->user->type             = 'User Type';
$lang->user->cropAvatar       = 'Crop Avatar';
$lang->user->cropAvatarTip    = 'Drag the frame to select the cropping area.';
$lang->user->cropImageTip     = 'The selected image is too small. The recommended minimum size is 48x48. Current size: %s.';
$lang->user->captcha          = 'Captcha';
$lang->user->avatar           = 'Avatar';
$lang->user->birthday         = 'DOB';
$lang->user->nature           = 'Personality Traits';
$lang->user->analysis         = 'Impact Analysis';
$lang->user->strategy         = 'Response Strategy';
$lang->user->fails            = 'Failed Attempts';
$lang->user->locked           = 'Lock Date';
$lang->user->scoreLevel       = 'Points Level';
$lang->user->clientStatus     = 'Login Status';
$lang->user->clientLang       = 'Client Language';
$lang->user->programs         = 'Program';
$lang->user->products         = $lang->productCommon;
$lang->user->projects         = $lang->projectCommon;
$lang->user->sprints          = $lang->execution->common;
$lang->user->identity         = 'Identity';
$lang->user->switchVision     = 'Switch to %s';
$lang->user->submit           = 'Submit';
$lang->user->resetPWD         = 'Reset Password';
$lang->user->resetPwdByAdmin  = 'Reset Password by Admin';
$lang->user->resetPwdByMail   = 'Reset password via email';

$lang->user->abbr = new stdclass();
$lang->user->abbr->id        = 'No.';
$lang->user->abbr->password2 = 'Confirm Password';
$lang->user->abbr->address   = 'Address';
$lang->user->abbr->join      = 'Join Date';

$lang->user->legendBasic        = 'Basic Info';
$lang->user->legendContribution = 'Contribution';

$lang->user->index         = "User Dashboard";
$lang->user->view          = "User Details";
$lang->user->create        = "Add User";
$lang->user->batchCreate   = "Batch Add Users";
$lang->user->edit          = "Edit User";
$lang->user->batchEdit     = "Batch Edit Users";
$lang->user->unlock        = "Unlock User";
$lang->user->delete        = "Delete User";
$lang->user->unbind        = "Unlink ZDO0";
$lang->user->login         = "Login";
$lang->user->bind          = "Link Existing Account";
$lang->user->oauthRegister = "Register New Account";
$lang->user->mobileLogin   = "Mobile Access";
$lang->user->editProfile   = "Edit Profile";
$lang->user->deny          = "Your access is denied.";
$lang->user->confirmDelete = "Are you sure you want to delete this user?";
$lang->user->confirmUnlock = "Are you sure you want to unlock this user?";
$lang->user->confirmUnbind = "Are you sure you want to unlink this user from Zdoo?";
$lang->user->relogin       = "Login Again";
$lang->user->asGuest       = "Guest Access";
$lang->user->goback        = "Return to previous page";
$lang->user->deleted       = '(Deleted)';
$lang->user->search        = 'Search';
$lang->user->else          = 'Others';

$lang->user->saveTemplate          = 'Save as Template';
$lang->user->setPublic             = 'Set as Public Template';
$lang->user->deleteTemplate        = 'Delete Template';
$lang->user->setTemplateTitle      = 'Enter template title.';
$lang->user->applyTemplate         = 'Apply Template';
$lang->user->confirmDeleteTemplate = 'Are you sure you want to delete this template?';
$lang->user->setPublicTemplate     = 'Set as Public Template';
$lang->user->tplContentNotEmpty    = 'Template content cannot be empty.';
$lang->user->sendEmailSuccess      = 'An email has been sent to your inbox. Please check it.';
$lang->user->linkExpired           = 'The link has expired. Please request a new one.';

$lang->user->profile   = 'Profile';
$lang->user->project   = $lang->executionCommon . 's';
$lang->user->execution = $lang->execution->common;
$lang->user->task      = 'Tasks';
$lang->user->bug       = 'Bugs';
$lang->user->test      = 'Test';
$lang->user->testTask  = 'Test Requests';
$lang->user->testCase  = 'Test Cases';
$lang->user->issue     = 'Issue';
$lang->user->risk      = 'Risk';
$lang->user->schedule  = 'Schedule';
$lang->user->todo      = 'To-do';
$lang->user->story     = 'Stories';
$lang->user->dynamic   = 'Recents';

$lang->user->openedBy    = 'Created by %s';
$lang->user->assignedTo  = 'Assigned to %s';
$lang->user->finishedBy  = 'Completed by %s';
$lang->user->involved    = '%s Involved';
$lang->user->resolvedBy  = 'Fixed by %s';
$lang->user->closedBy    = 'Closed by %s';
$lang->user->reviewedBy  = 'Reviewed by %s';
$lang->user->canceledBy  = 'Cancelled by %s';

$lang->user->testTask2Him = 'Managed by %s';
$lang->user->case2Him     = 'Assigned to %s';
$lang->user->caseByHim    = 'Created by %s';

$lang->user->errorDeny    = "Sorry, you do not have permission to access the [<b>%s</b>] feature in the 『<b>%s</b>」 module. Please contact your administrator for assistance. You can return to the Dashboard or log in again.";
$lang->user->errorView    = "Sorry, you do not have permission to access the [<b>%s</b>] view. Please contact your administrator for assistance. You can return to the Dashboard or log in again.";
$lang->user->loginFailed  = "Login failed. Please check if your account and password are correct.";
$lang->user->lockWarning  = "You have %s attempts remaining.";
$lang->user->loginLocked  = "Too many failed login attempts. Please contact your administrator to unlock your account, or try again in %s minutes.";
$lang->user->weakPassword = "Your password strength does not meet the system requirements.";
$lang->user->errorWeak    = "Common weak passwords such as [%s] cannot be used.";
$lang->user->errorCaptcha = "Incorrect Captcha.";
$lang->user->loginExpired = 'Your session has expired. Please log in again.';

$lang->user->roleList['']       = '';
$lang->user->roleList['dev']    = 'Dev Engineer';
$lang->user->roleList['qa']     = 'Test Engineer';
$lang->user->roleList['pm']     = 'Project Manager';
$lang->user->roleList['po']     = 'Product Owner';
$lang->user->roleList['td']     = 'Technical Manager';
$lang->user->roleList['pd']     = 'Product Manager';
$lang->user->roleList['qd']     = 'Test Manager';
$lang->user->roleList['top']    = 'Senior Manager';
$lang->user->roleList['others'] = 'Others';

$lang->user->genderList['m'] = 'Male';
$lang->user->genderList['f'] = 'Female';

$lang->user->thirdPerson['m'] = 'Him';
$lang->user->thirdPerson['f'] = 'Her';

$lang->user->typeList['inside']  = $lang->user->inside;
$lang->user->typeList['outside'] = $lang->user->outside;

$lang->user->passwordStrengthList[0] = "<span style='color:red'>Weak</span>";
$lang->user->passwordStrengthList[1] = "<span style='color:#000'>Medium</span>";
$lang->user->passwordStrengthList[2] = "<span style='color:green'>Strong</span>";

$lang->user->statusList['active'] = 'Normal';
$lang->user->statusList['delete'] = 'Deleted';

$lang->user->personalData['createdTodos']        = 'Created To-dos';
$lang->user->personalData['createdRequirements'] = "Created Features";
$lang->user->personalData['createdStories']      = "Created Stories";
$lang->user->personalData['finishedTasks']       = 'Completed Tasks';
$lang->user->personalData['createdBugs']         = 'Reported Bugs';
$lang->user->personalData['resolvedBugs']        = 'Fixed Bugs';
$lang->user->personalData['createdCases']        = 'Created Test Cases';
$lang->user->personalData['createdRisks']        = 'Created Risks';
$lang->user->personalData['resolvedRisks']       = 'Resolved Risks';
$lang->user->personalData['createdIssues']       = 'Created Issues';
$lang->user->personalData['resolvedIssues']      = 'Resolved Issues';
$lang->user->personalData['createdDocs']         = 'Created Docs';

$lang->user->keepLogin['on']   = 'Keep me logged in';
$lang->user->loginWithDemoUser = 'Log in with Demo Account:';
$lang->user->scanToLogin       = 'Scan QR Code to Log In';

$lang->user->tpl = new stdclass();
$lang->user->tpl->type    = 'Type';
$lang->user->tpl->title   = 'Template Name';
$lang->user->tpl->content = 'Content';
$lang->user->tpl->public  = 'Public';

$lang->usertpl = new stdclass();
$lang->usertpl->account = 'Creator';
$lang->usertpl->type    = 'Template Type';
$lang->usertpl->title   = 'Template Name';
$lang->usertpl->content = 'Template Content';
$lang->usertpl->public  = 'Public Template';

$lang->user->placeholder = new stdclass();
$lang->user->placeholder->account   = 'A combination of letters, numbers, and underscores; at least 3 characters.';
$lang->user->placeholder->password1 = 'At least 6 characters.';
$lang->user->placeholder->role      = "Job title affects the sorting order of content and user lists.";
$lang->user->placeholder->group     = "The group determines the user's permissions.";
$lang->user->placeholder->commiter  = 'SVN / Git account.';
$lang->user->placeholder->verify    = 'Enter your system login password.';

$lang->user->placeholder->loginPassword = 'Enter your password.';
$lang->user->placeholder->loginAccount  = 'Enter your account.';
$lang->user->placeholder->loginUrl      = 'Enter your ZenTao site URL.';
$lang->user->placeholder->email         = 'Enter your email.';

$lang->user->placeholder->passwordStrength[0] = 'Password must be at least 6 characters.';
$lang->user->placeholder->passwordStrength[1] = '≥6 characters:  A-Z, a-z, and 0-9.';
$lang->user->placeholder->passwordStrength[2] = '≥10 characters: A-Z, a-z, 0-9, and symbols.';

$lang->user->placeholder->passwordStrengthCheck[0] = 'Password must be at least 6 characters.';
$lang->user->placeholder->passwordStrengthCheck[1] = 'Password must be at least 6 characters, containing uppercase letters, lowercase letters, and numbers.';
$lang->user->placeholder->passwordStrengthCheck[2] = 'Password must be at least 10 characters, containing uppercase letters, lowercase letters, numbers, and special symbols.';

$lang->user->error = new stdclass();
$lang->user->error->account        = 'Account name must be a combination of letters, numbers, or underscores (at least 3 characters).';
$lang->user->error->accountDupl    = 'Account name already exists.';
$lang->user->error->realname       = 'Real Name is required.';
$lang->user->error->visions        = 'Interface Type is required.';
$lang->user->error->password       = 'Password must be at least 6 characters.';
$lang->user->error->mail           = 'Invalid Email.';
$lang->user->error->reserved       = 'The account name is reserved by the system.';
$lang->user->error->weakPassword   = 'The password strength does not meet the system requirements.';
$lang->user->error->dangerPassword = "Common weak passwords such as [%s] cannot be used.";

$lang->user->error->url              = "Invalid URL. Please contact the administrator.";
$lang->user->error->verify           = "Incorrect account or password.";
$lang->user->error->verifyPassword   = "Verification failed. Please check if your system login password is correct.";
$lang->user->error->originalPassword = "Incorrect current password.";
$lang->user->error->companyEmpty     = "Company name cannot be empty.";
$lang->user->error->noAccess         = "This user is not in the same department as you. You do not have permission to access their work information.";
$lang->user->error->accountEmpty     = 'Account cannot be empty.';
$lang->user->error->emailEmpty       = 'Email cannot be empty.';
$lang->user->error->noUser           = 'User does not exist.';
$lang->user->error->noEmail          = 'This user has no linked email address. Please contact the administrator to reset the password.';
$lang->user->error->errorEmail       = 'The account and email do not match. Please try again.';
$lang->user->error->emailSetting     = 'The system email is not configured. Please contact the administrator for a reset.';
$lang->user->error->sendMailFail     = 'Failed to send email. Please try again.';
$lang->user->error->loginTimeoutTip  = 'System login failed. Please check if the proxy service is working properly.';
$lang->user->error->uploadAvatar     = 'Failed to upload avatar. Please upload an image in %s format.';

$lang->user->contactFieldList['phone']    = $lang->user->phone;
$lang->user->contactFieldList['mobile']   = $lang->user->mobile;
$lang->user->contactFieldList['qq']       = $lang->user->qq;
$lang->user->contactFieldList['dingding'] = $lang->user->dingding;
$lang->user->contactFieldList['weixin']   = $lang->user->weixin;
$lang->user->contactFieldList['skype']    = $lang->user->skype;
$lang->user->contactFieldList['slack']    = $lang->user->slack;
$lang->user->contactFieldList['whatsapp'] = $lang->user->whatsapp;

$lang->user->executionTypeList['stage']  = 'Phase';
$lang->user->executionTypeList['sprint'] = $lang->iterationCommon;

$lang->user->contacts = new stdclass();
$lang->user->contacts->common   = 'Contacts';
$lang->user->contacts->listName = 'List Name';
$lang->user->contacts->userList = 'User List';

$lang->usercontact = new stdclass;
$lang->usercontact->account  = 'Creator';
$lang->usercontact->listName = 'List Name';
$lang->usercontact->userList = 'User List';
$lang->usercontact->public   = 'Public Contacts';

$lang->user->contacts->manage        = 'Manage Lists';
$lang->user->contacts->contactsList  = 'Existing Lists';
$lang->user->contacts->selectedUsers = 'Select Users';
$lang->user->contacts->selectList    = 'Select Lists';
$lang->user->contacts->createList    = 'Create Contacts';
$lang->user->contacts->noListYet     = 'No lists have been created yet. Please create a contact list first.';
$lang->user->contacts->confirmDelete = 'Are you sure you want to delete this list?';
$lang->user->contacts->or            = ' or ';

$lang->user->resetFail        = "Failed to reset password. Please check if the account exists.";
$lang->user->resetSuccess     = "Password reset successfully. Please log in with your new password.";
$lang->user->noticeDelete     = 'Are you sure you want to delete "%s" from the system?';
$lang->user->noticeHasDeleted = "This user has been deleted. To view their details, please restore them from the Recycle Bin first.";
$lang->user->noticeResetFile  = "<h5>Standard users: Please contact your administrator to reset your password.</h5>
<h5>Administrators: Please log in to the server where ZenTao is hosted and create the <span>%s </span> file. </h5>
<p>Note:</p>
<ol>
<li>The file content should be empty.</li>
<li>If the file already exists, delete it and create a new one.</li>
</ol>";
$lang->user->notice4Safe = "Warning: Weak password detected in the one-click installation package.";
$lang->user->process4DIR = "It appears you are using a one-click installation environment where other sites are still using weak passwords. For security purposes, if you are not using these other sites, please address this immediately by deleting or renaming the %s directory.";
$lang->user->process4DB  = "It appears you are using a one-click installation environment where other sites are still using weak passwords. For security purposes, if you are not using these other sites, please address this immediately. Please log in to the database and modify the 'password' field in the 'zt_user' table of the %s database.";
$lang->user->mkdirWin = <<<EOT
<html><head><meta charset="utf-8"></head>
<body><table align="center" style="width:700px; margin-top:100px; border:1px solid gray; font-size:14px;"><tr><td style="padding:8px">
<div style="margin-bottom:8px;"> Failed to create a temporary directory. Please verify that the directory <strong style="color:#ed980f">%s</strong> exists and has the necessary permissions.</div>
<div>Can"t create tmp directory, make sure the directory <strong style="color:#ed980f">%s</strong> exists and has permission to operate.</div>
</td></tr></table></body></html>
EOT;
$lang->user->mkdirLinux = <<<EOT
<html><head><meta charset="utf-8"></head>
<body><table align="center" style="width:700px; margin-top:100px; border:1px solid gray; font-size:14px;"><tr><td style="padding:8px">
<div style="margin-bottom:8px;"> Failed to create a temporary directory. Please verify that the directory <strong style="color:#ed980f">%s</strong>exists and has the necessary permissions.</div>
<div style="margin-bottom:8px;">Command: <strong style="color:#ed980f">chmod 777 -R %s</strong>。</div>
<div>Can"t create tmp directory, make sure the directory <strong style="color:#ed980f">%s</strong> exists and has permission to operate.</div>
<div style="margin-bottom:8px;">Command: <strong style="color:#ed980f">chmod 777 -R %s</strong>.</div>
</td></tr></table></body></html>
EOT;

$lang->user->jumping = "You will be automatically redirected to the login page in  <span id='time'>10</span> seconds. <a href='%s' id='redirect' class='btn primary'>Redirect Now</a>";

$lang->user->zentaoapp = new stdclass();
$lang->user->zentaoapp->logout = 'Log Out';

$lang->user->featureBar['todo']['all']             = 'Assigned to Me';
$lang->user->featureBar['todo']['before']          = 'Unfinished';
$lang->user->featureBar['todo']['future']          = 'TBD';
$lang->user->featureBar['todo']['thisWeek']        = 'This Week';
$lang->user->featureBar['todo']['thisMonth']       = 'This Month';
$lang->user->featureBar['todo']['thisYear']        = 'This Year';
$lang->user->featureBar['todo']['assignedToOther'] = 'Assigned to Others';
$lang->user->featureBar['todo']['cycle']           = 'Recurring';

$lang->user->featureBar['dynamic']['all']       = 'All';
$lang->user->featureBar['dynamic']['today']     = 'Today';
$lang->user->featureBar['dynamic']['yesterday'] = 'Yesterday';
$lang->user->featureBar['dynamic']['thisWeek']  = 'This Week';
$lang->user->featureBar['dynamic']['lastWeek']  = 'Last Week';
$lang->user->featureBar['dynamic']['thisMonth'] = 'This Month';
$lang->user->featureBar['dynamic']['lastMonth'] = 'Last Month';
