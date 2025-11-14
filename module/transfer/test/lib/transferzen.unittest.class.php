<?php
class transferZenTest
{
    public $transferZenTest;
    public $tester;

    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('transfer');

        $this->objectModel = $tester->loadModel('transfer');
        $this->transferZenTest = initReference('transfer');
    }

    /**
     * Test saveSession method.
     *
     * @param  string $module
     * @param  string $params
     * @access public
     * @return mixed
     */
    public function saveSessionTest(string $module = '', string $params = '')
    {
        $result = callZenMethod('transfer', 'saveSession', array($module, $params));

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processTaskTemplateFields method.
     *
     * @param  int    $executionID
     * @param  string $fields
     * @access public
     * @return mixed
     */
    public function processTaskTemplateFieldsTest(int $executionID = 0, string $fields = '')
    {
        $result = callZenMethod('transfer', 'processTaskTemplateFields', array($executionID, $fields));

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test initTemplateFields method.
     *
     * @param  string $module
     * @param  string $fields
     * @access public
     * @return mixed
     */
    public function initTemplateFieldsTest(string $module = '', string $fields = '')
    {
        global $tester;

        // 清理$_POST数据
        $_POST = array();
        $tester->post = new stdClass();
        $tester->post->num = 10;

        try {
            callZenMethod('transfer', 'initTemplateFields', array($module, $fields));

            if(dao::isError()) return dao::getError();

            // 检查方法是否正确设置了post属性
            $result = array();
            $result['module'] = $module;
            $result['kind'] = isset($tester->post->kind) ? $tester->post->kind : 'not_set';
            $result['fieldsSet'] = isset($tester->post->fields) ? 'yes' : 'no';
            $result['rowsSet'] = isset($tester->post->rows) ? 'yes' : 'no';
            $result['fileNameSet'] = isset($tester->post->fileName) ? 'yes' : 'no';

            return $result;
        } catch(Exception $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test formatFields method.
     *
     * @param  string $module
     * @param  array  $fields
     * @param  array  $sessionData
     * @access public
     * @return mixed
     */
    public function formatFieldsTest(string $module = '', array $fields = array(), array $sessionData = array())
    {
        global $tester;

        // 设置session数据
        if($sessionData)
        {
            foreach($sessionData as $key => $value)
            {
                $tester->session->$key = $value;
            }
        }

        $result = callZenMethod('transfer', 'formatFields', array($module, $fields));

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildNextList method.
     *
     * @param  array  $list
     * @param  int    $lastID
     * @param  array  $fields
     * @param  int    $pagerID
     * @param  string $module
     * @access public
     * @return mixed
     */
    public function buildNextListTest(array $list = array(), int $lastID = 0, $fields = array(), int $pagerID = 1, string $module = '')
    {
        global $tester;

        // 设置必要的配置
        $tester->config->transfer = new stdClass();
        $tester->config->transfer->lazyLoading = true;
        $tester->config->transfer->showImportCount = 10;
        $tester->config->transfer->actionModule = array('task', 'story', 'bug');

        // 设置transfer对象的maxImport属性
        $transferZen = $this->transferZenTest;
        $transferZen->maxImport = 20;

        try {
            $result = callZenMethod('transfer', 'buildNextList', array($list, $lastID, $fields, $pagerID, $module));
            if(dao::isError()) return dao::getError();
            return $result;
        } catch(Exception $e) {
            return 'Exception: ' . $e->getMessage();
        } catch(TypeError $e) {
            return 'TypeError: ' . $e->getMessage();
        }
    }

    /**
     * Test printRow method.
     *
     * @param  string $module
     * @param  int    $row
     * @param  array  $fields
     * @param  object $object
     * @param  string $trClass
     * @param  int    $addID
     * @access public
     * @return mixed
     */
    public function printRowTest(string $module = '', int $row = 0, array $fields = array(), object $object = null, string $trClass = '', int $addID = 1)
    {
        global $tester;

        // 设置必要的配置和语言
        if(!isset($tester->config->transfer)) $tester->config->transfer = new stdClass();
        $tester->config->transfer->actionModule = array('task', 'story', 'bug');

        if(!isset($tester->lang->transfer)) $tester->lang->transfer = new stdClass();
        $tester->lang->transfer->new = '新建';
        if(!isset($tester->lang->task)) $tester->lang->task = new stdClass();
        $tester->lang->task->children = '子任务';

        try {
            // 获取transfer对象并设置transferConfig
            $transferZen = $tester->app->loadTarget('transfer', '', 'zen');
            if(!property_exists($transferZen, 'transferConfig')) {
                $transferZen->transferConfig = new stdClass();
            }
            $transferZen->transferConfig->textareaFields = 'desc,content,steps';

            // 使用反射直接调用private方法,避免responseException
            $reflection = new ReflectionClass($transferZen);
            $method = $reflection->getMethod('printRow');
            $method->setAccessible(true);
            $result = $method->invokeArgs($transferZen, array($module, $row, $fields, $object, $trClass, $addID));

            if(dao::isError()) return dao::getError();
            return $result;
        } catch(EndResponseException $e) {
            return $e->getContent();
        } catch(Exception $e) {
            return 'Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        } catch(TypeError $e) {
            return 'TypeError: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        } catch(Error $e) {
            return 'Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        }
    }

    /**
     * Test printCell method.
     *
     * @param  string $module
     * @param  string $field
     * @param  string $control
     * @param  string $name
     * @param  string $selected
     * @param  array  $values
     * @param  int    $row
     * @access public
     * @return mixed
     */
    public function printCellTest(string $module = '', string $field = '', string $control = '', string $name = '', string $selected = '', array $values = array(), int $row = 0)
    {
        global $tester;

        // 设置必要的配置和session数据
        if(!isset($tester->config->transfer)) $tester->config->transfer = new stdClass();
        if(!isset($tester->config->transfer->requiredFields)) $tester->config->transfer->requiredFields = array('name', 'title');

        // 设置session数据用于测试hidden控件
        if($module && $field) {
            $sessionKey = $module . 'TransferParams';
            if(!isset($tester->session->$sessionKey)) {
                $tester->session->$sessionKey = array();
            }
            $tester->session->{$sessionKey}[$field . 'ID'] = 'sessionValue';
        }

        try {
            // 获取zen对象并设置transferConfig
            $zenTest = $tester->app->loadTarget('transfer', '', 'zen');
            if(!property_exists($zenTest, 'transferConfig')) {
                $zenTest->transferConfig = new stdClass();
            }
            $zenTest->transferConfig->textareaFields = 'desc,content,steps';

            // 使用反射调用private方法
            $reflection = new ReflectionClass($zenTest);
            $method = $reflection->getMethod('printCell');
            $method->setAccessible(true);
            $result = $method->invokeArgs($zenTest, array($module, $field, $control, $name, $selected, $values, $row));

            if(dao::isError()) return dao::getError();
            return $result;
        } catch(Exception $e) {
            return 'Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        } catch(TypeError $e) {
            return 'TypeError: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        } catch(Error $e) {
            return 'Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        }
    }

    /**
     * Test processEstimate method.
     *
     * @param  int     $row
     * @param  object  $object
     * @access public
     * @return mixed
     */
    public function processEstimateTest(int $row = 0, ?object $object = null)
    {
        global $tester;

        // 设置必要的session数据
        if(!isset($tester->session->taskTransferParams)) {
            $tester->session->taskTransferParams = array('executionID' => 101);
        }

        try {
            // 使用反射调用private方法
            $result = callZenMethod('transfer', 'processEstimate', array($row, $object));

            if(dao::isError()) return dao::getError();
            return $result;
        } catch(Exception $e) {
            return 'Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        } catch(TypeError $e) {
            return 'TypeError: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        } catch(Error $e) {
            return 'Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        }
    }

    /**
     * Test process4Testcase method.
     *
     * @param  string $field
     * @param  array  $datas
     * @param  int    $key
     * @access public
     * @return mixed
     */
    public function process4TestcaseTest(string $field = '', array $datas = array(), int $key = 0)
    {
        global $tester;

        try {
            // 使用反射调用protected方法
            $result = callZenMethod('transfer', 'process4Testcase', array($field, $datas, $key));

            if(dao::isError()) return dao::getError();
            return $result;
        } catch(Exception $e) {
            return 'Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        } catch(TypeError $e) {
            return 'TypeError: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        } catch(Error $e) {
            return 'Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        }
    }
}