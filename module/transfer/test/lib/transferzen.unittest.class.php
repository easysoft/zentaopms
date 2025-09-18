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
}