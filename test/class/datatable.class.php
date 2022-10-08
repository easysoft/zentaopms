<?php
class datatableTest
{

    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('datatable');
    }

    /**
     * Test get field list.
     *
     * @param string $module
     * @param string $method
     * @access public
     * @return void
     */
    public function getFieldListTest($module, $method)
    {
        $module = zget($this->objectModel->config->datatable->moduleAlias, "$module-$method", $module);
        $result = $this->objectModel->getFieldList($module);
        return $result;
    }

    /**
     * Test get save setting field.
     *
     * @param string $module
     * @param string $method
     * @access public
     * @return void
     */
    public function getSettingTest($module, $method)
    {
        $this->objectModel->app->methodName = $method;
        $result = $this->objectModel->getSetting($module);
        return $result;
    }

    /**
     * Test print table head.
     *
     * @param array $data
     * @access public
     * @return void
     */
    public function printHeadTest($data)
    {
        ob_start();
        $this->objectModel->printHead($data['cols'], $data['orderBy'], $data['vars'], $data['checkBox']);
        $head = ob_get_clean();

        $isTh   = strpos($head, '<th data-flex') !== false ? true : false;
        $order  = $data['orderBy'] ? (strpos($head, "class='sort-") !== false ? true : false) : true;
        $vars   = $data['vars'] ? (strpos($head, $data['vars']) !== false ? true : false) : true;
        $check  = $data['checkBox'] ? (strpos($head, 'checkbox-primary') !== false ? true : false) : true;
        $result = ($isTh and $order and $vars and $check) ? true : false;
        return $result;
    }

    /**
     * Test set fixed field width.
     *
     * @param object $setting
     * @access public
     * @return void
     */
    public function setFixedFieldWidthTest($setting)
    {
        $result = $this->objectModel->setFixedFieldWidth($setting);
        return $result;
    }
}
