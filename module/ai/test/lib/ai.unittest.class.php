<?php
declare(strict_types = 1);
class aiTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('ai');
        $this->objectTao   = $tester->loadTao('ai');
    }

    /**
     * Test isClickable method.
     *
     * @param  object $object
     * @param  string $action
     * @access public
     * @return mixed
     */
    public function isClickableTest($object = null, $action = '')
    {
        $result = aiModel::isClickable($object, $action);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setModelConfig method.
     *
     * @param  mixed $config
     * @access public
     * @return mixed
     */
    public function setModelConfigTest($config = null)
    {
        $result = $this->objectModel->setModelConfig($config);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test useLanguageModel method.
     *
     * @param  mixed $modelID
     * @access public
     * @return mixed
     */
    public function useLanguageModelTest($modelID = null)
    {
        /* Using reflection to call private method */
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('useLanguageModel');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectModel, $modelID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test hasModelsAvailable method.
     *
     * @access public
     * @return mixed
     */
    public function hasModelsAvailableTest()
    {
        $result = $this->objectModel->hasModelsAvailable();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getLanguageModels method.
     *
     * @param  string $type
     * @param  bool   $enabledOnly
     * @param  object $pager
     * @param  string $orderBy
     * @access public
     * @return mixed
     */
    public function getLanguageModelsTest($type = '', $enabledOnly = false, $pager = null, $orderBy = 'id_desc')
    {
        $result = $this->objectModel->getLanguageModels($type, $enabledOnly, $pager, $orderBy);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getLanguageModelNamesWithDefault method.
     *
     * @access public
     * @return mixed
     */
    public function getLanguageModelNamesWithDefaultTest()
    {
        $result = $this->objectModel->getLanguageModelNamesWithDefault();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getLanguageModel method.
     *
     * @param  mixed $modelID
     * @param  bool  $enabledOnly
     * @access public
     * @return mixed
     */
    public function getLanguageModelTest($modelID = null, $enabledOnly = false)
    {
        $result = $this->objectModel->getLanguageModel($modelID, $enabledOnly);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDefaultLanguageModel method.
     *
     * @access public
     * @return mixed
     */
    public function getDefaultLanguageModelTest()
    {
        /* Using reflection to call private method */
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('getDefaultLanguageModel');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectModel);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test serializeModel method.
     *
     * @param  mixed $model
     * @access public
     * @return mixed
     */
    public function serializeModelTest($model = null)
    {
        /* Using reflection to call private method */
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('serializeModel');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectModel, $model);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test unserializeModel method.
     *
     * @param  mixed $model
     * @access public
     * @return mixed
     */
    public function unserializeModelTest($model = null)
    {
        $result = $this->objectModel->unserializeModel($model);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test createModel method.
     *
     * @param  mixed $model
     * @access public
     * @return mixed
     */
    public function createModelTest($model = null)
    {
        $result = $this->objectModel->createModel($model);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateModel method.
     *
     * @param  int   $modelID
     * @param  mixed $model
     * @access public
     * @return mixed
     */
    public function updateModelTest($modelID = null, $model = null)
    {
        $result = $this->objectModel->updateModel($modelID, $model);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test toggleModel method.
     *
     * @param  int  $modelID
     * @param  bool $enabled
     * @access public
     * @return mixed
     */
    public function toggleModelTest($modelID = null, $enabled = null)
    {
        $result = $this->objectModel->toggleModel($modelID, $enabled);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test deleteModel method.
     *
     * @param  int $modelID
     * @access public
     * @return mixed
     */
    public function deleteModelTest($modelID = null)
    {
        $result = $this->objectModel->deleteModel($modelID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test testModelConnection method.
     *
     * @param  int $modelID
     * @access public
     * @return mixed
     */
    public function testModelConnectionTest($modelID = null)
    {
        $result = $this->objectModel->testModelConnection($modelID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test makeRequest method.
     *
     * @param  string $type
     * @param  mixed  $data
     * @param  int    $timeout
     * @access public
     * @return mixed
     */
    public function makeRequestTest($type = null, $data = null, $timeout = 10)
    {
        /* Using reflection to call private method */
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('makeRequest');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectModel, $type, $data, $timeout);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProxyType method.
     *
     * @param  string $proxyType
     * @access public
     * @return mixed
     */
    public function getProxyTypeTest($proxyType = null)
    {
        /* Using reflection to call private static method */
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('getProxyType');
        $method->setAccessible(true);
        $result = $method->invoke(null, $proxyType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test camelCaseToSnakeCase method.
     *
     * @param  string $str
     * @access public
     * @return mixed
     */
    public function camelCaseToSnakeCaseTest($str = null)
    {
        /* Using reflection to call private static method */
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('camelCaseToSnakeCase');
        $method->setAccessible(true);
        $result = $method->invoke(null, $str);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test standardizeParams method.
     *
     * @param  mixed $data
     * @access public
     * @return mixed
     */
    public function standardizeParamsTest($data = null)
    {
        /* Using reflection to call private static method */
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('standardizeParams');
        $method->setAccessible(true);
        $result = $method->invoke(null, $data);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test assembleRequestData method.
     *
     * @param  string $type
     * @param  mixed  $data
     * @access public
     * @return mixed
     */
    public function assembleRequestDataTest($type = null, $data = null)
    {
        /* Using reflection to call private method */
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('assembleRequestData');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectModel, $type, $data);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test decodeResponse method.
     *
     * @param  mixed $response
     * @access public
     * @return mixed
     */
    public function decodeResponseTest($response = null)
    {
        /* Using reflection to call private method */
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('decodeResponse');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectModel, $response);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test parseTextResponse method.
     *
     * @param  mixed $response
     * @access public
     * @return mixed
     */
    public function parseTextResponseTest($response = null)
    {
        /* Using reflection to call private method */
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('parseTextResponse');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectModel, $response);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test parseChatResponse method.
     *
     * @param  mixed $response
     * @access public
     * @return mixed
     */
    public function parseChatResponseTest($response = null)
    {
        /* Using reflection to call private method */
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('parseChatResponse');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectModel, $response);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test parseFunctionCallResponse method.
     *
     * @param  mixed $response
     * @access public
     * @return mixed
     */
    public function parseFunctionCallResponseTest($response = null)
    {
        /* Using reflection to call private method */
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('parseFunctionCallResponse');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectModel, $response);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test complete method.
     *
     * @param  mixed $model
     * @param  string $prompt
     * @param  int $maxTokens
     * @param  array $options
     * @access public
     * @return mixed
     */
    public function completeTest($model = null, $prompt = '', $maxTokens = 512, $options = array())
    {
        $result = $this->objectModel->complete($model, $prompt, $maxTokens, $options);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test edit method.
     *
     * @param  mixed $model
     * @param  string $input
     * @param  string $instruction
     * @param  array $options
     * @access public
     * @return mixed
     */
    public function editTest($model = null, $input = '', $instruction = '', $options = array())
    {
        $result = $this->objectModel->edit($model, $input, $instruction, $options);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test converse method.
     *
     * @param  mixed $model
     * @param  array $messages
     * @param  array $options
     * @access public
     * @return mixed
     */
    public function converseTest($model = null, $messages = array(), $options = array())
    {
        $result = $this->objectModel->converse($model, $messages, $options);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test converseForJSON method.
     *
     * @param  mixed $model
     * @param  array $messages
     * @param  object $schema
     * @param  array $options
     * @access public
     * @return mixed
     */
    public function converseForJSONTest($model = null, $messages = array(), $schema = null, $options = array())
    {
        $result = $this->objectModel->converseForJSON($model, $messages, $schema, $options);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test converseTwiceForJSON method.
     *
     * @param  mixed $model
     * @param  array $messages
     * @param  object $schema
     * @param  array $options
     * @access public
     * @return mixed
     */
    public function converseTwiceForJSONTest($model = null, $messages = array(), $schema = null, $options = array())
    {
        $result = $this->objectModel->converseTwiceForJSON($model, $messages, $schema, $options);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getLatestMiniPrograms method.
     *
     * @param  object $pager
     * @param  string $order
     * @access public
     * @return mixed
     */
    public function getLatestMiniProgramsTest($pager = null, $order = 'publishedDate_desc')
    {
        $result = $this->objectModel->getLatestMiniPrograms($pager, $order);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test countLatestMiniPrograms method.
     *
     * @access public
     * @return mixed
     */
    public function countLatestMiniProgramsTest()
    {
        $result = $this->objectModel->countLatestMiniPrograms();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test saveMiniProgramMessage method.
     *
     * @param  string $appID
     * @param  string $type
     * @param  string $content
     * @access public
     * @return mixed
     */
    public function saveMiniProgramMessageTest($appID, $type, $content)
    {
        $result = $this->objectModel->saveMiniProgramMessage($appID, $type, $content);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test deleteHistoryMessagesByID method.
     *
     * @param  string $appID
     * @param  string $userID
     * @param  array  $messageIDs
     * @access public
     * @return mixed
     */
    public function deleteHistoryMessagesByIDTest($appID, $userID, $messageIDs)
    {
        $result = $this->objectModel->deleteHistoryMessagesByID($appID, $userID, $messageIDs);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getHistoryMessages method.
     *
     * @param  mixed $appID
     * @param  int   $limit
     * @access public
     * @return mixed
     */
    public function getHistoryMessagesTest($appID = null, $limit = 20)
    {
        $result = $this->objectModel->getHistoryMessages($appID, $limit);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMiniProgramsByID method.
     *
     * @param  array $ids
     * @param  bool  $sort
     * @access public
     * @return mixed
     */
    public function getMiniProgramsByIDTest($ids = array(), $sort = false)
    {
        $result = $this->objectModel->getMiniProgramsByID($ids, $sort);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMiniProgramByID method.
     *
     * @param  string $id
     * @access public
     * @return mixed
     */
    public function getMiniProgramByIDTest($id)
    {
        $result = $this->objectModel->getMiniProgramByID($id);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}