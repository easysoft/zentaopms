<?php

/**
 * The ai module en lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
$lang->ai->common = 'AI Configuration';

/* Definitions of table columns, used to sprintf error messages to dao::$errors. */
$lang->prompt  = new stdclass();
$lang->prompt->name             = 'Name';
$lang->prompt->desc             = 'Description';
$lang->prompt->model            = 'Default Model';
$lang->prompt->module           = 'Module';
$lang->prompt->source           = 'Data Source';
$lang->prompt->targetForm       = 'Target Form';
$lang->prompt->purpose          = 'Purpose';
$lang->prompt->elaboration      = 'Elaboration';
$lang->prompt->role             = 'Role';
$lang->prompt->characterization = 'Characterization';
$lang->prompt->status           = 'Status';
$lang->prompt->createdBy        = 'Created By';
$lang->prompt->createdDate      = 'Created Date';
$lang->prompt->editedBy         = 'Edited By';
$lang->prompt->editedDate       = 'Edited Date';
$lang->prompt->deleted          = 'Deleted';

/* Lang for privs, keys are paired with privlang items. */
$lang->ai->modelBrowse             = 'Browse Language Models';
$lang->ai->modelView               = 'View Language Model';
$lang->ai->modelCreate             = 'Create Language Model';
$lang->ai->modelEdit               = 'Edit Language Model';
$lang->ai->modelEnable             = 'Enable Language Model';
$lang->ai->modelDisable            = 'Disable Language Model';
$lang->ai->modelDelete             = 'Delete Language Model';
$lang->ai->modelTestConnection     = 'Test Model Connection';
$lang->ai->promptCreate            = 'Create ZenTao Agent';
$lang->ai->promptEdit              = 'Edit ZenTao Agent';
$lang->ai->promptDelete            = 'Delete ZenTao Agent';
$lang->ai->promptAssignRole        = 'Assign ZenTao Agent Role';
$lang->ai->promptSelectDataSource  = 'Select ZenTao Agent Data';
$lang->ai->promptSetPurpose        = 'Set ZenTao Agent Purpose';
$lang->ai->promptSetTargetForm     = 'Set ZenTao Agent Target Form';
$lang->ai->promptFinalize          = 'Finalize ZenTao Agent';
$lang->ai->promptAudit             = 'Audit ZenTao Agent';
$lang->ai->promptPublish           = 'Publish ZenTao Agent';
$lang->ai->promptUnpublish         = 'Unpublish';
$lang->ai->promptBrowse            = 'Browse ZenTao Agents';
$lang->ai->promptView              = 'View ZenTao Agent';
$lang->ai->promptExecute           = 'Execute ZenTao Agent';
$lang->ai->promptExecutionReset    = 'Reset Execution';
$lang->ai->roleTemplates           = 'Manage Role Templates';
$lang->ai->chat                    = 'Chat';
$lang->ai->createMiniProgram       = 'Create General Agent';
$lang->ai->editMiniProgram         = 'Edit General Agent';
$lang->ai->configuredMiniProgram   = 'Configured General Agent';
$lang->ai->testMiniProgram         = 'Test General Agent';
$lang->ai->miniProgramList         = 'Browse General Agent List';
$lang->ai->miniProgramView         = 'View General Agent Details';
$lang->ai->publishMiniProgram      = 'Publish General Agent';
$lang->ai->unpublishMiniProgram    = 'Disable General Agent';
$lang->ai->publishSuccess          = 'Publish Success';
$lang->ai->unpublishSuccess        = 'Unpublish Success';
$lang->ai->deleteMiniProgram       = 'Delete General Agent';
$lang->ai->exportMiniProgram       = 'Export General Agent';
$lang->ai->importMiniProgram       = 'Import General Agent';
$lang->ai->editMiniProgramCategory = 'Manage group';
$lang->ai->assistants              = 'Browse AI Assistants';
$lang->ai->assistantView           = 'View AI Assistant';
$lang->ai->assistantCreate         = 'Create AI Assistant';
$lang->ai->assistantEdit           = 'Edit AI Assistant';
$lang->ai->assistantPublish        = 'Publish AI Assistant';
$lang->ai->assistantWithdraw       = 'Withdraw AI Assistant';
$lang->ai->assistantDelete         = 'Delete AI Assistant';

$lang->ai->name                   = 'Name';
$lang->ai->store                  = 'Store';
$lang->ai->export                 = 'Export';
$lang->ai->import                 = 'Import';
$lang->ai->saveFail               = 'Save failed';
$lang->ai->installPackage         = 'Installation package';
$lang->ai->toPublish              = 'Publish after installation';
$lang->ai->toZentaoStoreAIPage    = 'Click to jump to Zentao official app store general agents page.';
$lang->ai->exitManage             = 'Exit Management';

$lang->ai->chatPlaceholderMessage = 'Hi, I\'m Adao, your AI assistant at ZenTao!';
$lang->ai->chatPlaceholderInput   = 'type here...';
$lang->ai->chatSystemMessage      = 'You\'re Adao, the AI assistant and mascot of ZenTao. You can answer questions and chat with users. You\'re currently within the ZenTao project management software.';
$lang->ai->chatSend               = 'Send';
$lang->ai->chatReset              = 'Reset';
$lang->ai->chatNoResponse         = 'Something went wrong, <a id="retry" class="text-blue">click here to retry</a>.';
$lang->ai->noMiniProgram          = 'The general agent you visited does not exist.';

$lang->ai->nextStep  = 'Next';
$lang->ai->goTesting = 'Go testing';
$lang->ai->maintenanceGroup = 'Maintenance Group';

$lang->ai->maintenanceGroupDuplicated = 'The group name cannot be duplicated.';

$lang->ai->validate = new stdclass();
$lang->ai->validate->noEmpty       = '%s cannot be empty.';
$lang->ai->validate->dirtyForm     = 'The design step of %s has changed. Do you want to save and return it?';
$lang->ai->validate->nameNotUnique = 'A zenTao agent with the same name already exists, please change the name.';

$lang->ai->prompts = new stdclass();
$lang->ai->prompts->common       = 'ZenTao Agent';
$lang->ai->prompts->emptyList    = 'No ZenTao Agent yet.';
$lang->ai->prompts->create       = 'Create ZenTao Agent';
$lang->ai->prompts->edit         = 'Edit ZenTao Agent';
$lang->ai->prompts->id           = 'ID';
$lang->ai->prompts->name         = 'Name';
$lang->ai->prompts->description  = 'Description';
$lang->ai->prompts->createdBy    = 'Creator';
$lang->ai->prompts->createdDate  = 'Created Date';
$lang->ai->prompts->targetForm   = 'Target Form';
$lang->ai->prompts->funcDesc     = 'Function Description';
$lang->ai->prompts->deleted      = 'Deleted';
$lang->ai->prompts->stage        = 'Stage';
$lang->ai->prompts->basicInfo    = 'Basic Info';
$lang->ai->prompts->editInfo     = 'Edit Info';
$lang->ai->prompts->createdBy    = 'Created By';
$lang->ai->prompts->publishedBy  = 'Published By';
$lang->ai->prompts->draftedBy    = 'Drafted By';
$lang->ai->prompts->lastEditor   = 'Last Editor';
$lang->ai->prompts->modelNeutral = 'Model Neutral';

$lang->ai->prompts->viewTypeList            = array();
$lang->ai->prompts->viewTypeList['list']    = 'List View';
$lang->ai->prompts->viewTypeList['card']    = 'Card View';

$lang->ai->prompts->summary = 'There are %s zenTao agents on this page.';
$lang->ai->prompts->fieldSeparator = ', ';

$lang->ai->prompts->action = new stdclass();
$lang->ai->prompts->action->goDesignConfirm  = 'The current zenTao agent is not complete, continue designing?';
$lang->ai->prompts->action->goDesign         = 'Go designing';
$lang->ai->prompts->action->draftConfirm     = 'Once unpublished, the zenTao agent cannot be used any further. Are you sure you want to proceed?';
$lang->ai->prompts->action->design           = 'Design';
$lang->ai->prompts->action->test             = 'Test';
$lang->ai->prompts->action->edit             = 'Edit';
$lang->ai->prompts->action->publish          = 'Publish';
$lang->ai->prompts->action->unpublish        = 'Unpublish';
$lang->ai->prompts->action->delete           = 'Delete';
$lang->ai->prompts->action->disable          = 'Disable';
$lang->ai->prompts->action->deleteConfirm    = 'Deleted zenTao agents will be no longer available. Are you sure you want to proceed?';
$lang->ai->prompts->action->publishSuccess   = 'Publish Success';
$lang->ai->prompts->action->unpublishSuccess = 'Unpublish Success';
$lang->ai->prompts->action->deleteSuccess    = 'Delete Success';

/* Steps of prompt creation. */
$lang->ai->prompts->assignRole       = 'Assign Role';
$lang->ai->prompts->selectDataSource = 'Select Data Fields';
$lang->ai->prompts->setPurpose       = 'Set Purpose';
$lang->ai->prompts->setTargetForm    = 'Set Target Form';
$lang->ai->prompts->finalize         = 'Finalize';

/* Role assigning. */
$lang->ai->prompts->model               = 'Default Model';
$lang->ai->prompts->role                = 'Role';
$lang->ai->prompts->characterization    = 'Characterization';
$lang->ai->prompts->rolePlaceholder     = '"Act as a <role>"';
$lang->ai->prompts->charPlaceholder     = 'Detailed characterization of this role';
$lang->ai->prompts->roleTemplate        = 'Role Template';
$lang->ai->prompts->roleTemplateTip     = 'After a template is referenced, modifying the role or role description does not affect the template.';
$lang->ai->prompts->addRoleTemplate     = 'Add Role Template';
$lang->ai->prompts->editRoleTemplate    = 'Edit Role Template';
$lang->ai->prompts->editRoleTemplateTip = 'Editing this template will not affect its previous usages in zenTao agents.';
$lang->ai->prompts->roleAddedSuccess    = 'Role added successfully.';
$lang->ai->prompts->roleDelConfirm      = 'Deleting the role does not affect the role that is already in the zenTao agent. Do you want to delete it?';
$lang->ai->prompts->roleDelSuccess      = 'Role deleted successfully.';
$lang->ai->prompts->roleTemplateSave    = 'Save Role Template';
$lang->ai->prompts->roleTemplateSaveList = array();
$lang->ai->prompts->roleTemplateSaveList['save']    = 'Save';
$lang->ai->prompts->roleTemplateSaveList['discard'] = 'Discard';

/* Data source selecting. */
$lang->ai->prompts->selectData       = 'Select fields';
$lang->ai->prompts->selectDataTip    = 'Select an object and its fields will be shown below.';
$lang->ai->prompts->selectedFormat   = 'Selecting data from {0}, {1} fields selected.';
$lang->ai->prompts->nonSelected      = 'No field selected.';
$lang->ai->prompts->sortTip          = 'Sorting fields by priority is suggested.';
$lang->ai->prompts->object           = 'Object';
$lang->ai->prompts->field            = 'Field';

/* Purpose setting. */
$lang->ai->prompts->purpose        = 'Purpose';
$lang->ai->prompts->purposeTip     = 'What do I want it to accomplish, in order to achieve what goals?';
$lang->ai->prompts->elaboration    = 'Elaboration';
$lang->ai->prompts->elaborationTip = 'I hope its answer draws attention to some additional requests.';
$lang->ai->prompts->inputPreview   = 'ZenTao Agent Preview';
$lang->ai->prompts->dataPreview    = 'Data ZenTao Agent Preview';
$lang->ai->prompts->rolePreview    = 'Role ZenTao Agent Preview';
$lang->ai->prompts->promptPreview  = 'Purpose ZenTao Agent Preview';

/* Target form selecting. */
$lang->ai->prompts->selectTargetForm    = 'Select Target Form';
$lang->ai->prompts->selectTargetFormTip = 'Results returned from LLMs can be directly inputed into forms within ZenTao.';
$lang->ai->prompts->goingTesting        = 'Redirecting to testing page';
$lang->ai->prompts->goingTestingFail    = 'No testable object available.';

$lang->ai->prompts->testData['product']['product']['name'] = 'Corporate Website Construction Platform';
$lang->ai->prompts->testData['product']['product']['desc'] = 'The Corporate Website Construction Platform is a management platform designed specifically for modern enterprises, aimed at helping companies showcase themselves in a professional and innovative manner. The platform integrates the latest corporate news, project achievements, contact information, and business details, allowing visitors to easily understand the core values and services of the company. With a clear and concise interface and intuitive navigation, the platform enhances user experience and helps build closer connections between businesses and their customers and partners. Whether for information updates or content management, the platform provides efficient and flexible solutions for enterprises, supporting brand building and business development.';

$lang->ai->prompts->testData['project']['project']['name']     = 'Corporate Website Development Project';
$lang->ai->prompts->testData['project']['project']['type']     = 'Product Type';
$lang->ai->prompts->testData['project']['project']['desc']     = 'The Corporate Website Development Project aims to quickly and efficiently build a fully functional, user-friendly, and highly scalable corporate website by combining waterfall and agile development methods. This project will ensure that the final product meets user needs and provides a good user experience through detailed requirements analysis, design, development, and testing phases.';
$lang->ai->prompts->testData['project']['project']['begin']    = '2025-01-01';
$lang->ai->prompts->testData['project']['project']['end']      = '2025-06-01';
$lang->ai->prompts->testData['project']['project']['estimate'] = '800h';

$lang->ai->prompts->testData['project']['programplans']['name']         = array('Requirements Analysis and Planning', 'System Design', 'Development and Testing', 'Deployment Preparation and Release');
$lang->ai->prompts->testData['project']['programplans']['desc']         = array('During this phase, communication will be done with various stakeholders to collect, analyze, and confirm the functional requirements and user stories of the website.', 'Based on the confirmed requirements, system architecture design and page prototype design will lay the foundation for subsequent development.', 'In this phase, detailed development will be carried out according to the system design, and unit testing will be conducted to ensure functionality.', 'Final system testing, user acceptance testing, and deployment preparation will be conducted to ensure the website can be delivered smoothly.');
$lang->ai->prompts->testData['project']['programplans']['status']       = array('Closed', 'Closed', 'In Progress', 'Not Started');
$lang->ai->prompts->testData['project']['programplans']['begin']        = array('2025-01-01', '2025-02-01', '2025-04-01', '2025-05-15');
$lang->ai->prompts->testData['project']['programplans']['end']          = array('2025-01-31', '2025-02-28', '2025-05-14', '2025-06-01');
$lang->ai->prompts->testData['project']['programplans']['realBegan']    = array('2025-01-01', '2025-02-01', '2025-04-01', '-');
$lang->ai->prompts->testData['project']['programplans']['realEnd']      = array('2025-01-31', '2025-02-28', '-', '-');
$lang->ai->prompts->testData['project']['programplans']['planDuration'] = array('-', '-', '-', '-');
$lang->ai->prompts->testData['project']['programplans']['progress']     = array('100%', '100%', '41%', '0%');
$lang->ai->prompts->testData['project']['programplans']['estimate']     = array('190', '190', '290', '120');
$lang->ai->prompts->testData['project']['programplans']['consumed']     = array('200', '190', '120', '0');
$lang->ai->prompts->testData['project']['programplans']['left']         = array('0', '0', '170', '120');

$lang->ai->prompts->testData['project']['executions']['name']      = array('Corporate Website 1.0', 'Corporate Website 2.0', 'Corporate Website 3.0');
$lang->ai->prompts->testData['project']['executions']['desc']      = array('Develop the core functional modules of the intelligent corporate website, including the homepage, news center, and about us, completing unit testing.', 'Implement the Corporate Website 2.0 version, including the achievement display and after-sales service pages, fix bugs from version 1.0, and complete unit testing.', 'Develop additional functional modules such as contact information and business details, while conducting integration testing to ensure modules work together.');
$lang->ai->prompts->testData['project']['executions']['status']    = array('In Progress', 'Not Started', 'Not Started');
$lang->ai->prompts->testData['project']['executions']['begin']     = array('2025-04-01', '2025-04-14', '2025-04-21');
$lang->ai->prompts->testData['project']['executions']['end']       = array('2025-04-11', '2025-04-18', '2025-05-14');
$lang->ai->prompts->testData['project']['executions']['realBegan'] = array('2025-04-01', '-', '-');
$lang->ai->prompts->testData['project']['executions']['realEnd']   = array('-', '-', '-');
$lang->ai->prompts->testData['project']['executions']['estimate']  = array('120', '100', '70');
$lang->ai->prompts->testData['project']['executions']['consumed']  = array('77', '0', '0');
$lang->ai->prompts->testData['project']['executions']['left']      = array('50', '100', '70');
$lang->ai->prompts->testData['project']['executions']['progress']  = array('64%', '0%', '0%');

$lang->ai->prompts->testData['story']['story']['title']    = 'Implement Corporate Website Homepage';
$lang->ai->prompts->testData['story']['story']['spec']     = 'As a user of this company, I want to conveniently access the basic information of the website on the homepage, so that I can quickly understand the company’s latest news, some achievement displays, contact information, and business details. <br> - Company latest news module. <br> - Company achievement display module. <br> - Company contact information and business details display.';
$lang->ai->prompts->testData['story']['story']['verify']   = "1. The homepage should include the latest news section displaying recent news and event information. \n2. There should be a section for achievement display, highlighting the company\'s important projects and achievements.\n 3. Contact information should be clearly displayed, including phone, email, and address, ensuring visitors can easily find it.\n 4. Business details should be detailed, including company registration information and relevant qualifications, ensuring users can verify the legality and reliability of the company.\n 5. All information should be clearly visible on the homepage, with a beautiful layout and easy navigation.";
$lang->ai->prompts->testData['story']['story']['product']  = 'Corporate Website Construction Platform';
$lang->ai->prompts->testData['story']['story']['module']   = 'Homepage';
$lang->ai->prompts->testData['story']['story']['pri']      = '1';
$lang->ai->prompts->testData['story']['story']['category'] = 'Development Demand';
$lang->ai->prompts->testData['story']['story']['estimate'] = '3sp';

$lang->ai->prompts->testData['productplan']['productplan']['title']  = 'Version 2.0';
$lang->ai->prompts->testData['productplan']['productplan']['desc']   = "- Implement Corporate Website 2.0 version, including achievement display and after-sales service pages \n - Fix bugs left over from version 1.0";
$lang->ai->prompts->testData['productplan']['productplan']['begin']  = '2025-04-14';
$lang->ai->prompts->testData['productplan']['productplan']['end']    = '2025-04-18';

$lang->ai->prompts->testData['productplan']['stories']['title']    = array('Implement Achievement Display Page', 'Implement After-sales Service Page');
$lang->ai->prompts->testData['productplan']['stories']['module']   = array('Achievement Display', 'After-sales Service');
$lang->ai->prompts->testData['productplan']['stories']['pri']      = array('1', '1');
$lang->ai->prompts->testData['productplan']['stories']['estimate'] = array('1sp', '2sp');
$lang->ai->prompts->testData['productplan']['stories']['status']   = array('Activated', 'Activated');
$lang->ai->prompts->testData['productplan']['stories']['stage']    = array('Testing', 'In Development');

$lang->ai->prompts->testData['productplan']['bugs']['title']  = array('Homepage Latest News Module Error', 'Achievement Display Icon Overlapping with Title');
$lang->ai->prompts->testData['productplan']['bugs']['pri']    = array('1', '2');
$lang->ai->prompts->testData['productplan']['bugs']['status'] = array('Resolved', 'Activated');

$lang->ai->prompts->testData['release']['release']['product'] = 'Corporate Website Construction Platform';
$lang->ai->prompts->testData['release']['release']['name']    = 'Corporate Website Version 1.0';
$lang->ai->prompts->testData['release']['release']['desc']    = "- Implement Corporate Website Homepage \n - Implement News Center Page \n - Implement About Us Page";
$lang->ai->prompts->testData['release']['release']['date']    = '2025-04-11';

$lang->ai->prompts->testData['release']['stories']['title']    = array('Implement Corporate Website Homepage', 'Implement News Center Page', 'Implement About Us Page');
$lang->ai->prompts->testData['release']['stories']['estimate'] = array('3sp', '2sp', '1sp');

$lang->ai->prompts->testData['release']['bugs']['title']  = 'None';

$lang->ai->prompts->testData['execution']['execution']['name']     = 'Corporate Website 1.0';
$lang->ai->prompts->testData['execution']['execution']['desc']     = 'Develop the core functional modules of the intelligent corporate website, including the homepage, news center, and about us, completing unit testing.';
$lang->ai->prompts->testData['execution']['execution']['estimate'] = '120';

$lang->ai->prompts->testData['execution']['tasks']['name']         = array('Iteration Planning Meeting', 'Homepage Development Design', 'Homepage Development', 'Homepage Testing', 'News Center Development Design', 'News Center Page Development', 'News Center Page Testing', 'About Us Development Design', 'About Us Page Development', 'About Us Page Testing', 'Iteration Review Meeting');
$lang->ai->prompts->testData['execution']['tasks']['pri']          = array('1', '1', '2', '3', '1', '2', '3', '1', '2', '3', '4');
$lang->ai->prompts->testData['execution']['tasks']['status']       = array('Closed', 'Completed', 'Completed', 'In Progress', 'Completed', 'In Progress', 'Not Started', 'In Progress', 'Not Started', 'Not Started', 'Not Started');
$lang->ai->prompts->testData['execution']['tasks']['estimate']     = array('40h', '12h', '10h', '2h', '6h', '8h', '4h', '4h', '8h', '4h', '22h');
$lang->ai->prompts->testData['execution']['tasks']['consumed']     = array('40h', '12h', '10h', '1h', '6h', '6h', '0h', '2h', '0h', '0h', '0h');
$lang->ai->prompts->testData['execution']['tasks']['left']         = array('0h', '0h', '0h', '1h', '0h', '2h', '4h', '2h', '8h', '4h', '22h');
$lang->ai->prompts->testData['execution']['tasks']['progress']     = array('100%', '100%', '100%', '50%', '100%', '75%', '0%', '50%', '0%', '0%', '0%');
$lang->ai->prompts->testData['execution']['tasks']['estStarted']   = array('2025-04-01', '2025-04-01', '2025-04-02', '2025-04-04', '2025-04-02', '2025-04-02', '2025-04-07', '2025-04-03', '2025-04-03', '2025-04-08', '2025-04-11');
$lang->ai->prompts->testData['execution']['tasks']['realStarted']  = array('2025-04-01', '2025-04-01', '2025-04-02', '2025-04-04', '2025-04-02', '2025-04-02', '-', '2025-04-03', '-', '-', '-');
$lang->ai->prompts->testData['execution']['tasks']['finishedDate'] = array('2025-04-01', '2025-04-01', '2025-04-04', '-', '2025-04-02', '-', '-', '-', '-', '-', '-');
$lang->ai->prompts->testData['execution']['tasks']['closedReason'] = array('Completed', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-');

$lang->ai->prompts->testData['task']['task']['name']        = 'Iteration Planning Meeting';
$lang->ai->prompts->testData['task']['task']['desc']        = "The iteration planning meeting aims to ensure that the team has a clear direction and goals for the next development cycle, promote communication and collaboration among team members, and help the team allocate resources effectively.<br> objective of this planning meeting is to clarify the core functional modules of the corporate website (including the homepage, news center, and about us) with the product manager, ensuring that development and testing can complete the planned requirements on time during the iteration cycle.";
$lang->ai->prompts->testData['task']['task']['pri']         = '1';
$lang->ai->prompts->testData['task']['task']['status']      = 'Closed';
$lang->ai->prompts->testData['task']['task']['estimate']    = '40h';
$lang->ai->prompts->testData['task']['task']['consumed']    = '40h';
$lang->ai->prompts->testData['task']['task']['left']        = '0h';
$lang->ai->prompts->testData['task']['task']['progress']    = '100%';
$lang->ai->prompts->testData['task']['task']['estStarted']  = '2025-04-01';
$lang->ai->prompts->testData['task']['task']['realStarted'] = '2025-04-01';

$lang->ai->prompts->testData['case']['case']['title']         = 'Implement Corporate Website Homepage';
$lang->ai->prompts->testData['case']['case']['precondition']  = '1. The basic framework of the corporate website has been established and deployed on the server. 2. Users can access the corporate website.';
$lang->ai->prompts->testData['case']['case']['scene']         = 'User accesses the corporate website homepage';
$lang->ai->prompts->testData['case']['case']['product']       = 'Corporate Website Construction Platform';
$lang->ai->prompts->testData['case']['case']['module']        = 'Homepage';
$lang->ai->prompts->testData['case']['case']['pri']           = '1';
$lang->ai->prompts->testData['case']['case']['type']          = 'Functional Testing';
$lang->ai->prompts->testData['case']['case']['lastRunResult'] = 'Passed';
$lang->ai->prompts->testData['case']['case']['status']        = 'Normal';

$lang->ai->prompts->testData['case']['steps']['desc']   = array('1. User accesses the corporate website homepage.', '2. User views the latest news module and checks if it includes recent news and event information.', '3. User checks the achievement display module to see if it prominently showcases the company\'s important projects and achievements.', '4. User checks the contact information module to confirm it contains valid phone numbers, emails, and company addresses.', '5. User checks the business information module to confirm that the company registration information and relevant qualifications are detailed and accurate.', '6. Check if all information is clearly visible in their display locations.', '7. User uses the navigation function to view other pages, ensuring navigation is easy to use.');
$lang->ai->prompts->testData['case']['steps']['expect'] = array('User successfully accesses the corporate website homepage, and the homepage loads normally.', 'Latest news module: displays recent news and event information', 'Achievement display module: prominently showcases the company\'s important projects and achievements.', 'Contact information module: clearly displays phone, email, and address, making it easy for users to find.', 'Business information module: details the company registration information and relevant qualifications.', 'Users can see all information at a glance, with reasonable information placement and beautiful layout.', 'Users can smoothly use the navigation function to find other related pages, with a seamless navigation process.');

$lang->ai->prompts->testData['bug']['bug']['title']     = 'Homepage Latest News Module Error';
$lang->ai->prompts->testData['bug']['bug']['steps']     = "Steps:1. Open the application homepage<br> 2. Scroll to the latest news module <br>Result: <br> An error message appears in the module.<br>Expectation:<br> Latest news displays normally without errors.";
$lang->ai->prompts->testData['bug']['bug']['severity']  = '1';
$lang->ai->prompts->testData['bug']['bug']['pri']       = '1';
$lang->ai->prompts->testData['bug']['bug']['status']    = 'Resolved';
$lang->ai->prompts->testData['bug']['bug']['confirmed'] = 'Confirmed';
$lang->ai->prompts->testData['bug']['bug']['type']      = 'Code Error';

$lang->ai->prompts->testData['doc']['doc']['title']      = 'Why Do Well-Crafted Products Encounter Market Indifference?';
$lang->ai->prompts->testData['doc']['doc']['addedBy']    = '-';
$lang->ai->prompts->testData['doc']['doc']['addedDate']  = '-';
$lang->ai->prompts->testData['doc']['doc']['editedBy']   = '-';

/* Finalize page. */
$lang->ai->moduleDisableTip = 'Module is automatically selected based on selected objects.';

/* Data source definition. */
$lang->ai->dataSource = array();

$lang->ai->dataSource['my']['common']          = 'My';
$lang->ai->dataSource['product']['common']     = 'Product';
$lang->ai->dataSource['story']['common']       = 'Story';
$lang->ai->dataSource['productplan']['common'] = 'Product Plan';
$lang->ai->dataSource['release']['common']     = 'Release';
$lang->ai->dataSource['project']['common']     = 'Project';
$lang->ai->dataSource['execution']['common']   = 'Execution';
$lang->ai->dataSource['task']['common']        = 'Task';
$lang->ai->dataSource['bug']['common']         = 'Bug';
$lang->ai->dataSource['case']['common']        = 'Test Case';
$lang->ai->dataSource['doc']['common']         = 'Document';

$lang->ai->dataSource['my']['efforts']['common']    = 'Efforts';
$lang->ai->dataSource['my']['efforts']['date']      = 'Date';
$lang->ai->dataSource['my']['efforts']['work']      = 'Work';
$lang->ai->dataSource['my']['efforts']['account']   = 'Account';
$lang->ai->dataSource['my']['efforts']['consumed']  = 'Consumed';
$lang->ai->dataSource['my']['efforts']['left']      = 'Left';
$lang->ai->dataSource['my']['efforts']['objectID']  = 'Object';
$lang->ai->dataSource['my']['efforts']['product']   = 'Product';
$lang->ai->dataSource['my']['efforts']['project']   = 'Project';
$lang->ai->dataSource['my']['efforts']['execution'] = 'Execution';

$lang->ai->dataSource['product']['product']['common']  = 'Product';
$lang->ai->dataSource['product']['product']['name']    = 'Product Name';
$lang->ai->dataSource['product']['product']['desc']    = 'Description';
$lang->ai->dataSource['product']['modules']['common']  = 'Module';
$lang->ai->dataSource['product']['modules']['name']    = 'Module Name';
$lang->ai->dataSource['product']['modules']['modules'] = 'Sub Modules';

$lang->ai->dataSource['productplan']['productplan']['common'] = 'Product Plan';
$lang->ai->dataSource['productplan']['productplan']['title']  = 'Title';
$lang->ai->dataSource['productplan']['productplan']['desc']   = 'Description';
$lang->ai->dataSource['productplan']['productplan']['begin']  = 'Begin';
$lang->ai->dataSource['productplan']['productplan']['end']    = 'End';

$lang->ai->dataSource['productplan']['stories']['common']   = 'Stories';
$lang->ai->dataSource['productplan']['stories']['title']    = 'Title';
$lang->ai->dataSource['productplan']['stories']['module']   = 'Module';
$lang->ai->dataSource['productplan']['stories']['pri']      = 'Priority';
$lang->ai->dataSource['productplan']['stories']['estimate'] = 'Estimates';
$lang->ai->dataSource['productplan']['stories']['status']   = 'Status';
$lang->ai->dataSource['productplan']['stories']['stage']    = 'Stage';

$lang->ai->dataSource['productplan']['bugs']['common'] = 'Bugs';
$lang->ai->dataSource['productplan']['bugs']['title']  = 'Title';
$lang->ai->dataSource['productplan']['bugs']['pri']    = 'Priority';
$lang->ai->dataSource['productplan']['bugs']['status'] = 'Status';

$lang->ai->dataSource['release']['release']['common']  = 'Release';
$lang->ai->dataSource['release']['release']['product'] = 'Product';
$lang->ai->dataSource['release']['release']['name']    = 'Name';
$lang->ai->dataSource['release']['release']['desc']    = 'Description';
$lang->ai->dataSource['release']['release']['date']    = 'Release Date';

$lang->ai->dataSource['release']['stories']['common']   = 'Stories';
$lang->ai->dataSource['release']['stories']['title']    = 'Title';
$lang->ai->dataSource['release']['stories']['estimate'] = 'Estimates';

$lang->ai->dataSource['release']['bugs']['common'] = 'Bugs';
$lang->ai->dataSource['release']['bugs']['title']  = 'Title';

$lang->ai->dataSource['project']['project']['common']   = 'Project';
$lang->ai->dataSource['project']['project']['name']     = 'Name';
$lang->ai->dataSource['project']['project']['type']     = 'Type';
$lang->ai->dataSource['project']['project']['desc']     = 'Description';
$lang->ai->dataSource['project']['project']['begin']    = 'Begin';
$lang->ai->dataSource['project']['project']['end']      = 'End';
$lang->ai->dataSource['project']['project']['estimate'] = 'Estimates';

$lang->ai->dataSource['project']['programplans']['common']       = 'Program Plan';
$lang->ai->dataSource['project']['programplans']['name']         = 'Name';
$lang->ai->dataSource['project']['programplans']['desc']         = 'Description';
$lang->ai->dataSource['project']['programplans']['status']       = 'Status';
$lang->ai->dataSource['project']['programplans']['begin']        = 'Begin';
$lang->ai->dataSource['project']['programplans']['end']          = 'End';
$lang->ai->dataSource['project']['programplans']['realBegan']    = 'Actual Start';
$lang->ai->dataSource['project']['programplans']['realEnd']      = 'Actual End';
$lang->ai->dataSource['project']['programplans']['planDuration'] = 'Plan Duration';
$lang->ai->dataSource['project']['programplans']['progress']     = 'Progress';
$lang->ai->dataSource['project']['programplans']['estimate']     = 'Estimates';
$lang->ai->dataSource['project']['programplans']['consumed']     = 'Consumed';
$lang->ai->dataSource['project']['programplans']['left']         = 'Left';

$lang->ai->dataSource['project']['executions']['common']    = 'Execution';
$lang->ai->dataSource['project']['executions']['name']      = 'Name';
$lang->ai->dataSource['project']['executions']['desc']      = 'Description';
$lang->ai->dataSource['project']['executions']['status']    = 'Status';
$lang->ai->dataSource['project']['executions']['begin']     = 'Begin';
$lang->ai->dataSource['project']['executions']['end']       = 'End';
$lang->ai->dataSource['project']['executions']['realBegan'] = 'Actual Start';
$lang->ai->dataSource['project']['executions']['realEnd']   = 'Actual End';
$lang->ai->dataSource['project']['executions']['estimate']  = 'Estimates';
$lang->ai->dataSource['project']['executions']['consumed']  = 'Consumed';
$lang->ai->dataSource['project']['executions']['left']      = 'Left';
$lang->ai->dataSource['project']['executions']['progress']  = 'Progress';

$lang->ai->dataSource['story']['story']['common']   = 'Story';
$lang->ai->dataSource['story']['story']['title']    = 'Title';
$lang->ai->dataSource['story']['story']['spec']     = 'Description';
$lang->ai->dataSource['story']['story']['verify']   = 'Acceptance criteria';
$lang->ai->dataSource['story']['story']['product']  = 'Product';
$lang->ai->dataSource['story']['story']['module']   = 'Module';
$lang->ai->dataSource['story']['story']['pri']      = 'Priority';
$lang->ai->dataSource['story']['story']['category'] = 'Category';
$lang->ai->dataSource['story']['story']['estimate'] = 'Estimated hours';

$lang->ai->dataSource['execution']['execution']['common']   = 'Execution';
$lang->ai->dataSource['execution']['execution']['name']     = 'Name';
$lang->ai->dataSource['execution']['execution']['desc']     = 'Description';
$lang->ai->dataSource['execution']['execution']['estimate'] = 'Estimated hours';

$lang->ai->dataSource['execution']['tasks']['common']       = 'Task List';
$lang->ai->dataSource['execution']['tasks']['name']         = 'Name';
$lang->ai->dataSource['execution']['tasks']['pri']          = 'Priority';
$lang->ai->dataSource['execution']['tasks']['status']       = 'Status';
$lang->ai->dataSource['execution']['tasks']['estimate']     = 'Estimated hours';
$lang->ai->dataSource['execution']['tasks']['consumed']     = 'Consumed hours';
$lang->ai->dataSource['execution']['tasks']['left']         = 'Remaining hours';
$lang->ai->dataSource['execution']['tasks']['progress']     = 'Progress';
$lang->ai->dataSource['execution']['tasks']['estStarted']   = 'Estimated start date';
$lang->ai->dataSource['execution']['tasks']['realStarted']  = 'Actual start date';
$lang->ai->dataSource['execution']['tasks']['finishedDate'] = 'Finished date';
$lang->ai->dataSource['execution']['tasks']['closedReason'] = 'Closing reason';

$lang->ai->dataSource['task']['task']['common']      = 'Task';
$lang->ai->dataSource['task']['task']['name']        = 'Name';
$lang->ai->dataSource['task']['task']['desc']        = 'Description';
$lang->ai->dataSource['task']['task']['pri']         = 'Priority';
$lang->ai->dataSource['task']['task']['status']      = 'Status';
$lang->ai->dataSource['task']['task']['estimate']    = 'Estimates';
$lang->ai->dataSource['task']['task']['consumed']    = 'Consumed';
$lang->ai->dataSource['task']['task']['left']        = 'Left';
$lang->ai->dataSource['task']['task']['progress']    = 'Progress';
$lang->ai->dataSource['task']['task']['estStarted']  = 'Start Date';
$lang->ai->dataSource['task']['task']['realStarted'] = 'Actual Start';

$lang->ai->dataSource['case']['case']['common']        = 'Test Case';
$lang->ai->dataSource['case']['case']['title']         = 'Title';
$lang->ai->dataSource['case']['case']['precondition']  = 'Prerequisite';
$lang->ai->dataSource['case']['case']['scene']         = 'Scene';
$lang->ai->dataSource['case']['case']['product']       = 'Product';
$lang->ai->dataSource['case']['case']['module']        = 'Module';
$lang->ai->dataSource['case']['case']['pri']           = 'Priority';
$lang->ai->dataSource['case']['case']['type']          = 'Type';
$lang->ai->dataSource['case']['case']['lastRunResult'] = 'Result';
$lang->ai->dataSource['case']['case']['status']        = 'Status';

$lang->ai->dataSource['case']['steps']['common'] = 'Steps';
$lang->ai->dataSource['case']['steps']['desc']   = 'Description';
$lang->ai->dataSource['case']['steps']['expect'] = 'Expectation';

$lang->ai->dataSource['bug']['bug']['common']    = 'Bug';
$lang->ai->dataSource['bug']['bug']['title']     = 'Title';
$lang->ai->dataSource['bug']['bug']['steps']     = 'Repro Steps';
$lang->ai->dataSource['bug']['bug']['severity']  = 'Severity';
$lang->ai->dataSource['bug']['bug']['pri']       = 'Priority';
$lang->ai->dataSource['bug']['bug']['status']    = 'Status';
$lang->ai->dataSource['bug']['bug']['confirmed'] = 'Confirmed';
$lang->ai->dataSource['bug']['bug']['type']      = 'Type';

$lang->ai->dataSource['doc']['doc']['common']     = 'Document';
$lang->ai->dataSource['doc']['doc']['title']      = 'Title';
$lang->ai->dataSource['doc']['doc']['content']    = 'Text';
$lang->ai->dataSource['doc']['doc']['addedBy']    = 'Created By';
$lang->ai->dataSource['doc']['doc']['addedDate']  = 'Created Date';
$lang->ai->dataSource['doc']['doc']['editedBy']   = 'Edited By';
$lang->ai->dataSource['doc']['doc']['editedDate'] = 'Edited Date';

/* Target form definition. See `$config->ai->targetForm`. */
$lang->ai->targetForm = array();
$lang->ai->targetForm['product']['common']        = 'Product';
$lang->ai->targetForm['story']['common']          = 'Story';
$lang->ai->targetForm['productplan']['common']    = 'Plan';
$lang->ai->targetForm['projectrelease']['common'] = 'Release';
$lang->ai->targetForm['project']['common']        = 'Project';
$lang->ai->targetForm['execution']['common']      = 'Execution';
$lang->ai->targetForm['task']['common']           = 'Task';
$lang->ai->targetForm['testcase']['common']       = 'Test Case';
$lang->ai->targetForm['bug']['common']            = 'Bug';
$lang->ai->targetForm['doc']['common']            = 'Document';
$lang->ai->targetForm['empty']['common']          = '';

$lang->ai->targetForm['product']['tree/managechild'] = 'Manage Modules';
$lang->ai->targetForm['product']['doc/create']       = 'Create Doc';

$lang->ai->targetForm['story']['create']         = 'Create Story';
$lang->ai->targetForm['story']['batchcreate']    = 'Batch Create Story';
$lang->ai->targetForm['story']['change']         = 'Change Story';
$lang->ai->targetForm['story']['totask']         = 'Story to Task';
$lang->ai->targetForm['story']['testcasecreate'] = 'Create Test Case';
$lang->ai->targetForm['story']['subdivide']      = 'Subdivide Story';

$lang->ai->targetForm['productplan']['edit']   = 'Edit Plan';
$lang->ai->targetForm['productplan']['create'] = 'Create Sub-Plan';

$lang->ai->targetForm['projectrelease']['doc/create'] = 'Create Doc';

$lang->ai->targetForm['project']['risk/create']        = 'Create Risk';
$lang->ai->targetForm['project']['issue/create']       = 'Create Issue';
$lang->ai->targetForm['project']['doc/create']         = 'Create Doc';
$lang->ai->targetForm['project']['programplan/create'] = 'Set Program Plan';

$lang->ai->targetForm['execution']['batchcreatetask']  = 'Batch Create Task';
$lang->ai->targetForm['execution']['createtestreport'] = 'Create Test Report';
$lang->ai->targetForm['execution']['createqa']         = 'Create QA';
$lang->ai->targetForm['execution']['createrisk']       = 'Create Risk';
$lang->ai->targetForm['execution']['createissue']      = 'Create Issue';

$lang->ai->targetForm['task']['edit']        = 'Edit Task';
$lang->ai->targetForm['task']['batchcreate'] = 'Batch Create Task';

$lang->ai->targetForm['testcase']['edit']         = 'Edit Test Case';
$lang->ai->targetForm['testcase']['createscript'] = 'Create Script';

$lang->ai->targetForm['bug']['edit']            = 'Edit Bug';
$lang->ai->targetForm['bug']['story/create']    = 'Bug to Story';
$lang->ai->targetForm['bug']['testcase/create'] = 'Bug to Test Case';

$lang->ai->targetForm['doc']['create'] = 'Create Doc';
$lang->ai->targetForm['doc']['edit']   = 'Edit Doc';

$lang->ai->targetForm['empty']['empty'] = 'Empty';

$lang->ai->prompts->statuses = array();
$lang->ai->prompts->statuses['']       = 'All';
$lang->ai->prompts->statuses['draft']  = 'Draft';
$lang->ai->prompts->statuses['active'] = 'Active';

$lang->ai->featureBar['prompts']['']       = 'All';
$lang->ai->featureBar['prompts']['draft']  = 'Draft';
$lang->ai->featureBar['prompts']['active'] = 'Active';

$lang->ai->prompts->modules = array();
$lang->ai->prompts->modules['']            = 'All';
// $lang->ai->prompts->modules['my']          = 'My';
$lang->ai->prompts->modules['product']     = 'Product';
$lang->ai->prompts->modules['project']     = 'Project';
$lang->ai->prompts->modules['story']       = 'Story';
$lang->ai->prompts->modules['productplan'] = 'Product Plan';
$lang->ai->prompts->modules['release']     = 'Release';
$lang->ai->prompts->modules['execution']   = 'Execution';
$lang->ai->prompts->modules['task']        = 'Task';
$lang->ai->prompts->modules['case']        = 'Test Case';
$lang->ai->prompts->modules['bug']         = 'Bug';
$lang->ai->prompts->modules['doc']         = 'Document';

$lang->ai->conversations = new stdclass();
$lang->ai->conversations->common = 'Conversations';

$lang->ai->miniPrograms                    = new stdClass();
$lang->ai->miniPrograms->common            = 'General agents';
$lang->ai->miniPrograms->emptyList         = 'There is currently no general agent available.';
$lang->ai->miniPrograms->create            = 'Create a agent';
$lang->ai->miniPrograms->configuration     = 'Basic Information Configuration';
$lang->ai->miniPrograms->downloadTip       = 'After release, it will be displayed on the general agent Square and will be automatically synchronized to the client.';
$lang->ai->miniPrograms->download          = 'Download Zentao Client';
$lang->ai->miniPrograms->category          = 'Category';
$lang->ai->miniPrograms->icon              = 'Icon';
$lang->ai->miniPrograms->desc              = 'Introduction';
$lang->ai->miniPrograms->categoryList      = array('work' => 'Work', 'personal' => 'Personal', 'life' => 'Life', 'creative' => 'Creative', 'others' => 'Others');
$lang->ai->miniPrograms->allCategories     = array('' => 'All categories');
$lang->ai->miniPrograms->collect           = 'Collect';
$lang->ai->miniPrograms->more              = 'More';
$lang->ai->miniPrograms->iconModification  = 'Icon modification';
$lang->ai->miniPrograms->customBackground  = 'Custom background Color';
$lang->ai->miniPrograms->customIcon        = 'Custom icon';
$lang->ai->miniPrograms->backToListPage    = 'Return to list page';
$lang->ai->miniPrograms->lastStep          = 'Previous step';
$lang->ai->miniPrograms->backToListPageTip = 'The parameter configuration for selecting the object has been changed. Do you want to save and return?';
$lang->ai->miniPrograms->saveAndBack       = 'Save and Go Back';
$lang->ai->miniPrograms->publishConfirm    = array('Are you sure you want to publish?', 'After release, it will be displayed in the first-level navigation AI module, and the client will be updated simultaneously.');
$lang->ai->miniPrograms->emptyPrompterTip  = 'The prompter of the general agent is empty. Please edit it before publishing.';
$lang->ai->miniPrograms->maintenanceGroup  = 'Maintenance general agent group';

$lang->ai->miniPrograms->latestPublishedDate = 'Latest Published Date';
$lang->ai->miniPrograms->deleteTip           = 'Are you sure you want to delete this general agent?';
$lang->ai->miniPrograms->disableTip          = 'Disabling the general agent will prevent users from accessing it. Are you sure you want to disable it?';
$lang->ai->miniPrograms->publishTip          = 'After release, it will be displayed in the General Agent Model Square and the client will update synchronously.';
$lang->ai->miniPrograms->unpublishedTip      = 'The general agent you are using is not published.';

$lang->ai->miniPrograms->placeholder          = new stdClass();
$lang->ai->miniPrograms->placeholder->name    = 'Please enter a small general agent name';
$lang->ai->miniPrograms->placeholder->desc    = 'Please enter a brief introduction to the general agents';
$lang->ai->miniPrograms->placeholder->default = 'Please fill in the prompt, the default is "please enter"';
$lang->ai->miniPrograms->placeholder->input   = 'Please enter';
$lang->ai->miniPrograms->placeholder->prompt  = 'Please enter prompt design';
$lang->ai->miniPrograms->placeholder->asking  = 'Continue asking';

$lang->ai->miniPrograms->deleteFieldTip = 'Are you sure to delete this field? ';

$lang->ai->miniPrograms->field                    = new stdClass();
$lang->ai->miniPrograms->field->name              = 'Field name';
$lang->ai->miniPrograms->field->duplicatedNameTip = 'This name is already used, please try another name';
$lang->ai->miniPrograms->field->type              = 'control type';
$lang->ai->miniPrograms->field->typeList          = array('text' => 'single-line text', 'textarea' => 'multi-line text', 'radio' => 'single-selection', 'checkbox' => 'multi-selection');
$lang->ai->miniPrograms->field->placeholder       = 'Fill hints';
$lang->ai->miniPrograms->field->required          = 'Required';
$lang->ai->miniPrograms->field->requiredOptions   = array('No', 'Yes');
$lang->ai->miniPrograms->field->add               = 'New field';
$lang->ai->miniPrograms->field->addTip            = 'Please click here to add field information';
$lang->ai->miniPrograms->field->edit              = 'Edit field';
$lang->ai->miniPrograms->field->configuration     = 'Field Configuration area';
$lang->ai->miniPrograms->field->debug             = 'debug area';
$lang->ai->miniPrograms->field->preview           = 'Preview area';
$lang->ai->miniPrograms->field->option            = 'Options';
$lang->ai->miniPrograms->field->contentDebugging  = 'Content debugging';
$lang->ai->miniPrograms->field->contentDebuggingTip = 'Please enter the field here to debug.';
$lang->ai->miniPrograms->field->prompterDesign    = 'Prompt design';
$lang->ai->miniPrograms->field->prompterDesignTip = 'The <> symbol is used to reference the configured field. Space is used before and after <>.';
$lang->ai->miniPrograms->field->prompterPreview   = 'Prompt preview';
$lang->ai->miniPrograms->field->generateResult    = 'Generate result';
$lang->ai->miniPrograms->field->resultPreview     = 'Result Preview';

$lang->ai->miniPrograms->field->default = array(
    'Role',
    'Scene',
    'Objective',
    'As a <Role>, I hope to <Objective> in <Scene>.'
);

$lang->ai->miniPrograms->field->emptyNameWarning      = '「%s」 cannot be empty';
$lang->ai->miniPrograms->field->duplicatedNameWarning = 'Duplicate 「%s」';
$lang->ai->miniPrograms->field->emptyOptionWarning    = 'Please configure at least one option';

$lang->ai->miniPrograms->statuses = array(
    ''            => 'all',
    'draft'       => 'unpublished',
    'active'      => 'published',
    'createdByMe' => 'Created by me'
);

$lang->ai->featureBar['miniprograms']['']            = 'All';
$lang->ai->featureBar['miniprograms']['draft']       = 'Unpublished';
$lang->ai->featureBar['miniprograms']['active']      = 'Published';
$lang->ai->featureBar['miniprograms']['createdByMe'] = 'Created by me';

$lang->ai->miniPrograms->publishedOptions   = array('unpublished', 'published');
$lang->ai->miniPrograms->optionName         = 'Option name';
$lang->ai->miniPrograms->promptTemplate     = 'Prompt template';
$lang->ai->miniPrograms->fieldConfiguration = 'Field configuration';
$lang->ai->miniPrograms->summary            = 'There are %s small general agents on this page.';
$lang->ai->miniPrograms->generate           = 'Generate';
$lang->ai->miniPrograms->regenerate         = 'Regenerate';
$lang->ai->miniPrograms->noModel            = array('The language model has not been configured yet. Please contact the administrator or go to the backend to configure <a id="to-language-model"> the language model.</a>。', 'If the relevant configuration has been completed, please try <a id="reload-current">reloading</a> the page.');
$lang->ai->miniPrograms->clearContext       = 'The context content has been cleared.';
$lang->ai->miniPrograms->newVersionTip      = 'The general agent has been updated on %s. The above is the past record.';
$lang->ai->miniPrograms->disabledTip        = 'The current general agent is disabled.';
$lang->ai->miniPrograms->chatNoResponse     = 'Something went wrong.';

$lang->ai->models = new stdclass();
$lang->ai->models->title          = 'Language Model Configuration';
$lang->ai->models->common         = 'Language Model';
$lang->ai->models->name           = 'Name';
$lang->ai->models->type           = 'Model';
$lang->ai->models->vendor         = 'Vendor';
$lang->ai->models->base           = 'API Base URL';
$lang->ai->models->key            = 'API Key';
$lang->ai->models->secret         = 'Secret Key';
$lang->ai->models->resource       = 'Resource';
$lang->ai->models->deployment     = 'Deployment';
$lang->ai->models->proxyType      = 'Proxy Type';
$lang->ai->models->proxyAddr      = 'Proxy Address';
$lang->ai->models->description    = 'Description';
$lang->ai->models->createdDate    = 'Creation Date';
$lang->ai->models->createdBy      = 'Created By';
$lang->ai->models->editedDate     = 'Modification Date';
$lang->ai->models->editedBy       = 'Edited By';
$lang->ai->models->usesProxy      = 'Use Proxy';
$lang->ai->models->testConnection = 'Test Connection';
$lang->ai->models->unconfigured   = 'Unconfigured';
$lang->ai->models->create         = 'Create Model';
$lang->ai->models->edit           = 'Edit Parameters';
$lang->ai->models->view           = 'View Details';
$lang->ai->models->enable         = 'Enable Language Model';
$lang->ai->models->disable        = 'Disable Language Model';
$lang->ai->models->details        = 'Model Details';
$lang->ai->models->concealTip     = 'Visible when editing';
$lang->ai->models->upgradeBiz     = 'For more AI features, all in <a target="_blank" href="https://www.zentao.net/page/enterprise.html" class="text-blue">ZenTao Biz</a>.';
$lang->ai->models->noModelError   = 'No language model is configured, please contact the administrator.';
$lang->ai->models->noModels       = 'There is currently no language model.';
$lang->ai->models->confirmDelete  = 'After deleting the model, the associated ZenTao agents, General agents, and AI chats will be unavailable. Do you want to delete them?';
$lang->ai->models->confirmDisable = 'Are you sure you want to disable this language model?';
$lang->ai->models->default        = 'Default model';
$lang->ai->models->defaultTip     = 'The default language model (the first available language model) will be used to run zenTao agents and general agents that are not specified with a language model, and will also be used for chat.';
$lang->ai->models->authFailure    = 'API authentication failed';

$lang->ai->models->testConnectionResult = new stdclass();
$lang->ai->models->testConnectionResult->success    = 'Successfully connected';
$lang->ai->models->testConnectionResult->fail       = 'Failed to connect';
$lang->ai->models->testConnectionResult->failFormat = 'Failed to connect: %s';

$lang->ai->models->statusList = array();
$lang->ai->models->statusList['0'] = 'Disabled';
$lang->ai->models->statusList['off'] = 'Disabled';
$lang->ai->models->statusList['1']  = 'Enabled';
$lang->ai->models->statusList['on']  = 'Enabled';

$lang->ai->models->proxyStatusList = array();
$lang->ai->models->proxyStatusList['0']   = 'No';
$lang->ai->models->proxyStatusList['off'] = 'No';
$lang->ai->models->proxyStatusList['1']   = 'Yes';
$lang->ai->models->proxyStatusList['on']  = 'Yes';

$lang->ai->models->typeList = array();
$lang->ai->models->typeList['openai-gpt35'] = 'OpenAI / GPT-3.5';
$lang->ai->models->typeList['openai-gpt4']  = 'OpenAI / GPT-4';
$lang->ai->models->typeList['baidu-ernie']  = 'Baidu / ERNIE';

$lang->ai->models->vendorList = new stdclass();
$lang->ai->models->vendorList->{'openai-gpt35'} = array('openai' => 'OpenAI', 'azure' => 'Azure', 'openaiCompatible' => 'Custom');
$lang->ai->models->vendorList->{'openai-gpt4'}  = array('openai' => 'OpenAI', 'azure' => 'Azure', 'openaiCompatible' => 'Custom');
$lang->ai->models->vendorList->{'baidu-ernie'}  = array('baidu' => 'Baidu Qianfan LLM Platform');

$lang->ai->models->vendorTips = new stdclass();
$lang->ai->models->vendorTips->azure            = 'OpenAI GPT version is specified during model deployment creation on Azure.';
$lang->ai->models->vendorTips->openaiCompatible = 'Custom API needs to support Function Calling, otherwise some functions may not work properly.';

$lang->ai->models->proxyTypes = array();
$lang->ai->models->proxyTypes['']       = 'No Proxy';
$lang->ai->models->proxyTypes['socks5'] = 'SOCKS5';

$lang->ai->models->promptFor = 'ZenTao agent for %s';

$lang->ai->designStepNav = array();
$lang->ai->designStepNav['assignrole']       = 'Specify Role';
$lang->ai->designStepNav['selectdatasource'] = 'Select Object';
$lang->ai->designStepNav['setpurpose']       = 'Confirm Action';
$lang->ai->designStepNav['settargetform']    = 'Process Result';
$lang->ai->designStepNav['finalize']         = 'Ready to Publish';

$lang->ai->dataTypeDesc = '%s is %s type, %s';

$lang->ai->dataType            = new stdclass();
$lang->ai->dataType->pri       = new stdClass();
$lang->ai->dataType->pri->type = 'numeric';
$lang->ai->dataType->pri->desc = '1 is the highest priority, 4 is the lowest priority.';

$lang->ai->dataType->estimate       = new stdClass();
$lang->ai->dataType->estimate->type = 'numeric';
$lang->ai->dataType->estimate->desc = 'Unit is in hours.';

$lang->ai->dataType->consumed = $lang->ai->dataType->estimate;
$lang->ai->dataType->left     = $lang->ai->dataType->estimate;

$lang->ai->dataType->progress       = new stdClass();
$lang->ai->dataType->progress->type = 'percentage';
$lang->ai->dataType->progress->desc = '0 means not started, 100 means completed.';

$lang->ai->dataType->datetime       = new stdClass();
$lang->ai->dataType->datetime->type = 'datetime';
$lang->ai->dataType->datetime->desc = 'Format is: 1970-01-01 00:00:01, or leave it blank.';

$lang->ai->dataType->estStarted   = $lang->ai->dataType->datetime;
$lang->ai->dataType->realStarted  = $lang->ai->dataType->datetime;
$lang->ai->dataType->finishedDate = $lang->ai->dataType->datetime;

$lang->ai->demoData            = new stdclass();
$lang->ai->demoData->notExist  = 'The demo data does not exist for now.';
$lang->ai->demoData->story     = array(
    'story' => array(
        'title'    => 'Develop an online learning platform',
        'spec'     => 'We need to develop an online learning platform that provides course management, student management, teacher management, and other functions.',
        'verify' => '1. All functions can operate properly without any obvious errors or abnormalities.2. The interface is aesthetically pleasing and user-friendly.3. The platform can meet user needs and has a high level of user satisfaction.4. The code has good quality, with clear structure and easy maintenance.',
        'module'   => 7,
        'pri'      => 1,
        'estimate' => 1,
        'product'  => 1,
        'category' => 'feature',
    ),
);
$lang->ai->demoData->execution = array(
    'execution' => array(
        'name'     => 'Online Learning Platform Software Development',
        'desc'     => 'This plan aims to develop an online learning platform software that provides accessible learning resources, including text, video, and audio, as well as some learning tools such as exams, tests, and discussion forums.',
        'estimate' => 7,
    ),
    'tasks'     => array(
        0 =>
        array(
            'name'         => 'Technology Selection',
            'pri'          => 1,
            'status'       => 'done',
            'estimate'     => 1,
            'consumed'     => 1,
            'left'         => 0,
            'progress'     => 100,
            'estStarted'   => '2023-07-02 00:00:00',
            'realStarted'  => '2023-07-02 00:00:00',
            'finishedDate' => '2023-07-02 00:00:00',
            'closedReason' => 'Completed',
        ),
        1 =>
        array(
            'name'         => 'UI Design',
            'pri'          => 1,
            'status'       => 'doing',
            'estimate'     => 2,
            'consumed'     => 1,
            'left'         => 1,
            'progress'     => 50,
            'estStarted'   => '2023-07-03 00:00:00',
            'realStarted'  => '2023-07-03 00:00:00',
            'finishedDate' => '',
            'closedReason' => '',
        ),
        2 =>
        array(
            'name'         => 'Development',
            'pri'          => 1,
            'status'       => 'wait',
            'estimate'     => 1,
            'consumed'     => 0,
            'left'         => 1,
            'progress'     => 0,
            'estStarted'   => '',
            'realStarted'  => '',
            'finishedDate' => '',
            'closedReason' => '',
        ),
    ),
);

/* Forms as JSON Schemas. */
$lang->ai->formSchema = array();
$lang->ai->formSchema['story']['create'] = new stdclass();
$lang->ai->formSchema['story']['create']->title = 'Story';
$lang->ai->formSchema['story']['create']->type  = 'object';
$lang->ai->formSchema['story']['create']->properties = new stdclass();
$lang->ai->formSchema['story']['create']->properties->title  = new stdclass();
$lang->ai->formSchema['story']['create']->properties->spec   = new stdclass();
$lang->ai->formSchema['story']['create']->properties->verify = new stdclass();
$lang->ai->formSchema['story']['create']->properties->title->type         = 'string';
$lang->ai->formSchema['story']['create']->properties->title->description  = 'Title of story';
$lang->ai->formSchema['story']['create']->properties->spec->type          = 'string';
$lang->ai->formSchema['story']['create']->properties->spec->description   = 'Description of story';
$lang->ai->formSchema['story']['create']->properties->verify->type        = 'string';
$lang->ai->formSchema['story']['create']->properties->verify->description = 'Acceptance criteria of story';
$lang->ai->formSchema['story']['create']->required = array('title', 'spec', 'verify');
$lang->ai->formSchema['story']['change'] = $lang->ai->formSchema['story']['create'];

$lang->ai->formSchema['story']['batchcreate'] = new stdclass();
$lang->ai->formSchema['story']['batchcreate']->title = 'Stories';
$lang->ai->formSchema['story']['batchcreate']->type  = 'object';
$lang->ai->formSchema['story']['batchcreate']->properties = new stdclass();
$lang->ai->formSchema['story']['batchcreate']->properties->stories  = new stdclass();
$lang->ai->formSchema['story']['batchcreate']->properties->stories->type        = 'array';
$lang->ai->formSchema['story']['batchcreate']->properties->stories->description = 'Stories';
$lang->ai->formSchema['story']['batchcreate']->properties->stories->items       = $lang->ai->formSchema['story']['create'];

$lang->ai->formSchema['productplan']['create'] = new stdclass();
$lang->ai->formSchema['productplan']['create']->title = 'Product Plan';
$lang->ai->formSchema['productplan']['create']->type  = 'object';
$lang->ai->formSchema['productplan']['create']->properties = new stdclass();
$lang->ai->formSchema['productplan']['create']->properties->title  = new stdclass();
$lang->ai->formSchema['productplan']['create']->properties->begin  = new stdclass();
$lang->ai->formSchema['productplan']['create']->properties->end    = new stdclass();
$lang->ai->formSchema['productplan']['create']->properties->desc   = new stdclass();
$lang->ai->formSchema['productplan']['create']->properties->title->type         = 'string';
$lang->ai->formSchema['productplan']['create']->properties->title->description  = 'Title of product plan';
$lang->ai->formSchema['productplan']['create']->properties->begin->type         = 'date';
$lang->ai->formSchema['productplan']['create']->properties->begin->description  = 'Begin date of product plan';
$lang->ai->formSchema['productplan']['create']->properties->end->type           = 'date';
$lang->ai->formSchema['productplan']['create']->properties->end->description    = 'End date of product plan';
$lang->ai->formSchema['productplan']['create']->properties->desc->type          = 'string';
$lang->ai->formSchema['productplan']['create']->properties->desc->description   = 'Description of product plan';
$lang->ai->formSchema['productplan']['create']->required = array('title', 'begin', 'end');
$lang->ai->formSchema['productplan']['edit'] = $lang->ai->formSchema['productplan']['create'];

$lang->ai->formSchema['task']['create'] = new stdclass();
$lang->ai->formSchema['task']['create']->title = 'Task';
$lang->ai->formSchema['task']['create']->type  = 'object';
$lang->ai->formSchema['task']['create']->properties = new stdclass();
$lang->ai->formSchema['task']['create']->properties->type     = new stdclass();
$lang->ai->formSchema['task']['create']->properties->name     = new stdclass();
$lang->ai->formSchema['task']['create']->properties->desc     = new stdclass();
$lang->ai->formSchema['task']['create']->properties->pri      = new stdclass();
$lang->ai->formSchema['task']['create']->properties->estimate = new stdclass();
$lang->ai->formSchema['task']['create']->properties->begin    = new stdclass();
$lang->ai->formSchema['task']['create']->properties->end      = new stdclass();
$lang->ai->formSchema['task']['create']->properties->type->type            = 'string';
$lang->ai->formSchema['task']['create']->properties->type->description     = 'Type of task';
$lang->ai->formSchema['task']['create']->properties->type->enum            = array('design', 'devel', 'request', 'test', 'study', 'discuss', 'ui', 'affair', 'misc');
$lang->ai->formSchema['task']['create']->properties->name->type            = 'string';
$lang->ai->formSchema['task']['create']->properties->name->description     = 'Name of task';
$lang->ai->formSchema['task']['create']->properties->desc->type            = 'string';
$lang->ai->formSchema['task']['create']->properties->desc->description     = 'Description of task';
$lang->ai->formSchema['task']['create']->properties->pri->type             = 'string';
$lang->ai->formSchema['task']['create']->properties->pri->description      = 'Priority of task';
$lang->ai->formSchema['task']['create']->properties->pri->enum             = array('1', '2', '3', '4');
$lang->ai->formSchema['task']['create']->properties->estimate->type        = 'number';
$lang->ai->formSchema['task']['create']->properties->estimate->description = 'Estimated hours of task';
$lang->ai->formSchema['task']['create']->properties->begin->type           = 'string';
$lang->ai->formSchema['task']['create']->properties->begin->format         = 'date';
$lang->ai->formSchema['task']['create']->properties->begin->description    = 'Begin date of task';
$lang->ai->formSchema['task']['create']->properties->end->type             = 'string';
$lang->ai->formSchema['task']['create']->properties->end->format           = 'date';
$lang->ai->formSchema['task']['create']->properties->end->description      = 'End date of task';
$lang->ai->formSchema['task']['create']->required = array('type', 'name');
$lang->ai->formSchema['task']['edit'] = $lang->ai->formSchema['task']['create'];

$lang->ai->formSchema['task']['batchcreate'] = new stdclass();
$lang->ai->formSchema['task']['batchcreate']->title = 'Tasks';
$lang->ai->formSchema['task']['batchcreate']->type  = 'object';
$lang->ai->formSchema['task']['batchcreate']->properties = new stdclass();
$lang->ai->formSchema['task']['batchcreate']->properties->tasks  = new stdclass();
$lang->ai->formSchema['task']['batchcreate']->properties->tasks->type                          = 'array';
$lang->ai->formSchema['task']['batchcreate']->properties->tasks->description                   = 'Tasks';
$lang->ai->formSchema['task']['batchcreate']->properties->tasks->items                         = $lang->ai->formSchema['task']['create'];
$lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->estStarted = clone $lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->begin;
$lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->deadline   = clone $lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->end;
unset($lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->begin);
unset($lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->end);

$lang->ai->formSchema['bug']['create'] = new stdclass();
$lang->ai->formSchema['bug']['create']->title = 'Bug';
$lang->ai->formSchema['bug']['create']->type  = 'object';
$lang->ai->formSchema['bug']['create']->properties = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->title       = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->steps       = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->severity    = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->pri         = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->openedBuild = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->title->type              = 'string';
$lang->ai->formSchema['bug']['create']->properties->title->description       = 'Title of bug';
$lang->ai->formSchema['bug']['create']->properties->steps->type              = 'string';
$lang->ai->formSchema['bug']['create']->properties->steps->description       = 'Repro steps of bug';
$lang->ai->formSchema['bug']['create']->properties->severity->type           = 'string';
$lang->ai->formSchema['bug']['create']->properties->severity->description    = 'Severity of bug';
$lang->ai->formSchema['bug']['create']->properties->severity->enum           = array('1', '2', '3', '4');
$lang->ai->formSchema['bug']['create']->properties->pri->type                = 'string';
$lang->ai->formSchema['bug']['create']->properties->pri->description         = 'Priority of bug';
$lang->ai->formSchema['bug']['create']->properties->pri->enum                = array('1', '2', '3', '4');
$lang->ai->formSchema['bug']['create']->properties->openedBuild->type        = 'string';
$lang->ai->formSchema['bug']['create']->properties->openedBuild->description = 'Affected builds of bug';
$lang->ai->formSchema['bug']['create']->properties->openedBuild->enum        = array('trunk');
$lang->ai->formSchema['bug']['create']->required = array('title', 'steps', 'severity', 'pri', 'openedBuild');
$lang->ai->formSchema['bug']['edit'] = $lang->ai->formSchema['bug']['create'];

$lang->ai->formSchema['testcase']['create'] = new stdclass();
$lang->ai->formSchema['testcase']['create']->title = 'Test Case';
$lang->ai->formSchema['testcase']['create']->type  = 'object';
$lang->ai->formSchema['testcase']['create']->properties = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->type                             = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->stage                            = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->title                            = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->precondition                     = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->steps                            = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->steps->items                     = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties         = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->steps   = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->expects = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->type->type                                     = 'string';
$lang->ai->formSchema['testcase']['create']->properties->type->description                              = 'Type of test case';
$lang->ai->formSchema['testcase']['create']->properties->type->enum                                     = array('feature', 'performance', 'config', 'install', 'security', 'interface', 'unit', 'other');
$lang->ai->formSchema['testcase']['create']->properties->stage->type                                    = 'string';
$lang->ai->formSchema['testcase']['create']->properties->stage->description                             = 'Stage of test case';
$lang->ai->formSchema['testcase']['create']->properties->stage->enum                                    = array('unittest', 'feature', 'intergrate', 'system', 'smoke', 'bvt');
$lang->ai->formSchema['testcase']['create']->properties->title->type                                    = 'string';
$lang->ai->formSchema['testcase']['create']->properties->title->description                             = 'Title of test case';
$lang->ai->formSchema['testcase']['create']->properties->precondition->type                             = 'string';
$lang->ai->formSchema['testcase']['create']->properties->precondition->description                      = 'Precondition of test case';
$lang->ai->formSchema['testcase']['create']->properties->steps->type                                    = 'array';
$lang->ai->formSchema['testcase']['create']->properties->steps->description                             = 'Steps of test case';
$lang->ai->formSchema['testcase']['create']->properties->steps->items->type                             = 'object';
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->steps->type          = 'string';
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->steps->description   = 'Step description';
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->expects->type        = 'string';
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->expects->description = 'Expectation of step';
$lang->ai->formSchema['testcase']['create']->required = array('type', 'title', 'steps');
$lang->ai->formSchema['testcase']['edit'] = $lang->ai->formSchema['testcase']['create'];

$lang->ai->formSchema['testreport']['create'] = new stdclass();
$lang->ai->formSchema['testreport']['create']->title = 'Test Report';
$lang->ai->formSchema['testreport']['create']->type  = 'object';
$lang->ai->formSchema['testreport']['create']->properties = new stdclass();
$lang->ai->formSchema['testreport']['create']->properties->begin  = new stdclass();
$lang->ai->formSchema['testreport']['create']->properties->end    = new stdclass();
$lang->ai->formSchema['testreport']['create']->properties->title  = new stdclass();
$lang->ai->formSchema['testreport']['create']->properties->report = new stdclass();
$lang->ai->formSchema['testreport']['create']->properties->begin->type         = 'string';
$lang->ai->formSchema['testreport']['create']->properties->begin->format       = 'date';
$lang->ai->formSchema['testreport']['create']->properties->begin->description  = 'Begin date of testing';
$lang->ai->formSchema['testreport']['create']->properties->end->type           = 'string';
$lang->ai->formSchema['testreport']['create']->properties->end->format         = 'date';
$lang->ai->formSchema['testreport']['create']->properties->end->description    = 'End date of testing';
$lang->ai->formSchema['testreport']['create']->properties->title->type         = 'string';
$lang->ai->formSchema['testreport']['create']->properties->title->description  = 'Title of test report';
$lang->ai->formSchema['testreport']['create']->properties->report->type        = 'string';
$lang->ai->formSchema['testreport']['create']->properties->report->description = 'Report content';
$lang->ai->formSchema['testreport']['create']->required = array('begin', 'end', 'title', 'report');
$lang->ai->formSchema['execution']['testreport'] = $lang->ai->formSchema['testreport']['create'];

$lang->ai->formSchema['doc']['edit'] = new stdclass();
$lang->ai->formSchema['doc']['edit']->title = 'Document';
$lang->ai->formSchema['doc']['edit']->type  = 'object';
$lang->ai->formSchema['doc']['edit']->properties = new stdclass();
$lang->ai->formSchema['doc']['edit']->properties->title   = new stdclass();
$lang->ai->formSchema['doc']['edit']->properties->content = new stdclass();
$lang->ai->formSchema['doc']['edit']->properties->title->type          = 'string';
$lang->ai->formSchema['doc']['edit']->properties->title->description   = 'Title of the document';
$lang->ai->formSchema['doc']['edit']->properties->content->type        = 'string';
$lang->ai->formSchema['doc']['edit']->properties->content->description = 'Content of the document';
$lang->ai->formSchema['doc']['edit']->required = array('title', 'content');

$lang->ai->formSchema['tree']['browse'] = new stdclass();
$lang->ai->formSchema['tree']['browse']->title = 'Modules';
$lang->ai->formSchema['tree']['browse']->type  = 'object';
$lang->ai->formSchema['tree']['browse']->properties = new stdclass();
$lang->ai->formSchema['tree']['browse']->properties->modules = new stdclass();
$lang->ai->formSchema['tree']['browse']->properties->modules->type  = 'array';
$lang->ai->formSchema['tree']['browse']->properties->modules->title = 'Modules';
$lang->ai->formSchema['tree']['browse']->properties->modules->items = new stdclass();
$lang->ai->formSchema['tree']['browse']->properties->modules->items->type = 'string';
$lang->ai->formSchema['tree']['browse']->required = array('modules');

$lang->ai->formSchema['programplan']['create'] = new stdclass();
$lang->ai->formSchema['programplan']['create']->title = 'Program Plan';
$lang->ai->formSchema['programplan']['create']->type  = 'object';
$lang->ai->formSchema['programplan']['create']->properties = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->type  = 'array';
$lang->ai->formSchema['programplan']['create']->properties->stages->title = 'Plans';
$lang->ai->formSchema['programplan']['create']->properties->stages->items = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->type = 'object';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->names      = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->attributes = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->milestone  = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->begin      = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->end        = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->names->type             = 'string';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->names->description      = 'Name of stage';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->attributes->type        = 'string';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->attributes->description = 'Attribute of stage';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->attributes->enum        = array('request', 'design', 'dev', 'qa', 'release', 'review', 'other');
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->milestone->type         = 'boolean';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->milestone->description  = 'Is milestone?';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->begin->type             = 'string';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->begin->format           = 'date';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->begin->description      = 'Begin date of stage';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->end->type               = 'string';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->end->format             = 'date';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->end->description        = 'End date of stage';
$lang->ai->formSchema['programplan']['create']->required = array('stages');

$lang->ai->promptMenu = new stdclass();
$lang->ai->promptMenu->dropdownTitle = 'AI';

$lang->ai->dataInject = new stdclass();
$lang->ai->dataInject->success = 'ZenTao agent execution results are filled in.';
$lang->ai->dataInject->fail    = 'Failed to fill in zenTao agent execution results.';

$lang->ai->execute = new stdclass();
$lang->ai->execute->loading    = 'ZenTao agent executing...';
$lang->ai->execute->auditing   = 'Getting ready for auditing...';
$lang->ai->execute->success    = 'ZenTao agent executed.';
$lang->ai->execute->fail       = 'ZenTao agent execution failed.';
$lang->ai->execute->failFormat = 'ZenTao agent execution failed: %s.';
$lang->ai->execute->failReasons = array();
$lang->ai->execute->failReasons['noPrompt']     = 'unable to get zenTao agent';
$lang->ai->execute->failReasons['noObjectData'] = 'unable to get object data';
$lang->ai->execute->failReasons['noResponse']   = 'no response from external service';
$lang->ai->execute->failReasons['noTargetForm'] = 'unable to get target form or its required fields';
$lang->ai->execute->executeErrors = array();
$lang->ai->execute->executeErrors['-1'] = 'unable to get zenTao agent';
$lang->ai->execute->executeErrors['-2'] = 'unable to get object data';
$lang->ai->execute->executeErrors['-3'] = 'failed to serialize object data';
$lang->ai->execute->executeErrors['-4'] = 'unable to find available model';
$lang->ai->execute->executeErrors['-5'] = 'unable to get target form schema';
$lang->ai->execute->executeErrors['-6'] = 'request failed or API returned error';

$lang->ai->audit = new stdclass();
$lang->ai->audit->designPrompt = 'ZenTao agent Design';
$lang->ai->audit->afterSave    = 'After saving,';
$lang->ai->audit->regenerate   = 'Regenerate';
$lang->ai->audit->exit         = 'Exit Audit';

$lang->ai->audit->backLocationList = array();
$lang->ai->audit->backLocationList[0] = 'back to audit page.';
$lang->ai->audit->backLocationList[1] = 'back to audit page and regenerate.';

$lang->ai->engineeredPrompts = new stdclass();
$lang->ai->engineeredPrompts->askForFunctionCalling = array((object)array('role' => 'user', 'content' => 'Please convert my next message into a function call.'), (object)array('role' => 'assistant', 'content' => 'Sure, I\'ll convert your next message into a function call.'));

$lang->ai->aiResponseException = array();
$lang->ai->aiResponseException['notFunctionCalling'] = 'The response is not a function calling';

$lang->ai->assistant = new stdclass();
$lang->ai->assistant->view                     = 'AI Assistant Details';
$lang->ai->assistant->title                    = 'AI Assistant';
$lang->ai->assistant->create                   = 'Add Assistant';
$lang->ai->assistant->details                  = 'Assistant Details';
$lang->ai->assistant->edit                     = 'Edit Assistant';
$lang->ai->assistant->name                     = 'Assistant Name';
$lang->ai->assistant->refModel                 = 'Referenced Language Model';
$lang->ai->assistant->createdDate              = 'Creation Time';
$lang->ai->assistant->publishedDate            = 'Publication Time';
$lang->ai->assistant->desc                     = 'Description';
$lang->ai->assistant->descPlaceholder          = 'Please briefly describe the functions of this AI assistant and the experience it can bring to users.';
$lang->ai->assistant->systemMessage            = 'System Built-in Message';
$lang->ai->assistant->systemMessagePlaceholder = 'You can give this AI dialogue a "persona", for example, "You are a weekly report assistant, you will generate a formatted weekly report based on the input content".';
$lang->ai->assistant->greetings                = 'Greetings';
$lang->ai->assistant->greetingsPlaceholder     = 'You can set the greeting message for this AI dialogue, for example, "Hello, I am your weekly report assistant, are you still troubled by writing weekly reports? Try sending me a week\'s work?"';
$lang->ai->assistant->publish                  = 'Publish';
$lang->ai->assistant->withdraw                 = 'Disable';
$lang->ai->assistant->confirmPublishTip        = 'After publishing, it will be displayed in the AI dialogue and client dialogue in the lower right corner of ZenTao. Do you want to confirm the publication? ';
$lang->ai->assistant->confirmWithdrawTip       = 'After deactivation, front-end users will not be able to see this AI assistant. Do you confirm the deactivation? ';
$lang->ai->assistant->duplicateTip             = 'Assistant names in the same language model cannot be same.';
$lang->ai->assistant->confirmDeleteTip         = 'Do you want to confirm the deletion? ';
$lang->ai->assistant->switchAndClearContext    = 'Switch assistant %s, context has been cleared';
$lang->ai->assistant->noLlm                    = 'No language model is available, please create a language model first.';
$lang->ai->assistant->defaultAssistant         = 'Omni Assistant';

$lang->ai->assistant->statusList = array();
$lang->ai->assistant->statusList['0']   = 'Unpublished';
$lang->ai->assistant->statusList['off'] = 'Unpublished';
$lang->ai->assistant->statusList['1']   = 'Published';
$lang->ai->assistant->statusList['on']  = 'Published';

// for render action changes.
$lang->aiassistant = $lang->ai->assistant;
