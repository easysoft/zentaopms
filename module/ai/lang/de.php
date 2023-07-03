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
$lang->prompt = new stdclass();
$lang->prompt->name  = 'Name';
$lang->prompt->desc  = 'Description';
$lang->prompt->model = 'Model';

$lang->ai->nextStep = 'Next';

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

/* Purpose setting. */
$lang->ai->prompts->purpose        = 'Purpose';
$lang->ai->prompts->purposeTip     = '';
$lang->ai->prompts->elaboration    = 'Elaboration';
$lang->ai->prompts->elaborationTip = '';
$lang->ai->prompts->inputPreview   = 'Prompt Preview';
$lang->ai->prompts->dataPreview    = 'Data Prompt Preview';
$lang->ai->prompts->rolePreview    = 'Role Prompt Preview';
$lang->ai->prompts->promptPreview  = 'Purpose Prompt Preview';

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

$lang->ai->prompts->statuses = array();
$lang->ai->prompts->statuses[''] = 'All';
$lang->ai->prompts->statuses['draft'] = 'Draft';

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
