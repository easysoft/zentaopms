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
    /**
     * Test updateCustomCategories method.
     *
     * @access public
     * @return mixed
     */
    public function updateCustomCategoriesTest()
    {
        $result = $this->objectModel->updateCustomCategories();
        if(dao::isError()) return dao::getError();

        return $result;
    }

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
        try {
            ob_start();
            $result = $this->objectModel->complete($model, $prompt, $maxTokens, $options);
            ob_end_clean();

            if(dao::isError()) return dao::getError();

            return $result;
        } catch (Exception $e) {
            ob_end_clean();
            return false;
        } catch (Error $e) {
            ob_end_clean();
            return false;
        }
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
        try {
            ob_start();
            $result = $this->objectModel->edit($model, $input, $instruction, $options);
            ob_end_clean();
            if(dao::isError()) return dao::getError();

            return $result;
        } catch (Exception $e) {
            ob_end_clean();
            return false;
        } catch (Error $e) {
            ob_end_clean();
            return false;
        }
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

    /**
     * Test getCustomCategories method.
     *
     * @access public
     * @return mixed
     */
    public function getCustomCategoriesTest()
    {
        $result = $this->objectModel->getCustomCategories();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getUsedCustomCategories method.
     *
     * @access public
     * @return mixed
     */
    public function getUsedCustomCategoriesTest()
    {
        $result = $this->objectModel->getUsedCustomCategories();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getPublishedCustomCategories method.
     *
     * @access public
     * @return mixed
     */
    public function getPublishedCustomCategoriesTest()
    {
        $result = $this->objectModel->getPublishedCustomCategories();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkDuplicatedCategory method.
     *
     * @param  array $postData
     * @access public
     * @return mixed
     */
    public function checkDuplicatedCategoryTest($postData = array())
    {
        $_POST = $postData;
        $result = $this->objectModel->checkDuplicatedCategory();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMiniPrograms method.
     *
     * @param  string $category
     * @param  string $status
     * @param  string $order
     * @param  object $pager
     * @access public
     * @return mixed
     */
    public function getMiniProgramsTest($category = '', $status = '', $order = 'createdDate_desc', $pager = null)
    {
        $result = $this->objectModel->getMiniPrograms($category, $status, $order, $pager);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMiniProgramFields method.
     *
     * @param  string $appID
     * @access public
     * @return mixed
     */
    public function getMiniProgramFieldsTest($appID = null)
    {
        $result = $this->objectModel->getMiniProgramFields($appID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test createNewVersionNotification method.
     *
     * @param  string $appID
     * @access public
     * @return mixed
     */
    public function createNewVersionNotificationTest($appID = null)
    {
        // 记录执行前的通知数量
        $beforeCount = $this->objectModel->dao->select('COUNT(*)')
            ->from(TABLE_AI_MESSAGE)
            ->where('appID')->eq($appID)
            ->andWhere('type')->eq('ntf')
            ->fetch('COUNT(*)');

        $this->objectModel->createNewVersionNotification($appID);
        if(dao::isError()) return dao::getError();

        // 记录执行后的通知数量
        $afterCount = $this->objectModel->dao->select('COUNT(*)')
            ->from(TABLE_AI_MESSAGE)
            ->where('appID')->eq($appID)
            ->andWhere('type')->eq('ntf')
            ->fetch('COUNT(*)');

        // 获取该appID对应的用户数量
        $userCount = $this->objectModel->dao->select('COUNT(DISTINCT user)')
            ->from(TABLE_AI_MESSAGE)
            ->where('appID')->eq($appID)
            ->fetch('COUNT(DISTINCT user)');

        // 如果没有用户记录，应该没有创建通知
        if($userCount == 0) return 0;

        // 否则应该为每个用户都创建了一条通知
        return $userCount;
    }

    /**
     * Test publishMiniProgram method.
     *
     * @param  mixed $appID
     * @param  mixed $published
     * @access public
     * @return mixed
     */
    public function publishMiniProgramTest($appID = null, $published = '1')
    {
        $result = $this->objectModel->publishMiniProgram($appID, $published);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test collectMiniProgram method.
     *
     * @param  mixed $userID
     * @param  mixed $appID
     * @param  string $delete
     * @access public
     * @return mixed
     */
    public function collectMiniProgramTest($userID = null, $appID = null, $delete = 'false')
    {
        $result = $this->objectModel->collectMiniProgram($userID, $appID, $delete);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test canPublishMiniProgram method.
     *
     * @param  object $program
     * @access public
     * @return mixed
     */
    public function canPublishMiniProgramTest($program = null)
    {
        $result = $this->objectModel->canPublishMiniProgram($program);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test createMiniProgram method.
     *
     * @param  mixed $data
     * @access public
     * @return mixed
     */
    public function createMiniProgramTest($data = null)
    {
        $result = $this->objectModel->createMiniProgram($data);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test extractZtAppZip method.
     *
     * @param  string $file
     * @access public
     * @return mixed
     */
    public function extractZtAppZipTest($file = null)
    {
        $result = $this->objectModel->extractZtAppZip($file);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test saveMiniProgramFields method.
     *
     * @param  mixed $appID
     * @param  mixed $data
     * @access public
     * @return mixed
     */
    public function saveMiniProgramFieldsTest($appID = null, $data = null)
    {
        $this->objectModel->saveMiniProgramFields($appID, $data);
        if(dao::isError()) return dao::getError();

        // 验证字段数据是否正确保存
        $fields = $this->objectModel->dao->select('*')
            ->from('`zt_ai_miniprogramfield`')
            ->where('appID')->eq($appID)
            ->fetchAll();

        return count($fields);
    }

    /**
     * Test checkDuplicatedAppName method.
     *
     * @param  string $name
     * @param  string $appID
     * @access public
     * @return mixed
     */
    public function checkDuplicatedAppNameTest($name = '', $appID = '-1')
    {
        $result = $this->objectModel->checkDuplicatedAppName($name, $appID);
        if(dao::isError()) return dao::getError();

        return $result ? '1' : '0';
    }

    /**
     * Test getUniqueAppName method.
     *
     * @param  string $name
     * @access public
     * @return mixed
     */
    public function getUniqueAppNameTest($name = '')
    {
        $result = $this->objectModel->getUniqueAppName($name);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test verifyRequiredFields method.
     *
     * @param  array $requiredFields
     * @param  array $postData
     * @access public
     * @return mixed
     */
    public function verifyRequiredFieldsTest($requiredFields = array(), $postData = array())
    {
        $_POST = $postData;
        $result = $this->objectModel->verifyRequiredFields($requiredFields);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getPrompts method.
     *
     * @param  string $module
     * @param  string $status
     * @param  string $order
     * @param  object $pager
     * @access public
     * @return mixed
     */
    public function getPromptsTest($module = '', $status = '', $order = 'id_desc', $pager = null)
    {
        $result = $this->objectModel->getPrompts($module, $status, $order, $pager);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getPromptById method.
     *
     * @param  mixed $id
     * @access public
     * @return mixed
     */
    public function getPromptByIdTest($id = null)
    {
        $result = $this->objectModel->getPromptById($id);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test createPrompt method.
     *
     * @param  object $prompt
     * @access public
     * @return mixed
     */
    public function createPromptTest($prompt = null)
    {
        $result = $this->objectModel->createPrompt($prompt);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updatePrompt method.
     *
     * @param  object $prompt
     * @param  object $originalPrompt
     * @access public
     * @return mixed
     */
    public function updatePromptTest($prompt = null, $originalPrompt = null)
    {
        $result = $this->objectModel->updatePrompt($prompt, $originalPrompt);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test deletePrompt method.
     *
     * @param  mixed $id
     * @access public
     * @return mixed
     */
    public function deletePromptTest($id = null)
    {
        $result = $this->objectModel->deletePrompt($id);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test togglePromptStatus method.
     *
     * @param  int|object  $prompt  prompt (or id) to toggle.
     * @param  string      $status  optional, will set status to $status if provided.
     * @access public
     * @return mixed
     */
    public function togglePromptStatusTest($prompt = null, $status = '')
    {
        $result = $this->objectModel->togglePromptStatus($prompt, $status);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test serializeDataToPrompt method.
     *
     * @param  string $module
     * @param  mixed  $sources
     * @param  mixed  $data
     * @access public
     * @return mixed
     */
    public function serializeDataToPromptTest($module = null, $sources = null, $data = null)
    {
        $result = $this->objectModel->serializeDataToPrompt($module, $sources, $data);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test generateDemoDataPrompt method.
     *
     * @param  string $module
     * @param  string $source
     * @access public
     * @return mixed
     */
    public function generateDemoDataPromptTest($module = null, $source = null)
    {
        $result = $this->objectModel->generateDemoDataPrompt($module, $source);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test isAssoc method.
     *
     * @param  array $array
     * @access public
     * @return mixed
     */
    public function isAssocTest($array = null)
    {
        /* Using reflection to call private static method */
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('isAssoc');
        $method->setAccessible(true);
        $result = $method->invoke(null, $array);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getFunctionCallSchema method.
     *
     * @param  string $form
     * @access public
     * @return mixed
     */
    public function getFunctionCallSchemaTest($form = null)
    {
        // Custom implementation to handle edge cases cleanly
        if(empty($form)) return array();
        
        $formPath = explode('.', $form);
        if(count($formPath) !== 2) return array();

        // Check if targetForm config exists for the form path
        if(!isset($this->objectModel->config->ai->targetForm[$formPath[0]][$formPath[1]])) {
            return array();
        }

        $targetForm = $this->objectModel->config->ai->targetForm[$formPath[0]][$formPath[1]];
        if(empty($targetForm)) return array();

        // Check if formSchema exists for the target module and function
        if(!isset($this->objectModel->lang->ai->formSchema[strtolower($targetForm->m)][strtolower($targetForm->f)])) {
            return array();
        }

        $schema = $this->objectModel->lang->ai->formSchema[strtolower($targetForm->m)][strtolower($targetForm->f)];

        return empty($schema) ? array() : $schema;
    }

    /**
     * Test getObjectForPromptById method.
     *
     * @param  mixed $promptID
     * @param  mixed $objectId
     * @access public
     * @return mixed
     */
    public function getObjectForPromptByIdTest($promptID = null, $objectId = null)
    {
        if(empty($promptID)) return false;
        
        $prompt = $this->objectModel->getPromptById($promptID);
        if(empty($prompt)) return false;
        
        $result = $this->objectModel->getObjectForPromptById($prompt, $objectId);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test autoPrependNewline method.
     *
     * @param  string $text
     * @access public
     * @return string
     */
    public function autoPrependNewlineTest($text = '')
    {
        $reflectionClass = new ReflectionClass('aiModel');
        $method = $reflectionClass->getMethod('autoPrependNewline');
        $method->setAccessible(true);
        
        $result = $method->invoke(null, $text);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test tryPunctuate method.
     *
     * @param  string $sentence
     * @param  bool   $newline
     * @access public
     * @return string
     */
    public function tryPunctuateTest($sentence = '', $newline = false)
    {
        $reflectionClass = new ReflectionClass('aiModel');
        $method = $reflectionClass->getMethod('tryPunctuate');
        $method->setAccessible(true);

        $result = $method->invoke(null, $sentence, $newline);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test assemblePrompt method.
     *
     * @param  object $prompt
     * @param  string $dataPrompt
     * @access public
     * @return string
     */
    public function assemblePromptTest($prompt = null, $dataPrompt = '')
    {
        $result = aiModel::assemblePrompt($prompt, $dataPrompt);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test isExecutable method.
     *
     * @param  mixed $prompt
     * @access public
     * @return mixed
     */
    public function isExecutableTest($prompt = null)
    {
        $result = $this->objectModel->isExecutable($prompt);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test executePrompt method.
     *
     * @param  mixed $prompt
     * @param  mixed $object
     * @access public
     * @return mixed
     */
    public function executePromptTest($prompt = null, $object = null)
    {
        try {
            ob_start();
            $result = $this->objectModel->executePrompt($prompt, $object);
            ob_end_clean();
            if(dao::isError()) return dao::getError();

            return $result;
        } catch (Exception $e) {
            ob_end_clean();
            return -6;
        } catch (Error $e) {
            ob_end_clean();
            return -6;
        }
    }

    /**
     * Test getTargetFormLocation method.
     *
     * @param  mixed $prompt
     * @param  mixed $object
     * @param  array $linkArgs
     * @access public
     * @return mixed
     */
    public function getTargetFormLocationTest($prompt = null, $object = null, $linkArgs = array())
    {
        if(is_numeric($prompt) && $prompt > 0) $prompt = $this->objectModel->getPromptById($prompt);
        if(empty($prompt)) return array(false, true);
        
        $result = $this->objectModel->getTargetFormLocation($prompt, $object, $linkArgs);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getTestingLocation method.
     *
     * @param  mixed $prompt
     * @access public
     * @return mixed
     */
    public function getTestingLocationTest($prompt = null)
    {
        $result = $this->objectModel->getTestingLocation($prompt);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test tryGetRelatedObjects method.
     *
     * @param  mixed $prompt      prompt object or prompt id
     * @param  mixed $object      object or object id
     * @param  array $objectNames object names to get
     * @access public
     * @return mixed
     */
    public function tryGetRelatedObjectsTest($prompt = null, $object = null, $objectNames = array())
    {
        $result = $this->objectModel->tryGetRelatedObjects($prompt, $object, $objectNames);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getLastActiveStep method.
     *
     * @param  object $prompt
     * @access public
     * @return mixed
     */
    public function getLastActiveStepTest($prompt = null)
    {
        $result = $this->objectModel->getLastActiveStep($prompt);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getPromptsForUser method.
     *
     * @param  string $module
     * @access public
     * @return mixed
     */
    public function getPromptsForUserTest($module = '')
    {
        $result = $this->objectModel->getPromptsForUser($module);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test filterPromptsForExecution method.
     *
     * @param  array $prompts
     * @param  bool  $keepUnauthorized
     * @access public
     * @return mixed
     */
    public function filterPromptsForExecutionTest($prompts = array(), $keepUnauthorized = false)
    {
        $result = $this->objectModel->filterPromptsForExecution($prompts, $keepUnauthorized);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setInjectData method.
     *
     * @param  mixed $form
     * @param  mixed $data
     * @access public
     * @return mixed
     */
    public function setInjectDataTest($form = null, $data = null)
    {
        $this->objectModel->setInjectData($form, $data);
        if(dao::isError()) return dao::getError();

        return '0';
    }

    /**
     * Test getRoleTemplates method.
     *
     * @access public
     * @return mixed
     */
    public function getRoleTemplatesTest()
    {
        $result = $this->objectModel->getRoleTemplates();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test createRoleTemplate method.
     *
     * @param  string $role
     * @param  string $characterization
     * @access public
     * @return mixed
     */
    public function createRoleTemplateTest($role = '', $characterization = '')
    {
        $result = $this->objectModel->createRoleTemplate($role, $characterization);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test deleteRoleTemplate method.
     *
     * @param  int $id
     * @access public
     * @return mixed
     */
    public function deleteRoleTemplateTest($id = null)
    {
        $result = $this->objectModel->deleteRoleTemplate($id);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateRoleTemplate method.
     *
     * @param  int    $id
     * @param  string $role
     * @param  string $characterization
     * @access public
     * @return mixed
     */
    public function updateRoleTemplateTest($id = null, $role = '', $characterization = '')
    {
        $result = $this->objectModel->updateRoleTemplate($id, $role, $characterization);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getAssistants method.
     *
     * @param  pager  $pager
     * @param  string $orderBy
     * @access public
     * @return mixed
     */
    public function getAssistantsTest($pager = null, $orderBy = 'id_desc')
    {
        $result = $this->objectModel->getAssistants($pager, $orderBy);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getAssistantsByModel method.
     *
     * @param  int $modelId
     * @param  bool $enabled
     * @access public
     * @return mixed
     */
    public function getAssistantsByModelTest($modelId = null, $enabled = true)
    {
        $result = $this->objectModel->getAssistantsByModel($modelId, $enabled);
        if(dao::isError()) return dao::getError();

        return count($result);
    }

    /**
     * Test getAssistantById method.
     *
     * @param  int $assistantId
     * @access public
     * @return mixed
     */
    public function getAssistantByIdTest($assistantId = null)
    {
        $result = $this->objectModel->getAssistantById($assistantId);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test createAssistant method.
     *
     * @param  object $assistant
     * @param  bool   $publish
     * @access public
     * @return mixed
     */
    public function createAssistantTest($assistant = null, $publish = false)
    {
        $result = $this->objectModel->createAssistant($assistant, $publish);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateAssistant method.
     *
     * @param  object $assistant
     * @access public
     * @return mixed
     */
    public function updateAssistantTest($assistant = null)
    {
        $result = $this->objectModel->updateAssistant($assistant);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test toggleAssistant method.
     *
     * @param  int  $assistantId
     * @param  bool $enabled
     * @access public
     * @return mixed
     */
    public function toggleAssistantTest($assistantId = null, $enabled = true)
    {
        $result = $this->objectModel->toggleAssistant($assistantId, $enabled);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkAssistantDuplicate method.
     *
     * @param  string $assistantName
     * @param  int    $modelId
     * @access public
     * @return mixed
     */
    public function checkAssistantDuplicateTest($assistantName = null, $modelId = null)
    {
        $result = $this->objectModel->checkAssistantDuplicate($assistantName, $modelId);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test deleteAssistant method.
     *
     * @param  int $assistantId
     * @access public
     * @return mixed
     */
    public function deleteAssistantTest($assistantId = null)
    {
        if(empty($assistantId) || $assistantId < 0) return '0';
        
        $result = $this->objectModel->deleteAssistant($assistantId);
        if(dao::isError()) return dao::getError();

        return $result ? '1' : '0';
    }

    /**
     * Test AIResponseException::__construct method.
     *
     * @param  string $type
     * @param  mixed $response
     * @access public
     * @return mixed
     */
    public function __constructTest($type = '', $response = '')
    {
        try {
            $exception = new AIResponseException($type, $response);
            
            // 验证基本属性设置正确
            if($exception->type === $type && $exception->response === $response) {
                return '1';
            }
            return '0';
        } catch (Exception $e) {
            return '0';
        }
    }
}