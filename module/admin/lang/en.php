<?php
/**
 * The admin module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: en.php 4460 2013-02-26 02:28:02Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->admin->index           = 'Admin Home';
$lang->admin->sso             = 'Zdoo';
$lang->admin->ssoAction       = 'Link Zdoo';
$lang->admin->safeIndex       = 'Password Security Setting';
$lang->admin->checkWeak       = 'Check Weak Password';
$lang->admin->certifyMobile   = 'Verify your cellphone';
$lang->admin->certifyEmail    = 'Verify your Email';
$lang->admin->ztCompany       = 'Verify your company';
$lang->admin->captcha         = 'Verification Code';
$lang->admin->getCaptcha      = 'Send Verification Code';
$lang->admin->register        = 'Register';
$lang->admin->resetPWDSetting = 'Reset password Setting';
$lang->admin->tableEngine     = 'Table Engine';
$lang->admin->setModuleIndex  = 'Set Module';

$lang->admin->mon              = 'month';
$lang->admin->day              = 'day';
$lang->admin->updateDynamics   = 'Update Dynamics';
$lang->admin->updatePatch      = 'Patch Update';
$lang->admin->upgradeRecommend = 'Upgrade Recommend';
$lang->admin->zentaoUsed       = 'You have been using ZenTao';

$lang->admin->api                  = 'API';
$lang->admin->log                  = 'Log';
$lang->admin->setting              = 'Setting';
$lang->admin->setFlow              = 'Set Flow';
$lang->admin->pluginRecommendation = 'Recommended plug-ins';
$lang->admin->zentaoInfo           = 'ZenTao information';
$lang->admin->officialAccount      = 'WeChat Official Account';
$lang->admin->publicClass          = 'Public class';
$lang->admin->days                 = 'Valid Day';
$lang->admin->resetPWDByMail       = 'Reset the password via the email';
$lang->admin->followUs             = 'Follow Us';
$lang->admin->followUsContent      = 'Check out ZenTao news, events, and support at any time';

$lang->admin->changeEngine               = "Change to InnoDB";
$lang->admin->changingTable              = 'Replacing data table %s engine...';
$lang->admin->changeSuccess              = 'The data table %s engine has been changed to InnoDB.';
$lang->admin->changeFail                 = "Failed to replace table %s engine. Reason: <span class='text-red'>%s</span>。";
$lang->admin->errorInnodb                = 'Your MySQL does not support InnoDB data table engine.';
$lang->admin->changeFinished             = "Database engine replacement completed.";
$lang->admin->engineInfo                 = "The <strong>%s</strong> table engine is <strong>%s</strong>.";
$lang->admin->engineSummary['hasMyISAM'] = "There are %s tables that are not InnoDB engines";
$lang->admin->engineSummary['allInnoDB'] = "All tables are InnoDB engines";

$lang->admin->info = new stdclass();
$lang->admin->info->version = 'Current Version is %s. ';
$lang->admin->info->links   = 'You can visit links below';
$lang->admin->info->account = 'Your ZenTao account is %s.';
$lang->admin->info->log     = 'Log that exceeds valid days will be deleted and you have to run cron.';

$lang->admin->notice = new stdclass();
$lang->admin->notice->register                = "Note: You haven't registered in ZenTao official website(www.zentao.pm). %s then get the Latest ZenTao Upgrades and News.";
$lang->admin->notice->ignore                  = "Ignore";
$lang->admin->notice->int                     = "『%s』should be a positive integer.";
$lang->admin->notice->confirmDisableStoryType = "‘{type}’ function closed, the system will remove all stories associated with the project and execution, the operation is irreversible";
$lang->admin->notice->openDependFeature       = 'Using "{source}" function requires enabling "{target}" function synchronously.';
$lang->admin->notice->closeDependFeature      = 'Closing "{source}" function requires synchronously closing "{target}" function.';

$lang->admin->registerNotice = new stdclass();
$lang->admin->registerNotice->common     = 'Register Account';
$lang->admin->registerNotice->caption    = 'ZenTao Community Signup';
$lang->admin->registerNotice->click      = 'Sign Up';
$lang->admin->registerNotice->lblAccount = '>= 3 letters and numbers';
$lang->admin->registerNotice->lblPasswd  = '>= 6 letters and numbers';
$lang->admin->registerNotice->submit     = 'Submit';
$lang->admin->registerNotice->submitHere = 'RegistierenHere';
$lang->admin->registerNotice->bind       = "Bind Exsiting Account";
$lang->admin->registerNotice->success    = "You have signed up!";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = 'Link Account';
$lang->admin->bind->success = "Account is linked!";
$lang->admin->bind->submit  = "Bind";

$lang->admin->setModule = new stdclass();
$lang->admin->setModule->module         = 'Module';
$lang->admin->setModule->optional       = 'Optional';
$lang->admin->setModule->opened         = 'Opened';
$lang->admin->setModule->closed         = 'Closed';

$lang->admin->setModule->my             = 'My';
$lang->admin->setModule->product        = $lang->productCommon;
$lang->admin->setModule->project        = $lang->projectCommon;
$lang->admin->setModule->assetlib       = 'Assetlib';
$lang->admin->setModule->other          = 'Other';

$lang->admin->setModule->score          = 'Score';
$lang->admin->setModule->repo           = 'Repo';
$lang->admin->setModule->issue          = 'Issue';
$lang->admin->setModule->risk           = 'Risk';
$lang->admin->setModule->opportunity    = 'Opportunity';
$lang->admin->setModule->process        = 'Process';
$lang->admin->setModule->auditplan      = 'QA';
$lang->admin->setModule->meeting        = 'Meeting';
$lang->admin->setModule->roadmap        = 'Roadmap';
$lang->admin->setModule->track          = 'Track';
$lang->admin->setModule->ER             = $lang->ERCommon;
$lang->admin->setModule->UR             = $lang->URCommon;
$lang->admin->setModule->deliverable    = 'Deliverable';
$lang->admin->setModule->cm             = 'Baseline';
$lang->admin->setModule->change         = 'Project Change';
$lang->admin->setModule->researchplan   = 'Researchplan';
$lang->admin->setModule->gapanalysis    = 'Gapanalysis';
$lang->admin->setModule->storylib       = 'Story Lib';
$lang->admin->setModule->caselib        = 'Case Lib';
$lang->admin->setModule->issuelib       = 'Issue Lib';
$lang->admin->setModule->risklib        = 'Risk Lib';
$lang->admin->setModule->opportunitylib = 'Opportunity Lib';
$lang->admin->setModule->practicelib    = 'Practice Lib';
$lang->admin->setModule->componentlib   = 'Component Lib';
$lang->admin->setModule->devops         = 'DevOps';
$lang->admin->setModule->deliverable    = 'Deliverable';
$lang->admin->setModule->kanban         = 'Kanban';
$lang->admin->setModule->OA             = 'OA';
$lang->admin->setModule->deploy         = 'Deploy';
$lang->admin->setModule->traincourse    = 'Traincourse';
$lang->admin->setModule->setCode        = 'Code';
$lang->admin->setModule->measrecord     = 'Measrecord';

$lang->admin->safe = new stdclass();
$lang->admin->safe->common                   = 'Security Policy';
$lang->admin->safe->set                      = 'Password Settings';
$lang->admin->safe->password                 = 'Password Strength';
$lang->admin->safe->weak                     = 'Common Weak Passwords';
$lang->admin->safe->reason                   = 'Type';
$lang->admin->safe->checkWeak                = 'Weak Password Scan';
$lang->admin->safe->changeWeak               = 'Force to change weak password';
$lang->admin->safe->loginCaptcha             = 'Login using CAPTCHA';
$lang->admin->safe->modifyPasswordFirstLogin = 'Force to change password after first login';
$lang->admin->safe->passwordStrengthWeak     = 'The password strength is weaker than the system settings.';

$lang->admin->safe->modeList[0] = 'I don\'t care.';
$lang->admin->safe->modeList[1] = 'Medium';
$lang->admin->safe->modeList[2] = 'Strong';

$lang->admin->safe->modeRuleList[1] = ' ≥ 6 upper and lower case, and numbers.';
$lang->admin->safe->modeRuleList[2] = ' ≥ 10 letters, combination of uppercase, lowercase letters, numbers, and special symbols.';

$lang->admin->safe->reasonList['weak']     = 'Common Weak Password';
$lang->admin->safe->reasonList['account']  = 'Same as account';
$lang->admin->safe->reasonList['mobile']   = 'Same as mobilephone number';
$lang->admin->safe->reasonList['phone']    = 'Same as phone number';
$lang->admin->safe->reasonList['birthday'] = 'Same as DOB';

$lang->admin->safe->modifyPasswordList[1] = 'Yes';
$lang->admin->safe->modifyPasswordList[0] = 'No';

$lang->admin->safe->loginCaptchaList[1] = 'Yes';
$lang->admin->safe->loginCaptchaList[0] = 'No';

$lang->admin->safe->resetPWDList[1] = 'ON';
$lang->admin->safe->resetPWDList[0] = 'Off';

$lang->admin->safe->noticeMode     = 'The password will be checked when creating and modifying user information, and changing passwords.';
$lang->admin->safe->noticeWeakMode = 'The password will be checked when logging into the system, creating and modifying user information, and changing passwords.';
$lang->admin->safe->noticeStrong   = 'The longer the password, the more letters, numbers, or special characters it contains, and the less repetitive the password, the more secure it is!';
$lang->admin->safe->noticeGd       = 'Your server does not have GD module installed or enabled FreeType support, you cannot use the Captcha function, Please use it after installation.';

$lang->admin->menuSetting['system']['name']        = 'System';
$lang->admin->menuSetting['system']['desc']        = 'System elements configuration, such as backup, chat, security etc.';
$lang->admin->menuSetting['user']['name']          = 'User';
$lang->admin->menuSetting['user']['desc']          = 'Manage departments,add members and group configuration permissions.';
$lang->admin->menuSetting['switch']['name']        = 'Switch';
$lang->admin->menuSetting['switch']['desc']        = 'Turn on and off some functions of the system.';
$lang->admin->menuSetting['model']['name']         = 'Model';
$lang->admin->menuSetting['model']['desc']         = 'Configure different project management models and project common elements.';
$lang->admin->menuSetting['feature']['name']       = 'Feature';
$lang->admin->menuSetting['feature']['desc']       = 'Configure the elements of the system according to the function menu.';
$lang->admin->menuSetting['template']['name']      = 'Template';
$lang->admin->menuSetting['template']['desc']      = 'Configure the template type and template content of the document.';
$lang->admin->menuSetting['message']['name']       = 'Message';
$lang->admin->menuSetting['message']['desc']       = 'Configure notification paths and customize the actions to be notified.';
$lang->admin->menuSetting['extension']['name']     = 'Extension';
$lang->admin->menuSetting['extension']['desc']     = 'Browse and install plugins.';
$lang->admin->menuSetting['dev']['name']           = 'Develop';
$lang->admin->menuSetting['dev']['desc']           = 'Support for secondary development of the system.';
$lang->admin->menuSetting['convert']['name']       = 'Data Import';
$lang->admin->menuSetting['convert']['desc']       = 'Data import from third-party systems.';
$lang->admin->menuSetting['adminregister']['name'] = 'ZenTao Community';
$lang->admin->menuSetting['adminregister']['desc'] = 'Get the project management resources, tech support, and try out demo versions.';

$lang->admin->updateDynamics   = 'Dynamics';
$lang->admin->updatePatch      = 'Patch';
$lang->admin->upgradeRecommend = 'Recommend Upgrade';
$lang->admin->zentaoUsed       = 'You have used ZenTao';
$lang->admin->noPriv           = 'You have no permission to visit this block.';

$lang->admin->openTag = 'ZenTao Opensource ';
$lang->admin->bizTag  = 'ZenTao Biz ';
$lang->admin->maxTag  = 'ZenTao Max ';
$lang->admin->ipdTag  = 'ZenTao IPD';

$lang->admin->bizInfoURL    = 'https://www.zentao.net/page/enterprise.html';
$lang->admin->maxInfoURL    = 'https://www.zentao.net/page/max.html';
$lang->admin->productDetail = 'Detail';
$lang->admin->productFeature['biz'][] = 'Feedback Management';
$lang->admin->productFeature['biz'][] = 'Task Gantt Charts/Calendar/Effort';
$lang->admin->productFeature['biz'][] = 'MS Word/Excel Import & Export';
$lang->admin->productFeature['biz'][] = 'LDAP Support';
$lang->admin->productFeature['max'][] = 'Project Metrics';
$lang->admin->productFeature['max'][] = 'Asset Library';
$lang->admin->productFeature['max'][] = 'QA Plan';
$lang->admin->productFeature['max'][] = 'Opportunity/Risk/Issues Management';
$lang->admin->productFeature['ipd'][] = 'Built-in requirement pool management for requirement collection and distribution';
$lang->admin->productFeature['ipd'][] = 'Complete support for product roadmap planning and project initiation process';
$lang->admin->productFeature['ipd'][] = 'Provides comprehensive market management, research management and report management';
$lang->admin->productFeature['ipd'][] = 'Provides complete IPD R&D process with built-in TR and DCP reviews';

$lang->admin->community = new stdclass();
$lang->admin->community->registerTitle       = 'Join Us';
$lang->admin->community->skip                = 'Skip';
$lang->admin->community->uxPlanTitle         = 'ZenTao User Experience Improvement Program';
$lang->admin->community->loginFailed         = 'Login failed';
$lang->admin->community->loginFailedMobile   = 'Please provide your phone number';
$lang->admin->community->loginFailedCode     = 'Please fill in the verification code';
$lang->admin->community->officialWebsite     = '<span style="font-size: 12px;">ZenTao Official website </span>';
$lang->admin->community->uxPlanWithBookTitle = ' ZenTao User Experience Improvement Program ';
$lang->admin->community->uxPlanStatusTitle   = 'to help us improve.';
$lang->admin->community->mobile              = 'phone number';
$lang->admin->community->smsCode             = 'Verification code';
$lang->admin->community->sendCode            = 'Get code';
$lang->admin->community->join                = 'join the';
$lang->admin->community->joinDesc            = 'and help us understand the usage of the product';
$lang->admin->community->captchaTip          = 'verification code';
$lang->admin->community->sure                = 'Confirm';
$lang->admin->community->unBindText          = 'Disconnect';
$lang->admin->community->welcome             = 'Join Us';
$lang->admin->community->welcomeForBound     = "You're joined the ZenTao community. your account:";
$lang->admin->community->advantage1          = 'PM resources';
$lang->admin->community->advantage2          = 'Technical support';
$lang->admin->community->advantage3          = 'Demos';
$lang->admin->community->advantage4          = 'User manual';
$lang->admin->community->goCommunity         = 'Visit Community';
$lang->admin->community->giftPackage         = 'Gift package';
$lang->admin->community->enterMobile         = 'Please enter your phone number';
$lang->admin->community->enterCode           = 'Please enter the verification code';
$lang->admin->community->goBack              = 'back';
$lang->admin->community->reSend              = 'resend';
$lang->admin->community->unbindTitle         = 'Are you sure you want to disconnect from the ZenTao website?';
$lang->admin->community->unbindContent       = 'After unbinding, it will not be possible to directly redirect to the official website of Zen Buddhism through ZenTao Buddhism software';
$lang->admin->community->cancelButton        = 'Cancel';
$lang->admin->community->unbindButton        = 'Disconnect';
$lang->admin->community->joinSuccess         = 'Success';
$lang->admin->community->receiveGiftPackage  = 'Collect gift package';
$lang->admin->community->giftPackageSuccess  = 'Success';

$lang->admin->community->positionList['Project Manager']        = 'Project Manager';
$lang->admin->community->positionList['R&D Supervisor']         = 'R&D Supervisor';
$lang->admin->community->positionList['Operation']              = 'Operation';
$lang->admin->community->positionList['Procurement']            = 'Procurement';
$lang->admin->community->positionList['Product Manager']        = 'Product Manager';
$lang->admin->community->positionList['UI/UX Design']           = 'UI/UX Design';
$lang->admin->community->positionList['Front Development']      = 'Front Development';
$lang->admin->community->positionList['Backend Development']    = 'Backend Development';
$lang->admin->community->positionList['Full Stack Development'] = 'Full Stack Development';
$lang->admin->community->positionList['Testing/QA']             = 'Testing/QA';
$lang->admin->community->positionList['Architect']              = 'Architect';

$lang->admin->community->solvedProblems['Product Management']    = 'Product Management';
$lang->admin->community->solvedProblems['Project Management']    = 'Project Management';
$lang->admin->community->solvedProblems['BUG Management']        = 'BUG Management';
$lang->admin->community->solvedProblems['Workflow Management']   = 'Workflow Management';
$lang->admin->community->solvedProblems['Efficiency Management'] = 'Efficiency Management';
$lang->admin->community->solvedProblems['Document Management']   = 'Document Management';
$lang->admin->community->solvedProblems['Feedback Management']   = 'Feedback Management';
$lang->admin->community->solvedProblems['Other']                 = 'Other';

$lang->admin->community->giftPackageFormNickname = 'How should I address you';
$lang->admin->community->giftPackageFormPosition = 'Your position';
$lang->admin->community->giftPackageFormCompany  = 'Company name';
$lang->admin->community->giftPackageFormQuestion = 'What project management problems do you want to use ZenTao to solve';

$lang->admin->community->giftPackageFailed         = 'Submission failed';
$lang->admin->community->giftPackageFailedNickname = 'Please fill in your name';
$lang->admin->community->giftPackageFailedPosition = 'Please fill in the position';
$lang->admin->community->giftPackageFailedCompany  = 'Please fill in the company name';

$lang->admin->community->uxPlan = new stdclass();
$lang->admin->community->uxPlan->agree  = 'Agreed';
$lang->admin->community->uxPlan->cancel = 'Canceled';

$lang->admin->community->unBind = new stdclass();
$lang->admin->community->unBind->success = 'Disconnect';

$lang->admin->nickname       = 'Nickname';
$lang->admin->position       = 'Position';
$lang->admin->company        = 'Company Name';
$lang->admin->solvedProblems = 'Project Management Problems';

$lang->admin->mobile  = 'Mobile Phone';
$lang->admin->code    = 'SMS Verification Code';
$lang->admin->agreeUX = 'User Experience Program';

$lang->admin->metricLib = new stdclass();
$lang->admin->metricLib->startUpdate = 'Start Update';
$lang->admin->metricLib->updating    = 'Updating';
$lang->admin->metricLib->updated     = 'Update Completed';
$lang->admin->metricLib->tips        = "Due to the large amount of data in the metric library table, updating indexes may take a long time. Please operate during low business hours. During this time, you can perform other operations, but please do not close the current page or browser.";

include dirname(__FILE__) . '/menu.php';
