<?php
/**
 * The group module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: en.php 4719 2013-05-03 02:20:28Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->group->common             = 'Berechtigungen';
$lang->group->browse             = 'Gruppen Rechte';
$lang->group->browseAction       = 'Group List';
$lang->group->create             = 'Gruppe hinzufügen';
$lang->group->edit               = 'Bearbeiten';
$lang->group->copy               = 'Kopieren';
$lang->group->delete             = 'Löschen';
$lang->group->manageView         = 'Anzeigen';
$lang->group->managePriv         = 'Assign Privileges';
$lang->group->managePrivByGroup  = 'Assign Privileges by Group';
$lang->group->managePrivByModule = 'Modul Rechte';
$lang->group->byModuleTips       = '(SHIFT/STRG für Multi-Select)';
$lang->group->allTips            = 'After checking this option, the administrator can manage all objects in the system, including objects created later.';
$lang->group->manageMember       = 'Mitglieder';
$lang->group->manageProjectAdmin = "Manage {$lang->projectCommon} Admins";
$lang->group->editManagePriv     = 'Permission Edit';
$lang->group->confirmDelete      = "Do you want to delete '%s'?";
$lang->group->confirmDeleteAB    = 'Do you want to delete this?';
$lang->group->successSaved       = 'Gespeichert!';
$lang->group->errorNotSaved      = 'Fehlgeschlagen. Bitte aktion und Gruppe wählen.';
$lang->group->viewList           = 'Anzeige ist zulässig.';
$lang->group->object             = 'Manage Object';
$lang->group->manageProgram      = 'Manage Program';
$lang->group->manageProject      = 'Manage ' . $lang->projectCommon;
$lang->group->manageExecution    = 'Manage ' . $lang->execution->common;
$lang->group->manageProduct      = 'Manage ' . $lang->productCommon;
$lang->group->programList        = 'Access Program';
$lang->group->productList        = $lang->productCommon . ' sind zugänglich.';
$lang->group->projectList        = $lang->projectCommon . ' sind zugänglich.';
$lang->group->executionList      = "Access {$lang->execution->common}";
$lang->group->dynamic            = 'Access Dynamics';
$lang->group->noticeVisit        = 'Leer bedeutet Zugriff verweigert.';
$lang->group->noticeNoChecked    = 'Please checked privilege!';
$lang->group->noneProgram        = "No Program";
$lang->group->noneProduct        = "No {$lang->productCommon}";
$lang->group->noneExecution      = "No {$lang->execution->common}";
$lang->group->project            = $lang->projectCommon;
$lang->group->group              = 'Group';
$lang->group->more               = 'More';
$lang->group->allCheck           = 'All';
$lang->group->noGroup            = 'No group';
$lang->group->repeat             = "『%s』『%s』exists.Please adjust it and try again.";
$lang->group->noneProject        = 'No ' . $lang->projectCommon;
$lang->group->createPriv         = 'Add Priv';
$lang->group->editPriv           = 'Edit Priv';
$lang->group->deletePriv         = 'Delete Priv';
$lang->group->privName           = 'Priv Name';
$lang->group->privDesc           = 'Priv Desc';
$lang->group->add                = 'Add';
$lang->group->privModuleName     = 'Module Name';
$lang->group->privMethodName     = 'Method Name';
$lang->group->privView           = 'View';
$lang->group->privModule         = 'Module';
$lang->group->repeatPriv         = 'The method name of the same module cannot be the same. Please modify the method name and try again.';
$lang->group->dependPrivTips     = 'Here are the selected permissions in the left Permission Dependency List, which are must-require assigned.';
$lang->group->recommendPrivTips  = 'Here are the recommended permissions in the left Permission Dependency List, which are recommended to be assigned.';
$lang->group->dependPrivsSaveTip = 'Privileges and dependented privileges are saved successfully';

$lang->group->batchActions              = 'Batch Operation';
$lang->group->batchSetDependency        = 'Batch Set Dependency';
$lang->group->batchSetRecommendation    = 'Batch Set Recommendation';
$lang->group->batchDeleteDependency     = 'Batch Delete Dependency';
$lang->group->batchDeleteRecommendation = 'Batch Delete Recommendation';
$lang->group->managePrivPackage         = 'Manage Priv Package';
$lang->group->createPrivPackage         = 'Create Priv Package';
$lang->group->editPrivPackage           = 'Edit Priv Package';
$lang->group->deletePrivPackage         = 'Delete Priv Package';
$lang->group->sortPrivPackages          = 'Sort Priv Package';
$lang->group->addRecommendation         = 'Add Recommendation';
$lang->group->addDependent              = 'Add Dependent';
$lang->group->deleteRecommendation      = 'Delete Recommendation';
$lang->group->deleteDependent           = 'Delete Dependent';
$lang->group->selectedPrivs             = 'Selected Privilege: %s';
$lang->group->selectModule              = 'Select Module';
$lang->group->recommendPrivs            = 'Recommended Privs';
$lang->group->dependentPrivs            = 'Dependented Privs';
$lang->group->addRelation               = 'Add Relation';
$lang->group->deleteRelation            = 'Delete Relation';
$lang->group->batchDeleteRelation       = 'Batch Delete Relation';
$lang->group->batchChangePackage        = 'Batch Change Priv Package';

$lang->group->id         = 'ID';
$lang->group->name       = 'Name';
$lang->group->desc       = 'Beschreibung';
$lang->group->role       = 'Rolle';
$lang->group->acl        = 'Rechte';
$lang->group->users      = 'Benutzer';
$lang->group->module     = 'Module';
$lang->group->method     = 'Methoden';
$lang->group->priv       = 'Gruppe';
$lang->group->option     = 'Option';
$lang->group->inside     = 'Gruppenbenutzer';
$lang->group->outside    = 'Andere Benutzer';
$lang->group->limited    = 'Limited Users';
$lang->group->general    = 'General';
$lang->group->all        = 'Alle';
$lang->group->config     = 'Config';
$lang->group->unassigned = 'Unassigned';
$lang->group->view       = 'View';
$lang->group->other      = 'Other';

if(!isset($lang->privpackage)) $lang->privpackage = new stdclass();
$lang->privpackage->common = 'Priv Package';
$lang->privpackage->id     = 'ID';
$lang->privpackage->name   = 'Priv Package Name';
$lang->privpackage->module = 'Module';
$lang->privpackage->desc   = 'Priv Package Desc';
$lang->privpackage->belong = 'Priv Package';

$lang->group->copyOptions['copyPriv'] = 'Rechte kopieren';
$lang->group->copyOptions['copyUser'] = 'Benutzer kopieren';

$lang->group->versions['']            = 'Verlauf';
$lang->group->versions['21.4']        = 'ZenTao21.4';
$lang->group->versions['21.2']        = 'ZenTao21.2';
$lang->group->versions['21.0']        = 'ZenTao21.0';
$lang->group->versions['20.8']        = 'ZenTao20.8';
$lang->group->versions['20.7']        = 'ZenTao20.7';
$lang->group->versions['20.5']        = 'ZenTao20.5';
$lang->group->versions['20.4']        = 'ZenTao20.4';
$lang->group->versions['20.2']        = 'ZenTao20.2';
$lang->group->versions['20.0.1']      = 'ZenTao20.0.1';
$lang->group->versions['20.0']        = 'ZenTao20.0';
$lang->group->versions['20.0.beta1']  = 'ZenTao20.0.beta1';
$lang->group->versions['18_11']       = 'ZenTao18.11';
$lang->group->versions['18_9']        = 'ZenTao18.9';
$lang->group->versions['18_8']        = 'ZenTao18.8';
$lang->group->versions['18_7']        = 'ZenTao18.7';
$lang->group->versions['18_6']        = 'ZenTao18.6';
$lang->group->versions['18_4_alpha1'] = 'ZenTao18.4.alpha1';
$lang->group->versions['18_3']        = 'ZenTao18.3';
$lang->group->versions['18_2']        = 'ZenTao18.2';
$lang->group->versions['18_1']        = 'ZenTao18.1';
$lang->group->versions['18_0']        = 'ZenTao18.0';
$lang->group->versions['18_0_beta3']  = 'ZenTao18.0.beta3';
$lang->group->versions['18_0_beta2']  = 'ZenTao18.0.beta2';
$lang->group->versions['18_0_beta1']  = 'ZenTao18.0.beta1';
$lang->group->versions['17_6_2']      = 'ZenTao17.6.2';
$lang->group->versions['17_6']        = 'ZenTao17.6';
$lang->group->versions['17_5']        = 'ZenTao17.5';
$lang->group->versions['17_4']        = 'ZenTao17.4';
$lang->group->versions['17_3']        = 'ZenTao17.3';
$lang->group->versions['17_2']        = 'ZenTao17.2';
$lang->group->versions['17_1']        = 'ZenTao17.1';
$lang->group->versions['17_0_beta2']  = 'ZenTao17.0.beta2';
$lang->group->versions['17_0_beta1']  = 'ZenTao17.0.beta1';
$lang->group->versions['16_5_beta1']  = 'ZenTao16.5.beta1';
$lang->group->versions['16_4']        = 'ZenTao16.4';
$lang->group->versions['16_3']        = 'ZenTao16.3';
$lang->group->versions['16_2']        = 'ZenTao16.2';
$lang->group->versions['16_1']        = 'ZenTao16.1';
$lang->group->versions['16_0']        = 'ZenTao16.0';
$lang->group->versions['16_0_beta1']  = 'ZenTao16.0.beta1';
$lang->group->versions['15_8']        = 'ZenTao15.8';
$lang->group->versions['15_7']        = 'ZenTao15.7';
$lang->group->versions['15_0_rc1']    = 'ZenTao15.0.rc1';
$lang->group->versions['12_5']        = 'ZenTao12.5';
$lang->group->versions['12_3']        = 'ZenTao12.3';
$lang->group->versions['11_6_2']      = 'ZenTao11.6.2';
$lang->group->versions['10_6']        = 'ZenTao10.6';
$lang->group->versions['10_1']        = 'ZenTao10.1';
$lang->group->versions['10_0_alpha']  = 'ZenTao10.0.alpha';
$lang->group->versions['9_8']         = 'ZenTao9.8';
$lang->group->versions['9_6']         = 'ZenTao9.6';
$lang->group->versions['9_5']         = 'ZenTao9.5';
$lang->group->versions['9_2']         = 'ZenTao9.2';
$lang->group->versions['9_1']         = 'ZenTao9.1';
$lang->group->versions['9_0']         = 'ZenTao9.0';
$lang->group->versions['8_4']         = 'ZenTao8.4';
$lang->group->versions['8_3']         = 'ZenTao8.3';
$lang->group->versions['8_2_beta']    = 'ZenTao8.2.beta';
$lang->group->versions['8_0_1']       = 'ZenTao8.0.1';
$lang->group->versions['8_0']         = 'ZenTao8.0';
$lang->group->versions['7_4_beta']    = 'ZenTao7.4.beta';
$lang->group->versions['7_3']         = 'ZenTao7.3';
$lang->group->versions['7_2']         = 'ZenTao7.2';
$lang->group->versions['7_1']         = 'ZenTao7.1';
$lang->group->versions['6_4']         = 'ZenTao6.4';
$lang->group->versions['6_3']         = 'ZenTao6.3';
$lang->group->versions['6_2']         = 'ZenTao6.2';
$lang->group->versions['6_1']         = 'ZenTao6.1';
$lang->group->versions['5_3']         = 'ZenTao5.3';
$lang->group->versions['5_1']         = 'ZenTao5.1';
$lang->group->versions['5_0_beta2']   = 'ZenTao5.0.beta2';
$lang->group->versions['5_0_beta1']   = 'ZenTao5.0.beta1';
$lang->group->versions['4_3_beta']    = 'ZenTao4.3.beta';
$lang->group->versions['4_2_beta']    = 'ZenTao4.2.beta';
$lang->group->versions['4_1']         = 'ZenTao4.1';
$lang->group->versions['4_0_1']       = 'ZenTao4.0.1';
$lang->group->versions['4_0']         = 'ZenTao4.0';
$lang->group->versions['4_0_beta2']   = 'ZenTao4.0.beta2';
$lang->group->versions['4_0_beta1']   = 'ZenTao4.0.beta1';
$lang->group->versions['3_3']         = 'ZenTao3.3';
$lang->group->versions['3_2_1']       = 'ZenTao3.2.1';
$lang->group->versions['3_2']         = 'ZenTao3.2';
$lang->group->versions['3_1']         = 'ZenTao3.1';
$lang->group->versions['3_0_beta2']   = 'ZenTao3.0.beta2';
$lang->group->versions['3_0_beta1']   = 'ZenTao3.0.beta1';
$lang->group->versions['2_4']         = 'ZenTao2.4';
$lang->group->versions['2_3']         = 'ZenTao2.3';
$lang->group->versions['2_2']         = 'ZenTao2.2';
$lang->group->versions['2_1']         = 'ZenTao2.1';
$lang->group->versions['2_0']         = 'ZenTao2.0';
$lang->group->versions['1_5']         = 'ZenTao1.5';
$lang->group->versions['1_4']         = 'ZenTao1.4';
$lang->group->versions['1_3']         = 'ZenTao1.3';
$lang->group->versions['1_2']         = 'ZenTao1.2';
$lang->group->versions['1_1']         = 'ZenTao1.1';
$lang->group->versions['1_0_1']       = 'ZenTao1.0.1';

$lang->group->package = new stdclass();
$lang->group->package->browse                = 'Browse';
$lang->group->package->manage                = 'Manage';
$lang->group->package->delete                = 'Delete';
$lang->group->package->other                 = 'Other';
$lang->group->package->browseTodo            = 'Browse Todo';
$lang->group->package->manageTodo            = 'Manage Todo';
$lang->group->package->deleteTodo            = 'Delete Todo';
$lang->group->package->importTodo            = 'Import Todo';
$lang->group->package->manageContact         = 'Manage Contact';
$lang->group->package->profile               = 'Profile';
$lang->group->package->preference            = 'Preference';
$lang->group->package->browseProgram         = 'Browse Program';
$lang->group->package->manageProgram         = 'Manage Program';
$lang->group->package->deleteProgram         = 'Delete Program';
$lang->group->package->invest                = 'Invest';
$lang->group->package->accessible            = 'Accessible';
$lang->group->package->whitelist             = 'Whitelist';
$lang->group->package->stakeholder           = 'Stakeholder';
$lang->group->package->my                    = 'My';
$lang->group->package->browseProduct         = 'Browse Product';
$lang->group->package->manageProductLine     = 'Manage Product Line';
$lang->group->package->createProduct         = 'Create Product';
$lang->group->package->importProduct         = 'Import Product';
$lang->group->package->productWhitelist      = 'Product Whitelist';
$lang->group->package->branch                = 'Branch';
$lang->group->package->browseStory           = 'Browse ' . $lang->SRCommon;
$lang->group->package->manageStory           = 'Manage ' . $lang->SRCommon;
$lang->group->package->importStory           = 'Import ' . $lang->SRCommon;
$lang->group->package->deleteStory           = 'Delete ' . $lang->SRCommon;
$lang->group->package->reviewStory           = 'Review ' . $lang->SRCommon;
$lang->group->package->deleteProduct         = 'Delete Product';
$lang->group->package->browseEpic            = 'Browse Epic';
$lang->group->package->manageEpic            = 'Manage Epic';
$lang->group->package->importEpic            = 'Import Epic';
$lang->group->package->deleteEpic            = 'Delete Epic';
$lang->group->package->reviewEpic            = 'Review Epic';
$lang->group->package->browseRequirement     = 'Browse Requirement';
$lang->group->package->manageRequirement     = 'Manage Requirement';
$lang->group->package->importRequirement     = 'Import Requirement';
$lang->group->package->deleteRequirement     = 'Delete Requirement';
$lang->group->package->reviewRequirement     = 'Review Requirement';
$lang->group->package->browseProductPlan     = 'Browse Product Plan';
$lang->group->package->manageProductPlan     = 'Manage Product Plan';
$lang->group->package->deleteProductPlan     = 'Delete Product Plan';
$lang->group->package->browseRelease         = 'Browse Release';
$lang->group->package->manageRelease         = 'Manage Release';
$lang->group->package->importRelease         = 'Import Release';
$lang->group->package->deleteRelease         = 'Delete Release';
$lang->group->package->releaseNotify         = 'Release Notify';
$lang->group->package->projectPlan           = 'Project Plan';
$lang->group->package->manageProjectStory    = 'Manage Project ' . $lang->SRCommon;
$lang->group->package->importProjectStory    = 'Import Project ' . $lang->SRCommon;
$lang->group->package->browseProject         = 'Browse Project';
$lang->group->package->manageProject         = 'Manage Project';
$lang->group->package->importProject         = 'Import Project';
$lang->group->package->projectTeam           = 'Project Team';
$lang->group->package->deleteProject         = 'Delete Project';
$lang->group->package->projectWhitelist      = 'Project Whitelist';
$lang->group->package->browseExecution       = 'Browse Execution';
$lang->group->package->manageExecution       = 'Manage Execution';
$lang->group->package->deleteExecution       = 'Delete Execution';
$lang->group->package->importExecution       = 'Import Execution';
$lang->group->package->executionWhitelist    = 'Execution Whitelist';
$lang->group->package->executionGantt        = 'Execution Gantt';
$lang->group->package->browseTask            = 'Browse Task';
$lang->group->package->manageTask            = 'Manage Task';
$lang->group->package->deleteTask            = 'Delete Task';
$lang->group->package->importTask            = 'Import Task';
$lang->group->package->executionTeam         = 'Execution Team';
$lang->group->package->kanban                = 'Kanban';
$lang->group->package->groupView             = 'Group View';
$lang->group->package->burndown              = 'Burndown';
$lang->group->package->cfd                   = 'Cfd';
$lang->group->package->manageExecutionStory  = 'Manage Execution ' . $lang->SRCommon;
$lang->group->package->manageExecutionEffort = 'Manage Execution Effort';
$lang->group->package->manageBuild           = 'Manage Build';
$lang->group->package->deleteBuild           = 'Delete Build';
$lang->group->package->browseStoryLib        = 'Browse Story Lib';
$lang->group->package->manageStoryLib        = 'Manage Story Lib';
$lang->group->package->deleteStoryLib        = 'Delete Story Lib';
$lang->group->package->reviewStoryLib        = 'Review Story Lib';
$lang->group->package->browseIssueLib        = 'Browse Issue Lib';
$lang->group->package->manageIssueLib        = 'Manage Issue Lib';
$lang->group->package->deleteIssueLib        = 'Delete Issue Lib';
$lang->group->package->reviewIssue           = 'Review Issue';
$lang->group->package->browseRiskLib         = 'Browse Risk Lib';
$lang->group->package->manageRiskLib         = 'Manage Risk Lib';
$lang->group->package->deleteRiskLib         = 'Delete Risk Lib';
$lang->group->package->reivewRisk            = 'Reivew Risk';
$lang->group->package->browseOpportunityLib  = 'Browse Opportunity Lib';
$lang->group->package->manageOpportunityLib  = 'Manage Opportunity Lib';
$lang->group->package->deleteOpportunityLib  = 'Delete Opportunity Lib';
$lang->group->package->reviewOpportunity     = 'Review Opportunity';
$lang->group->package->browsePracticeLib     = 'Browse Practice Lib';
$lang->group->package->managePracticeLib     = 'Manage Practice Lib';
$lang->group->package->deletePracticeLib     = 'Delete Practice Lib';
$lang->group->package->reviewPractice        = 'Review Practice';
$lang->group->package->browseComponentLib    = 'Browse Component Lib';
$lang->group->package->manageComponentLib    = 'Manage Component Lib';
$lang->group->package->deleteComponentLib    = 'Delete Component Lib';
$lang->group->package->reviewComponent       = 'Review Component';
$lang->group->package->browseCaseLib         = 'Browse Case Lib';
$lang->group->package->manageCaseLib         = 'Manage Case Lib';
$lang->group->package->deleteCaseLib         = 'Delete Case Lib';
$lang->group->package->importCaseLib         = 'Import Case Lib';
$lang->group->package->importToCaseLib       = 'Import To Case Lib';
$lang->group->package->officeApproval        = 'Office Approval';
$lang->group->package->attend                = 'Attend';
$lang->group->package->officeSetting         = 'Office Setting';
$lang->group->package->dataPermission        = 'Data Permission';
$lang->group->package->leave                 = 'Leave';
$lang->group->package->makeup                = 'Makeup';
$lang->group->package->overtime              = 'Overtime';
$lang->group->package->lieu                  = 'Lieu';
$lang->group->package->holiday               = 'Holiday';
$lang->group->package->exportOffice          = 'Export Office';
$lang->group->package->browseFeedback        = 'Browse Feedback';
$lang->group->package->browseLiteFeedback    = $lang->group->package->browseFeedback;
$lang->group->package->manageFeedback        = 'Manage Feedback';
$lang->group->package->importFeedback        = 'Import Feedback';
$lang->group->package->handleFeedback        = 'Handle Feedback';
$lang->group->package->faq                   = 'Faq';
$lang->group->package->browseTicket          = 'Browse Ticket';
$lang->group->package->manageTicket          = 'Manage Ticket';
$lang->group->package->importTicket          = 'Import Ticket';
$lang->group->package->deleteFeedback        = 'Delete Feedback';
$lang->group->package->deleteTIcket          = 'Delete T Icket';
$lang->group->package->feedbackPriv          = 'Feedback Priv';
$lang->group->package->browseCourse          = 'Browse Course';
$lang->group->package->manageTrainCourse     = 'Manage Train Course';
$lang->group->package->system                = 'Platform';
$lang->group->package->host                  = 'Host';
$lang->group->package->serverRoom            = 'Server Room';
$lang->group->package->account               = 'Account';
$lang->group->package->domain                = 'Domain';
$lang->group->package->service               = 'Service';
$lang->group->package->deployPlan            = 'Online';
$lang->group->package->deployScope           = 'Deploy Scope';
$lang->group->package->deployStep            = 'Deploy Step';
$lang->group->package->deployCase            = 'Deploy Case';
$lang->group->package->qaIndex               = 'Qa Index';
$lang->group->package->browseBug             = 'Browse Bug';
$lang->group->package->manageBug             = 'Manage Bug';
$lang->group->package->deleteBug             = 'Delete Bug';
$lang->group->package->importBug             = 'Import Bug';
$lang->group->package->browseCase            = 'Browse Case';
$lang->group->package->manageCase            = 'Manage Case';
$lang->group->package->importCase            = 'Import Case';
$lang->group->package->deleteCase            = 'Delete Case';
$lang->group->package->reviewCase            = 'Review Case';
$lang->group->package->browseTesttask        = 'Browse Testtask';
$lang->group->package->manageTesttask        = 'Manage Testtask';
$lang->group->package->deleteTesttask        = 'Delete Testtask';
$lang->group->package->unitTest              = 'Unit Test';
$lang->group->package->testsuite             = 'Testsuite';
$lang->group->package->manageTestsuite       = 'Manage Testsuite';
$lang->group->package->deleteTestsuite       = 'Delete Testsuite';
$lang->group->package->browseTestreport      = 'Browse Testreport';
$lang->group->package->manageTestreport      = 'Manage Testreport';
$lang->group->package->deleteTestreport      = 'Delete Testreport';
$lang->group->package->importTestreport      = 'Import Testreport';
$lang->group->package->autotestInstruction   = 'Instruction';
$lang->group->package->browseZAHost          = 'Browse Z A Host';
$lang->group->package->manageZAHost          = 'Manage Z A Host';
$lang->group->package->deleteZAHost          = 'Delete Z A Host';
$lang->group->package->image                 = 'Image';
$lang->group->package->browseZANode          = 'Browse Z A Node';
$lang->group->package->manageZANode          = 'Manage Z A Node';
$lang->group->package->importZANode          = 'Import Z A Node';
$lang->group->package->snapshot              = 'Snapshot';
$lang->group->package->companyTeam           = 'Company Team';
$lang->group->package->companyCalendar       = 'Company Calendar';
$lang->group->package->companyEffort         = 'Company Effort';
$lang->group->package->companyDynamic        = 'Company Dynamic';
$lang->group->package->companySetting        = 'Company Setting';
$lang->group->package->companyDataPermission = 'Company Data Permission';
$lang->group->package->programPlan           = 'Program Plan';
$lang->group->package->browseDesign          = 'Browse Design';
$lang->group->package->manageDesign          = 'Manage Design';
$lang->group->package->deleteDesign          = 'Delete Design';
$lang->group->package->manageReview          = 'Manage Review';
$lang->group->package->manageIssue           = 'Manage Issue';
$lang->group->package->projectSetting        = 'Project Setting';
$lang->group->package->browseProjectRelease  = 'Browse Project Release';
$lang->group->package->projectWeekly         = 'Project Weekly';
$lang->group->package->projectMilestone      = 'Project Milestone';
$lang->group->package->researchPlan          = 'Research Plan';
$lang->group->package->researchReport        = 'Research Report';
$lang->group->package->budget                = 'Budget';
$lang->group->package->workEstimation        = 'Work Estimation';
$lang->group->package->durationEstimation    = 'Duration Estimation';
$lang->group->package->browseIssue           = 'Browse Issue';
$lang->group->package->manageIssue           = 'Manage Issue';
$lang->group->package->importIssue           = 'Import Issue';
$lang->group->package->deleteIssue           = 'Delete Issue';
$lang->group->package->importIssueLib        = 'Import Issue Lib';
$lang->group->package->browseRisk            = 'Browse Risk';
$lang->group->package->manageRisk            = 'Manage Risk';
$lang->group->package->importRisk            = 'Import Risk';
$lang->group->package->deleteRisk            = 'Delete Risk';
$lang->group->package->importRiskLib         = 'Import Risk Lib';
$lang->group->package->browseOpportunity     = 'Browse Opportunity';
$lang->group->package->manageOpportunity     = 'Manage Opportunity';
$lang->group->package->deleteOpportunity     = 'Delete Opportunity';
$lang->group->package->importOpportunityLib  = 'Import Opportunity Lib';
$lang->group->package->importOpportunity     = 'Import Opportunity';
$lang->group->package->pssp                  = 'Pssp';
$lang->group->package->manageAuditPlan       = 'Manage Audit Plan';
$lang->group->package->nc                    = 'Nc';
$lang->group->package->meeting               = 'Meeting';
$lang->group->package->trainPlan             = 'Train Plan';
$lang->group->package->gapAnalysis           = 'Gap Analysis';
$lang->group->package->workflowField         = 'Workflow Field';
$lang->group->package->workflowAction        = 'Workflow Action';
$lang->group->package->workflowLayout        = 'Workflow Layout';
$lang->group->package->workflowCondition     = 'Workflow Condition';
$lang->group->package->workflowLinkage       = 'Workflow Linkage';
$lang->group->package->workflowHook          = 'Workflow Hook';
$lang->group->package->workflowLabel         = 'Workflow Label';
$lang->group->package->workflowReport        = 'Workflow Report';
$lang->group->package->workflowDatasource    = 'Workflow Datasource';
$lang->group->package->workflowRule          = 'Workflow Rule';
$lang->group->package->workflowGroup         = 'Workflow Group';
$lang->group->package->workflow              = 'Workflow';
$lang->group->package->rule                  = 'Rule';
$lang->group->package->downloadCode          = 'Download Code';
$lang->group->package->dev                   = 'Dev';
$lang->group->package->browseCodeIssue       = 'Review List';
$lang->group->package->editor                = 'Editor';
$lang->group->package->serverLink            = 'Server Link';
$lang->group->package->browseMR              = 'MR List';
$lang->group->package->managePriv            = 'Manage Priv';
$lang->group->package->dept                  = 'Dept';
$lang->group->package->group                 = 'Group';
$lang->group->package->user                  = 'User';
$lang->group->package->extension             = 'Extension';
$lang->group->package->message               = 'Message';
$lang->group->package->mail                  = 'Mail';
$lang->group->package->webhook               = 'Webhook';
$lang->group->package->gitlab                = 'Gitlab';
$lang->group->package->sms                   = 'Sms';
$lang->group->package->gogs                  = 'Gogs';
$lang->group->package->gitea                 = 'Gitea';
$lang->group->package->sonarqube             = 'Sonarqube';
$lang->group->package->repoRules             = 'Repo Rules';
$lang->group->package->browseJob             = 'PipeLine List';
$lang->group->package->manageJob             = 'Manage PipeLine';
$lang->group->package->manageMR              = 'Manage MR';
$lang->group->package->backup                = 'Backup';
$lang->group->package->trash                 = 'Trash';
$lang->group->package->security              = 'Security';
$lang->group->package->cron                  = 'Cron';
$lang->group->package->ldap                  = 'Ldap';
$lang->group->package->chat                  = 'Chat';
$lang->group->package->jenkins               = 'Jenkins';
$lang->group->package->systemSetting         = 'System Setting';
$lang->group->package->search                = 'Search';
$lang->group->package->comment               = 'Comment';
$lang->group->package->module                = 'Module';
$lang->group->package->file                  = 'File';
$lang->group->package->commonEffort          = 'Common Effort';
$lang->group->package->docTemplate           = 'Doc Template';
$lang->group->package->importStoryLib        = 'Import Story Lib';
$lang->group->package->projectStakeholder    = 'Project Stakeholder';
$lang->group->package->projectBuild          = 'Project Build';
$lang->group->package->importCaseLib         = 'Import Case Lib';
$lang->group->package->commonSetting         = 'Common Setting';
$lang->group->package->stageSetting          = 'Stage Setting';
$lang->group->package->deliverable           = 'Deliverable Setting';
$lang->group->package->classify              = 'Classify';
$lang->group->package->cmcl                  = 'Cmcl';
$lang->group->package->auditcl               = 'Auditcl';
$lang->group->package->reviewcl              = 'Reviewcl';
$lang->group->package->process               = 'Process';
$lang->group->package->activity              = 'Activity';
$lang->group->package->zoutput               = 'Zoutput';
$lang->group->package->custom                = 'Custom';
$lang->group->package->approvalflow          = 'Approval flow';
$lang->group->package->usercl                = 'Usercl';
$lang->group->package->meetingroom           = 'Meetingroom';
$lang->group->package->sqlBuilder            = 'Sql Builder';
$lang->group->package->designSetting         = 'Design Setting';
$lang->group->package->kanbanSpace           = 'Kanban Space';
$lang->group->package->deleteKanbanSpace     = 'Delete Kanban Space';
$lang->group->package->browseKanban          = 'Browse Kanban';
$lang->group->package->manageKanban          = 'Manage Kanban';
$lang->group->package->deleteKanban          = 'Delete Kanban';
$lang->group->package->deleteZANode          = 'Delete Z A Node';
$lang->group->package->autotesting           = 'Autotesting';
$lang->group->package->executionTesting      = 'Execution Testing';
$lang->group->package->manageEffort          = 'Manage Effort';
$lang->group->package->projectTesting        = 'Project Testing';
$lang->group->package->track                 = 'Track';
$lang->group->package->workflowRelation      = 'Workflow Relation';
$lang->group->package->template              = 'Template';
$lang->group->package->table                 = 'Table';
$lang->group->package->automation            = 'Automation';
$lang->group->package->git                   = 'Git';
$lang->group->package->subversion            = 'Subversion';
$lang->group->package->ping                  = 'Ping';
$lang->group->package->review                = 'Review';
$lang->group->package->manageProjectRelease  = 'Manage Project Release';
$lang->group->package->deleteProjectRelease  = 'Delete Project Release';
$lang->group->package->importProjectRelease  = 'Import Project Release';
$lang->group->package->projectReleaseNotify  = 'Project Release Notify';
$lang->group->package->gantt                 = 'Gantt';
$lang->group->package->projectRelation       = 'Project Relation';
$lang->group->package->executionRelation     = 'Execution Relation';
$lang->group->package->browseBuild           = 'Browse Build';
$lang->group->package->browseExecutionStory  = 'Browse Execution ' . $lang->SRCommon;
$lang->group->package->manageCard            = 'Manage Card';
$lang->group->package->browseProjectStory    = 'Browse Project ' . $lang->SRCommon;
$lang->group->package->chckAuditPlan         = 'Chck Audit Plan';
$lang->group->package->reviewAssess          = 'Review Assess';
$lang->group->package->reviewAudit           = 'Review Audit';
$lang->group->package->dimension             = 'Dimension';
$lang->group->package->browseScreen          = 'Browse Screen';
$lang->group->package->manageScreen          = 'Manage Screen';
$lang->group->package->deleteScreen          = 'Delete Screen';
$lang->group->package->screenDataPermission  = 'Screen Data Permission';
$lang->group->package->browsePivot           = 'Browse Pivot';
$lang->group->package->designPivot           = 'Design Pivot';
$lang->group->package->exportPivot           = 'Export Pivot';
$lang->group->package->pivotDataPermission   = 'Pivot Data Permission';
$lang->group->package->browseChart           = 'Browse Chart';
$lang->group->package->designChart           = 'Design Chart';
$lang->group->package->exportChart           = 'Export Chart';
$lang->group->package->browseDataview        = 'Browse Dataview';
$lang->group->package->manageDataview        = 'Manage Dataview';
$lang->group->package->deleteDataview        = 'Delete Dataview';
$lang->group->package->browseMetric          = 'Browse Metric';
$lang->group->package->manageMetric          = 'Manage Metric';
$lang->group->package->browseDoc             = 'Browse Doc';
$lang->group->package->manageDoc             = 'Manage Doc';
$lang->group->package->deleteDoc             = 'Delete Doc';
$lang->group->package->exportDoc             = 'Export Doc';
$lang->group->package->browseDoctemplate     = 'Browse Doc Template';
$lang->group->package->manageDoctemplate     = 'Manage Doc Template';
$lang->group->package->deleteDoctemplate     = 'Delete Doc Template';
$lang->group->package->browseAPI             = 'Browse API';
$lang->group->package->manageAPI             = 'Manage API';
$lang->group->package->exportAPI             = 'Export API';
$lang->group->package->deleteAPI             = 'Delete API';
$lang->group->package->callAPI               = 'Call API';
$lang->group->package->scene                 = 'Scene';
$lang->group->package->executionTree         = 'Execution Tree';
$lang->group->package->taskEffort            = 'Task Effort';
$lang->group->package->taskCalendar          = 'Task Calendar';
$lang->group->package->code                  = 'Code';
$lang->group->package->repo                  = 'Repository';
$lang->group->package->browseDemandPool      = 'Browse Demand Pool';
$lang->group->package->browseCharter         = 'Browse Charter';
$lang->group->package->manageDemandPool      = 'Manage Demand Pool';
$lang->group->package->manageCharter         = 'Manage Charter';
$lang->group->package->reviewCharter         = 'Review Charter';
$lang->group->package->browseDemand          = 'Browse Demand';
$lang->group->package->manageDemand          = 'Manage Demand';
$lang->group->package->reviewDemand          = 'Review Demand';
$lang->group->package->importDemand          = 'Import Demand';
$lang->group->package->deleteDemand          = 'Delete Demand';
$lang->group->package->admin                 = 'Admin';
$lang->group->package->browseRoadmap         = 'Browse Roadmap';
$lang->group->package->manageRoadmap         = 'Manage Roadmap';
$lang->group->package->deleteRoadmap         = 'Delete Roadmap';
$lang->group->package->deleteDemandPool      = 'Delete Demand Pool';
$lang->group->package->exportDatatable       = 'Export Datatable';
$lang->group->package->ai                    = 'AI';
$lang->group->package->aiChatting            = 'AI Chat';
$lang->group->package->executePrompt         = 'Execute Prompts';
$lang->group->package->manageLLM             = 'Manage Models';
$lang->group->package->browsePrompt          = 'Browse Prompts';
$lang->group->package->managePrompt          = 'Manage and Design Prompts';
$lang->group->package->publishPrompt         = 'Publish and Unpublish Prompts';
$lang->group->package->deletePrompt          = 'Delete Prompts';
$lang->group->package->manageMiniProgram     = 'Manage and Design Mini Programs';
$lang->group->package->browseMiniProgram     = 'Browse Mini Programs';
$lang->group->package->miniProgramSquare     = 'AI Mini Program Square';
$lang->group->package->publishMiniProgram    = 'Publish and Unpublish AI Mini Programs';
$lang->group->package->deleteMiniProgram     = 'Delete AI Mini Programs';
$lang->group->package->impAndExpMiniProgram  = 'Import and Export Mini Programs';
$lang->group->package->aiAssistant           = 'AI Assistant';
$lang->group->package->dashboard             = 'Monitor';
$lang->group->package->resource              = 'Resource';
$lang->group->package->manageServiceProvider = 'Provider';
$lang->group->package->manageCity            = 'City';
$lang->group->package->manageCPU             = 'Cpu Brand';
$lang->group->package->manageOS              = 'OS Version';
$lang->group->package->browseRepo            = 'Repo List';
$lang->group->package->manageRepo            = 'Manage Repository';
$lang->group->package->deleteRepo            = 'Delete Repository';
$lang->group->package->browseCode            = 'Code View';
$lang->group->package->manageCode            = 'Manage Code';
$lang->group->package->CodeIssule            = 'Review';
$lang->group->package->manageCodeIssue       = 'Manage Review';
$lang->group->package->deleteCodeIssue       = 'Delete Review';
$lang->group->package->deleteMR              = 'Delete MR';
$lang->group->package->deleteJob             = 'Delete PipeLine';
$lang->group->package->browseApplication     = 'Service List';
$lang->group->package->mangeApplication      = 'Manage Service';
$lang->group->package->trainPracticeLib      = 'Practice Library';
$lang->group->package->application           = 'Manage Application';
$lang->group->package->component             = 'Component';
$lang->group->package->browseRule            = 'Browse Rule';
$lang->group->package->manageRule            = 'Manage Rule';
$lang->group->package->executionDeliverable  = 'Execution Deliverable';
$lang->group->package->projectDeliverable    = 'Project Deliverable';
$lang->group->package->projectTemplate       = 'Project Template';

include (dirname(__FILE__) . '/resource.php');
