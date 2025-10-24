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
     * Action model.
     *
     * @var actionModel
     * @access public
     */
    public $action;

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
     * Check if object action is clickable, used in datatables.
     *
     * @param  object  $object  object to check, model objects are supported, add support for more if needed.
     * @param  string  $method  method on object
     * @access public
     * @static
     * @return boolean
     */
    public function isClickable($object, $action)
    {
        $action = strtolower($action);
        if(empty($object) || empty($action)) return false;

        /* Assumes object is a language model record. */
        if(in_array($this->app->rawMethod, array('models', 'modelview')))
        {
            if($action === 'modelenable')       return $object->enabled != '1';
            if($action === 'modeldisable')      return $object->enabled != '0';
            if($action === 'assistantpublish')  return $object->enabled != '1';
            if($action === 'assistantwithdraw') return $object->enabled != '0';
            if($action === 'assistantedit')     return $object->enabled != '1';
        }

        if(in_array($this->app->rawMethod, array('prompts', 'promptview')))
        {
            $executable = $this->isExecutable($object);
            $published  = $object->status == 'active';

            if($action == 'promptassignrole') return common::hasPriv('ai', 'designPrompt') && !$published;
            if($action == 'promptaudit')      return common::hasPriv('ai', 'designPrompt') && $executable && !$published;
            if($action == 'promptedit')       return common::hasPriv('ai', 'promptedit');
            if($action == 'promptpublish')    return common::hasPriv('ai', 'promptpublish') && !$published && $executable;
            if($action == 'promptunpublish')  return common::hasPriv('ai', 'promptunpublish') && $published && $executable;
        }

        if(in_array($this->app->rawMethod, array('miniprograms', 'miniprogramview')))
        {
            $isPublished = $object->published === '1';
            $isBuiltIn   = $object->builtIn === '1';

            if($action == 'editminiprogram')      return common::hasPriv('ai', 'editMiniProgram') && !$isPublished && !$isBuiltIn;
            if($action == 'testminiprogram')      return common::hasPriv('ai', 'testMiniProgram') && !$isPublished && !$isBuiltIn;
            if($action == 'publishminiprogram')   return common::hasPriv('ai', 'publishMiniProgram') && !$isPublished;
            if($action == 'unpublishminiprogram') return common::hasPriv('ai', 'unpublishMiniProgram') && $isPublished;
            if($action == 'exportminiprogram')    return common::hasPriv('ai', 'exportMiniProgram') && $isPublished && !$isBuiltIn;
        }

        return true;
    }

    /**
     * Set model config with model stored in database.
     *
     * @param  object $config
     * @access public
     * @return bool
     */
    public function setModelConfig($config)
    {
        /* Extract model options if model is from DB. */
        if(isset($config->credentials)) $config = $this->unserializeModel($config);

        /* Abort if config is falsy. */
        if(empty($config)) return false;

        $this->modelConfig = $config;
        return true;
    }

    /**
     * Setup $modelConfig with model stored in database, or default model if not specified.
     *
     * @param  int|mixed  $modelID or falsy values, if falsy, will use default model.
     * @access private
     * @return bool
     */
    private function useLanguageModel($modelID)
    {
        if(!empty($modelID)) $model = $this->getLanguageModel($modelID, true);
        if(empty($model))    $model = $this->getDefaultLanguageModel();
        if(empty($model)) return false;

        return $this->setModelConfig($model);
    }

    /**
     * Whether any LLM is configured and enabled.
     *
     * @access public
     * @return boolean
     */
    public function hasModelsAvailable()
    {
        $models = $this->getLanguageModels('', true);
        return !empty($models);
    }

    /**
     * Get list of configured LLMs.
     *
     * @param  string  $type         type of LLM, empty for all, check keys of `$config->ai->models` for available types.
     * @param  bool    $enabledOnly  whether to only return enabled models
     * @param  pager   $pager
     * @param  string  $orderBy
     * @access public
     * @return array
     */
    public function getLanguageModels($type = '', $enabledOnly = false, $pager = null, $orderBy = 'id_desc')
    {
        return $this->dao->select('*')->from(TABLE_AI_MODEL)
            ->where('deleted')->eq('0')
            ->beginIF(!empty($type))->andWhere('`type`')->eq($type)->fi()
            ->beginIF($enabledOnly)->andWhere('`enabled`')->eq('1')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * Get key-value of all LLMs, includes default.
     *
     * @return array
     */
    public function getLanguageModelNamesWithDefault()
    {
        $models = $this->getLanguageModels();
        $models = array_reduce($models, function ($carry, $model)
        {
            $carry[$model->id] = $model->name;
            return $carry;
        }, array('default' => $this->lang->ai->models->default));
        return $models;
    }

    /**
     * Get LLM config.
     *
     * @param  int           $modelID
     * @param  bool          $enabledOnly
     * @access public
     * @return object|false  LLM config, false if not found.
     */
    public function getLanguageModel($modelID, $enabledOnly = false)
    {
        return $this->dao->select('*')->from(TABLE_AI_MODEL)
            ->where('id')->eq($modelID)
            ->beginIF($enabledOnly)->andWhere('`enabled`')->eq('1')->fi()
            ->andWhere('deleted')->eq('0')
            ->fetch();
    }

    /**
     * Get default (first enabled available) LLM config.
     *
     * @access private
     * @return object|false  default LLM config, false if cannot find any.
     */
    private function getDefaultLanguageModel()
    {
        $models = $this->getLanguageModels('', true, null, 'id_asc');
        if(empty($models)) return false;

        return current($models);
    }

    /**
     * Format model config submitted from form for use with `createModel()` and `updateModel()`.
     *
     * @param  object        $model
     * @access private
     * @return object|false
     */
    private function serializeModel($model)
    {
        /* Check if all credentials fields are present. */
        if(empty($this->config->ai->vendorList[$model->vendor])) return false;
        $credentials = new stdclass();
        foreach($this->config->ai->vendorList[$model->vendor]['credentials'] as $credKey)
        {
            if(empty($model->$credKey)) return false;
            $credentials->$credKey = $model->$credKey;
        }

        $modelConfig = new stdclass();
        $modelConfig->name        = $model->name;
        $modelConfig->desc        = $model->description;
        $modelConfig->type        = $model->type;
        $modelConfig->vendor      = $model->vendor;
        $modelConfig->credentials = json_encode($credentials);
        $modelConfig->enabled     = $model->enabled;

        if(!empty($model->proxyType) && !empty($model->proxyAddr))
        {
            $proxy = new stdclass();
            $proxy->type = $model->proxyType;
            $proxy->addr = $model->proxyAddr;
            $modelConfig->proxy = json_encode($proxy);
        }
        else
        {
            $modelConfig->proxy = '';
        }

        return $modelConfig;
    }

    /**
     * Extract model config for use with actual API calls or for viewing details.
     *
     * @param  object        $model
     * @access public
     * @return object|false
     */
    public function unserializeModel($model)
    {
        $modelConfig = new stdclass();
        $modelConfig->id      = $model->id;
        $modelConfig->name    = $model->name;
        $modelConfig->type    = $model->type;
        $modelConfig->vendor  = $model->vendor;
        $modelConfig->desc    = $model->desc;
        $modelConfig->enabled = $model->enabled;
        $modelConfig->deleted = $model->deleted;

        /* Set default name. */
        if(empty($model->name)) $modelConfig->name = $this->lang->ai->models->typeList[$model->type];

        /* Extract credential props. */
        $credentials = json_decode($model->credentials);
        foreach($credentials as $credKey => $credValue) $modelConfig->$credKey = $credValue;

        /* Extract proxy options. */
        if(!empty($model->proxy))
        {
            $proxy = json_decode($model->proxy);
            foreach($proxy as $proxyKey => $proxyValue) $modelConfig->{'proxy' . ucfirst($proxyKey)} = $proxyValue;
        }

        return $modelConfig;
    }

    /**
     * Create LLM configuration.
     *
     * @param  object     $model  model config details.
     * @access public
     * @return int|false  id of created LLM if success, otherwise false.
     */
    public function createModel($model)
    {
        $model->enabled = '1';

        $modelConfig = $this->serializeModel($model);
        if(!$modelConfig) return false;

        $modelConfig->createdDate = helper::now();
        $modelConfig->createdBy   = $this->app->user->account;

        $this->dao->insert(TABLE_AI_MODEL)
            ->data($modelConfig)
            ->exec();

        return dao::isError() ? false : $this->dao->lastInsertID();
    }

    /**
     * Update LLM configuration.
     *
     * @param  int       $modelID
     * @param  object    $model
     * @access public
     * @return bool
     */
    public function updateModel($modelID, $model)
    {
        $currentModel = $this->getLanguageModel($modelID);
        if(!$currentModel) return false;

        $model->enabled = $currentModel->enabled;
        $modelConfig = $this->serializeModel($model);
        if(!$modelConfig) return false;

        $modelConfig->editedDate = helper::now();
        $modelConfig->editedBy   = $this->app->user->account;

        $this->dao->update(TABLE_AI_MODEL)
            ->data($modelConfig)
            ->where('id')->eq($modelID)
            ->exec();

        if(isset($model->name))
        {
            $this->dao->update(TABLE_IM_CHAT)
                ->set('name')->eq($model->name)
                ->where('gid')->like( "%&ai-$modelID")
                ->exec();
        }

        return !dao::isError();
    }

    /**
     * Toggle model enablement status.
     *
     * @param  int     $modelID
     * @param  bool    $enabled
     * @access public
     * @return bool
     */
    public function toggleModel($modelID, $enabled)
    {
        $this->dao->update(TABLE_AI_MODEL)
            ->set('enabled')->eq(empty($enabled) ? '0' : '1')
            ->set('editedDate')->eq(helper::now())
            ->set('editedBy')->eq($this->app->user->account)
            ->where('id')->eq($modelID)
            ->exec();

        $this->dao->update(TABLE_IM_CHAT)
            ->set('archiveDate')->eq(empty($enabled) ? helper::now() : null)
            ->where('gid')->like( "%&ai-$modelID")
            ->exec();

        return !dao::isError();
    }

    /**
     * Mark model as deleted.
     *
     * @param  int     $modelID
     * @access public
     * @return bool
     */
    public function deleteModel($modelID)
    {
        $this->dao->update(TABLE_AI_MODEL)
            ->set('deleted')->eq('1')
            ->set('editedDate')->eq(helper::now())
            ->set('editedBy')->eq($this->app->user->account)
            ->where('id')->eq($modelID)
            ->exec();

        $this->dao->update(TABLE_IM_CHAT)
            ->set('dismissDate')->eq(helper::now())
            ->where('gid')->like( "%&ai-$modelID")
            ->exec();

        $this->dao->update(TABLE_AI_ASSISTANT)
            ->set('deleted')->eq('1')
            ->where('modelId')->eq($modelID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Make a test request to the model.
     *
     * @param  int     $modelID
     * @access public
     * @return bool
     */
    public function testModelConnection($modelID)
    {
        $this->useLanguageModel($modelID);
        if($this->config->ai->models[$this->modelConfig->type] == 'ernie' || $this->modelConfig->vendor == 'azure' || $this->modelConfig->type == 'openai-gpt4' || $this->modelConfig->vendor == 'openaiCompatible')
        {
            $messages = array((object)array('role' => 'user', 'content' => 'test'));
            $result = $this->converse($modelID, $messages, array('maxTokens' => 1));
        }
        else
        {
            $result = $this->complete($modelID, 'test', 1); // Test completing 'test' with length of 1.
        }

        return !empty($result);
    }

    /**
     * Make request to OpenAI API.
     *
     * @param  string   $type     chat | completion | edit
     * @param  mixed    $data     data to send
     * @param  int      $timeout  request timeout in seconds
     * @access private
     * @return object   response object, three properties: result, message (if fail), content (if success).
     */
    private function makeRequest($type, $data, $timeout = 10)
    {
        $modelType   = $this->config->ai->models[$this->modelConfig->type];
        $modelVendor = $this->modelConfig->vendor;

        /* Try encoding data to json, handles both encoded json and raw data. */
        $postData = json_encode($data);
        if(json_last_error()) $postData = $data;

        /* Set auth and content-type headers. */
        $requestHeaders = array();
        if(isset($this->config->ai->$modelType->api->$modelVendor->authFormat)) $requestHeaders[] = sprintf($this->config->ai->$modelType->api->$modelVendor->authFormat, $this->modelConfig->key);
        $requestHeaders[] = isset($this->config->ai->$modelType->contentType[$type]) ? $this->config->ai->$modelType->contentType[$type] : $this->config->ai->$modelType->contentType[''];

        /* Assemble request url. */
        if($modelType == 'openai')
        {
            if($modelVendor == 'openaiCompatible')
            {
                $url = sprintf($this->config->ai->openai->api->openaiCompatible->format, rtrim($this->modelConfig->base, '/'), $this->config->ai->openai->api->methods[$type]);
            }
            elseif($modelVendor == 'azure')
            {
                $url = sprintf($this->config->ai->openai->api->azure->format, $this->modelConfig->resource, $this->modelConfig->deployment, $this->config->ai->openai->api->methods[$type], $this->config->ai->openai->api->azure->apiVersion);
            }
            else
            {
                $url = sprintf($this->config->ai->openai->api->openai->format, $this->config->ai->openai->api->openai->version, $this->config->ai->openai->api->methods[$type]);
            }
        }
        elseif($modelType == 'ernie')
        {
            $clientID     = $this->modelConfig->key;
            $clientSecret = $this->modelConfig->secret;
            $authURL = sprintf($this->config->ai->ernie->api->$modelVendor->auth, $clientID, $clientSecret);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $authURL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            $result = curl_exec($ch);
            if(!$result || curl_errno($ch))
            {
                $response = new stdclass();
                $response->result  = 'fail';
                $response->message = curl_error($ch);
                curl_close($ch);
                return $response;
            }
            $result = json_decode($result);
            if(json_last_error())
            {
                $response = new stdclass();
                $response->result  = 'fail';
                $response->message = 'JSON decode error: ' . json_last_error_msg();
                curl_close($ch);
                return $response;
            }
            if(empty($result->access_token)) return (object)array('result' => 'fail', 'message' => $this->lang->ai->models->authFailure);
            $accessToken = $result->access_token;

            $url = sprintf($this->config->ai->ernie->api->$modelVendor->format, $accessToken);
        }

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

        $response = new stdclass();

        if(!$result || curl_errno($ch))
        {
            $response->result  = 'fail';
            $response->message = curl_error($ch);
        }
        else
        {
            $response->result = 'success';
            $response->content = $result;
        }

        curl_close($ch);
        return $response;
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
        $modelType = $this->config->ai->models[$this->modelConfig->type];

        /* Models of the same type may differ in version, in which case they are stored as an array. */
        $modelName = $this->config->ai->$modelType->model->$type;
        if(is_array($modelName)) $modelName = $modelName[$this->modelConfig->type];

        $postData = new stdclass();
        $postData->model = $modelName;

        $data = static::standardizeParams($data);

        /* Set required params, abort if missing. */
        foreach($this->config->ai->$modelType->params->$type->required as $param)
        {
            if(!isset($data->$param)) return false;
            $postData->$param = $data->$param;
        }

        /* Set optional params. */
        foreach($this->config->ai->$modelType->params->$type->optional as $param)
        {
            if(isset($data->$param)) $postData->$param = $data->$param;
        }

        return $postData;
    }

    /**
     * Decode response from OpenAI API, add error to $this->errors if any.
     *
     * @param  object   $response  response object
     * @access private
     * @return mixed    false if error, json object if success
     */
    private function decodeResponse($response)
    {
        if($response->result === 'fail')
        {
            $this->errors[] = empty($response->message) ? 'Unknown error' : $response->message;
            return false;
        }

        $response = json_decode($response->content);
        if(json_last_error())
        {
            /* Polyfill for PHP 5 < 5.5.0. */
            if(!function_exists('json_last_error_msg'))
            {
                function json_last_error_msg()
                {
                    switch (json_last_error())
                    {
                        case JSON_ERROR_DEPTH:
                            return 'Maximum stack depth exceeded';
                        case JSON_ERROR_STATE_MISMATCH:
                            return 'Underflow or the modes mismatch';
                        case JSON_ERROR_CTRL_CHAR:
                            return 'Unexpected control character found';
                        case JSON_ERROR_SYNTAX:
                            return 'Syntax error, malformed JSON';
                        case JSON_ERROR_UTF8:
                            return 'Malformed UTF-8 characters, possibly incorrectly encoded';
                        default:
                            return 'Unknown error';
                    }
                }
            }

            $this->errors[] = 'JSON decode error: ' . json_last_error_msg();
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

        if($this->config->ai->models[$this->modelConfig->type] == 'ernie')
        {
            /* Extract chat message text. */
            if(!empty($response->result))
            {
                $messages = array($response->result);
                return $messages;
            }
        }
        else
        {
            /* Extract chat message choices. */
            if(isset($response->choices) && count($response->choices) > 0)
            {
                $messages = array();
                foreach($response->choices as $choice) $messages[] = $choice->message->content;
                return $messages;
            }
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

        if($this->config->ai->models[$this->modelConfig->type] == 'ernie')
        {
            /* Extract function call arguments. */
            if(!empty($response->function_call)) return array($response->function_call->arguments);
            throw new AIResponseException('notFunctionCalling', $response);
        }
        else
        {
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
        }
        return false;
    }

    /**
     * Complete text with OpenAI GPT.
     *
     * @param   int      $model      model id
     * @param   string   $prompt     text to complete
     * @param   int      $maxTokens  max tokens to generate
     * @param   array    $options    optional params, see https://platform.openai.com/docs/api-reference/completions/create
     * @access  public
     * @return  mixed    false if error, array of texts (choices) if success
     */
    public function complete($model, $prompt, $maxTokens = 512, $options = array())
    {
        if(empty($this->modelConfig) && !$this->useLanguageModel($model)) return false;

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
     * @param   int     $model        model id
     * @param   string  $input        text to edit
     * @param   string  $instruction  edit instruction
     * @param   array   $options      optional params, see https://platform.openai.com/docs/api-reference/edits/create
     * @access  public
     * @return  mixed   false if error, array of texts (choices) if success
     */
    public function edit($model, $input, $instruction, $options = array())
    {
        if(empty($this->modelConfig) && !$this->useLanguageModel($model)) return false;

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
     * @param  int     $model     model id
     * @param  array   $messages  array of chat messages
     * @param  array   $options   optional params, see https://platform.openai.com/docs/api-reference/chat/create
     * @access public
     * @return mixed   false if error, array of chat messages if success
     */
    public function converse($model, $messages, $options = array())
    {
        if(empty($this->modelConfig) && !$this->useLanguageModel($model)) return false;

        /* Filter system message out for ERNIE. */
        if($this->config->ai->models[$this->modelConfig->type] == 'ernie')
        {
            $systemMessage = current(array_filter($messages, function($message) { return $message->role == 'system'; }));
            if(!empty($systemMessage)) $options['system'] = $systemMessage->content;

            $messages = array_values(array_filter($messages, function($message) { return $message->role == 'assistant' || $message->role == 'user'; }));
        }

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
     * @param  int    $model     model id
     * @param  array  $messages  array of chat messages
     * @param  object $schema    schema of the output
     * @param  array  $options   optional params, see https://platform.openai.com/docs/api-reference/chat/create
     * @access public
     * @return mixed  false if error, array of JSON object if success
     */
    public function converseForJSON($model, $messages, $schema, $options = array())
    {
        if(empty($this->modelConfig) && !$this->useLanguageModel($model)) return false;

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
     * Generate conversations with LLM for JSON output, but seperate the conversation into two parts.
     *
     * Usage is the same as function `converseForJSON`, but this function will generate two conversations:
     * one for manipulating the natural language data, and the other for generating the JSON output.
     *
     * @param  int    $model     model id
     * @param  array  $messages  array of chat messages
     * @param  object $schema    schema of the output
     * @param  array  $options   optional params, see https://platform.openai.com/docs/api-reference/chat/create
     * @access public
     * @return mixed  false if error, array of JSON object if success
     * @throws AIResponseException
     */
    public function converseTwiceForJSON($model, $messages, $schema, $options = array())
    {
        if(empty($this->modelConfig) && !$this->useLanguageModel($model)) return false;

        /* First conversation. */
        $data = compact('messages');
        if(!empty($options))
        {
            foreach($options as $key => $value) $data[$key] = $value;
        }

        $postData = $this->assembleRequestData('chat', $data);
        if(!$postData) return false;

        $chatResponse = $this->makeRequest('chat', $postData);
        $chatMessages = $this->parseChatResponse($chatResponse);

        $chatMessage = current($chatMessages);
        if(empty($chatMessage)) return false;

        /* Second conversation for JSON output. */
        $messages  = array_merge($this->lang->ai->engineeredPrompts->askForFunctionCalling, array((object)array('role' => 'user', 'content' => $chatMessage)));
        $functions = array((object)array('name' => 'function', 'description' => 'function', 'parameters' => $schema));

        $data = compact('messages', 'functions');
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
     * Get latest mini programs.
     *
     * @param pager $pager
     * @access public
     * @return array
     */
    public function getLatestMiniPrograms($pager = null, $order = 'publishedDate_desc')
    {
        return $this->dao->select('*')
            ->from(TABLE_AI_MINIPROGRAM)
            ->where('deleted')->eq('0')
            ->andWhere('published')->eq('1')
            ->andWhere('publishedDate')->ge(date('Y-m-d H:i:s', strtotime('-1 months')))
            ->orderBy($order)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * Count latest mini programs.
     *
     * @access public
     * @return int
     */
    public function countLatestMiniPrograms()
    {
        return (int)$this->dao->select('COUNT(1) AS count')
            ->from(TABLE_AI_MINIPROGRAM)
            ->where('deleted')->eq('0')
            ->andWhere('published')->eq('1')
            ->andWhere('createdDate')->ge(date('Y-m-d H:i:s', strtotime('-1 months')))
            ->fetch('count');
    }

    /**
     * Save mini program message.
     *
     * @param string $appID
     * @param string $type
     * @param string $content
     * @access public
     * @return bool
     */
    public function saveMiniProgramMessage($appID, $type, $content)
    {
        $message = new stdClass();
        $message->appID = $appID;
        $message->user = $this->app->user->id;
        $message->type = $type;
        $message->content = $content;
        $message->createdDate = helper::now();

        $this->dao->insert(TABLE_AI_MESSAGE)
            ->data($message)
            ->exec();
        return !dao::isError();
    }

    /**
     * Delete history messages by id.
     *
     * @param string $appID
     * @param string $userID
     * @param array  $messageIDs
     * @access public
     * @return void
     */
    public function deleteHistoryMessagesByID($appID, $userID, $messageIDs)
    {
        $this->dao->delete()
            ->from(TABLE_AI_MESSAGE)
            ->where('appID')->eq($appID)
            ->andWhere('user')->eq($userID)
            ->andWhere('id')->notin($messageIDs)
            ->exec();
    }

    /**
     * Get history messages.
     *
     * @param string $appID
     * @param int    $limit
     * @access public
     * @return array
     */
    public function getHistoryMessages($appID, $limit = 20)
    {
        $messages = $this->dao->select('*')
            ->from(TABLE_AI_MESSAGE)
            ->where('appID')->eq($appID)
            ->andWhere('user')->eq($this->app->user->id)
            ->orderBy('createdDate_desc')
            ->limit($limit)
            ->fetchAll('id', false);

        $messageIDs = array();
        foreach($messages as $message)
        {
            $message->createdDate = date('Y/n/j G:i', strtotime($message->createdDate));
            $messageIDs[] = $message->id;
        }
        $this->deleteHistoryMessagesByID($appID, $this->app->user->id, $messageIDs);

        return $messages;
    }

    /**
     * Get mini programs by appid.
     *
     * @param array $ids
     * @param bool $sort
     * @access public
     * @return array
     */
    public function getMiniProgramsByID($ids, $sort = false)
    {
        $miniPrograms = $this->dao->select('*')
            ->from(TABLE_AI_MINIPROGRAM)
            ->where('id')->in($ids)
            ->fetchAll('id', false);
        if(!$sort) return $miniPrograms;

        $sortIDs = array_flip($ids);
        $sortedPrograms = array();
        foreach($miniPrograms as $program) $sortedPrograms[$sortIDs[$program->id]] = $program;
        ksort($sortedPrograms);
        return $sortedPrograms;
    }

    /**
     * Get mini program by appid.
     *
     * @param string $id
     * @access public
     * @return object
     */
    public function getMiniProgramByID($id)
    {
        return $this->dao->select('*')
            ->from(TABLE_AI_MINIPROGRAM)
            ->where('id')->eq($id)
            ->fetch();
    }

    /**
     * Get custom categories.
     *
     * @access public
     * @return array
     */
    public function getCustomCategories()
    {
        return $this->dao->select('`key`,`value`')
            ->from(TABLE_CONFIG)
            ->where('module')->eq('ai')
            ->andWhere('section')->eq('miniProgram')
            ->fetchPairs();
    }

    /**
     * Get used custom categories.
     *
     * @access public
     * @return array
     */
    public function getUsedCustomCategories()
    {
        $categories = $this->dao->select('distinct `category`')
            ->from(TABLE_AI_MINIPROGRAM)
            ->where('deleted')->eq('0')
            ->fetchAll('category');
        return array_keys($categories);
    }

    /**
     * Get published custom categories.
     *
     * @access public
     * @return array
     */
    public function getPublishedCustomCategories()
    {
        $categories = $this->dao->select('distinct `category`')
            ->from(TABLE_AI_MINIPROGRAM)
            ->where('published')->eq('1')
            ->fetchAll('category');
        return array_keys($categories);
    }

    /**
     * Check duplicated category name.
     *
     * @access public
     * @return bool
     */
    public function checkDuplicatedCategory()
    {
        $data = array_filter($_POST);
        if(empty($data)) return false;

        $categories = array_values($this->lang->ai->miniPrograms->categoryList);
        foreach($data as $value)
        {
            if(is_string($value))
            {
                if(in_array($value, $categories)) return true;
                $categories[] = $value;
            }

            if(is_array($value))
            {
                $value = array_filter($value);
                foreach($value as $v)
                {
                    if(in_array($v, $categories)) return true;
                    $categories[] = $v;
                }
            }
        }

        return false;
    }

    /**
     * Update custom categories.
     *
     * @access public
     * @access public
     * @return void
     */
    public function updateCustomCategories()
    {
        $this->dao->delete()
            ->from(TABLE_CONFIG)
            ->where('module')->eq('ai')
            ->andWhere('section')->eq('miniProgram')
            ->exec();

        $data = array_filter($_POST);
        if(empty($data)) return;

        foreach($data as $key => $value)
        {
            if(is_string($value))
            {
                $category = new stdClass();
                $category->module  = 'ai';
                $category->section = 'miniProgram';
                $category->owner   = 'system';
                $category->vision  = '';
                $category->key   = $key;
                $category->value = $value;

                $this->dao->insert(TABLE_CONFIG)
                    ->data($category)
                    ->exec();

                continue;
            }

            if(is_array($value))
            {
                $value = array_filter($value);
                foreach($value as $v)
                {
                    $category = new stdClass();
                    $category->module  = 'ai';
                    $category->section = 'miniProgram';
                    $category->owner   = 'system';
                    $category->vision  = '';
                    $category->key     = uniqid('custom-');
                    $category->value   = $v;

                    $this->dao->insert(TABLE_CONFIG)
                        ->data($category)
                        ->exec();
                }
            }
        }
    }

    /**
     * Get not deleted mini programs.
     *
     * @param string $category
     * @param string $status
     * @param string $order
     * @param pager  $pager
     * @access public
     * @return array
     */
    public function getMiniPrograms($category = '', $status = '', $order = 'createdDate_desc', $pager = null)
    {
        $programs = $this->dao->select('*')
            ->from(TABLE_AI_MINIPROGRAM)
            ->where('deleted')->eq('0')
            ->beginIF(!empty($category))->andWhere('category')->eq($category)->fi()
            ->beginIF($status === 'active')->andWhere('published')->eq('1')->fi()
            ->beginIF($status === 'draft')->andWhere('published')->ne('1')->fi()
            ->beginIF($status === 'createdByMe')->andWhere('createdBy')->eq($this->app->user->account)->fi()
            ->orderBy($order)
            ->page($pager)
            ->fetchAll('id', false);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'miniprogram');
        return $programs;
    }

    /**
     * Get mini program fields by appid.
     *
     * @param string $appID
     * @access public
     * @return array
     */
    public function getMiniProgramFields($appID)
    {
        return $this->dao->select('*')
            ->from(TABLE_AI_MINIPROGRAMFIELD)
            ->where('appID')->eq($appID)
            ->fetchAll('id', false);
    }

    /**
     * @param string $appID
     * @access public
     * @return void
     */
    public function createNewVersionNotification($appID)
    {
        $users = $this->dao->select('user')
            ->from(TABLE_AI_MESSAGE)
            ->where('appID')->eq($appID)
            ->fetchAll('user');
        $users = array_keys($users);

        foreach($users as $user)
        {
            $data = new stdClass();
            $data->appID       = $appID;
            $data->user        = $user;
            $data->type        = 'ntf';
            $data->content     = sprintf($this->lang->ai->miniPrograms->newVersionTip, date("Y/n/j G:i"));
            $data->createdDate = helper::now();

            $this->dao->delete()
                ->from(TABLE_AI_MESSAGE)
                ->where('appID')->eq($appID)
                ->andWhere('user')->eq($user)
                ->andWhere('type')->eq('ntf')
                ->exec();

            $this->dao->insert(TABLE_AI_MESSAGE)
                ->data($data)
                ->exec();
        }
    }

    /**
     * Change mini program `publish` value.
     *
     * @param string $appID
     * @param string $published
     * @access public
     * @return bool
     */
    public function publishMiniProgram($appID, $published = '1')
    {
        $data = new stdClass();
        $data->published = $published;
        if($published === '1')
        {
            $data->publishedDate = helper::now();
            $miniProgram = $this->getMiniProgramByID($appID);
            if(!empty($miniProgram->publishedDate))
            {
                $this->createNewVersionNotification($appID);
            }
        }

        $this->dao->update(TABLE_AI_MINIPROGRAM)
            ->data($data)
            ->where('id')->eq($appID)
            ->exec();

        $this->loadModel('action')->create('miniProgram', $appID, $published === '1' ? 'published' : 'unpublished');

        if($published !== '1') $this->collectMiniProgram(null, $appID, 'true');
        return !dao::isError();
    }

    /**
     * Collect mini program.
     *
     * @param string $userID
     * @param string $appID
     * @param string $delete
     * @access public
     * @return bool
     */
    public function collectMiniProgram($userID, $appID, $delete = 'false')
    {
        if($delete === 'true')
        {
            $this->dao->delete()
                ->from(TABLE_AI_MINIPROGRAMSTAR)
                ->where('appID')->eq($appID)
                ->beginIF(!empty($userID))->andWhere('userID')->eq($userID)->fi()
                ->exec();
            return !dao::isError();
        }

        $data = new stdClass();
        $data->appID = $appID;
        $data->userID = $userID;
        $data->createdDate = helper::now();

        $this->dao->insert(TABLE_AI_MINIPROGRAMSTAR)
            ->data($data)
            ->exec();
        return !dao::isError();
    }

    /**
     * Determine whether Mini programs can be published.
     *
     * @param object $program
     * @access public
     * @return bool
     */
    public function canPublishMiniProgram($program)
    {
        $isNotEmpty = function ($str)
        {
            return isset($str) && strlen(strval($str)) > 0;
        };

        return $isNotEmpty($program->id)
            && $isNotEmpty($program->name)
            && $isNotEmpty($program->desc)
            && $isNotEmpty($program->category)
            && $isNotEmpty($program->model)
            && $isNotEmpty($program->prompt);
    }

    /**
     * Create a mini program.
     *
     * @access public
     * @return int|false
     */
    public function createMiniProgram($data = null)
    {
        if(empty($data)) $data = fixer::input('post')->get();
        if(!isset($data->editedBy))    $data->editedBy    = $this->app->user->account;
        if(!isset($data->editedDate))  $data->editedDate  = helper::now();
        if(!isset($data->published))   $data->published   = '0';
        if(!isset($data->createdBy))   $data->createdBy   = $this->app->user->account;
        if(!isset($data->createdDate)) $data->createdDate = helper::now();
        if(!isset($data->prompt))      $data->prompt      = $this->lang->ai->miniPrograms->field->default[3];
        if($data->published === '1')   $data->publishedDate = helper::now();
        $data->builtIn = '0';

        if(!empty($data->iconName) && !empty($data->iconTheme))
        {
            $data->icon = $data->iconName . '-' . $data->iconTheme;
            unset($data->iconName);
            unset($data->iconTheme);
        }

        if(isset($data->fields))
        {
            $fields = $data->fields;
            unset($data->fields);
        }

        $this->dao->insert(TABLE_AI_MINIPROGRAM)
            ->data($data)
            ->exec();
        $appID = $this->dao->lastInsertID();

        if(!isset($fields))
        {
            $defaultFields = $this->lang->ai->miniPrograms->field->default;
            $fieldData = new stdClass();
            $fieldData->fields = array();
            for($i = 0; $i < 3; $i++)
            {
                $field = new stdClass();
                $field->appID        = $appID;
                $field->name         = $defaultFields[$i];
                $field->type         = 'text';
                $field->placeholder  = $this->lang->ai->miniPrograms->placeholder->input;
                $field->options      = null;
                $field->required     = '0';
                $fieldData->fields[] = $field;
            }
            $this->saveMiniProgramFields($appID, $fieldData);
        }
        else
        {
            $fieldData = new stdClass();
            $fieldData->fields = $fields;
            foreach($fields as $field)
            {
                if(!isset($field->appID)) $field->appID = $appID;
            }
            $this->saveMiniProgramFields($appID, $fieldData);
        }

        if(dao::isError()) return false;

        $this->loadModel('action')->create('miniProgram', $appID, 'created');
        return $appID;
    }

    public function extractZtAppZip($file)
    {
        $this->app->loadClass('pclzip', true);
        $zip = new pclzip($file);
        return $zip->extract(PCLZIP_OPT_PATH, $this->app->getAppRoot() . 'tmp/');
    }

    /**
     * Save mini program fields.
     *
     * @param string $appID
     * @access public
     * @return void
     */
    public function saveMiniProgramFields($appID, $data = null)
    {
        if(empty($data)) $data = fixer::input('post')->get();

        if(isset($data->prompt))
        {
            $this->dao->update(TABLE_AI_MINIPROGRAM)
                ->set('prompt')->eq($data->prompt)
                ->where('id')->eq($appID)
                ->exec();
        }

        $this->dao->delete()
            ->from(TABLE_AI_MINIPROGRAMFIELD)
            ->where('appID')->eq($appID)
            ->exec();

        $fields = $data->fields;
        foreach($fields as $field)
        {
            $field = (array)$field;
            if(isset($field['options']) && is_array($field['options'])) $field['options'] = implode(',', $field['options']);
            $this->dao->insert(TABLE_AI_MINIPROGRAMFIELD)
                ->data($field)
                ->exec();
        }
    }

    /**
     * Check for duplicate names of Mini programs.
     *
     * @param string      $name
     * @param string|null $appID
     * @access public
     * @return boolean
     */
    public function checkDuplicatedAppName($name, $appID = '-1')
    {
        $miniProgram = $this->dao->select('*')
            ->from(TABLE_AI_MINIPROGRAM)
            ->where('name')->eq($name)
            ->andWhere('id')->ne($appID)
            ->andWhere('deleted')->eq('0')
            ->fetch();
        return !empty($miniProgram);
    }

    /**
     * Get unique app name.
     *
     * @param string $name
     * @return string
     */
    public function getUniqueAppName($name)
    {
        while($this->checkDuplicatedAppName($name))
        {
            $name = $name . '_1';
        }
        return $name;
    }

    /**
     * Verify that any required fields are empty.
     *
     * @param array $requiredFields
     * @access public
     * @return false|array false or errors.
     */
    public function verifyRequiredFields($requiredFields)
    {
        $errors = array();
        foreach($requiredFields as $field => $fieldLang)
        {
            if(!isset($_POST[$field]) || $_POST[$field] === "") $errors[$field] = sprintf($this->lang->error->notempty, $fieldLang);
        }
        if(!empty($errors)) return $errors;
        return false;
    }

    /**
     * Get list of prompts.
     *
     * @param  string  $module
     * @param  string  $status
     * @access public
     * @return array
     */
    public function getPrompts($module = '', $status = '', $order = 'id_desc', $pager = null)
    {
        $prompts = $this->dao->select('*')->from(TABLE_AI_PROMPT)
            ->where('deleted')->eq(0)
            ->beginIF(!empty($module))->andWhere('module')->eq($module)->fi()
            ->beginIF(!empty($status))->andWhere('status')->eq($status)->fi()
            ->orderBy($order)
            ->page($pager)
            ->fetchAll('id', false);
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'prompt');
        return $prompts;
    }

    /**
     * Get prompt by id.
     *
     * @param  int     $id
     * @access public
     * @return object
     */
    public function getPromptById($id)
    {
        return $this->dao->select('*')->from(TABLE_AI_PROMPT)
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

        $this->dao->insert(TABLE_AI_PROMPT)
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
                $changes = commonModel::createChanges($originalPrompt, $prompt);
            }
        }

        $prompt->editedDate = helper::now();
        $prompt->editedBy   = $this->app->user->account;

        /* Override uniqueness error message. */
        $this->lang->error->unique = $this->lang->ai->validate->nameNotUnique;

        $this->dao->update(TABLE_AI_PROMPT)
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

        $this->dao->update(TABLE_AI_PROMPT)
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
        if(is_string($sources))
        {
            if(empty($sources)) return '';
            if(strpos($sources, ',') !== false) $sources = array_filter(explode(',', $sources));
            else                                $sources = array($sources);
            $sources = array_map(function ($source)
            {
                return explode('.', $source);
            }, $sources);
        }

        /* Handle empty sources array. */
        if(empty($sources)) return '';

        $dataObject = array();

        $supplement = '';
        $supplementTypes = array();

        foreach($sources as $source)
        {
            $objectName = $source[0];
            $objectKey  = $source[1];

            if(!isset($this->lang->ai->dataSource[$module][$objectName]['common'])) continue;
            if(!isset($this->lang->ai->dataSource[$module][$objectName][$objectKey])) continue;

            $semanticName = $this->lang->ai->dataSource[$module][$objectName]['common'];
            $semanticKey  = $this->lang->ai->dataSource[$module][$objectName][$objectKey];

            if(empty($dataObject[$semanticName])) $dataObject[$semanticName] = array();

            $obj = $data[$objectName];
            if(static::isAssoc($obj))
            {
                $dataObject[$semanticName][$semanticKey] = isset($data[$objectName][$objectKey]) ? $data[$objectName][$objectKey] : '';
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
        return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match)
        {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, json_encode($dataObject)) . (empty($supplement) ? '' : ("\n" . $supplement));
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
        if($form == 'empty.empty') return $form;

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
        switch ($module)
        {
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
                if(isset($sourceGroups['bugs']))    $object->bugs    = array_values($this->loadModel('bug')->getByIdList(array_filter(explode(',', $object->release->bugs))));
                break;
            case 'productplan':
                if(isset($sourceGroups['productplan'])) $object->productplan = $this->loadModel('productplan')->getByID($objectId);
                if(isset($sourceGroups['stories']))     $object->stories     = array_values($this->loadModel('story')->getPlanStories($objectId));
                if(isset($sourceGroups['bugs']))        $object->bugs        = array_values($this->dao->select('*')->from(TABLE_BUG)->where('plan')->eq($objectId)->andWhere('deleted')->eq(0)->fetchAll('id', false));
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
                if(!isset($object->$objectName)) continue;

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
    public static function assemblePrompt($prompt, $dataPrompt)
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
     * @throws AIResponseException
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

        $wholePrompt = static::assemblePrompt($prompt, $dataPrompt);
        $schema      = $this->getFunctionCallSchema($prompt->targetForm);
        if(empty($schema)) return -5;

        $this->useLanguageModel($prompt->model);
        return array('prompt' => $wholePrompt, 'schema' => $schema, 'dataPrompt' => $dataPrompt, 'name' => $prompt->name, 'purpose' => $prompt->purpose, 'status' => $prompt->status, 'targetForm' => $prompt->targetForm, 'promptID' => $prompt->id);
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
        elseif($module == 'product')
        {
            $objectId = $this->dao->select('max(id) as maxId')->from(TABLE_PRODUCT)
                ->where('id')->in($this->app->user->view->products)
                ->fetch('maxId');
        }
        elseif($module == 'productplan')
        {
            $objectId = $this->dao->select('max(id) as maxId')->from(TABLE_PRODUCTPLAN)
                ->where('product')->in($this->app->user->view->products)
                ->fetch('maxId');
        }
        elseif($module == 'release')
        {
            $objectId = $this->dao->select('max(id) as maxId')->from(TABLE_RELEASE)
                ->where('project')->in($this->app->user->view->projects)
                ->orWhere('product')->in($this->app->user->view->products)
                ->fetch('maxId');
        }
        elseif($module == 'project')
        {
            /* programplan/create only exist in the waterfall model project. */
            if(strpos($prompt->targetForm, 'programplan/create'))
            {
                $objectId = $this->dao->select('max(id) as maxId')->from(TABLE_PROJECT)
                    ->where('id')->in($this->app->user->view->projects)
                    ->andWhere('model')->eq('waterfall')
                    ->fetch('maxId');
            }
            else
            {
                $objectId = $this->dao->select('max(id) as maxId')->from(TABLE_PROJECT)
                    ->where('id')->in($this->app->user->view->projects)
                    ->fetch('maxId');
            }
        }
        elseif($module == 'story')
        {
            $objectId = $this->dao->select('max(id) as maxId')->from(TABLE_STORY)
                ->where('product')->in($this->app->user->view->products)
                ->fetch('maxId');
        }
        elseif($module == 'execution')
        {
            $executionIds = array_map('intval', explode(',', $this->app->user->view->sprints));
            $objectId  = max($executionIds);
        }
        elseif($module == 'task')
        {
            $objectId = $this->dao->select('max(id) as maxId')->from(TABLE_TASK)
                ->where('project')->in($this->app->user->view->projects)
                ->fetch('maxId');
        }
        elseif($module == 'case')
        {
            $objectId = $this->dao->select('max(id) as maxId')->from(TABLE_CASE)
                ->where('project')->in($this->app->user->view->projects)
                ->orWhere('product')->in($this->app->user->view->products)
                ->fetch('maxId');
        }
        elseif($module == 'bug')
        {
            $objectId = $this->dao->select('max(id) as maxId')->from(TABLE_BUG)
                ->where('project')->in($this->app->user->view->projects)
                ->orWhere('product')->in($this->app->user->view->products)
                ->fetch('maxId');
        }
        elseif($module == 'doc')
        {
            $objectId = $this->dao->select('max(id) as maxId')->from(TABLE_DOC)
                ->where('project')->in($this->app->user->view->projects)
                ->orWhere('product')->in($this->app->user->view->products)
                ->fetch('maxId');
            if(empty($objectId))
            {
                $userDocLibs = $this->dao->select('id')->from(TABLE_DOCLIB)
                    ->where('type')->eq('mine')
                    ->andWhere('addedBy')->eq($this->app->user->account)
                    ->fetchPairs();
                if(!empty($userDocLibs))
                {
                    $objectId = $this->dao->select('max(id) as maxId')->from(TABLE_DOC)
                        ->where('lib')->in($userDocLibs)
                        ->fetch('maxId');
                }
            }
        }

        if(!empty($objectId)) return helper::createLink('ai', 'promptexecute', "promptId=$prompt->id&objectId=$objectId");

        return false;
    }

    /**
     * Get target form location of object for prompt.
     *
     * @param  object|int   $prompt    prompt object or prompt id
     * @param  object       $object
     * @param  array        $linkArgs  optional, link arguments, as defined in `->args` of items of `$config->ai->targetFormVars`, e.g. array('story' => 1). if not provided, will try to get from object.
     * @access public
     * @return array        array(link|false, bool), link to target form, and whether php should stop execution and return link.
     */
    public function getTargetFormLocation($prompt, $object, $linkArgs = array())
    {
        if(is_numeric($prompt)) $prompt = $this->getByID($prompt);
        if(empty($prompt)) return array(false, true);

        $targetForm = $prompt->targetForm;
        if(empty($targetForm)) return array(false, true);

        list($m, $f) = explode('.', $targetForm);
        $targetFormConfig = $this->config->ai->targetForm[$m][$f];
        $module = strtolower($targetFormConfig->m);
        $method = strtolower($targetFormConfig->f);

        /* Try to assemble link vars from both passed-in `$linkArgs` and object props. */
        $varsConfig = isset($this->config->ai->targetFormVars[$m][$f]) ? $this->config->ai->targetFormVars[$m][$f] : $this->config->ai->targetFormVars[$module][$method];
        $vars = array();
        foreach($varsConfig->args as $arg => $isRequired)
        {
            $var = '';
            if(!empty($linkArgs[$arg])) // Use provided link args.
            {
                $var = $linkArgs[$arg];
            }
            elseif(!empty($object->$arg) && is_object($object->$arg) && !empty($object->$arg->id)) // If the corresponding object exists, use its id.
            {
                $var = $object->$arg->id;
            }
            elseif(!empty($object->{$prompt->module}->$arg)) // If object has the prop, use it.
            {
                $var = $object->{$prompt->module}->$arg;
            }
            else
            {
                /* Try to get a related object, we are sorry if it could not find any. */
                $relatedObj = $this->tryGetRelatedObjects($prompt, $object, array($arg));
                if(!empty($relatedObj))
                {
                    $relatedObj = current($relatedObj);
                    if(is_string($relatedObj) && strlen($relatedObj) && $relatedObj[0] === ',' && $relatedObj[strlen($relatedObj) - 1] === ',')
                    {
                        $relatedObj = explode(',', $relatedObj);
                        $relatedObj = array_filter($relatedObj);
                        $relatedObj = current($relatedObj);
                    }
                    $var = $relatedObj;
                }
            }
            if(!empty($isRequired) && empty($var)) return array(helper::createLink('ai', 'promptExecutionReset', 'failed=1'), true);
            $vars[] = $var;
        }
        $linkVars = vsprintf($varsConfig->format, $vars);

        /* Overrides for stories. */
        if($module == 'story' && $method == 'change' && !empty($object->story) && $object->story->status == 'draft') $method = 'edit';
        if($module == 'story' && $method == 'change' && !empty($object->story) && $object->story->type == 'epic')    $module = 'epic';

        return array(helper::createLink($module, $method, $linkVars) . (empty($varsConfig->app) ? '' : "#app=$varsConfig->app"), false);
    }

    /**
     * Get related objects for prompt and object, using object's id.
     *
     * @param  object|int   $prompt      prompt object or prompt id
     * @param  object|int   $object      object or object id
     * @param  array        $objectNames object names to get, e.g. array('story', 'tasks'),
     *                                   values in: product, story, branch, productplan, execution, task, bug, case, project, doc
     * @access public
     * @return array|false  returns array of required object values, or false if error.
     */
    public function tryGetRelatedObjects($prompt, $object, $objectNames = array())
    {
        if(empty($objectNames)) return array();

        if(is_numeric($prompt)) $prompt = $this->getByID($prompt);
        if(empty($prompt)) return false;

        if(is_numeric($object)) $object = $this->getObjectForPromptById($prompt, $object);
        if(empty($object)) return false;

        $vars = array();

        /* If a native object of a module exists, try getting stuff related to its id. */
        if(!empty($object->{$prompt->module}) && is_object($object->{$prompt->module}) && !empty($object->{$prompt->module}->id))
        {
            $objectId = $object->{$prompt->module}->id;
            foreach($objectNames as $objectName)
            {
                /* Note that modules are within (product, story, productplan, release, project, execution, task, bug, case, doc). */
                switch ($prompt->module)
                {
                    case 'product': // story, branch, productplan, execution, task, bug, case, project, doc
                        if(in_array($objectName, array('story', 'branch', 'productplan', 'task', 'bug', 'case', 'doc')))
                        {
                            $vars[] = $this->dao->select('max(id) as maxId')->from(constant(strtoupper("TABLE_$objectName")))
                                ->where('product')->eq($objectId)
                                ->andWhere('deleted')->eq(0)
                                ->fetch('maxId');
                        }
                        elseif($objectName == 'project')
                        {
                            $vars[] = $this->dao->select('max(tpp.project) as maxId')->from(TABLE_PROJECTPRODUCT)->alias('tpp')
                                ->leftJoin(TABLE_PROJECT)->alias('tp')->on('tp.id = tpp.project')
                                ->where('tpp.product')->eq($objectId)
                                ->andWhere('tp.type')->eq('project')
                                ->andWhere('tp.deleted')->eq(0)
                                ->fetch('maxId');
                        }
                        elseif($objectName == 'execution')
                        {
                            $vars[] = $this->dao->select('max(tpp.project) as maxId')->from(TABLE_PROJECTPRODUCT)->alias('tpp')
                                ->leftJoin(TABLE_PROJECT)->alias('tp')->on('tp.id = tpp.project')
                                ->where('tpp.product')->eq($objectId)
                                ->andWhere('tp.project')->ne(0)
                                ->andWhere('tp.deleted')->eq(0)
                                ->fetch('maxId');
                        }
                        break;
                    case 'story': // product, branch, productplan, execution, task, bug, case, project, doc
                        if($objectName == 'product') $vars[] = $this->dao->select('product')->from(TABLE_STORY)->where('id')->eq($objectId)->fetch('product');
                        if($objectName == 'branch')  $vars[] = $this->dao->select('branch')->from(TABLE_STORY)->where('id')->eq($objectId)->fetch('branch');
                        if($objectName == 'productplan') $vars[] = $this->dao->select('plan')->from(TABLE_STORY)->where('id')->eq($objectId)->fetch('plan');
                        if(in_array($objectName, array('task', 'bug', 'case', 'doc')))
                        {
                            $vars[] = $this->dao->select('max(id) as maxId')->from(constant(strtoupper("TABLE_$objectName")))->where('story')->eq($objectId)->andWhere('deleted')->eq(0)->fetch('maxId');
                        }
                        if($objectName == 'execution')
                        {
                            $vars[] = $this->dao->select('tps.project')->from(TABLE_PROJECTSTORY)->alias('tps')
                                ->leftJoin(TABLE_PROJECT)->alias('tp')->on('tp.id = tps.project')
                                ->where('tps.story')->eq($objectId)
                                ->andWhere('tp.project')->ne(0)
                                ->andWhere('tp.deleted')->eq(0)
                                ->fetch('project');
                        }
                        if($objectName == 'project')
                        {
                            $vars[] = $this->dao->select('tps.project')->from(TABLE_PROJECTSTORY)->alias('tps')
                                ->leftJoin(TABLE_PROJECT)->alias('tp')->on('tp.id = tps.project')
                                ->where('tps.story')->eq($objectId)
                                ->andWhere('tp.type')->eq('project')
                                ->andWhere('tp.deleted')->eq(0)
                                ->fetch('project');
                        }
                        break;
                    case 'productplan': // product, story, branch, execution, task, bug, case, project, doc
                        if($objectName == 'product') $vars[] = $this->dao->select('product')->from(TABLE_PRODUCTPLAN)->where('id')->eq($objectId)->fetch('product');
                        if($objectName == 'branch')  $vars[] = $this->dao->select('branch')->from(TABLE_PRODUCTPLAN)->where('id')->eq($objectId)->fetch('branch');
                        if(in_array($objectName, array('story', 'bug')))
                        {
                            $vars[] = $this->dao->select('max(id) as maxId')->from(constant(strtoupper("TABLE_$objectName")))->where('plan')->eq($objectId)->andWhere('deleted')->eq(0)->fetch('maxId');
                        }
                        if($objectName == 'execution')
                        {
                            $vars[] = $this->dao->select('max(tpp.project) as maxId')->from(TABLE_PROJECTPRODUCT)->alias('tpp')
                                ->leftJoin(TABLE_PROJECT)->alias('tp')->on('tp.id = tpp.project')
                                ->where('tpp.plan')->eq($objectId)
                                ->andWhere('tp.project')->ne(0)
                                ->andWhere('tp.deleted')->eq(0)
                                ->fetch('maxId');
                        }
                        if($objectName == 'project')
                        {
                            $vars[] = $this->dao->select('max(tpp.project) as maxId')->from(TABLE_PROJECTPRODUCT)->alias('tpp')
                                ->leftJoin(TABLE_PROJECT)->alias('tp')->on('tp.id = tpp.project')
                                ->where('tpp.plan')->eq($objectId)
                                ->andWhere('tp.type')->eq('project')
                                ->andWhere('tp.deleted')->eq(0)
                                ->fetch('maxId');
                        }
                        if($objectName == 'task') $vars[] = '';
                        if($objectName == 'case') $vars[] = '';
                        if($objectName == 'doc')  $vars[] = '';
                        break;
                    case 'release': // product, story, branch, productplan, execution, task, bug, case, project, doc
                        if(in_array($objectName, array('product', 'branch', 'project')))
                        {
                            $this->dao->select($objectName)->from(TABLE_RELEASE)->where('id')->eq($objectId)->fetch($objectName);
                        }
                        if($objectName == 'story')
                        {
                            $stories = $this->dao->select('stories')->from(TABLE_RELEASE)->where('id')->eq($objectId)->fetch('stories');
                            $stories = explode(',', $stories);
                            $vars[] = empty($stories) ? '' : max($stories);
                        }
                        if($objectName == 'productplan')
                        {
                            $execution = $this->dao->select('tb.execution')->from(TABLE_BUILD)->alias('tb')
                                ->leftJoin(TABLE_RELEASE)->alias('tr')->on('tr.build = tb.id')
                                ->where('tr.id')->eq($objectId)
                                ->fetch('tb.execution');
                            $vars[] = empty($execution) ? '' : $this->dao->select('max(tpp.plan) as maxId')->from(TABLE_PROJECTPRODUCT)->alias('tpp')
                                ->leftJoin(TABLE_PROJECT)->alias('tp')->on('tp.id = tpp.project')
                                ->where('tp.id')->eq($execution)
                                ->andWhere('tp.deleted')->eq(0)
                                ->fetch('maxId');
                        }
                        if($objectName == 'execution')
                        {
                            $vars[] = $this->dao->select('tb.execution')->from(TABLE_BUILD)->alias('tb')
                                ->leftJoin(TABLE_RELEASE)->alias('tr')->on('tr.build = tb.id')
                                ->where('tr.id')->eq($objectId)
                                ->fetch('execution');
                        }
                        if($objectName == 'bug')
                        {
                            $bugs = $this->dao->select('bugs')->from(TABLE_RELEASE)->where('id')->eq($objectId)->fetch('bugs');
                            $bugs = explode(',', $bugs);
                            $vars[] = empty($bugs) ? '' : max($bugs);
                        }
                        if($objectName == 'task') $vars[] = '';
                        if($objectName == 'case') $vars[] = '';
                        if($objectName == 'doc')  $vars[] = '';
                        break;
                    case 'project': // product, story, branch, productplan, execution, task, bug, case, doc
                        if(in_array($objectName, array('product', 'branch', 'productplan')))
                        {
                            if($objectName == 'productplan') $objectName = 'plan';
                            $vars[] = $this->dao->select($objectName)->from(TABLE_PROJECTPRODUCT)->where('project')->eq($objectId)->fetch($objectName);
                        }
                        if(in_array($objectName, array('execution', 'bug', 'task', 'case', 'doc')))
                        {
                            $vars[] = $this->dao->select('max(id) as maxId')->from(constant(strtoupper("TABLE_$objectName")))->where('project')->eq($objectId)->andWhere('deleted')->eq(0)->fetch('maxId');
                        }
                        if($objectName == 'story')
                        {
                            $vars[] = $this->dao->select('max(tps.story) as maxId')->from(TABLE_PROJECTSTORY)->alias('tps')
                                ->leftJoin(TABLE_STORY)->alias('ts')->on('ts.id = tps.story')
                                ->where('tps.project')->eq($objectId)
                                ->andWhere('ts.deleted')->eq(0)
                                ->fetch('maxId');
                        }
                        break;
                    case 'execution': // product, story, branch, productplan, task, bug, case, project, doc
                        if($objectName == 'project')     $vars[] = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($objectId)->fetch('project');
                        if($objectName == 'product')     $vars[] = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($objectId)->fetch('product');
                        if($objectName == 'story')       $vars[] = $this->dao->select('max(story) as maxId')->from(TABLE_PROJECTSTORY)->where('project')->eq($objectId)->fetch('maxId');
                        if($objectName == 'branch')      $vars[] = $this->dao->select('max(branch) as maxId')->from(TABLE_PROJECTSTORY)->where('project')->eq($objectId)->fetch('maxId');
                        if($objectName == 'productplan') $vars[] = $this->dao->select('plan')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($objectId)->fetch('plan');
                        if($objectName == 'task')        $vars[] = $this->dao->select('max(id) as maxId')->from(TABLE_TASK)->where('execution')->eq($objectId)->andWhere('deleted')->eq(0)->fetch('maxId');
                        if($objectName == 'bug')         $vars[] = $this->dao->select('max(id) as maxId')->from(TABLE_BUG)->where('project')->eq($objectId)->andWhere('deleted')->eq(0)->fetch('maxId');
                        if($objectName == 'case')        $vars[] = $this->dao->select('max(id) as maxId')->from(TABLE_CASE)->where('project')->eq($objectId)->andWhere('deleted')->eq(0)->fetch('maxId');
                        if($objectName == 'doc')         $vars[] = $this->dao->select('max(id) as maxId')->from(TABLE_DOC)->where('project')->eq($objectId)->andWhere('deleted')->eq(0)->fetch('maxId');
                        break;
                    case 'task': // product, story, branch, productplan, execution, bug, case, project, doc
                        if($objectName == 'product')
                        {
                            $vars[] = $this->dao->select('tpp.product')->from(TABLE_TASK)->alias('tt')
                                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('tpp')->on('tpp.project = tt.project')
                                ->where('tt.id')->eq($objectId)
                                ->fetch('product');
                        }
                        if($objectName == 'story')     $vars[] = $this->dao->select('story')->from(TABLE_TASK)->where('id')->eq($objectId)->fetch('story');
                        if($objectName == 'branch')
                        {
                            $vars[] = $this->dao->select('tpp.branch')->from(TABLE_TASK)->alias('tt')
                                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('tpp')->on('tpp.project = tt.project')
                                ->where('tt.id')->eq($objectId)
                                ->fetch('branch');
                        }
                        if($objectName == 'productplan')
                        {
                            $vars[] = $this->dao->select('tpp.plan')->from(TABLE_TASK)->alias('tt')
                                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('tpp')->on('tpp.project = tt.project')
                                ->where('tt.id')->eq($objectId)
                                ->fetch('plan');
                        }
                        if($objectName == 'execution') $vars[] = $this->dao->select('execution')->from(TABLE_TASK)->where('id')->eq($objectId)->fetch('execution');
                        if($objectName == 'bug')       $vars[] = $this->dao->select('max(id) as maxId')->from(TABLE_BUG)->where('task')->eq($objectId)->andWhere('deleted')->eq(0)->fetch('maxId');
                        if($objectName == 'case')      $vars[] = '';
                        if($objectName == 'doc')       $vars[] = '';
                        break;
                    case 'bug': // product, story, branch, productplan, execution, task, case, project, doc
                        if(in_array($objectName, array('product', 'branch', 'productplan', 'execution', 'task', 'case', 'story', 'project')))
                        {
                            if($objectName == 'productplan') $objectName = 'plan';
                            $vars[] = $this->dao->select($objectName)->from(TABLE_BUG)->where('id')->eq($objectId)->fetch($objectName);
                        }
                        if($objectName == 'doc') $vars[] = '';
                        break;
                    case 'case': // product, story, branch, productplan, execution, task, bug, project, doc
                        if(in_array($objectName, array('product', 'story', 'branch', 'execution', 'project')))
                        {
                            $vars[] = $this->dao->select($objectName)->from(TABLE_CASE)->where('id')->eq($objectId)->fetch($objectName);
                        }
                        if($objectName == 'productplan')
                        {
                            $vars[] = $this->dao->select('tpp.plan')->from(TABLE_CASE)->alias('tc')
                                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('tpp')->on('tpp.project = tc.project')
                                ->where('tc.id')->eq($objectId)
                                ->fetch('plan');
                        }
                        if($objectName == 'task') $vars[] = $this->dao->select('max(task) as maxId')->from(TABLE_BUG)->where('case')->eq($objectId)->andWhere('deleted')->eq(0)->fetch('maxId');
                        if($objectName == 'bug')  $vars[] = $this->dao->select('max(id) as maxId')->from(TABLE_BUG)->where('case')->eq($objectId)->andWhere('deleted')->eq(0)->fetch('maxId');
                        if($objectName == 'doc')  $vars[] = '';
                        break;
                    case 'doc': // product, story, branch, productplan, execution, task, bug, case, project
                        if(in_array($objectName, array('product', 'execution', 'project')))
                        {
                            $vars[] = $this->dao->select($objectName)->from(TABLE_DOC)->where('id')->eq($objectId)->fetch($objectName);
                        }
                        if($objectName == 'story')
                        {
                            $vars[] = $this->dao->select('max(id) as maxId')->from(TABLE_STORY)->alias('ts')
                                ->leftJoin(TABLE_DOC)->alias('td')->on('td.product = ts.product')
                                ->where('td.id')->eq($objectId)
                                ->andWhere('ts.deleted')->eq(0)
                                ->fetch('maxId');
                        }
                        if($objectName == 'branch')
                        {
                            $vars[] = $this->dao->select('branch')->from(TABLE_STORY)->alias('ts')
                                ->leftJoin(TABLE_DOC)->alias('td')->on('td.product = ts.product')
                                ->where('td.id')->eq($objectId)
                                ->andWhere('ts.deleted')->eq(0)
                                ->fetch('branch');
                        }
                        if($objectName == 'productplan')
                        {
                            $vars[] = $this->dao->select('plan')->from(TABLE_STORY)->alias('ts')
                                ->leftJoin(TABLE_DOC)->alias('td')->on('td.product = ts.product')
                                ->where('td.id')->eq($objectId)
                                ->andWhere('ts.deleted')->eq(0)
                                ->fetch('plan');
                        }
                        if($objectName == 'task')
                        {
                            $vars[] = $this->dao->select('max(id) as maxId')->from(TABLE_TASK)->alias('tt')
                                ->leftJoin(TABLE_DOC)->alias('td')->on('td.project = tt.project or td.execution = tt.execution')
                                ->where('td.id')->eq($objectId)
                                ->andWhere('tt.deleted')->eq(0)
                                ->fetch('maxId');
                        }
                        if($objectName == 'bug')
                        {
                            $vars[] = $this->dao->select('max(id) as maxId')->from(TABLE_BUG)->alias('tb')
                                ->leftJoin(TABLE_DOC)->alias('td')->on('td.product = tb.product')
                                ->where('td.id')->eq($objectId)
                                ->andWhere('tb.deleted')->eq(0)
                                ->fetch('maxId');
                        }
                        if($objectName == 'case')
                        {
                            $vars[] = $this->dao->select('max(id) as maxId')->from(TABLE_CASE)->alias('tc')
                                ->leftJoin(TABLE_DOC)->alias('td')->on('td.product = tc.product')
                                ->where('td.id')->eq($objectId)
                                ->andWhere('tc.deleted')->eq(0)
                                ->fetch('maxId');
                        }
                        break;
                    default:
                        $vars[] = '';
                }
            }
        }
        return $vars;
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
        return $this->dao->select('*')->from(TABLE_AI_PROMPT)
            ->where('deleted')->eq(0)
            ->andWhere('module')->eq($module)
            ->beginIF(!commonModel::hasPriv('ai', 'designPrompt') || $this->config->edition == 'open')->andWhere('status')->eq('active')->fi() // Only show active prompts to non-auditors.
            ->orderBy('id_desc')
            ->fetchAll('id', false);
    }

    /**
     * Filter prompts by user's privilege and executable state.
     *
     * @param  array   $prompts
     * @param  bool    $keepUnauthorized  optional, whether to keep unauthorized prompts but set their `unauthorized` property to true.
     * @access public
     * @return array   filtered prompts, those unauthorized will be removed if `$keepUnauthorized` is false, unexecutable ones will always be removed.
     */
    public function filterPromptsForExecution($prompts, $keepUnauthorized = false)
    {
        if(empty($prompts)) return array();

        /* Remove the unexecutable ones. */
        $prompts = array_filter($prompts, array($this, 'isExecutable'));

        /* Check user's priv to targetForm. */
        foreach($prompts as $idx => $prompt)
        {
            list($m, $f) = explode('.', $prompt->targetForm);
            $targetFormConfig = $this->config->ai->targetForm[$m][$f];
            if(empty($targetFormConfig))
            {
                unset($prompts[$idx]);
                continue;
            }
            if(!commonModel::hasPriv($targetFormConfig->m, $targetFormConfig->f))
            {
                if($keepUnauthorized)
                {
                    $prompts[$idx]->unauthorized = true;
                }
                else
                {
                    unset($prompts[$idx]);
                }
            }
        }
        return array_values($prompts);
    }

    /**
     * Get prompt test data.
     *
     * @param  object $prompt
     * @access public
     * @return array
     */
    public function getTestPromptData($prompt)
    {
        $module = $prompt->module;
        $source = explode(',', $prompt->source);
        $source = array_filter($source, function($value) {return !empty($value);});

        $titleData = $this->lang->ai->dataSource[$module];
        $testData  = $this->lang->ai->prompts->testData[$module];

        $categorized = array();
        foreach($source as $value)
        {
            $prefix = explode('.', $value)[0];
            $column = explode('.', $value)[1];
            if(!isset($categorized[$prefix])) $categorized[$prefix] = [];
            $categorized[$prefix][] = $column;
        }

        $result = '';

        return array($testData, $result);
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

        /* Override method for story drafts. */
        if($targetForm->m == 'story' && $targetForm->f == 'change') $_SESSION['aiInjectData']['story']['edit'] = is_string($data) ? $data : json_encode($data);

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
        return $this->dao->select('*')->from(TABLE_AI_PROMPTROLE)
            ->where('deleted')->eq(0)
            ->fetchAll('id', false);
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

        $this->dao->insert(TABLE_AI_PROMPTROLE)
            ->data($roleTemplate)
            ->exec();
        if(dao::isError()) return false;

        return $this->dao->lastInsertID();
    }

    /**
     * Delete a role template.
     *
     * @param  int     $id
     * @access public
     * @return false|int
     */
    public function deleteRoleTemplate($id)
    {
        $this->dao->update(TABLE_AI_PROMPTROLE)
            ->set('deleted')->eq(1)
            ->where('id')->eq($id)
            ->exec();
        if(dao::isError()) return false;

        return true;
    }

    /**
     * Update a role template.
     *
     * @param  string    $role
     * @param  string    $characterization
     * @access public
     * @return bool
     */
    public function updateRoleTemplate($id, $role, $characterization)
    {
        $roleTemplate = new stdclass();
        $roleTemplate->role = $role;
        $roleTemplate->characterization = $characterization;

        $this->dao->update(TABLE_AI_PROMPTROLE)
            ->data($roleTemplate)
            ->where('id')->eq($id)
            ->exec();
        if(dao::isError()) return false;

        return true;
    }

    /**
     * Get assistants.
     * @param  pager    $pager
     * @param  string   $orderBy
     * @access public
     * @return array
     */
    public function getAssistants($pager = null, $orderBy = 'id_desc')
    {
        return $this->dao->select('*')->from(TABLE_AI_ASSISTANT)
            ->where('deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    public function getAssistantsByModel($modelId, $enabled = true)
    {
        return $this->dao->select('*')->from(TABLE_AI_ASSISTANT)
            ->where('modelId')->eq($modelId)
            ->andWhere('deleted')->eq('0')
            ->andWhere('enabled')->eq($enabled ? '1' : '0')
            ->fetchAll('id', false);
    }

    /**
     * Get assistant by id.
     * @param  int    $assistantId
     * @access public
     * @return object
     */
    public function getAssistantById($assistantId)
    {
        return $this->dao->select('*')->from(TABLE_AI_ASSISTANT)
            ->where('id')->eq($assistantId)
            ->fetch();
    }

    /**
     * create an assistant.
     * @param  object    $assistant
     * @access public
     * @return bool
     */
    public function createAssistant($assistant, $publish = false)
    {
        $assistant->createdDate = helper::now();
        if($publish)
        {
            $assistant->publishedDate = helper::now();
            $assistant->enabled = '1';
        }
        else
        {
            $assistant->enabled = '0';
        }
        $this->dao
            ->insert(TABLE_AI_ASSISTANT)
            ->data($assistant)
            ->exec();
        if (dao::isError()) return false;
        $assistantId = $this->dao->lastInsertID();
        $this->loadModel('action')->create('aiAssistant', $assistantId, 'created');
        if($publish)
        {
            $this->loadModel('action')->create('aiAssistant', $assistantId, 'online');
        }
        return true;
    }

    /**
     * update an assistant.
     * @param  object $assistant
     * @access public
     * @return bool
     */
    public function updateAssistant($assistant)
    {
        $originAssistant = $this->getAssistantById($assistant->id);

        $actionType = 'edited';

        if(!empty($originAssistant))
        {
            $changedFields = array();
            foreach($assistant as $key => $value)
            {
                if($value != $originAssistant->$key) $changedFields[] = $key;
            }

            if(count($changedFields) == 1 && current($changedFields) == 'status')
            {
                $actionType = $assistant->enabled == '0' ? 'offline' : 'online';
            }
            else
            {
                $changes = commonModel::createChanges($originAssistant, $assistant);
            }
        }

        foreach ($assistant as $key => $value)
        {
            if(isset($originAssistant->$key)) $originAssistant->$key = $value;
        }
        if(empty($originAssistant->publishedDate))
        {
            unset($originAssistant->publishedDate);
        }

        $this->dao->update(TABLE_AI_ASSISTANT)
            ->data($originAssistant)
            ->where('id')->eq($assistant->id)
            ->exec();
        if(dao::isError()) return false;

        if(!empty($changes))
        {
            $actionId = $this->loadModel('action')->create('aiAssistant', $assistant->id, $actionType);
            $this->action->logHistory($actionId, $changes);
        }

        return true;
    }

    /**
     * Toggle an assistant.
     * @param  int    $assistantId
     * @param  bool   $enabled
     * @access public
     * @return bool
     */
    public function toggleAssistant($assistantId, $enabled)
    {
        $this->dao->update(TABLE_AI_ASSISTANT)
            ->set('enabled')->eq($enabled ? '1' : '0')
            ->set('publishedDate')->eq($enabled ? helper::now() : null)
            ->where('id')->eq($assistantId)
            ->exec();
        if(dao::isError()) return false;

        $actionType = $enabled ? 'online' : 'offline';
        $this->loadModel('action')->create('aiAssistant', $assistantId, $actionType);

        return true;
    }

    /**
     * Find assistants name is duplicate.
     * @param  string $AssistantName
     * @param  int    $modelId
     * @access public
     * @return object
     */
    public function checkAssistantDuplicate($AssistantName, $modelId)
    {
        return $this->dao->select('*')->from(TABLE_AI_ASSISTANT)
            ->where('name')->eq($AssistantName)
            ->andWhere('modelId')->eq($modelId)
            ->andWhere('deleted')->eq('0')
            ->fetch();
    }

    public function deleteAssistant($assistantId)
    {
        $this->dao->update(TABLE_AI_ASSISTANT)
            ->set('deleted')->eq('1')
            ->where('id')->eq($assistantId)
            ->exec();
        if(dao::isError()) return false;

        $this->loadModel('action')->create('aiAssistant', $assistantId, 'deleted');

        return true;
    }
}

/**
 * Exception class for response from AI.
 */
class AIResponseException extends Exception
{
    /**
     * Type of response error, error messages are defined in lang files with type as key.
     *
     * See `$lang->ai->aiResponseException`.
     *
     * @var    string
     * @access public
     */
    public $type;

    /**
     * Response with error.
     *
     * @var    string|object
     * @access public
     */
    public $response;

    /**
     * Create a AIResponseException, load error message from lang file.
     *
     * @param  string        $type
     * @param  string|object $response
     * @access public
     * @return void
     */
    public function __construct($type, $response)
    {
        global $app;
        $this->type     = $type;
        $this->response = $response;
        $this->message  = zget($app->lang->ai->aiResponseException, $type, '');
    }
}
