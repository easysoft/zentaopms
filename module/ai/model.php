<?php
/**
 * The model file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
class aiModel extends model
{
    /**
     * Model config.
     *
     * @var    object
     * @access public
     */
    public $modelConfig;

    /**
     * Errors from last request.
     *
     * @var    array
     * @access public
     */
    public $errors = array();

    /**
     * Constructor. Get model config from system.ai settings.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        /* Load config from setting. */
        $this->modelConfig = new stdclass();
        $openaiSettings = $this->loadModel('setting')->getItems("owner=system&module=ai");
        foreach($openaiSettings as $item) $this->modelConfig->{$item->key} = $item->value;
    }

    /**
     * Set model config, used for testing.
     *
     * @param  object $config
     * @access public
     * @return void
     */
    public function setConfig($config)
    {
        $this->modelConfig = $config;
    }

    /**
     * Make request to OpenAI API.
     *
     * @param  string   $type     chat | completion | edit
     * @param  mixed    $data     data to send
     * @param  int      $timeout  request timeout in seconds
     * @access private
     * @return mixed    false if error, json string if success
     */
    private function makeRequest($type, $data, $timeout = 10)
    {
        /* Try encoding data to json, handles both encoded json and raw data. */
        $postData = json_encode($data);
        if(json_last_error()) $postData = $data;

        /* Set auth and content-type headers. */
        $requestHeaders = array(sprintf($this->config->ai->openai->api->authFormat, $this->modelConfig->key));
        $requestHeaders[] = isset($this->config->ai->openai->contentType[$type]) ? $this->config->ai->openai->contentType[$type] : $this->config->ai->openai->contentType[''];

        /* Assemble request url. */
        $url = sprintf($this->config->ai->openai->api->format, $this->config->ai->openai->api->version, $this->config->ai->openai->api->methods[$type]);

        /* Set up requestor. */
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        /* Use proxy if proxy is set. */
        if(!empty($this->modelConfig->proxyType) && !empty($this->modelConfig->proxyAddr))
        {
            curl_setopt($ch, CURLOPT_PROXY,     $this->modelConfig->proxyAddr);
            curl_setopt($ch, CURLOPT_PROXYTYPE, static::getProxyType($this->modelConfig->proxyType));
        }

        $result = curl_exec($ch);

        if(isset($this->config->debug) && $this->config->debug >= 1)
        {
            global $app;
            $logFile = $app->getLogRoot() . 'saas.' . date('Ymd') . '.log.php';
            if(!file_exists($logFile)) file_put_contents($logFile, '<?php die(); ?' . '>');
            $fh = @fopen($logFile, 'a');
            if($fh)
            {
                fwrite($fh, date('Ymd H:i:s') . ": " . $app->getURI() . ' AI Request' . "\n");
                fwrite($fh, "postData:    " . print_r($data, true) . "\n");
                fwrite($fh, "results:" . print_r($result, true) . "\n");
                fclose($fh);
            }
        }

        if(curl_errno($ch)) return false;
        curl_close($ch);

        return $result;
    }

    /**
     * Get proxy type.
     *
     * @param  string    $proxyType
     * @access private
     * @return int|false
     */
    private static function getProxyType($proxyType)
    {
        if(!in_array($proxyType, array('http', 'socks4', 'socks5'))) return false;
        return constant('CURLPROXY_' . strtoupper($proxyType));
    }

    /**
     * Convert camelCase to snake_case.
     *
     * @param  string   $str
     * @access private
     * @return string
     */
    private static function camelCaseToSnakeCase($str)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $str));
    }

    /**
     * Standardize params keys, convert camelCase to snake_case.
     *
     * @param  object   $data
     * @access private
     * @return object
     */
    private static function standardizeParams($data)
    {
        $standardizedData = new stdclass();
        foreach($data as $key => $value) $standardizedData->{static::camelCaseToSnakeCase($key)} = $value;
        return $standardizedData;
    }

    /**
     * Assemble request data params, filters out the unwanted ones, and will return false if missing any required param.
     *
     * @param  string   $type
     * @param  object   $data
     * @access private
     * @return mixed    false if missing required params, post data object if success
     */
    private function assembleRequestData($type, $data)
    {
        $postData = new stdclass();
        $postData->model = $this->config->ai->openai->model->$type;

        $data = static::standardizeParams($data);

        /* Set required params, abort if missing. */
        foreach($this->config->ai->openai->params->$type->required as $param)
        {
            if(!isset($data->$param)) return false;
            $postData->$param = $data->$param;
        }

        /* Set optional params. */
        foreach($this->config->ai->openai->params->$type->optional as $param)
        {
            if(isset($data->$param)) $postData->$param = $data->$param;
        }

        return $postData;
    }

    /**
     * Decode response from OpenAI API, add error to $this->errors if any.
     *
     * @param  string   $response  json string
     * @access private
     * @return mixed    false if error, json object if success
     */
    private function decodeResponse($response)
    {
        $response = json_decode($response);
        if(json_last_error())
        {
            $this->errors[] = 'JSON decode error: ' . json_last_error_msg();
            return false;
        }
        if(isset($response->error))
        {
            $this->errors[] = isset($response->error->message) ? $response->error->message : 'Unknown error';
            return false;
        }
        return $response;
    }

    /**
     * Parse text responses from simple APIs. For example, completion.
     *
     * @param  string   $response  json string
     * @access private
     * @return mixed    false if error, array of texts (choices) if success
     */
    private function parseTextResponse($response)
    {
        $response = $this->decodeResponse($response);
        if(empty($response)) return false;

        /* Extract text response choices. */
        if(isset($response->choices) && count($response->choices) > 0)
        {
            $texts = array();
            foreach($response->choices as $choice) $texts[] = $choice->text;
            return $texts;
        }
        return false;
    }

    /**
     * Parse chat responses from chat completion API.
     *
     * @param  string   $response  json string
     * @access private
     * @return mixed    false if error, array of chat message texts (choices) if success
     */
    private function parseChatResponse($response)
    {
        $response = $this->decodeResponse($response);
        if(empty($response)) return false;

        /* Extract chat message choices. */
        if(isset($response->choices) && count($response->choices) > 0)
        {
            $messages = array();
            foreach($response->choices as $choice) $messages[] = $choice->message->content;
            return $messages;
        }
        return false;
    }

    /**
     * Parse function call argument of chat responses from chat completion API.
     *
     * @param  string   $response  json string
     * @access private
     * @return mixed    false if error, array of function call arguments (choices) if success
     */
    private function parseFunctionCallResponse($response)
    {
        $response = $this->decodeResponse($response);
        if(empty($response)) return false;

        /* Extract function call choices. */
        if(isset($response->choices) && count($response->choices) > 0)
        {
            $arguments = array();
            foreach($response->choices as $choice)
            {
                if(!empty($choice->message->function_call)) $arguments[] = $choice->message->function_call->arguments;
            }
            return $arguments;
        }
        return false;
    }

    /**
     * Complete text with OpenAI GPT.
     *
     * @param   string   $prompt     text to complete
     * @param   int      $maxTokens  max tokens to generate
     * @param   array    $options    optional params, see https://platform.openai.com/docs/api-reference/completions/create
     * @access  public
     * @return  mixed    false if error, array of texts (choices) if success
     */
    public function complete($prompt, $maxTokens = 512, $options = array())
    {
        $data = compact('prompt', 'maxTokens');

        if(!empty($options))
        {
            foreach($options as $key => $value) $data[$key] = $value;
        }

        $postData = $this->assembleRequestData('completion', $data);
        if(!$postData) return false;

        $response = $this->makeRequest('completion', $postData);
        return $this->parseTextResponse($response);
    }

    /**
     * Edit text with OpenAI GPT.
     *
     * @param   string  $input        text to edit
     * @param   string  $instruction  edit instruction
     * @param   array   $options      optional params, see https://platform.openai.com/docs/api-reference/edits/create
     * @access  public
     * @return  mixed   false if error, array of texts (choices) if success
     */
    public function edit($input, $instruction, $options = array())
    {
        $data = compact('input', 'instruction');

        if(!empty($options))
        {
            foreach($options as $key => $value) $data[$key] = $value;
        }

        $postData = $this->assembleRequestData('edit', $data);
        if(!$postData) return false;

        $response = $this->makeRequest('edit', $postData);
        return $this->parseTextResponse($response);
    }

    /**
     * Generate conversation with OpenAI GPT.
     *
     * Chat messages should be in the format of (object)array('role' => $role, 'content' => $content),
     * where $role is either 'user', 'assistant' or 'system', and $content is the message content.
     *
     * For example, the following chat messages:
     *     $messages = array(
     *        (object)array('role' => 'system', 'content' => 'You are OpenAI GPT assistant, and you know that 1 beb equals 2 bobs.'),
     *        (object)array('role' => 'user', 'content' => 'Hello, how many bobs are there in 24 bebs?')
     *     );
     *
     * API returns:
     *     $message = array('Hi, there are 48 bobs in 24 bebs.');
     *
     * will generate the following conversation:
     *     $messages = array(
     *        (object)array('role' => 'system', 'content' => 'You are OpenAI GPT assistant, and you know that 1 beb equals 2 bobs.'),
     *        (object)array('role' => 'user', 'content' => 'Hello, how many bobs are there in 24 bebs?'),
     *        (object)array('role' => 'assistant', 'content' => 'Hi, there are 48 bobs in 24 bebs.')
     *     );
     *
     * @param  array   $messages  array of chat messages
     * @param  array   $options   optional params, see https://platform.openai.com/docs/api-reference/chat/create
     * @access public
     * @return mixed   false if error, array of chat messages if success
     */
    public function converse($messages, $options = array())
    {
        $data = compact('messages');

        if(!empty($options))
        {
            foreach($options as $key => $value) $data[$key] = $value;
        }

        $postData = $this->assembleRequestData('chat', $data);
        if(!$postData) return false;

        $response = $this->makeRequest('chat', $postData);
        return $this->parseChatResponse($response);
    }

    /**
     * Generate conversation with OpenAI GPT, but for JSON as output.
     *
     * Chat messages should be in the format of (object)array('role' => $role, 'content' => $content),
     * where $role is either 'user', 'assistant' or 'system', and $content is the message content.
     *
     * Schema should be in the format of JSON schema, see https://json-schema.org/understanding-json-schema/.
     *
     * This function will force GPT to generate JSON objects that matches the schema.
     *
     * For technical details, see https://platform.openai.com/docs/guides/gpt/function-calling.
     *
     * @param  array  $messages  array of chat messages
     * @param  object $schema    schema of the output
     * @param  array  $options   optional params, see https://platform.openai.com/docs/api-reference/chat/create
     * @access public
     * @return mixed  false if error, array of JSON object if success
     */
    public function converseForJSON($messages, $schema, $options = array())
    {
        $functions    = array((object)array('name' => 'function', 'parameters' => $schema));
        $functionCall = (object)array('name' => 'function');

        $data = compact('messages', 'functions', 'functionCall');

        if(!empty($options))
        {
            foreach($options as $key => $value) $data[$key] = $value;
        }

        $postData = $this->assembleRequestData('function', $data);
        if(!$postData) return false;

        $response = $this->makeRequest('function', $postData);
        return $this->parseFunctionCallResponse($response);
    }

    /**
     * Get list of prompts.
     *
     * TODO: fully implement this.
     *
     * @param  string  $module
     * @param  string  $status
     * @access public
     * @return array
     */
    public function getPrompts($module = '', $status = '', $order = 'id_desc', $pager = null)
    {
        $prompts = $this->dao->select('*')->from(TABLE_PROMPT)
            ->where('deleted')->eq(0)
            ->beginIF(!empty($module))->andWhere('module')->eq($module)->fi()
            ->beginIF(!empty($status))->andWhere('status')->eq($status)->fi()
            ->orderBy($order)
            ->page($pager)
            ->fetchAll();
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'prompt');
        return $prompts;
    }

    /**
     * Get prompt by id.
     *
     * TODO: fully implement this.
     *
     * @param  int     $id
     * @access public
     * @return object
     */
    public function getPromptById($id)
    {
        return $this->dao->select('*')->from(TABLE_PROMPT)
            ->where('id')->eq($id)
            ->fetch();
    }

    /**
     * Create a prompt.
     *
     * @param  object    $prompt
     * @access public
     * @return int|false returns prompt id on success, false on fail
     */
    public function createPrompt($prompt)
    {
        $prompt->createdDate = helper::now();
        $prompt->createdBy   = $this->app->user->account;

        /* Override uniqueness error message. */
        $this->lang->error->unique = $this->lang->ai->validate->nameNotUnique;

        $this->dao->insert(TABLE_PROMPT)
            ->data($prompt)
            ->batchCheck($this->config->ai->createprompt->requiredFields, 'notempty')
            ->check('name', 'unique')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        $promptId = $this->dao->lastInsertID();
        $this->loadModel('action')->create('prompt', $promptId, 'created');

        return $promptId;
    }

    /**
     * Update a prompt.
     *
     * @param  object    $prompt
     * @param  object    $originalPrompt  optional, original prompt to compare with and generate action.
     * @access public
     * @return bool
     */
    public function updatePrompt($prompt, $originalPrompt = null)
    {
        /* Action name to create action record with. */
        $actionType = 'edited';

        /* Compare with original, check what changed. */
        if(!empty($originalPrompt))
        {
            $changedFields = array();
            foreach($prompt as $key => $value)
            {
                if($value != $originalPrompt->$key) $changedFields[] = $key;
            }

            /* If only status changed, action is either published or unpublished. */
            if(count($changedFields) == 1 && current($changedFields) == 'status')
            {
                $actionType = $prompt->status == 'draft' ? 'unpublished' : 'published';
            }
            else
            {
                $changes = common::createChanges($originalPrompt, $prompt);
            }

        }

        $prompt->editedDate = helper::now();
        $prompt->editedBy   = $this->app->user->account;

        /* Override uniqueness error message. */
        $this->lang->error->unique = $this->lang->ai->validate->nameNotUnique;

        $this->dao->update(TABLE_PROMPT)
            ->data($prompt)
            ->batchCheck($this->config->ai->createprompt->requiredFields, 'notempty')
            ->check('name', 'unique', "`id` != {$prompt->id}")
            ->autoCheck()
            ->where('id')->eq($prompt->id)
            ->exec();
        if(dao::isError()) return false;

        $actionId = $this->loadModel('action')->create('prompt', $prompt->id, $actionType);
        if(!empty($changes)) $this->action->logHistory($actionId, $changes);

        return true;
    }

    /**
     * Delete a prompt.
     *
     * @param  int  $id
     * @access public
     * @return bool
     */
    public function deletePrompt($id)
    {
        $prompt = $this->getPromptById($id);
        if(empty($prompt)) return false;

        $this->dao->update(TABLE_PROMPT)
            ->set('deleted')->eq(1)
            ->where('id')->eq($id)
            ->exec();
        if(dao::isError()) return false;

        $this->loadModel('action')->create('prompt', $id, 'deleted');

        return true;
    }

    /**
     * Toggle prompt status.
     *
     * @param  int|object  $prompt  prompt (or id) to toggle.
     * @param  string      $status  optional, will set status to $status if provided.
     * @access public
     * @return bool
     */
    public function togglePromptStatus($prompt, $status = '')
    {
        if(is_numeric($prompt)) $prompt = $this->getPromptById($prompt);
        if(empty($prompt)) return false;

        $flipedStatus = $prompt->status == 'draft' ? 'active' : 'draft';

        $originalPrompt = clone $prompt;
        $prompt->status = empty($status) ? $flipedStatus : $status;

        return $this->updatePrompt($prompt, $originalPrompt);
    }

    /**
     * Serialize data to prompt.
     *
     * @param  string        $module
     * @param  array|string  $sources   both raw `$prompt->sources` and `array(array('objectName', 'objectKey'), ...)` are supported.
     * @param  array|object  $data      array of data to be serialized
     * @access public
     * @return string
     */
    public function serializeDataToPrompt($module, $sources, $data)
    {
        if(empty($data)) return '';

        /* Handle object data. */
        if(is_object($data)) $data = (array)$data;

        /* Handle raw (non-exploded) sources. */
        if(is_string($sources) && strpos($sources, ',') !== false)
        {
            $sources = array_filter(explode(',', $sources));
            $sources = array_map(function($source) { return explode('.', $source); }, $sources);
        }

        $dataObject = array();

        $supplement = '';
        $supplementTypes = array();

        foreach($sources as $source)
        {
            $objectName = $source[0];
            $objectKey  = $source[1];

            $semanticName = $this->lang->ai->dataSource[$module][$objectName]['common'];
            $semanticKey  = $this->lang->ai->dataSource[$module][$objectName][$objectKey];

            if(empty($dataObject[$semanticName])) $dataObject[$semanticName] = array();

            $obj = $data[$objectName];
            if(static::isAssoc($obj))
            {
                $dataObject[$semanticName][$semanticKey] = $data[$objectName][$objectKey];
            }
            else
            {
                foreach(array_keys($obj) as $idx)
                {
                    if(empty($dataObject[$semanticName][$idx])) $dataObject[$semanticName][$idx] = array();
                    $dataObject[$semanticName][$idx][$semanticKey] = $data[$objectName][$idx][$objectKey];
                }
            }

            if(in_array($objectKey, $supplementTypes) || !isset($this->lang->ai->dataType->$objectKey)) continue;

            $supplementTypes[] = $objectKey;
            $supplement .= sprintf($this->lang->ai->dataTypeDesc, $semanticKey, $this->lang->ai->dataType->$objectKey->type, $this->lang->ai->dataType->$objectKey->desc) . "\n";
        }

        /* @see https://stackoverflow.com/a/2934602 */
        return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');}, json_encode($dataObject)) . "\n" . $supplement;
    }

    /**
     * Generate demo data prompt by source.
     *
     * @param  string $module
     * @param  string $source
     * @access public
     * @return string
     */
    public function generateDemoDataPrompt($module, $source)
    {
        if(empty($this->lang->ai->demoData->$module)) return $this->lang->ai->demoData->notExist;

        $sources = explode(',', $source);
        $sources = array_filter($sources);

        if(empty($sources)) return '';

        foreach($sources as $index => $source)
        {
            $sources[$index] = explode('.', $source);
        }

        $data = array();
        foreach($sources as $source)
        {
            $objectName = $source[0];
            $objectKey  = $source[1];
            if(empty($data[$objectName])) $data[$objectName] = array();

            $demoData = $this->lang->ai->demoData->$module[$objectName];
            if(static::isAssoc($demoData))
            {
                $data[$objectName][$objectKey] = $demoData[$objectKey];
            }
            else
            {
                foreach($demoData as $index => $value)
                {
                    if(empty($data[$objectName][$index])) $data[$objectName][$index] = array();
                    $data[$objectName][$index][$objectKey] = $value[$objectKey];
                }
            }
        }

        return $this->serializeDataToPrompt($module, $sources, $data);
    }

    /**
     * Determines if an array is associative.
     *
     * @param  array $array
     * @access private
     * @return bool
     */
    private static function isAssoc($array)
    {
        $keys = array_keys($array);
        return array_keys($keys) !== $keys;
    }

    /**
     * Get function call schema for form.
     *
     * @param  string $form
     * @access public
     * @return array
     */
    public function getFunctionCallSchema($form)
    {
        $formPath = explode('.', $form);
        if(count($formPath) !== 2) return array();

        $targetForm = $this->config->ai->targetForm[$formPath[0]][$formPath[1]];
        if(empty($targetForm)) return array();

        $schema = $this->lang->ai->formSchema[strtolower($targetForm->m)][strtolower($targetForm->f)];

        return empty($schema) ? array() : $schema;
    }

    /**
     * Get object data for prompt by id.
     *
     * @param  object        $prompt    prompt object
     * @param  int           $objectId  object id
     * @access public
     * @return array|false   array of object data and object, or false if error.
     */
    public function getObjectForPromptById($prompt, $objectId)
    {
        $module  = $prompt->module;
        $sources = array_filter(explode(',', $prompt->source));

        /* Explode into grouped sources list. */
        $sourceGroups = array();
        foreach($sources as $source)
        {
            $source = explode('.', $source);
            $objectName = $source[0];
            $objectKey  = $source[1];
            if(empty($sourceGroups[$objectName])) $sourceGroups[$objectName] = array();
            $sourceGroups[$objectName][] = $objectKey;
        }

        $object     = new stdclass();
        $objectData = new stdclass();

        /* Query necessary object data from zentao. */
        switch($module){
            case 'story':
                if(isset($sourceGroups['story'])) $object->story = $this->loadModel('story')->getById($objectId);
                break;
            case 'execution':
                if(isset($sourceGroups['execution'])) $object->execution = $this->loadModel('execution')->getByID($objectId);
                if(isset($sourceGroups['tasks']))     $object->tasks     = array_values($this->loadModel('task')->getExecutionTasks($objectId));
                break;
            case 'product':
                if(isset($sourceGroups['product'])) $object->product = $this->loadModel('product')->getById($objectId);
                break;
            case 'project':
                if(isset($sourceGroups['project'])) $object->project = $this->loadModel('project')->getById($objectId);
                if($object->project->model == 'waterfall' && isset($sourceGroups['programplans'])) $object->programplans = array_values($this->loadModel('execution')->getByProject($object->project->id));
                if($object->project->model != 'waterfall' && isset($sourceGroups['executions'])) $object->executions = array_values($this->loadModel('execution')->getByProject($object->project->id));
                break;
            case 'release':
                if(isset($sourceGroups['release'])) $object->release = $this->loadModel('release')->getById($objectId);
                if(isset($sourceGroups['stories'])) $object->stories = array_values($this->loadModel('story')->getByList(array_filter(explode(',', $object->release->stories))));
                if(isset($sourceGroups['bugs']))    $object->bugs    = array_values($this->loadModel('bug')->getByList(array_filter(explode(',', $object->release->bugs))));
                break;
            case 'productplan':
                if(isset($sourceGroups['productplan'])) $object->productplan = $this->loadModel('productplan')->getByID($objectId);
                if(isset($sourceGroups['stories']))     $object->stories     = array_values($this->loadModel('story')->getPlanStories($objectId));
                if(isset($sourceGroups['bugs']))        $object->bugs        = array_values($this->dao->select('*')->from(TABLE_BUG)->where('plan')->eq($objectId)->andWhere('deleted')->eq(0)->fetchAll());
                break;
            case 'task':
                if(isset($sourceGroups['task'])) $object->task = $this->loadModel('task')->getById($objectId);
                break;
            case 'case':
                if(isset($sourceGroups['case']))  $object->case  = $this->loadModel('testcase')->getById($objectId);
                if(isset($sourceGroups['steps'])) $object->steps = array_values($object->case->steps);
                break;
            case 'bug':
                if(isset($sourceGroups['bug'])) $object->bug = $this->loadModel('bug')->getById($objectId);
                break;
            case 'doc':
                if(isset($sourceGroups['doc'])) $object->doc = $this->loadModel('doc')->getById($objectId);
                break;
            case 'my':
                /* TODO: add more later. */
                break;
        }

        $objectVars = get_object_vars($object);
        if(empty($objectVars)) return false;

        /* Format data as per data source definitions. */
        foreach($sourceGroups as $objectName => $objectKeys)
        {
            $objectData->$objectName = array();
            foreach($objectKeys as $objectKey)
            {
                /* Check if is plain object, data might contain arrays. */
                if(is_object($object->$objectName))
                {
                    if(isset($object->$objectName->$objectKey)) $objectData->$objectName[$objectKey] = $object->$objectName->$objectKey;
                }
                elseif(is_array($object->$objectName))
                {
                    foreach($object->$objectName as $idx => $obj)
                    {
                        if(isset($obj->$objectKey)) $objectData->$objectName[$idx][$objectKey] = $obj->$objectKey;
                    }
                }
            }
        }

        return array($objectData, $object);
    }

    /**
     * Auto prepend newline to text if text has newline in the middle.
     *
     * @param  string  $text
     * @access private
     * @return string
     */
    private static function autoPrependNewline($text)
    {
        if(empty($text)) return '';

        preg_match('/\n[^$]/', $text, $matches);
        return empty($matches) ? $text : "\n$text";
    }

    /**
     * Try to punctuate sentence if sentence is not ended with punctuation.
     *
     * @param  string  $sentence
     * @param  bool    $newline
     * @access private
     * @return string
     */
    private static function tryPunctuate($sentence, $newline = false)
    {
        if(empty($sentence)) return '';

        preg_match('/\p{P}$/u', $sentence, $matches);
        if(empty($matches)) $sentence .= '.';

        return $newline ? "$sentence\n" : $sentence;
    }

    /**
     * Assemble prompt with prompt data.
     *
     * @param  object $prompt
     * @param  string $dataPrompt
     * @access public
     * @return string
     */
    public function assemblePrompt($prompt, $dataPrompt)
    {
        $wholePrompt = "$dataPrompt\n";

        $wholePrompt .= static::tryPunctuate($prompt->role);
        $wholePrompt .= static::autoPrependNewline(static::tryPunctuate($prompt->characterization, true));

        $wholePrompt .= static::autoPrependNewline(static::tryPunctuate($prompt->purpose));
        $wholePrompt .= static::autoPrependNewline(static::tryPunctuate($prompt->elaboration, true));

        return $wholePrompt;
    }

    /**
     * Execute prompt on object.
     *
     * @param  int|object    $prompt    prompt (or id) to execute.
     * @param  int|object    $object    object (or id) to execute prompt on.
     * @access public
     * @return string|int    returns either JSON string or negative integer on error.
     */
    public function executePrompt($prompt, $object)
    {
        if(is_numeric($prompt)) $prompt = $this->getPromptById($prompt);
        if(empty($prompt)) return -1;

        if(is_numeric($object)) $object = $this->getObjectForPromptById($prompt, $object);
        if(empty($object)) return -2;

        list($objectData) = $object;
        $dataPrompt = $this->serializeDataToPrompt($prompt->module, $prompt->source, $objectData);
        if(empty($dataPrompt)) return -3;

        $wholePrompt = $this->assemblePrompt($prompt, $dataPrompt);
        $schema      = $this->getFunctionCallSchema($prompt->targetForm);
        if(empty($schema)) return -4;

        $response = $this->converseForJSON(array((object)array('role' => 'user', 'content' => $wholePrompt)), $schema);
        if(empty($response)) return -5;

        return current($response);
    }

    /**
     * Check if prompt can be tested.
     *
     * @param  object|int  $prompt  prompt object or prompt id
     * @access public
     * @return bool
     */
    public function isExecutable($prompt)
    {
        if(is_numeric($prompt)) $prompt = $this->getByID($prompt);
        if(empty($prompt)) return false;

        $executable = true;
        $requiredFields = explode(',', $this->config->ai->testPrompt->requiredFields);

        foreach($requiredFields as $field)
        {
            if(empty($prompt->$field) || $prompt->$field == ',,')
            {
                $executable = false;
                break;
            }
        }

        return $executable;
    }

    /**
     * Get testing location for prompt, usually a link to object with max id and is accessable by user.
     *
     * @param  object        $prompt
     * @access public
     * @return string|false
     */
    public function getTestingLocation($prompt)
    {
        $module = $prompt->module;

        if($module == 'my')
        {
            return helper::createLink('my', 'effort', "type=all");
        }
        if($module == 'product')
        {
            $productId = $this->dao->select('max(id) as maxId')->from(TABLE_PRODUCT)
                ->where('id')->in($this->app->user->view->products)
                ->fetch('maxId');
            if(!empty($productId)) return helper::createLink('product', 'view', "productID=$productId");
        }
        if($module == 'productplan')
        {
            $productplanId = $this->dao->select('max(id) as maxId')->from(TABLE_PRODUCTPLAN)
                ->where('product')->in($this->app->user->view->products)
                ->fetch('maxId');
            if(!empty($productplanId)) return helper::createLink('productplan', 'view', "productplanID=$productplanId");
        };
        if($module == 'release')
        {
            $releaseId = $this->dao->select('max(id) as maxId')->from(TABLE_RELEASE)
                ->where('project')->in($this->app->user->view->projects)
                ->orWhere('product')->in($this->app->user->view->products)
                ->fetch('maxId');
            if(!empty($releaseId)) return helper::createLink('release', 'view', "releaseID=$releaseId");
        }
        if($module == 'project')
        {
            $projectId = $this->dao->select('max(id) as maxId')->from(TABLE_PROJECT)
                ->where('id')->in($this->app->user->view->projects)
                ->fetch('maxId');
            if(!empty($projectId)) return helper::createLink('project', 'view', "projectID=$projectId");
        }
        if($module == 'story')
        {
            $storyId = $this->dao->select('max(id) as maxId')->from(TABLE_STORY)
                ->where('product')->in($this->app->user->view->products)
                ->fetch('maxId');
            if(!empty($storyId)) return helper::createLink('story', 'view', "storyID=$storyId");
        }
        if($module == 'execution')
        {
            $executionIds = array_map('intval', explode(',', $this->app->user->view->sprints));
            $executionId  = max($executionIds);
            if(!empty($executionId)) return helper::createLink('execution', 'view', "executionID=$executionId");
        }
        if($module == 'task')
        {
            $taskId = $this->dao->select('max(id) as maxId')->from(TABLE_TASK)
                ->where('project')->in($this->app->user->view->projects)
                ->fetch('maxId');
            if(!empty($taskId)) return helper::createLink('task', 'view', "taskID=$taskId");
        }
        if($module == 'case')
        {
            $caseId = $this->dao->select('max(id) as maxId')->from(TABLE_CASE)
                ->where('project')->in($this->app->user->view->projects)
                ->orWhere('product')->in($this->app->user->view->products)
                ->fetch('maxId');
            if(!empty($caseId)) return helper::createLink('testcase', 'view', "caseID=$caseId");
        }
        if($module == 'bug')
        {
            $bugId = $this->dao->select('max(id) as maxId')->from(TABLE_BUG)
                ->where('project')->in($this->app->user->view->projects)
                ->orWhere('product')->in($this->app->user->view->products)
                ->fetch('maxId');
            if(!empty($bugId)) return helper::createLink('bug', 'view', "bugID=$bugId");
        }
        if($module == 'doc')
        {
            $docId = $this->dao->select('max(id) as maxId')->from(TABLE_DOC)
                ->where('project')->in($this->app->user->view->projects)
                ->orWhere('product')->in($this->app->user->view->products)
                ->fetch('maxId');
            if(empty($docId))
            {
                $userDocLibs = $this->dao->select('id')->from(TABLE_DOCLIB)
                    ->where('type')->eq('mine')
                    ->andWhere('addedBy')->eq($this->app->user->account)
                    ->fetchPairs();
                if(!empty($userDocLibs))
                {
                    $docId = $this->dao->select('max(id) as maxId')->from(TABLE_DOC)
                        ->where('lib')->in($userDocLibs)
                        ->fetch('maxId');
                }
            }
            if(!empty($docId)) return helper::createLink('doc', 'view', "docID=$docId");
        }

        return false;
    }

    /**
     * Get target form location of object for prompt.
     *
     * @param  object|int   $prompt    prompt object or prompt id
     * @param  object       $object
     * @param  array        $linkArgs  optional, link arguments, as defined in `->args` of items of `$config->ai->targetFormVars`, e.g. array('story' => 1). If not provided, will try to get from object.
     * @access public
     * @return string|false returns either link or false.
     */
    public function getTargetFormLocation($prompt, $object, $linkArgs = array())
    {
        if(is_numeric($prompt)) $prompt = $this->getByID($prompt);
        if(empty($prompt)) return false;

        $targetForm = $prompt->targetForm;
        if(empty($targetForm)) return false;

        list($m, $f) = explode('.', $targetForm);
        $targetFormConfig = $this->config->ai->targetForm[$m][$f];
        $module = strtolower($targetFormConfig->m);
        $method = strtolower($targetFormConfig->f);

        /* Try assemble link vars from both passed-in `$linkArgs` and object props. */
        $varsConfig = $this->config->ai->targetFormVars[$module][$method];
        $vars = array();
        foreach($varsConfig->args as $arg)
        {
            if(!empty($linkArgs[$arg]))
            {
                $vars[] = $linkArgs[$arg];
            }
            elseif(!empty($object->$arg) && is_object($object->$arg) && !empty($object->$arg->id))
            {
                $vars[] = $object->$arg->id;
            }
            elseif(!empty($object->{$prompt->module}->$arg))
            {
                $vars[] = $object->{$prompt->module}->$arg;
            }
            else
            {
                $vars[] = '';
            }
        }
        $linkVars = vsprintf($varsConfig->format, $vars);

        return helper::createLink($module, $method, $linkVars);
    }

    /**
     * Get the last active step of prompt by id.
     *
     * @param  object $prompt
     * @access public
     * @return string
     */
    public function getLastActiveStep($prompt)
    {
        if(!empty($prompt))
        {
            if($prompt->status == 'active') return 'finalize';
            if(!empty($prompt->targetForm)) return 'settargetform';
            if(!empty($prompt->purpose))    return 'setpurpose';
            if(!empty($prompt->source))     return 'selectdatasource';
        }
        return 'assignrole';
    }

    /**
     * Get prompts available for calling (which are either active or created by the user) of a module for user.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getPromptsForUser($module)
    {
        return $this->dao->select('*')->from(TABLE_PROMPT)
            ->where('deleted')->eq(0)
            ->andWhere('module')->eq($module)
            ->andWhere('status', true)->eq('active')->orWhere('createdBy')->eq($this->app->user->account)->markRight(1)
            ->orderBy('id_desc')
            ->fetchAll();
    }

    /**
     * Set inject data for a form. For how injection works, see view/inputinject.html.php file.
     *
     * @param  string|array  $form  'module.method' or array('module', 'method').
     * @param  string|object $data  data to inject, object will be json encoded.
     * @access public
     * @return void
     */
    public function setInjectData($form, $data)
    {
        if(is_string($form)) $form = explode('.', $form);

        $targetForm = $this->config->ai->targetForm[$form[0]][$form[1]];
        if(empty($targetForm)) return;

        $_SESSION['aiInjectData'][$targetForm->m][$targetForm->f] = is_string($data) ? $data : json_encode($data);
    }

    /**
     * Get role templates from db.
     *
     * @access public
     * @return array
     */
    public function getRoleTemplates()
    {
        return $this->dao->select('*')->from(TABLE_PROMPTROLE)
            ->where('deleted')->eq(0)
            ->fetchAll();
    }

    /**
     * add role template.
     *
     * @param  string    $role
     * @param  string    $characterization
     * @access public
     * @return false|int
     */
    public function createRoleTemplate($role, $characterization)
    {
        $roleTemplate = new stdclass();
        $roleTemplate->role = $role;
        $roleTemplate->characterization = $characterization;

        $this->dao->insert(TABLE_PROMPTROLE)
            ->data($roleTemplate)
            ->exec();
        if(dao::isError()) return false;

        return $this->dao->lastInsertID();
    }
}
