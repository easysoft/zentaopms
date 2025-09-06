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
}