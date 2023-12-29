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
$lang->admin->sso             = 'SSO';
$lang->admin->ssoAction       = 'Link Zdoo';
$lang->admin->safeIndex       = 'Password Security Setting';
$lang->admin->checkWeak       = 'Schwache Passwörter prüfen';
$lang->admin->certifyMobile   = 'Prüfen Sie die Mobilnummer';
$lang->admin->certifyEmail    = 'Prüfen Sie die Emailadresse';
$lang->admin->ztCompany       = 'Prüfen Sie das Unternehmen';
$lang->admin->captcha         = 'Bestätigungscode';
$lang->admin->getCaptcha      = 'Bestätigungscode anfordern';
$lang->admin->register        = 'Register';
$lang->admin->resetPWDSetting = 'Reset password Setting';
$lang->admin->tableEngine     = 'Table Engine';
$lang->admin->setModuleIndex  = 'Set Module';

$lang->admin->mon              = 'month';
$lang->admin->day              = 'day';
$lang->admin->updateDynamics   = 'updateDynamics';
$lang->admin->updatePatch      = 'updatePatch';
$lang->admin->upgradeRecommend = 'upgradeRecommend';
$lang->admin->zentaoUsed       = '';

$lang->admin->api                  = 'API';
$lang->admin->log                  = 'Log';
$lang->admin->setting              = 'Einstellungen';
$lang->admin->pluginRecommendation = 'Recommended plug-ins';
$lang->admin->zentaoInfo           = 'ZenTao information';
$lang->admin->officialAccount      = 'WeChat Official Account';
$lang->admin->publicClass          = 'Public class';
$lang->admin->days                 = 'Gültige Tage';
$lang->admin->resetPWDByMail       = 'Reset the password via the email';
$lang->admin->followUs             = 'Follow Us';
$lang->admin->followUsContent      = 'Check out ZenTao news, events, and support at any time';

$lang->admin->changeEngine   = "Change to InnoDB";
$lang->admin->changingTable  = 'Replacing data table %s engine...';
$lang->admin->changeSuccess  = 'The data table %s engine has been changed to InnoDB.';
$lang->admin->changeFail     = "Failed to replace table %s engine. Reason: <span class='text-red'>%s</span>。";
$lang->admin->errorInnodb    = 'Your MySQL does not support InnoDB data table engine.';
$lang->admin->changeFinished = "Database engine replacement completed.";
$lang->admin->engineInfo     = "The <strong>%s</strong> table engine is <strong>%s</strong>.";
$lang->admin->engineSummary['hasMyISAM'] = "There are %s tables that are not InnoDB engines";
$lang->admin->engineSummary['allInnoDB'] = "All tables are InnoDB engines";

$lang->admin->info = new stdclass();
$lang->admin->info->version = 'Aktuelle Version ist %s. ';
$lang->admin->info->links   = 'You can visit links below';
$lang->admin->info->account = 'Ihr ZenTao Konto ist %s.';
$lang->admin->info->log     = 'Logs die über die Gültigen Tage hinausgehen werden gelöscht. Aufgabenplanung muss laufen (cron).';

$lang->admin->notice = new stdclass();
$lang->admin->notice->register = "Hinweiß: Sie haben sich nicht bei ZenTao (www.zentao.pm) registriert. %s um die aktuellen Updates und News zu erhalten.";
$lang->admin->notice->ignore   = "Ignorieren";
$lang->admin->notice->int      = "『%s』sollte eine positive Zahl sein.";

$lang->admin->registerNotice = new stdclass();
$lang->admin->registerNotice->common     = 'Register Account';
$lang->admin->registerNotice->caption    = 'In der Zentao Community registieren';
$lang->admin->registerNotice->click      = 'Bitte registieren Sie sich hier';
$lang->admin->registerNotice->lblAccount = 'mindestens 3 Zeichen bitte; mit Buchsten und Ziffern.';
$lang->admin->registerNotice->lblPasswd  = 'mindestens 6 Zeichen bitte; mit Buchsten und Ziffern.';
$lang->admin->registerNotice->submit     = 'Registieren';
$lang->admin->registerNotice->submitHere = 'RegistierenHere';
$lang->admin->registerNotice->bind       = "Verbinde bestehendes Konto";
$lang->admin->registerNotice->success    = "Sie haben sich erfolgreich registriert!";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = 'Konto verknüpfen';
$lang->admin->bind->success = "Konto wurde verknüpft!";
$lang->admin->bind->submit  = "Binden";

$lang->admin->setModule = new stdclass();
$lang->admin->setModule->module         = 'Module';
$lang->admin->setModule->optional       = 'Optional';
$lang->admin->setModule->opened         = 'Opened';
$lang->admin->setModule->closed         = 'Closed';

$lang->admin->setModule->my             = 'My';
$lang->admin->setModule->product        = $lang->productCommon;
$lang->admin->setModule->scrum          = 'Scrum ' . $lang->projectCommon;
$lang->admin->setModule->waterfall      = 'Waterfall ' . $lang->projectCommon;
$lang->admin->setModule->agileplus      = 'Agile + ' . $lang->projectCommon;
$lang->admin->setModule->waterfallplus  = 'Waterfall + ' . $lang->projectCommon;
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
$lang->admin->setModule->UR             = $lang->URCommon;
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
$lang->admin->setModule->kanban         = 'Kanban';
$lang->admin->setModule->OA             = 'OA';
$lang->admin->setModule->deploy         = 'Deploy';
$lang->admin->setModule->traincourse    = 'Traincourse';

$lang->admin->safe = new stdclass();
$lang->admin->safe->common                   = 'Sicherheits-Regeln';
$lang->admin->safe->set                      = 'PasswortStärke';
$lang->admin->safe->password                 = 'Passwort Stärke';
$lang->admin->safe->weak                     = 'Schwache Passwörter';
$lang->admin->safe->reason                   = 'Typ';
$lang->admin->safe->checkWeak                = 'Schwache Passwörter prüfen';
$lang->admin->safe->changeWeak               = 'Ihr Passwort ist schwach. Bitte ändern.';
$lang->admin->safe->loginCaptcha             = 'Login using CAPTCHA';
$lang->admin->safe->modifyPasswordFirstLogin = 'Passwort nach der ersten Anmeldung ändern';
$lang->admin->safe->passwordStrengthWeak     = 'The password strength is weaker than the system settings.';

$lang->admin->safe->modeList[0] = 'N/A';
$lang->admin->safe->modeList[1] = 'Medium';
$lang->admin->safe->modeList[2] = 'Stark';

$lang->admin->safe->modeRuleList[1] = 'Beinhaltet Groß und Kleinbuchstaben sowie Ziffern. Länge ≥ 6.';
$lang->admin->safe->modeRuleList[2] = ' ≥ 10 letters, combination of uppercase, lowercase letters, numbers, and special symbols.';

$lang->admin->safe->reasonList['weak']     = 'Bekannte Schwache Passwörter';
$lang->admin->safe->reasonList['account']  = 'Entspricht ihrem Konto';
$lang->admin->safe->reasonList['mobile']   = 'Entspricht Ihrer Mobilnummer';
$lang->admin->safe->reasonList['phone']    = 'Entspricht Ihrer Telefonnummer';
$lang->admin->safe->reasonList['birthday'] = 'Entspricht Ihrem Geburtstag';

$lang->admin->safe->modifyPasswordList[1] = 'Ja';
$lang->admin->safe->modifyPasswordList[0] = 'Nein';

$lang->admin->safe->loginCaptchaList[1] = 'Ja';
$lang->admin->safe->loginCaptchaList[0] = 'Nein';

$lang->admin->safe->resetPWDList[1] = 'ON';
$lang->admin->safe->resetPWDList[0] = 'Off';

$lang->admin->safe->noticeMode     = 'The password will be checked when creating and modifying user information, and changing passwords.';
$lang->admin->safe->noticeWeakMode = 'The password will be checked when logging into the system, creating and modifying user information, and changing passwords.';
$lang->admin->safe->noticeStrong   = 'The longer the password, the more letters, numbers, or special characters it contains, and the less repetitive the password, the more secure it is!';
$lang->admin->safe->noticeGd       = 'Your server does not have GD module installed or enabled FreeType support, you cannot use the Captcha function, Please use it after installation.';

$lang->admin->menuSetting['system']['name']    = 'System';
$lang->admin->menuSetting['system']['desc']    = 'System elements configuration, such as backup, chat, security etc.';
$lang->admin->menuSetting['user']['name']      = 'User';
$lang->admin->menuSetting['user']['desc']      = 'Manage departments,add members and group configuration permissions.';
$lang->admin->menuSetting['switch']['name']    = 'Switch';
$lang->admin->menuSetting['switch']['desc']    = 'Turn on and off some functions of the system.';
$lang->admin->menuSetting['model']['name']     = 'Model';
$lang->admin->menuSetting['model']['desc']     = 'Configure different project management models and project common elements.';
$lang->admin->menuSetting['feature']['name']   = 'Feature';
$lang->admin->menuSetting['feature']['desc']   = 'Configure the elements of the system according to the function menu.';
$lang->admin->menuSetting['template']['name']  = 'Template';
$lang->admin->menuSetting['template']['desc']  = 'Configure the template type and template content of the document.';
$lang->admin->menuSetting['message']['name']   = 'Message';
$lang->admin->menuSetting['message']['desc']   = 'Configure notification paths and customize the actions to be notified.';
$lang->admin->menuSetting['extension']['name'] = 'Extension';
$lang->admin->menuSetting['extension']['desc'] = 'Browse and install plugins.';
$lang->admin->menuSetting['dev']['name']       = 'Develop';
$lang->admin->menuSetting['dev']['desc']       = 'Support for secondary development of the system.';
$lang->admin->menuSetting['convert']['name']   = 'Data Import';
$lang->admin->menuSetting['convert']['desc']   = 'Data import from third-party systems.';
$lang->admin->menuSetting['platform']['name']  = 'DevOps';
$lang->admin->menuSetting['platform']['desc']  = 'Settings of DevOps elements such as resources and environment.';
$lang->admin->menuSetting['ai']['name']        = 'AI Configurations';
$lang->admin->menuSetting['ai']['desc']        = 'Support configuration and management of AI auto-suggestions, AI mini-programs, and large language models.';

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

$lang->admin->ai = new stdclass();
$lang->admin->ai->model        = 'Language Models';
$lang->admin->ai->conversation = 'Conversations';
$lang->admin->ai->prompt       = 'Prompts';

include dirname(__FILE__) . '/menu.php';
