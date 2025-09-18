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
}