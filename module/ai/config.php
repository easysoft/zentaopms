<?php
$config->ai->vendorList = array();
$config->ai->vendorList['openai']['requiredFields'] = array('key');
$config->ai->vendorList['azure']['requiredFields']  = array('key', 'resource', 'deployment');
$config->ai->vendorList['baidu']['requiredFields']  = array('key', 'secret');

$config->ai->models = array('openai-gpt35' => 'openai', 'openai-gpt4' => 'openai', 'baidu-ernie' => 'ernie');

/* OpenAI GPT configurations. */
$config->ai->openai = new stdclass();
$config->ai->openai->api = new stdclass();
$config->ai->openai->api->vendor = array('openai', 'azure');
$config->ai->openai->api->openai = new stdclass();
$config->ai->openai->api->openai->version    = 'v1';                           // OpenAI API version, required.
$config->ai->openai->api->openai->format     = 'https://api.openai.com/%s/%s'; // OpenAI API format, args: API version, API name.
$config->ai->openai->api->openai->authFormat = 'Authorization: Bearer %s';     // OpenAI API auth header format.
$config->ai->openai->api->azure = new stdclass();
$config->ai->openai->api->azure->resource    = '';                             // Azure OpenAI resource name, required.
$config->ai->openai->api->azure->deployment  = '';                             // Azure OpenAI deployment name, required.
$config->ai->openai->api->azure->apiVersion  = '2023-07-01-preview';           // Azure OpenAI API version, required.
$config->ai->openai->api->azure->format      = 'https://%s.openai.azure.com/openai/deployments/%s/%s?api-version=%s'; // Azure API format, args: resource name, deployment name, API name, API version.
$config->ai->openai->api->azure->authFormat  = 'api-key: %s';                  // Azure API auth header format.
$config->ai->openai->api->methods            = array('function' => 'chat/completions', 'chat' => 'chat/completions', 'completion' => 'completions');

$config->ai->openai->params = new stdclass();
$config->ai->openai->params->chat       = new stdclass();
$config->ai->openai->params->function   = new stdclass();
$config->ai->openai->params->completion = new stdclass();
$config->ai->openai->params->chat->required       = array('messages');
$config->ai->openai->params->chat->optional       = array('max_tokens', 'temperature', 'top_p', 'n', 'stream', 'stop', 'presence_penalty', 'frequency_penalty', 'logit_bias', 'user');
$config->ai->openai->params->function->required   = array('messages', 'functions', 'function_call');
$config->ai->openai->params->function->optional   = array('max_tokens', 'temperature', 'top_p', 'n', 'stream', 'stop', 'presence_penalty', 'frequency_penalty', 'logit_bias', 'user');
$config->ai->openai->params->completion->required = array('prompt', 'max_tokens');
$config->ai->openai->params->completion->optional = array('suffix', 'temperature', 'top_p', 'n', 'stream', 'logprobs', 'echo', 'stop', 'presence_penalty', 'frequency_penalty', 'best_of', 'logit_bias', 'user');

$config->ai->openai->model = new stdclass();
$config->ai->openai->model->chat       = array('openai-gpt35' => 'gpt-3.5-turbo', 'openai-gpt4' => 'gpt-4-1106-preview');
$config->ai->openai->model->function   = array('openai-gpt35' => 'gpt-3.5-turbo', 'openai-gpt4' => 'gpt-4-1106-preview');
$config->ai->openai->model->completion = 'gpt-3.5-turbo-instruct';

$config->ai->openai->contentTypeMapping = array('Content-Type: application/json' => array('', 'function', 'chat', 'completion'), 'Content-Type: multipart/form-data' => array());
$config->ai->openai->contentType = array();
foreach($config->ai->openai->contentTypeMapping as $contentType => $apis)
{
    foreach($apis as $api) $config->ai->openai->contentType[$api] = $contentType;
}

/* Baidu ERNIE configurations. */
$config->ai->ernie = new stdclass();
$config->ai->ernie->api = new stdclass();
$config->ai->ernie->api->vendor = array('baidu');
$config->ai->ernie->api->baidu = new stdclass();
$config->ai->ernie->api->baidu->format = 'https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/chat/completions?access_token=%s';        // ERNIE API format, arg: access_token (obtained from bce oauth).
$config->ai->ernie->api->baidu->auth   = 'https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id=%s&client_secret=%s'; // BCE auth URL format, args: client_id, client_secret.

$config->ai->ernie->params = new stdclass();
$config->ai->ernie->params->chat     = new stdclass();
$config->ai->ernie->params->function = new stdclass();
$config->ai->ernie->params->chat->required     = array('messages');
$config->ai->ernie->params->chat->optional     = array('temperature', 'top_p', 'penalty_score', 'stream', 'system', 'user_id');
$config->ai->ernie->params->function->required = array('messages', 'functions');
$config->ai->ernie->params->function->optional = array('temperature', 'top_p', 'penalty_score', 'stream', 'system', 'user_id');

$config->ai->ernie->model = new stdclass();
$config->ai->ernie->model->chat     = 'ernie-bot-turbo';
$config->ai->ernie->model->function = 'ernie-bot-turbo';

$config->ai->ernie->contentTypeMapping = array('Content-Type: application/json' => array('', 'function', 'chat'), 'Content-Type: multipart/form-data' => array());
$config->ai->ernie->contentType = array();
foreach($config->ai->ernie->contentTypeMapping as $contentType => $apis)
{
    foreach($apis as $api) $config->ai->ernie->contentType[$api] = $contentType;
}

/* Required fields of forms. */
$config->ai->createprompt = new stdclass();
$config->ai->testPrompt   = new stdclass();
$config->ai->createprompt->requiredFields = 'name';
$config->ai->testPrompt->requiredFields   = 'name,module,source,purpose,targetForm';

/* Data source object props definations, commented out ones are not supported for now. */
$config->ai->dataSource = array();
// $config->ai->dataSource['my']['efforts']              = array('date', 'work', 'account', 'consumed', 'left', 'objectID', 'product', 'project', 'execution');
$config->ai->dataSource['product']['product']         = array('name', 'desc');
// $config->ai->dataSource['product']['modules']         = array('name', 'modules');
$config->ai->dataSource['project']['project']         = array('name', 'type', 'desc', 'begin', 'end', 'estimate');
$config->ai->dataSource['project']['programplans']    = array('name', 'desc', 'status', 'begin', 'end', 'realBegan', 'realEnd', 'planDuration', 'progress', 'estimate', 'consumed', 'left');
$config->ai->dataSource['project']['executions']      = array('name', 'desc', 'status', 'begin', 'end', 'realBegan', 'realEnd', 'estimate', 'consumed', 'progress');
$config->ai->dataSource['story']['story']             = array('title', 'spec', 'verify', 'product', 'module', 'pri', 'category', 'estimate');
$config->ai->dataSource['productplan']['productplan'] = array('title', 'desc', 'begin', 'end');
$config->ai->dataSource['productplan']['stories']     = array('title', 'module', 'pri', 'estimate', 'status', 'stage');
$config->ai->dataSource['productplan']['bugs']        = array('title', 'pri', 'status');
$config->ai->dataSource['release']['release']         = array('product', 'name', 'desc', 'date');
$config->ai->dataSource['release']['stories']         = array('title', 'estimate');
$config->ai->dataSource['release']['bugs']            = array('title');
$config->ai->dataSource['execution']['execution']     = array('name', 'desc', 'estimate');
$config->ai->dataSource['execution']['tasks']         = array('name', 'pri', 'status', 'estimate', 'consumed', 'left', 'progress', 'estStarted', 'realStarted', 'finishedDate', 'closedReason');
$config->ai->dataSource['task']['task']               = array('name', 'desc', 'pri', 'status', 'estimate', 'consumed', 'left', 'progress', 'estStarted', 'realStarted');
$config->ai->dataSource['case']['case']               = array('title', 'precondition', 'scene', 'product', 'module', 'pri', 'type', 'lastRunResult', 'status');
$config->ai->dataSource['case']['steps']              = array('desc', 'expect');
$config->ai->dataSource['bug']['bug']                 = array('title', 'steps', 'severity','pri', 'status', 'confirmed', 'type');
$config->ai->dataSource['doc']['doc']                 = array('title', 'addedBy', 'addedDate', 'editedBy', 'editedDate', 'content');

/* Available target form definations. Please also update `$lang->ai->targetForm` upon changes! Some are commented out, these need extra work. */
$config->ai->targetForm = array();
// $config->ai->targetForm['product']['tree/managechild']   = (object)array('m' => 'tree', 'f' => 'browse');
// $config->ai->targetForm['product']['doc/create']         = (object)array('m' => 'doc', 'f' => 'create');
$config->ai->targetForm['story']['create']               = (object)array('m' => 'story', 'f' => 'create');
$config->ai->targetForm['story']['batchcreate']          = (object)array('m' => 'story', 'f' => 'batchcreate');
$config->ai->targetForm['story']['change']               = (object)array('m' => 'story', 'f' => 'change');
$config->ai->targetForm['story']['totask']               = (object)array('m' => 'task', 'f' => 'batchcreate');
$config->ai->targetForm['story']['testcasecreate']       = (object)array('m' => 'testcase', 'f' => 'create');
$config->ai->targetForm['story']['subdivide']            = (object)array('m' => 'story', 'f' => 'batchcreate');
$config->ai->targetForm['productplan']['edit']           = (object)array('m' => 'productplan', 'f' => 'edit');
$config->ai->targetForm['productplan']['create']         = (object)array('m' => 'productplan', 'f' => 'create');
// $config->ai->targetForm['projectrelease']['doc/create']  = (object)array('m' => 'doc', 'f' => 'create');
// $config->ai->targetForm['project']['risk/create']        = (object)array('m' => 'risk', 'f' => 'create');
// $config->ai->targetForm['project']['issue/create']       = (object)array('m' => 'issue', 'f' => 'create');
// $config->ai->targetForm['project']['doc/create']         = (object)array('m' => 'doc', 'f' => 'create');
$config->ai->targetForm['project']['programplan/create'] = (object)array('m' => 'programplan', 'f' => 'create');
$config->ai->targetForm['execution']['batchcreatetask']  = (object)array('m' => 'task', 'f' => 'batchcreate');
// $config->ai->targetForm['execution']['createtestreport'] = (object)array('m' => 'execution', 'f' => 'testreport');
// $config->ai->targetForm['execution']['createqa']         = (object)array('m' => 'execution', 'f' => 'createQA');
// $config->ai->targetForm['execution']['createrisk']       = (object)array('m' => 'execution', 'f' => 'createRisk');
// $config->ai->targetForm['execution']['createissue']      = (object)array('m' => 'execution', 'f' => 'createIssue');
$config->ai->targetForm['task']['edit']                  = (object)array('m' => 'task', 'f' => 'edit');
$config->ai->targetForm['task']['batchcreate']           = (object)array('m' => 'task', 'f' => 'batchcreate');
$config->ai->targetForm['testcase']['edit']              = (object)array('m' => 'testcase', 'f' => 'edit');
// $config->ai->targetForm['testcase']['createscript']      = (object)array('m' => 'testcase', 'f' => 'createScript');
$config->ai->targetForm['bug']['edit']                   = (object)array('m' => 'bug', 'f' => 'edit');
$config->ai->targetForm['bug']['story/create']           = (object)array('m' => 'story', 'f' => 'create');
$config->ai->targetForm['bug']['testcase/create']        = (object)array('m' => 'testcase', 'f' => 'create');
// $config->ai->targetForm['doc']['create']                 = (object)array('m' => 'doc', 'f' => 'create');
$config->ai->targetForm['doc']['edit']                   = (object)array('m' => 'doc', 'f' => 'edit');

/* Used to check if form injection is available, generated from `$config->ai->targetForm`. */
$config->ai->availableForms = array();
foreach($config->ai->targetForm as $forms)
{
    foreach($forms as $form)
    {
        if(!empty($config->ai->availableForms[$form->m]) && in_array($form->f, $config->ai->availableForms[$form->m])) continue;
        $config->ai->availableForms[$form->m][] = $form->f;
    }
}

/**
 * Target form variables definations, defines format and arguments of target form redirection links,
 * useful when method requires additional arguments.
 *
 * Arg keys are names of objects, usually the same as object name. Arg values indicate if arg is required.
 * It will be used to get object ID and sprintf to format.
 */
$config->ai->targetFormVars = array();
$config->ai->targetFormVars['story']['create']         = (object)array('format' => 'product=%d', 'args' => array('product' => 1), 'app' => 'product');
$config->ai->targetFormVars['story']['batchcreate']    = (object)array('format' => 'productID=%d', 'args' => array('product' => 1), 'app' => 'product');
$config->ai->targetFormVars['story']['change']         = (object)array('format' => 'storyID=%d', 'args' => array('story' => 1), 'app' => 'product');
$config->ai->targetFormVars['productplan']['create']   = (object)array('format' => 'productID=%d&branch=%d&parent=%d', 'args' => array('product' => 1, 'branch' => 0, 'productplan' => 0), 'app' => 'product');
$config->ai->targetFormVars['productplan']['edit']     = (object)array('format' => 'planID=%d', 'args' => array('productplan' => 1), 'app' => 'product');
$config->ai->targetFormVars['task']['create']          = (object)array('format' => 'executionID=%d&storyID=%d', 'args' => array('execution' => 1, 'story' => 0), 'app' => 'execution');
$config->ai->targetFormVars['task']['batchcreate']     = (object)array('format' => 'executionID=%d&storyID=%d', 'args' => array('execution' => 1, 'story' => 0), 'app' => 'execution');
$config->ai->targetFormVars['task']['edit']            = (object)array('format' => 'taskID=%d', 'args' => array('task' => 1), 'app' => 'execution');
$config->ai->targetFormVars['bug']['create']           = (object)array('format' => 'productID=%d', 'args' => array('product' => 1), 'app' => 'qa');
$config->ai->targetFormVars['bug']['edit']             = (object)array('format' => 'bugID=%d', 'args' => array('bug' => 1), 'app' => 'qa');
$config->ai->targetFormVars['testcase']['create']      = (object)array('format' => 'productID=%d', 'args' => array('product' => 1), 'app' => 'qa');
$config->ai->targetFormVars['testcase']['edit']        = (object)array('format' => 'caseID=%d', 'args' => array('case' => 1), 'app' => 'qa');
$config->ai->targetFormVars['testreport']['create']    = (object)array('format' => 'productID=%d', 'args' => array('product' => 1), 'app' => 'qa');
$config->ai->targetFormVars['execution']['testreport'] = (object)array('format' => '', 'args' => array(), 'app' => 'execution');
// $config->ai->targetFormVars['tree']['browse']          = (object)array('format' => 'rootID=%d&view=%s', 'args' => array('root', 'view'), 'app' => 'product');
$config->ai->targetFormVars['programplan']['create']   = (object)array('format' => 'projectID=%d', 'args' => array('project' => 1), 'app' => 'project');
$config->ai->targetFormVars['doc']['edit']             = (object)array('format' => 'docID=%d', 'args' => array('doc' => 1), 'app' => 'doc');

/* Menu printing configurations. */
$config->ai->menuPrint = new stdclass();
/**
 * Menu location definations, defines acceptable module-methods and on page menu locations, etc.
 * Some are identical except for module name, reuse them as much as possible.
 *
 * @param string $module           prompt module name (actual module could differ from prompt module name)
 * @param string $targetContainer  injection target container selector
 * @param string $class            class of menu or dropdown button
 * @param string $buttonClass      specified class of action menu buttons
 * @param string $dropdownClass    specified class of dropdown menu button
 * @param string $objectVarName    object variable name of view
 * @param string $stylesheet       stylesheet to be injected
 * @param string $injectMethod     injection jQuery method, `append` by default
 * @see ./view/promptmenu.html.php
 */
$config->ai->menuPrint->locations = array();
$config->ai->menuPrint->locations['story']['view'] = (object)array(
    'module'          => 'story',
    'targetContainer' => '#mainContent .main-col .cell:first-of-type .detail:first-of-type .detail-title',
    'class'           => 'pull-right',
    'stylesheet'      => '#mainContent .cell:first-of-type .detail:first-of-type .detail-title>button {margin-left: 10px;} #mainContent .cell:first-of-type .detail:first-of-type .detail-content {margin-top: 12px;}'
);
$config->ai->menuPrint->locations['task']['view']             = clone $config->ai->menuPrint->locations['story']['view'];
$config->ai->menuPrint->locations['task']['view']->module     = 'task';
$config->ai->menuPrint->locations['testcase']['view']         = clone $config->ai->menuPrint->locations['story']['view'];
$config->ai->menuPrint->locations['testcase']['view']->module = 'case';
$config->ai->menuPrint->locations['bug']['view']              = clone $config->ai->menuPrint->locations['story']['view'];
$config->ai->menuPrint->locations['bug']['view']->module      = 'bug';
$config->ai->menuPrint->locations['projectstory']['view']     = clone $config->ai->menuPrint->locations['story']['view'];
$config->ai->menuPrint->locations['execution']['storyView']   = $config->ai->menuPrint->locations['story']['view'];

$config->ai->menuPrint->locations['execution']['view'] = (object)array(
    'module'          => 'execution',
    'injectMethod'    => 'prepend',
    'targetContainer' => '#mainContent.main-row > .col-4.side-col .detail:first-child  > .detail-title',
    'class'           => 'pull-right'
);

$config->ai->menuPrint->locations['project']['view']         = clone $config->ai->menuPrint->locations['execution']['view'];
$config->ai->menuPrint->locations['project']['view']->module = 'project';
$config->ai->menuPrint->locations['project']['view']         = clone $config->ai->menuPrint->locations['execution']['view'];
$config->ai->menuPrint->locations['project']['view']->module = 'project';

$config->ai->menuPrint->locations['product']['view'] = (object)array(
    'module'          => 'product',
    'injectMethod'    => 'append',
    'targetContainer' => '#mainContent.main-row > .col-8.main-col .detail:first-child > .detail-title',
    'class'           => 'pull-right'
);

$config->ai->menuPrint->locations['productplan']['view'] = (object)array(
    'module'          => 'productplan',
    'targetContainer' => '#mainMenu>.btn-toolbar.pull-right',
    'objectVarName'   => 'plan'
);
$config->ai->menuPrint->locations['projectplan']['view']                   = $config->ai->menuPrint->locations['productplan']['view'];
$config->ai->menuPrint->locations['release']['view']                       = clone $config->ai->menuPrint->locations['productplan']['view'];
$config->ai->menuPrint->locations['release']['view']->module               = 'release';
$config->ai->menuPrint->locations['release']['view']->objectVarName        = null;
$config->ai->menuPrint->locations['projectrelease']['view']                = clone $config->ai->menuPrint->locations['productplan']['view'];
$config->ai->menuPrint->locations['projectrelease']['view']->objectVarName = null;

$config->ai->menuPrint->locations['doc']['view'] = (object)array(
    'module'          => 'doc',
    'injectMethod'    => 'prepend',
    'targetContainer' => '#mainMenu>.btn-toolbar.pull-right',
);

$config->ai->injectAuditButton = new stdclass();
$config->ai->injectAuditButton->locations = array();
$config->ai->injectAuditButton->locations['task']['edit'] = array(
    'toolbar' => (object)array(
        'targetContainer' => '#mainContent .main-header',
        'injectMethod'    => 'append',
        'class'           => 'pull-right btn-toolbar',
    ),
    'action' => (object)array(
        'targetContainer' => '#mainContent .form-actions',
        'injectMethod'    => 'html',
    )
);

$config->ai->injectAuditButton->locations['bug']['create'] = array(
    'toolbar' => (object)array(
        'targetContainer' => '#mainContent .main-header .btn-toolbar',
        'injectMethod'    => 'prepend',
    ),
    'action' => (object)array(
        'targetContainer' => '#mainContent .form-actions',
        'injectMethod'    => 'html',
    )
);

$config->ai->injectAuditButton->locations['doc']['edit'] = array(
    'toolbar' => (object)array(
        'targetContainer' => '#mainContent #headerBox .btn-tools',
        'injectMethod'    => 'prepend',
    ),
    'action' => (object)array(
        'targetContainer' => '#mainContent #headerBox .btn-tools',
        'injectMethod'    => 'html',
        'containerStyles' => '{"width": "600px"}'
    )
);

$config->ai->injectAuditButton->locations['productplan']['create'] = $config->ai->injectAuditButton->locations['task']['edit'];
$config->ai->injectAuditButton->locations['productplan']['edit']   = $config->ai->injectAuditButton->locations['task']['edit'];
$config->ai->injectAuditButton->locations['programplan']['create'] = $config->ai->injectAuditButton->locations['task']['edit'];
$config->ai->injectAuditButton->locations['bug']['edit']           = $config->ai->injectAuditButton->locations['task']['edit'];
$config->ai->injectAuditButton->locations['story']['change']       = $config->ai->injectAuditButton->locations['task']['edit'];
$config->ai->injectAuditButton->locations['testcase']['edit']      = $config->ai->injectAuditButton->locations['task']['edit'];
$config->ai->injectAuditButton->locations['testreport']['create']  = $config->ai->injectAuditButton->locations['task']['edit'];

$config->ai->injectAuditButton->locations['story']['batchcreate'] = $config->ai->injectAuditButton->locations['bug']['create'];
$config->ai->injectAuditButton->locations['story']['create']      = $config->ai->injectAuditButton->locations['bug']['create'];
$config->ai->injectAuditButton->locations['task']['batchcreate']  = $config->ai->injectAuditButton->locations['bug']['create'];
$config->ai->injectAuditButton->locations['task']['create']       = $config->ai->injectAuditButton->locations['bug']['create'];
$config->ai->injectAuditButton->locations['testcase']['create']   = $config->ai->injectAuditButton->locations['bug']['create'];
