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
$lang->ai->common = 'AI';

/* Definitions of table columns, used to sprintf error messages to dao::$errors. */
$lang->prompt  = new stdclass();
$lang->prompt->name        = 'Name';
$lang->prompt->desc        = 'Description';
$lang->prompt->model       = 'Model';
$lang->prompt->module      = 'Module';
$lang->prompt->basicInfo   = 'Basic Info';
$lang->prompt->editInfo    = 'Edit Info';
$lang->prompt->createdBy   = 'Created By';
$lang->prompt->publishedBy = 'Published By';
$lang->prompt->draftedBy   = 'Drafted By';
$lang->prompt->lastEditor  = 'Last Editor';

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
$lang->ai->prompts->id          = 'ID';
$lang->ai->prompts->name        = 'Name';
$lang->ai->prompts->description = 'Description';
$lang->ai->prompts->createdBy   = 'Creator';
$lang->ai->prompts->createdDate = 'Created Date';
$lang->ai->prompts->targetForm  = 'Target Form';
$lang->ai->prompts->funcDesc    = 'Function Description';
$lang->ai->prompts->deleted     = 'Deleted';
$lang->ai->prompts->stage       = 'Stage';

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
$lang->ai->dataSource['story']['common']     = 'Story';
$lang->ai->dataSource['execution']['common'] = 'Execution';

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

/* Target form definition. */
$lang->ai->targetForm = array();
$lang->ai->targetForm['story']['common']     = 'Story';
$lang->ai->targetForm['execution']['common'] = 'Execution';

$lang->ai->targetForm['story']['create']         = 'Create Story';
$lang->ai->targetForm['story']['batchcreate']    = 'Batch Create Story';
$lang->ai->targetForm['story']['change']         = 'Change Story';
$lang->ai->targetForm['story']['totask']         = 'Story to Task';
$lang->ai->targetForm['story']['testcasecreate'] = 'Create Test Case';
$lang->ai->targetForm['story']['subdivide']      = 'Subdivide Story';

$lang->ai->targetForm['execution']['batchcreatetask']  = 'Batch Create Task';
$lang->ai->targetForm['execution']['createtestreport'] = 'Create Test Report';
$lang->ai->targetForm['execution']['createqa']         = 'Create QA';
$lang->ai->targetForm['execution']['createrisk']       = 'Create Risk';
$lang->ai->targetForm['execution']['createissue']      = 'Create Issue';

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
