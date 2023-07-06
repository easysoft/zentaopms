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
            curl_setopt($ch, CURLOPT_PROXYTYPE, self::getProxyType($this->modelConfig->proxyType));
        }

        $result = curl_exec($ch);
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
        foreach($data as $key => $value) $standardizedData->{self::camelCaseToSnakeCase($key)} = $value;
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

        $data = self::standardizeParams($data);

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
     * Parse text responses from simple APIs. For example, completion.
     *
     * @param  string   $response  json string
     * @access private
     * @return mixed    false if error, array of texts (choices) if success
     */
    private function parseTextResponse($response)
    {
        $response = json_decode($response);
        if(isset($response->error)) return false;

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
        $response = json_decode($response);
        if(isset($response->error)) return false;

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
        return $this->dao->select('*')->from(TABLE_PROMPT)
            ->where('1=1')
            ->beginIF(!empty($module))->andWhere('module')->eq($module)->fi()
            ->beginIF(!empty($status))->andWhere('status')->eq($status)->fi()
            ->orderBy($order)
            ->page($pager)
            ->fetchAll();
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
     * TODO: fully implement this.
     *
     * @param  object    $prompt
     * @access public
     * @return int|false returns prompt id on success, false on fail
     */
    public function createPrompt($prompt)
    {
        $prompt->createdDate = helper::now();
        $prompt->createdBy   = $this->app->user->account;

        $this->dao->insert(TABLE_PROMPT)
            ->data($prompt)
            ->autoCheck()
            ->batchCheck($this->config->ai->createprompt->requiredFields, 'notempty')
            ->exec();

        return dao::isError() ? false : $this->dao->lastInsertID();
    }

    /**
     * Update a prompt.
     *
     * TODO: fully implement this.
     *
     * @param  object    $prompt
     * @access public
     * @return bool
     */
    public function updatePrompt($prompt)
    {
        $prompt->editedDate = helper::now();
        $prompt->editedBy   = $this->app->user->account;

        $this->dao->update(TABLE_PROMPT)
            ->data($prompt)
            ->autoCheck()
            ->batchCheck($this->config->ai->createprompt->requiredFields, 'notempty')
            ->where('id')->eq($prompt->id)
            ->exec();

        return !dao::isError();
    }

    /**
     * serialize data to prompt.
     *
     * @param  string $module
     * @param  array  $sources
     * @param  array  $data
     * @access public
     * @return string
     */
    public function serializeDataToPrompt($module, $sources, $data)
    {
        $newData = array();

        $supplement = '';
        $supplementTypes = array();

        foreach($sources as $source)
        {
            $objectName = $source[0];
            $objectKey  = $source[1];

            $semanticName = $this->lang->ai->dataSource[$module][$objectName]['common'];
            $semanticKey  = $this->lang->ai->dataSource[$module][$objectName][$objectKey];

            if(empty($newData[$semanticName])) $newData[$semanticName] = array();

            $objectData = $data[$objectName];
            if($this->isAssoc($objectData))
            {
                $newData[$semanticName][$semanticKey] = $data[$objectName][$objectKey];
            }
            else
            {
                foreach($objectData as $index => $value)
                {
                    if(empty($newData[$semanticName][$index])) $newData[$semanticName][$index] = array();
                    $newData[$semanticName][$index][$semanticKey] = $data[$objectName][$index][$objectKey];
                }
            }

            if(in_array($objectKey, $supplementTypes)) continue;

            if(!isset($this->lang->ai->dataType->$objectKey)) continue;

            $supplementTypes[] = $objectKey;
            $supplement .= sprintf($this->lang->ai->dataTypeDesc, $semanticKey, $this->lang->ai->dataType->$objectKey->type, $this->lang->ai->dataType->$objectKey->desc) . "\n";
        }

        /* @see https://stackoverflow.com/a/2934602 */
        return "'''\n" . preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');}, json_encode($newData)) . "\n'''\n" . $supplement;
    }

    /**
     * generate demo data prompt by source.
     *
     * @param  string $module
     * @param  string $source
     * @access public
     * @return string
     */
    public function generateDemoDataPrompt($module,$source)
    {
        $sources = explode(',', $source);
        $sources = array_filter($sources);
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
            if($this->isAssoc($demoData))
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
     * @access public
     * @return bool
     */
    public function isAssoc($array)
    {
        $keys = array_keys($array);
        return array_keys($keys) !== $keys;
    }
}
