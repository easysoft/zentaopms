<?php
/**
 * The ai module de lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
$lang->ai->common = 'AI';

/* Definitions of table columns, used to sprintf error messages to dao::$errors. */
$lang->prompt  = new stdclass();
$lang->prompt->name             = 'Name';
$lang->prompt->desc             = 'Description';
$lang->prompt->model            = 'Model';
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

$lang->ai->nextStep  = 'Next';
$lang->ai->goTesting = 'Go testing';

$lang->ai->validate = new stdclass();
$lang->ai->validate->noEmpty       = '%s cannot be empty.';
$lang->ai->validate->dirtyForm     = 'The design step of %s has changed. Do you want to save and return it?';
$lang->ai->validate->nameNotUnique = 'A prompt with the same name already exists, please change the name.';

$lang->ai->prompts = new stdclass();
$lang->ai->prompts->common      = 'Prompt';
$lang->ai->prompts->emptyList   = 'No prompts yet.';
$lang->ai->prompts->create      = 'Create Prompt';
$lang->ai->prompts->edit        = 'Edit Prompt';
$lang->ai->prompts->id          = 'ID';
$lang->ai->prompts->name        = 'Name';
$lang->ai->prompts->description = 'Description';
$lang->ai->prompts->createdBy   = 'Creator';
$lang->ai->prompts->createdDate = 'Created Date';
$lang->ai->prompts->targetForm  = 'Target Form';
$lang->ai->prompts->funcDesc    = 'Function Description';
$lang->ai->prompts->deleted     = 'Deleted';
$lang->ai->prompts->stage       = 'Stage';

$lang->ai->prompts->basicInfo   = 'Basic Info';
$lang->ai->prompts->editInfo    = 'Edit Info';
$lang->ai->prompts->createdBy   = 'Created By';
$lang->ai->prompts->publishedBy = 'Published By';
$lang->ai->prompts->draftedBy   = 'Drafted By';
$lang->ai->prompts->lastEditor  = 'Last Editor';

$lang->ai->prompts->summary = 'There are %s prompts on this page.';
$lang->ai->prompts->action = new stdclass();
$lang->ai->prompts->action->goDesignConfirm = 'The current prompt is not complete, continue designing?';
$lang->ai->prompts->action->goDesign        = 'Go designing';
$lang->ai->prompts->action->draftConfirm    = 'Once unpublished, the prompt cannot be used any further. Are you sure you want to proceed?';
$lang->ai->prompts->action->design          = 'Design';
$lang->ai->prompts->action->test            = 'Test';
$lang->ai->prompts->action->edit            = 'Edit';
$lang->ai->prompts->action->publish         = 'Publish';
$lang->ai->prompts->action->unpublish       = 'Unpublish';
$lang->ai->prompts->action->delete          = 'Delete';
$lang->ai->prompts->action->deleteConfirm   = 'Deleted prompts will be no longer available. Are you sure you want to proceed?';
$lang->ai->prompts->action->publishSuccess  = 'Publish Success';

/* Steps of prompt creation. */
$lang->ai->prompts->assignRole       = 'Assign Role';
$lang->ai->prompts->selectDataSource = 'Select Data Source';
$lang->ai->prompts->setPurpose       = 'Set Purpose';
$lang->ai->prompts->setTargetForm    = 'Set Target Form';
$lang->ai->prompts->finalize         = 'Finalize';

/* Role assigning. */
$lang->ai->prompts->assignModel      = 'Select Model';
$lang->ai->prompts->model            = 'Model';
$lang->ai->prompts->role             = 'Role';
$lang->ai->prompts->characterization = 'Characterization';
$lang->ai->prompts->rolePlaceholder  = '"Act as a <role>"';
$lang->ai->prompts->charPlaceholder  = 'Detailed characterization of this role';

/* Data source selecting. */
$lang->ai->prompts->selectData       = 'Select data';
$lang->ai->prompts->selectDataTip    = 'Select an object and its fields will be shown below.';
$lang->ai->prompts->selectedFormat   = 'Selecting data from {0}, {1} fields selected.';
$lang->ai->prompts->nonSelected      = 'No data selected.';
$lang->ai->prompts->sortTip          = 'Sorting fields by priority is suggested.';
$lang->ai->prompts->object           = 'object';
$lang->ai->prompts->field            = 'field';

/* Purpose setting. */
$lang->ai->prompts->purpose        = 'Purpose';
$lang->ai->prompts->purposeTip     = '';
$lang->ai->prompts->elaboration    = 'Elaboration';
$lang->ai->prompts->elaborationTip = '';
$lang->ai->prompts->inputPreview   = 'Prompt Preview';
$lang->ai->prompts->dataPreview    = 'Data Prompt Preview';
$lang->ai->prompts->rolePreview    = 'Role Prompt Preview';
$lang->ai->prompts->promptPreview  = 'Purpose Prompt Preview';

/* Target form selecting. */
$lang->ai->prompts->selectTargetForm    = 'Select Target Form';
$lang->ai->prompts->selectTargetFormTip = 'Results returned from LLMs can be directly inputed into forms within ZenTao.';
$lang->ai->prompts->goingTesting        = 'Redirecting';
$lang->ai->prompts->goingTestingFail    = 'Unable to go testing, no suitable object found.';

/* Finalize page. */
$lang->ai->moduleDisableTip = 'Module is automatically selected based on selected objects.';

/* Data source definition. */
$lang->ai->dataSource = array();

$lang->ai->dataSource['my']['common']          = 'My';
$lang->ai->dataSource['product']['common']     = 'Product';
$lang->ai->dataSource['story']['common']       = 'Story';
$lang->ai->dataSource['execution']['common']   = 'Execution';
$lang->ai->dataSource['productplan']['common'] = 'Product Plan';
$lang->ai->dataSource['release']['common']     = 'Release';
$lang->ai->dataSource['project']['common']     = 'Project';
$lang->ai->dataSource['task']['common']        = 'Task';
$lang->ai->dataSource['case']['common']        = 'Test Case';
$lang->ai->dataSource['bug']['common']         = 'Bug';
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

$lang->ai->dataSource['project']['programplan']['common']       = 'Program Plan';
$lang->ai->dataSource['project']['programplan']['name']         = 'Name';
$lang->ai->dataSource['project']['programplan']['desc']         = 'Description';
$lang->ai->dataSource['project']['programplan']['status']       = 'Status';
$lang->ai->dataSource['project']['programplan']['begin']        = 'Begin';
$lang->ai->dataSource['project']['programplan']['end']          = 'End';
$lang->ai->dataSource['project']['programplan']['realBegan']    = 'Actual Start';
$lang->ai->dataSource['project']['programplan']['realEnd']      = 'Actual End';
$lang->ai->dataSource['project']['programplan']['planDuration'] = 'Plan Duration';
$lang->ai->dataSource['project']['programplan']['progress']     = 'Progress';
$lang->ai->dataSource['project']['programplan']['estimate']     = 'Estimates';
$lang->ai->dataSource['project']['programplan']['consumed']     = 'Consumed';
$lang->ai->dataSource['project']['programplan']['left']         = 'Left';

$lang->ai->dataSource['project']['execution']['common']    = 'Execution';
$lang->ai->dataSource['project']['execution']['name']      = 'Name';
$lang->ai->dataSource['project']['execution']['desc']      = 'Description';
$lang->ai->dataSource['project']['execution']['status']    = 'Status';
$lang->ai->dataSource['project']['execution']['begin']     = 'Begin';
$lang->ai->dataSource['project']['execution']['end']       = 'End';
$lang->ai->dataSource['project']['execution']['realBegan'] = 'Actual Start';
$lang->ai->dataSource['project']['execution']['realEnd']   = 'Actual End';
$lang->ai->dataSource['project']['execution']['estimate']  = 'Estimates';
$lang->ai->dataSource['project']['execution']['consumed']  = 'Consumed';
$lang->ai->dataSource['project']['execution']['left']      = 'Left';
$lang->ai->dataSource['project']['execution']['progress']  = 'Progress';

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

$lang->ai->dataSource['execution']['tasks']['common']      = 'Task List';
$lang->ai->dataSource['execution']['tasks']['name']        = 'Name';
$lang->ai->dataSource['execution']['tasks']['pri']         = 'Priority';
$lang->ai->dataSource['execution']['tasks']['status']      = 'Status';
$lang->ai->dataSource['execution']['tasks']['estimate']    = 'Estimated hours';
$lang->ai->dataSource['execution']['tasks']['consumed']    = 'Consumed hours';
$lang->ai->dataSource['execution']['tasks']['left']        = 'Remaining hours';
$lang->ai->dataSource['execution']['tasks']['progress']    = 'Progress';
$lang->ai->dataSource['execution']['tasks']['estStarted']  = 'Estimated start date';
$lang->ai->dataSource['execution']['tasks']['realStarted'] = 'Actual start date';
$lang->ai->dataSource['execution']['tasks']['finishedDate']= 'Finished date';
$lang->ai->dataSource['execution']['tasks']['closedReason']= 'Closing reason';

$lang->ai->dataSource['task']['task']['common']      = 'Task';
$lang->ai->dataSource['task']['task']['name']        = 'Name';
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
$lang->ai->targetForm['task']['batchCreate'] = 'Batch Create Task';

$lang->ai->targetForm['testcase']['edit']         = 'Edit Test Case';
$lang->ai->targetForm['testcase']['createscript'] = 'Create Script';

$lang->ai->targetForm['bug']['edit']            = 'Edit Bug';
$lang->ai->targetForm['bug']['story/create']    = 'Bug to Story';
$lang->ai->targetForm['bug']['testcase/create'] = 'Bug to Test Case';

$lang->ai->targetForm['doc']['create'] = 'Create Doc';
$lang->ai->targetForm['doc']['edit']   = 'Edit Doc';

$lang->ai->prompts->statuses = array();
$lang->ai->prompts->statuses['']       = 'All';
$lang->ai->prompts->statuses['draft']  = 'Draft';
$lang->ai->prompts->statuses['active'] = 'Active';

$lang->ai->prompts->modules = array();
$lang->ai->prompts->modules['']            = 'All';
$lang->ai->prompts->modules['my']          = 'My';
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

$lang->ai->models = new stdclass();
$lang->ai->models->title          = 'Language Model Configuration';
$lang->ai->models->common         = 'Language Model';
$lang->ai->models->type           = 'Model';
$lang->ai->models->apiKey         = 'API Key';
$lang->ai->models->proxyType      = 'Proxy Type';
$lang->ai->models->proxyAddr      = 'Proxy Address';
$lang->ai->models->description    = 'Description';
$lang->ai->models->testConnection = 'Test Connection';
$lang->ai->models->unconfigured   = 'Unconfigured';
$lang->ai->models->edit           = 'Edit Parameters';
$lang->ai->models->concealTip     = 'Visible when editing';

$lang->ai->models->testConnectionResult = new stdclass();
$lang->ai->models->testConnectionResult->success = 'Successfully connected';
$lang->ai->models->testConnectionResult->fail    = 'Failed to connect';

$lang->ai->models->statusList = array();
$lang->ai->models->statusList['on']  = 'Enable';
$lang->ai->models->statusList['off'] = 'Disable';

$lang->ai->models->typeList = array();
$lang->ai->models->typeList['openai-gpt35'] = 'OpenAI / GPT-3.5';
// $lang->ai->models->typeList['azure-gpt35']  = 'Azure / GPT-3.5';

$lang->ai->models->proxyTypes = array();
$lang->ai->models->proxyTypes['']       = 'No Proxy';
$lang->ai->models->proxyTypes['socks5'] = 'SOCKS5';

$lang->ai->models->promptFor = 'Prompt for %s';

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
    'story' => array
    (
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
$lang->ai->demoData->execution = array
(
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
$lang->ai->formSchema['story']['batchcreate']->type  = 'array';
$lang->ai->formSchema['story']['batchcreate']->items = $lang->ai->formSchema['story']['create'];

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
$lang->ai->formSchema['task']['create']->properties->begin->type           = 'date';
$lang->ai->formSchema['task']['create']->properties->begin->description    = 'Begin date of task';
$lang->ai->formSchema['task']['create']->properties->end->type             = 'date';
$lang->ai->formSchema['task']['create']->properties->end->description      = 'End date of task';
$lang->ai->formSchema['task']['create']->required = array('type', 'name');
$lang->ai->formSchema['task']['edit'] = $lang->ai->formSchema['task']['create'];

$lang->ai->formSchema['task']['batchcreate'] = new stdclass();
$lang->ai->formSchema['task']['batchcreate']->title = 'Tasks';
$lang->ai->formSchema['task']['batchcreate']->type  = 'array';
$lang->ai->formSchema['task']['batchcreate']->items = $lang->ai->formSchema['task']['create'];

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
$lang->ai->formSchema['testcase']['create']->properties->type         = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->stage        = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->title        = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->precondition = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->steps        = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->expects      = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->type->type                = 'string';
$lang->ai->formSchema['testcase']['create']->properties->type->description         = 'Type of test case';
$lang->ai->formSchema['testcase']['create']->properties->type->enum                = array('feature', 'performance', 'config', 'install', 'security', 'interface', 'unit', 'other');
$lang->ai->formSchema['testcase']['create']->properties->stage->type               = 'string';
$lang->ai->formSchema['testcase']['create']->properties->stage->description        = 'Stage of test case';
$lang->ai->formSchema['testcase']['create']->properties->stage->enum               = array('unittest', 'feature', 'intergrate', 'system', 'smoke', 'bvt');
$lang->ai->formSchema['testcase']['create']->properties->title->type               = 'string';
$lang->ai->formSchema['testcase']['create']->properties->title->description        = 'Title of test case';
$lang->ai->formSchema['testcase']['create']->properties->precondition->type        = 'string';
$lang->ai->formSchema['testcase']['create']->properties->precondition->description = 'Precondition of test case';
$lang->ai->formSchema['testcase']['create']->properties->steps->type               = 'string';
$lang->ai->formSchema['testcase']['create']->properties->steps->description        = 'Steps of test case';
$lang->ai->formSchema['testcase']['create']->properties->expects->type             = 'string';
$lang->ai->formSchema['testcase']['create']->properties->expects->description      = 'Expectation of test case';
$lang->ai->formSchema['testcase']['create']->required = array('type', 'title', 'steps', 'expects');
$lang->ai->formSchema['testcase']['edit'] = $lang->ai->formSchema['testcase']['create'];

$lang->ai->formSchema['execution']['testreport'] = new stdclass();
$lang->ai->formSchema['execution']['testreport']->title = 'Test Report';
$lang->ai->formSchema['execution']['testreport']->type  = 'object';
$lang->ai->formSchema['execution']['testreport']->properties = new stdclass();
$lang->ai->formSchema['execution']['testreport']->properties->begin  = new stdclass();
$lang->ai->formSchema['execution']['testreport']->properties->end    = new stdclass();
$lang->ai->formSchema['execution']['testreport']->properties->title  = new stdclass();
$lang->ai->formSchema['execution']['testreport']->properties->report = new stdclass();
$lang->ai->formSchema['execution']['testreport']->properties->begin->type         = 'date';
$lang->ai->formSchema['execution']['testreport']->properties->begin->description  = 'Begin date of testing';
$lang->ai->formSchema['execution']['testreport']->properties->end->type           = 'date';
$lang->ai->formSchema['execution']['testreport']->properties->end->description    = 'End date of testing';
$lang->ai->formSchema['execution']['testreport']->properties->title->type         = 'string';
$lang->ai->formSchema['execution']['testreport']->properties->title->description  = 'Title of test report';
$lang->ai->formSchema['execution']['testreport']->properties->report->type        = 'string';
$lang->ai->formSchema['execution']['testreport']->properties->report->description = 'Report content';
$lang->ai->formSchema['execution']['testreport']->required = array('begin', 'end', 'title', 'report');

$lang->ai->formSchema['story']['change'] = new stdclass();
$lang->ai->formSchema['story']['change']->title = 'Story';
$lang->ai->formSchema['story']['change']->type  = 'object';
$lang->ai->formSchema['story']['change']->properties = new stdclass();
$lang->ai->formSchema['story']['change']->properties->title  = new stdclass();
$lang->ai->formSchema['story']['change']->properties->spec   = new stdclass();
$lang->ai->formSchema['story']['change']->properties->verify = new stdclass();
$lang->ai->formSchema['story']['change']->properties->title->type         = 'string';
$lang->ai->formSchema['story']['change']->properties->title->description  = 'Title of the story';
$lang->ai->formSchema['story']['change']->properties->spec->type          = 'string';
$lang->ai->formSchema['story']['change']->properties->spec->description   = 'Description of the story';
$lang->ai->formSchema['story']['change']->properties->verify->type        = 'string';
$lang->ai->formSchema['story']['change']->properties->verify->description = 'Acceptance criteria of the story';
$lang->ai->formSchema['story']['change']->required = array('title', 'spec', 'verify');

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

$lang->ai->formSchema['tree']['browse'] = new stdclass(); // TODO: This might not work.
$lang->ai->formSchema['tree']['browse']->title = 'Modules';
$lang->ai->formSchema['tree']['browse']->type  = 'array';
$lang->ai->formSchema['tree']['browse']->items = new stdclass();
$lang->ai->formSchema['tree']['browse']->items->type = 'object';
$lang->ai->formSchema['tree']['browse']->items->properties = new stdclass();
$lang->ai->formSchema['tree']['browse']->items->properties->{'modules[]'} = new stdclass();
$lang->ai->formSchema['tree']['browse']->items->properties->{'modules[]'}->type = 'string';
$lang->ai->formSchema['tree']['browse']->items->properties->{'modules[]'}->description = 'Name of module';
$lang->ai->formSchema['tree']['browse']->items->required = array('modules[]');

$lang->ai->formSchema['programplan']['create'] = new stdclass();
$lang->ai->formSchema['programplan']['create']->title = 'Program Plan';
$lang->ai->formSchema['programplan']['create']->type  = 'array';
$lang->ai->formSchema['programplan']['create']->items = new stdclass();
$lang->ai->formSchema['programplan']['create']->items->type = 'object';
$lang->ai->formSchema['programplan']['create']->items->properties = new stdclass();
$lang->ai->formSchema['programplan']['create']->items->properties->name      = new stdclass();
$lang->ai->formSchema['programplan']['create']->items->properties->attribute = new stdclass();
$lang->ai->formSchema['programplan']['create']->items->properties->milestone = new stdclass();
$lang->ai->formSchema['programplan']['create']->items->properties->begin     = new stdclass();
$lang->ai->formSchema['programplan']['create']->items->properties->end       = new stdclass();
$lang->ai->formSchema['programplan']['create']->items->properties->name->type             = 'string';
$lang->ai->formSchema['programplan']['create']->items->properties->name->description      = 'Name of stage';
$lang->ai->formSchema['programplan']['create']->items->properties->attribute->type        = 'string';
$lang->ai->formSchema['programplan']['create']->items->properties->attribute->description = 'Attribute of stage';
$lang->ai->formSchema['programplan']['create']->items->properties->attribute->enum        = array('request', 'design', 'dev', 'qa', 'release', 'review', 'other');
$lang->ai->formSchema['programplan']['create']->items->properties->milestone->type        = 'boolean';
$lang->ai->formSchema['programplan']['create']->items->properties->milestone->description = 'Is milestone?';
$lang->ai->formSchema['programplan']['create']->items->properties->begin->type            = 'date';
$lang->ai->formSchema['programplan']['create']->items->properties->begin->description     = 'Begin date of stage';
$lang->ai->formSchema['programplan']['create']->items->properties->end->type              = 'date';
$lang->ai->formSchema['programplan']['create']->items->properties->end->description       = 'End date of stage';
$lang->ai->formSchema['programplan']['create']->items->required = array('name', 'attribute', 'milestone', 'begin', 'end');

$lang->ai->promptMenu = new stdclass();
$lang->ai->promptMenu->dropdownTitle = 'AI';

$lang->ai->dataInject = new stdclass();
$lang->ai->dataInject->success = 'Prompt execution results are filled in.';
$lang->ai->dataInject->fail    = 'Failed to fill in prompt execution results.';

$lang->ai->execute = new stdclass();
$lang->ai->execute->success = 'Prompt executed.';
$lang->ai->execute->fail    = 'Prompt execution failed.';

$lang->ai->audit = new stdclass();
$lang->ai->audit->designPrompt = 'Prompt Design';
$lang->ai->audit->afterSave    = 'After saving,';
$lang->ai->audit->regenerate   = 'Regenerate';

$lang->ai->audit->backLocationList = array();
$lang->ai->audit->backLocationList[0] = 'back to audit page.';
$lang->ai->audit->backLocationList[1] = 'back to audit page and regenerate.';
