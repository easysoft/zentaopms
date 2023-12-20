<?php
declare(strict_types=1);
/**
 * The zen file of transfer module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tang Hucheng<tanghucheng@easycorp.ltd>
 * @package     transfer
 * @link        https://www.zentao.net
 */

class transferTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('transfer');
    }

    /**
     * 根据config:dataSource中配置的方法获取字段数据源。
     * Get source by module method.
     *
     * @param  string $module
     * @param  string $callModule
     * @param  string $method
     * @param  string|array $params
     * @param  string|array $pairs
     * @access public
     * @return array|string
     */
    public function getSourceByModuleMethodTest(string $module, string $callModule, string $method, string|array $params = '', string|array $pairs = ''): array|string
    {
        if(empty($module))     return 'Module is empty';
        if(empty($callModule)) return 'Call module is empty';
        if(empty($method))     return 'Method is empty';
        return $this->objectModel->getSourceByModuleMethod($module, $callModule, $method, $params, $pairs);
    }

    /**
     * 测试获取文件
     * Test get files.
     *
     * @param  string $module
     * @param  array  $rows
     * @param  int    $index
     * @access public
     * @return string
     */
    public function getFilesTest(string $module, array $rows, int $index): string
    {
        $result = $this->objectModel->getFiles($module, $rows);
        if(!empty($result[$index]->files)) return 'File isset';
        return 'No File';
    }

    /**
     * 测试setListValue
     * setListValueTest
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function setListValueTest(string $module = '')
    {
        global $tester, $app;
        $app->methodName = 'ajaxgettbody';

        $_SESSION['testcaseTransferParams']['productID'] = '0';
        $_SESSION['testcaseTransferParams']['branch']    = '0';

        $object = $tester->loadModel($module);
        $fields = isset($object->config->$module->exportFields) ? $object->config->$module->exportFields : '';

        $object->config->bug->listFields   = "module,project,execution,story,severity,pri,type,os,browser,openedBuild";
        if($module == 'testcase')
        {
            $app->config->testcase->cascade    = array('story' => 'module');
            $app->config->testcase->listFields = 'module,type,stage,pri,story,status,branch,results';
        }

        $fields    = explode(',', $fields);
        $fieldList = $this->objectModel->initFieldList($module, $fields);

        return $this->objectModel->setListValue($module, $fieldList);
    }

    /**
     * 测试setListValue
     * setListValueTest
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getCascadeListTest(string $module = '')
    {
        global $tester, $app;
        $app->methodName = 'ajaxgettbody';

        $_SESSION['testcaseTransferParams']['productID'] = '0';
        $_SESSION['testcaseTransferParams']['branch']    = '0';

        $object = $tester->loadModel($module);
        $fields = isset($object->config->$module->exportFields) ? $object->config->$module->exportFields : '';

        $object->config->bug->listFields   = "module,project,execution,story,severity,pri,type,os,browser,openedBuild";
        if($module == 'testcase')
        {
            $app->config->testcase->cascade    = array('story' => 'module');
            $app->config->testcase->listFields = 'module,type,stage,pri,story,status,branch,results';
        }

        $fields    = explode(',', $fields);
        $fieldList = $this->objectModel->initFieldList($module, $fields);

        return $this->objectModel->setListValue($module, $fieldList);
    }

    /**
     * 测试getQueryDatas。
     * Get query datas.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getQueryDatasTest(string $module = '', string $checkedItem = '')
    {
        global $tester;

        /* 设置是否导出选中数据。*/
        if($checkedItem)
        {
            $_POST['exportType']    = 'selected';
            $_COOKIE['checkedItem'] = $checkedItem;
        }

        /* 设置task的查询条件(OnlyCondition/QueryCondition都存在时)。*/
        if($module == 'task')
        {
            $execution = $tester->loadModel('execution');
            $execution->getTasks(0, 101, array(), 'unclosed', 0, 0, '', null);
        }

        /* 设置story的查询条件(只有QueryCondition时)。*/
        if($module == 'story')
        {
            $_SESSION['storyOnlyCondition']  = false;
            $_SESSION['storyQueryCondition'] = "SELECT * FROM `zt_story` WHERE `status` = 'active'";
        }

        return $this->objectModel->getQueryDatas($module);
    }

    /**
     * 测试getRows。
     * Get query datas.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getRowsTest(string $module = '', string $checkedItem = '')
    {
        global $tester, $app;
        $app->methodName = 'export';

        $object = $tester->loadModel($module);
        $fields = isset($object->config->$module->exportFields) ? $object->config->$module->exportFields : '';

        $_SESSION[$module . 'TransferParams']['executionID'] = 101;
        if($module == 'testcase')
        {
            $app->config->testcase->cascade    = array('story' => 'module');
            $app->config->testcase->listFields = 'module,type,stage,pri,story,status,branch,results';
        }

        $fields    = explode(',', $fields);
        $fieldList = $this->objectModel->initFieldList($module, $fields);

        $this->getQueryDatasTest($module);
        return $this->objectModel->getRows($module, $fieldList);
    }
}
