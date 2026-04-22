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
$lang->admin->sso             = 'Integrated with Zdoo';
$lang->admin->ssoAction       = 'Integrated with Zdoo';
$lang->admin->safeIndex       = 'Password Settings';
$lang->admin->checkWeak       = 'Weak Password Detection';
$lang->admin->certifyMobile   = 'Mobile Verification';
$lang->admin->certifyEmail    = 'Email Verification';
$lang->admin->ztCompany       = 'Organization Verification';
$lang->admin->captcha         = 'Verification Code';
$lang->admin->getCaptcha      = 'Send Code';
$lang->admin->register        = 'Register';
$lang->admin->resetPWDSetting = 'Reset Password';
$lang->admin->tableEngine     = 'Table Engine';
$lang->admin->setModuleIndex  = 'System Features';

$lang->admin->mon              = 'month';
$lang->admin->day              = 'day';
$lang->admin->updateDynamics   = 'Update Dynamics';
$lang->admin->updatePatch      = 'Patch Update';
$lang->admin->upgradeRecommend = 'Upgrade Recommend';
$lang->admin->zentaoUsed       = 'You have been using ZenTao';

$lang->admin->api                  = 'API';
$lang->admin->log                  = 'Log';
$lang->admin->setting              = 'Settings';
$lang->admin->setFlow              = 'Set Flow';
$lang->admin->pluginRecommendation = 'Popular Add-ons';
$lang->admin->zentaoInfo           = 'ZenTao News';
$lang->admin->officialAccount      = 'Official Social Media';
$lang->admin->publicClass          = 'Webinars';
$lang->admin->days                 = 'Log Retention Days';
$lang->admin->resetPWDByMail       = 'Reset Via Email';
$lang->admin->followUs             = 'Follow Us';
$lang->admin->followUsContent      = 'Stay updated on ZenTao news, events, and access support at any time.';

$lang->admin->changeEngine               = "Switch to InnoDB";
$lang->admin->changingTable              = 'Converting storage engine for table %s...';
$lang->admin->changeSuccess              = 'Table %s engine successfully converted to InnoDB.';
$lang->admin->changeFail                 = "Failed to convert table %s engine. Reason: <span class='text-red'>%s</span>.";
$lang->admin->errorInnodb                = 'Your current database does not support the InnoDB storage engine.';
$lang->admin->changeFinished             = "Database engine conversion completed.";
$lang->admin->engineInfo                 = "The engine for table <strong>%s</strong> is <strong>%s</strong>.";
$lang->admin->engineSummary['hasMyISAM'] = "%s tables are not using the InnoDB engine.";
$lang->admin->engineSummary['allInnoDB'] = "All tables are now using the InnoDB engine.";

$lang->admin->info = new stdclass();
$lang->admin->info->version = 'The current system version is %s, ';
$lang->admin->info->links   = 'You can visit the following link: ';
$lang->admin->info->account = 'Your ZenTao Community account is %s.';
$lang->admin->info->log     = 'Logs exceeding the retention period will be deleted. Please enable Scheduled Tasks (Cron).';

$lang->admin->notice = new stdclass();
$lang->admin->notice->register                = "You can %s the ZenTao Community (www.zentao.net) to get the latest updates.";
$lang->admin->notice->ignore                  = "Ignore";
$lang->admin->notice->int                     = "『%s』should be a positive integer.";
$lang->admin->notice->confirmDisableStoryType = "‘{type}’ function closed, the system will remove all stories associated with the project and execution, the operation is irreversible";
$lang->admin->notice->openDependFeature       = 'Using "{source}" function requires enabling "{target}" function synchronously.';
$lang->admin->notice->closeDependFeature      = 'Closing "{source}" function requires synchronously closing "{target}" function.';

$lang->admin->registerNotice = new stdclass();
$lang->admin->registerNotice->common     = 'Register';
$lang->admin->registerNotice->caption    = 'ZenTao Community Registration';
$lang->admin->registerNotice->click      = 'Register';
$lang->admin->registerNotice->lblAccount = 'Please set your username. It must contain at least 3 alphanumeric characters (letters and numbers).';
$lang->admin->registerNotice->lblPasswd  = 'Please set your password. It must contain at least 6 characters (letters and numbers combined).';
$lang->admin->registerNotice->submit     = 'Register';
$lang->admin->registerNotice->submitHere = 'Register Here';
$lang->admin->registerNotice->bind       = "Link Existing Account";
$lang->admin->registerNotice->success    = "Account registered successfully.";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = 'Link Community Account';
$lang->admin->bind->success = "Account linked successfully.";
$lang->admin->bind->submit  = "Link";

$lang->admin->setModule = new stdclass();
$lang->admin->setModule->module         = 'Modules';
$lang->admin->setModule->optional       = 'Optional Features';
$lang->admin->setModule->opened         = 'Enabled';
$lang->admin->setModule->closed         = 'Disabled';

$lang->admin->setModule->my             = 'Dashboard';
$lang->admin->setModule->product        = $lang->productCommon;
$lang->admin->setModule->project        = $lang->projectCommon;
$lang->admin->setModule->qa             = 'Test';
$lang->admin->setModule->assetlib       = 'Asset';
$lang->admin->setModule->other          = 'General Modules';

$lang->admin->setModule->program        = 'Program';
$lang->admin->setModule->testsuite      = 'Test Suits';
$lang->admin->setModule->automated      = 'Automation';
$lang->admin->setModule->AI             = 'Agents';
$lang->admin->setModule->BI             = 'Reports';
$lang->admin->setModule->workestimation = 'Work Estimation';
$lang->admin->setModule->score          = 'Points';
$lang->admin->setModule->repo           = 'Code';
$lang->admin->setModule->issue          = 'Issue';
$lang->admin->setModule->risk           = 'Risk';
$lang->admin->setModule->opportunity    = 'Opportunity';
$lang->admin->setModule->process        = 'Process';
$lang->admin->setModule->auditplan      = 'Test';
$lang->admin->setModule->meeting        = 'Meeting';
$lang->admin->setModule->roadmap        = 'Roadmap';
$lang->admin->setModule->track          = 'Matrix';
$lang->admin->setModule->ER             = $lang->ERCommon . 's';
$lang->admin->setModule->UR             = $lang->URCommon . 's';
$lang->admin->setModule->deliverable    = 'Deliverable';
$lang->admin->setModule->cm             = 'Baseline';
$lang->admin->setModule->change         = 'Project Change';
$lang->admin->setModule->researchplan   = 'Research';
$lang->admin->setModule->gapanalysis    = 'Training';
$lang->admin->setModule->storylib       = 'Story Lib';
$lang->admin->setModule->caselib        = 'Test Case Lib';
$lang->admin->setModule->issuelib       = 'Issue Lib';
$lang->admin->setModule->risklib        = 'Risk Lib';
$lang->admin->setModule->opportunitylib = 'Opportunity Lib';
$lang->admin->setModule->practicelib    = ' Best Practice Lib';
$lang->admin->setModule->componentlib   = 'Component Lib';
$lang->admin->setModule->devops         = 'CI&CD';
$lang->admin->setModule->deliverable    = 'Deliverable';
$lang->admin->setModule->kanban         = 'Kanban';
$lang->admin->setModule->OA             = 'OA';
$lang->admin->setModule->deploy         = 'OPS';
$lang->admin->setModule->traincourse    = 'Academy';
$lang->admin->setModule->setCode        = 'Code';
$lang->admin->setModule->measrecord     = 'Metric';

$lang->admin->safe = new stdclass();
$lang->admin->safe->common                   = 'Security Policy';
$lang->admin->safe->set                      = 'Password Settings';
$lang->admin->safe->password                 = 'Password Strength';
$lang->admin->safe->weak                     = 'Common Weak Passwords';
$lang->admin->safe->reason                   = 'Type';
$lang->admin->safe->checkWeak                = 'Weak Password Audit';
$lang->admin->safe->changeWeak               = 'Change Weak Passwords';
$lang->admin->safe->loginCaptcha             = 'Enable CAPTCHA on Login';
$lang->admin->safe->modifyPasswordFirstLogin = 'Force Password Change on First Login';
$lang->admin->safe->passwordStrengthWeak     = 'Password strength is below system requirements.';

$lang->admin->safe->modeList[0] = 'Ignore';
$lang->admin->safe->modeList[1] = 'Medium';
$lang->admin->safe->modeList[2] = 'Strong';

$lang->admin->safe->modeRuleList[1] = '≥6 characters:  A-Z, a-z, and 0-9.';
$lang->admin->safe->modeRuleList[2] = '≥10 characters: A-Z, a-z, 0-9, and symbols.';

$lang->admin->safe->reasonList['weak']     = 'Common Weak Passwords';
$lang->admin->safe->reasonList['account']  = 'Same as user name.';
$lang->admin->safe->reasonList['mobile']   = 'Same as mobile number.';
$lang->admin->safe->reasonList['phone']    = 'Same as phone number.';
$lang->admin->safe->reasonList['birthday'] = 'Same as date of birth.';

$lang->admin->safe->modifyPasswordList[1] = 'Mandatory';
$lang->admin->safe->modifyPasswordList[0] = 'Optional';

$lang->admin->safe->loginCaptchaList[1] = 'Yes';
$lang->admin->safe->loginCaptchaList[0] = 'No';

$lang->admin->safe->resetPWDList[1] = 'Enable';
$lang->admin->safe->resetPWDList[0] = 'Disable';

$lang->admin->safe->noticeMode     = 'The system checks password strength when creating users, editing users, or changing passwords.';
$lang->admin->safe->noticeWeakMode = 'The system checks password strength during login, user creation, user editing, and password changes.';
$lang->admin->safe->noticeStrong   = 'Security increases with password length, the use of uppercase letters, numbers, and special symbols, and fewer repeated characters.';
$lang->admin->safe->noticeGd       = 'The system detected that the GD module is not installed or FreeType support is not enabled on your server. CAPTCHA functionality is currently unavailable. Please install the necessary dependencies.';

$lang->admin->menuSetting['system']['name']        = 'System';
$lang->admin->menuSetting['system']['desc']        = 'Configure system components such as backups, chat, and security settings.';
$lang->admin->menuSetting['user']['name']          = 'Users';
$lang->admin->menuSetting['user']['desc']          = 'Manage departments, users, and permissions.';
$lang->admin->menuSetting['switch']['name']        = 'Feature Options';
$lang->admin->menuSetting['switch']['desc']        = 'Enable or disable specific system features.';
$lang->admin->menuSetting['feature']['name']       = 'Features';
$lang->admin->menuSetting['feature']['desc']       = 'Configure system elements organized by the feature menu.';
$lang->admin->menuSetting['template']['name']      = 'Doc Template';
$lang->admin->menuSetting['template']['desc']      = 'Configure document template types and content.';
$lang->admin->menuSetting['message']['name']       = 'Notifications';
$lang->admin->menuSetting['message']['desc']       = 'Configure notification channels and customize trigger actions.';
$lang->admin->menuSetting['extension']['name']     = 'Add-ons';
$lang->admin->menuSetting['extension']['desc']     = 'Browse and install plugins.';
$lang->admin->menuSetting['dev']['name']           = 'Custom Development';
$lang->admin->menuSetting['dev']['desc']           = 'Extend system functionality with custom code.';
$lang->admin->menuSetting['convert']['name']       = 'Data import';
$lang->admin->menuSetting['convert']['desc']       = 'Data migration from third-party systems.';
$lang->admin->menuSetting['adminregister']['name'] = 'ZenTao Community';
$lang->admin->menuSetting['adminregister']['desc'] = 'Access project management resources, technical support, and try demos of various versions.';

$lang->admin->updateDynamics   = 'Recents';
$lang->admin->updatePatch      = 'Patch';
$lang->admin->upgradeRecommend = 'Available Upgrade';
$lang->admin->zentaoUsed       = 'ZenTao has been with you for ';
$lang->admin->noPriv           = 'You have no permission to visit this block.';

$lang->admin->openTag = 'ZenTao Community Edition';
$lang->admin->bizTag  = 'ZenTao Biz ';
$lang->admin->maxTag  = 'ZenTao Max ';
$lang->admin->ipdTag  = 'ZenTao IPD';

$lang->admin->bizInfoURL    = 'https://www.zentao.net/page/enterprise.html';
$lang->admin->maxInfoURL    = 'https://www.zentao.net/page/max.html';
$lang->admin->productDetail = 'Details';
$lang->admin->productFeature['biz'][] = 'Feedback Management';
$lang->admin->productFeature['biz'][] = 'Task Gantt Charts/Calendar/Effort';
$lang->admin->productFeature['biz'][] = 'MS Word/Excel Import & Export';
$lang->admin->productFeature['biz'][] = 'Competitive pricing with dedicated technical support.';
$lang->admin->productFeature['max'][] = 'Project Metrics';
$lang->admin->productFeature['max'][] = 'Asset Library';
$lang->admin->productFeature['max'][] = 'QA Plan';
$lang->admin->productFeature['max'][] = 'Strict permission controls with flexible and secure access.';
$lang->admin->productFeature['ipd'][] = 'Built-in requirement pool management for requirement collection and distribution';
$lang->admin->productFeature['ipd'][] = 'Complete support for product roadmap planning and project initiation process';
$lang->admin->productFeature['ipd'][] = 'Provides comprehensive market management, research management and report management';
$lang->admin->productFeature['ipd'][] = 'Provides a complete IPD R&D workflow with built-in TR and DCP reviews.';

$lang->admin->community = new stdclass();
$lang->admin->community->registerTitle       = 'Join ZenTao Community';
$lang->admin->community->skip                = 'Skip';
$lang->admin->community->uxPlanTitle         = 'ZenTao User Experience Improvement Program';
$lang->admin->community->loginFailed         = 'Login failed.';
$lang->admin->community->loginFailedMobile   = 'Please enter your mobile number.';
$lang->admin->community->loginFailedCode     = 'Please enter the verification code.';
$lang->admin->community->officialWebsite     = 'ZenTao Website';
$lang->admin->community->uxPlanWithBookTitle = 'ZenTao User Experience Improvement Program';
$lang->admin->community->uxPlanStatusTitle   = 'Help us understand how we can make improvement.';
$lang->admin->community->mobile              = 'Mobile Number';
$lang->admin->community->smsCode             = 'Verification code';
$lang->admin->community->sendCode            = 'Send Code';
$lang->admin->community->join                = 'Join';
$lang->admin->community->joinDesc            = 'Help us understand how the product is used.';
$lang->admin->community->captchaTip          = 'Please enter the verification code.';
$lang->admin->community->sure                = '<span style="font-size: 15px;">&nbsp;&nbsp;Confirm</span>';
$lang->admin->community->unBindText          = 'Unlink';
$lang->admin->community->welcome             = 'Join ZenTao Community';
$lang->admin->community->welcomeForBound     = "You have joined ZenTao Community. Your account is: ";
$lang->admin->community->advantage1          = 'PM resources';
$lang->admin->community->advantage2          = 'Technical support';
$lang->admin->community->advantage3          = 'Demos';
$lang->admin->community->advantage4          = 'ZenTao Software Manual';
$lang->admin->community->goCommunity         = 'Visit Community';
$lang->admin->community->giftPackage         = 'Fill in your info to claim your resource kit.';
$lang->admin->community->enterMobile         = 'Please enter your mobile number.';
$lang->admin->community->enterCode           = 'Please enter the verification code.';
$lang->admin->community->goBack              = 'back';
$lang->admin->community->reSend              = 'resend';
$lang->admin->community->unbindTitle         = 'Are you sure you want to unlink your ZenTao website account?';
$lang->admin->community->unbindContent       = 'Once unlinked, you will no longer be able to access the ZenTao website directly from the software.';
$lang->admin->community->cancelButton        = 'Cancel';
$lang->admin->community->unbindButton        = 'Unlink';
$lang->admin->community->joinSuccess         = 'You hava joined the ZenTao Community.';
$lang->admin->community->receiveGiftPackage  = 'Claim your resource kit';
$lang->admin->community->giftPackageSuccess  = 'Submitted successfully.';

$lang->admin->community->positionList['Project Manager']        = 'Project Manager';
$lang->admin->community->positionList['R&D Supervisor']         = 'Technical Manager';
$lang->admin->community->positionList['Operation']              = 'Operations';
$lang->admin->community->positionList['Procurement']            = 'Procurement';
$lang->admin->community->positionList['Product Manager']        = 'Product Owner';
$lang->admin->community->positionList['UI/UX Design']           = 'UI/UX Designer';
$lang->admin->community->positionList['Front Development']      = 'Front-end Developer';
$lang->admin->community->positionList['Backend Development']    = 'Back-end Developer';
$lang->admin->community->positionList['Full Stack Development'] = 'Full Stack Developer';
$lang->admin->community->positionList['Testing/QA']             = 'QA Engineer';
$lang->admin->community->positionList['Architect']              = 'Software Architect';

$lang->admin->community->solvedProblems['Product Management']    = 'Product Management';
$lang->admin->community->solvedProblems['Project Management']    = 'Project Management';
$lang->admin->community->solvedProblems['BUG Management']        = 'BUG Management';
$lang->admin->community->solvedProblems['Workflow Management']   = 'Workflow Management';
$lang->admin->community->solvedProblems['Efficiency Management'] = 'Efficiency Management';
$lang->admin->community->solvedProblems['Document Management']   = 'Document Management';
$lang->admin->community->solvedProblems['Feedback Management']   = 'Feedback Management';
$lang->admin->community->solvedProblems['Other']                 = 'Others';

$lang->admin->community->giftPackageFormNickname = 'How should I address you?';
$lang->admin->community->giftPackageFormPosition = 'Your Job Ttitle';
$lang->admin->community->giftPackageFormCompany  = 'Company name';
$lang->admin->community->giftPackageFormQuestion = 'What project management challenges do you want to solve with ZenTao?';

$lang->admin->community->giftPackageFailed         = 'Submission failed.';
$lang->admin->community->giftPackageFailedNickname = 'Please enter your name.';
$lang->admin->community->giftPackageFailedPosition = 'Please enter your job title.';
$lang->admin->community->giftPackageFailedCompany  = 'Please enter your company name.';

$lang->admin->community->uxPlan = new stdclass();
$lang->admin->community->uxPlan->agree  = 'Agreed';
$lang->admin->community->uxPlan->cancel = 'Canceled';

$lang->admin->community->unBind = new stdclass();
$lang->admin->community->unBind->success = 'Unlinked';

$lang->admin->nickname       = 'Name';
$lang->admin->position       = 'Job Title';
$lang->admin->company        = 'Company Name';
$lang->admin->solvedProblems = 'Project Management Challenges';

$lang->admin->mobile  = 'Mobile Number';
$lang->admin->code    = 'SMS Verification Code';
$lang->admin->agreeUX = 'User Experience Program';

$lang->admin->metricLib = new stdclass();
$lang->admin->metricLib->startUpdate = 'Start Update';
$lang->admin->metricLib->updating    = 'Updating';
$lang->admin->metricLib->updated     = 'Update Completed';
$lang->admin->metricLib->tips        = "Due to the large volume of data in the metrics tables, updating the index may take a significant amount of time. Please perform this operation during off-peak hours. You may continue with other tasks, but please do not close this page or your browser.";

include dirname(__FILE__) . '/menu.php';
