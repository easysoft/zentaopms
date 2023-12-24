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
     * 生成导出配置
     * initConfig
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function initConfig($module = 'story')
    {
        global $tester, $app;
        $app->methodName = 'export';
        $tester->loadModel($module);

        $_SESSION[$module . 'TransferParams']['productID']   = 1;
        $_SESSION[$module . 'TransferParams']['executionID'] = 101;
        $_SESSION[$module . 'TransferParams']['projectID']   = 11;

        switch ($module)
        {
            case 'story':
                $app->config->story->templateFields = 'product,branch,module,source,sourceNote,title,spec,verify,keywords,pri,estimate,reviewer,linkStories';
                $app->config->story->listFields     = 'product,branch,module,pri';
                $app->config->story->dtable->fieldList['product']['control'] = 'select';
                $app->config->story->dtable->fieldList['module']['control']  = 'multiple';
                break;
            case 'task':
                $app->config->task->templateFields = 'project,execution,module,type,story,pri,estimate,consumed,deadline';
                $app->config->task->listFields     = 'project,execution,module,pri,type';
                $app->config->task->dtable->fieldList['project']['control'] = 'select';
                break;
        }
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

    /**
     * 测试parseExcelDropdownValues。
     * Parse excel dropdown values.
     *
     * @param  string $module
     * @param  array  $rows
     * @access public
     * @return array
     */
    public function parseExcelDropdownValuesTest(string $module, array $rows = array())
    {
        $this->initConfig();

        $fields = $this->objectModel->getImportFields($module);
        return $this->objectModel->parseExcelDropdownValues($module, $rows, '', $fields);
    }

    /**
     * 测试initFieldList。
     * initFieldListTest.
     *
     * @param  string $module
     * @param  bool   $withKey
     * @access public
     * @return array
     */
    public function initFieldListTest(string $module, bool $withKey = false)
    {
        $this->initConfig($module);

        $fields = $this->objectModel->getImportFields($module);
        return $this->objectModel->initFieldList($module, array_keys($fields), $withKey);
    }

    /**
     * 测试initValues。
     * initValues.
     *
     * @param  string $module
     * @param  bool   $withKey
     * @param  string $field
     * @access public
     * @return array
     */
    public function initValuesTest(string $module, bool $withKey = false, string $field = '')
    {
        $fieldList = $this->initFieldListTest($module, $withKey);
        return $fieldList[$field]['values'];
    }

    /**
     * 测试getImportFields。
     * getImportFields.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getImportFieldsTest(string $module)
    {
        $this->initConfig($module);
        return $this->objectModel->getImportFields($module);
    }

    /**
     * 导出测试。
     * exportTest
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function exportTest(string $module)
    {
        $_POST = array();
        global $tester;
        $this->initConfig($module);
        $_POST['exportFields'] = $tester->config->$module->templateFields;
        $this->getQueryDatasTest($module);
        $this->objectModel->export($module);
        $_POST['count'] = count($_POST['rows']);
        return $_POST;
    }

    /**
     * 测试generateExportDatas。
     * generateExportDatasTest.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function generateExportDatasTest(string $module)
    {
        $_POST = array();
        global $tester;
        $this->initConfig($module);
        $_POST['exportFields'] = $tester->config->$module->templateFields;
        $this->getQueryDatasTest($module);
        $this->objectModel->export($module);
        $_POST['count'] = count($_POST['rows']);
        return $_POST['rows'];
    }

    /**
     * 测试updateChildDatas。
     * updateChildDatasTest.
     *
     * @param  string $module
     * @param  string $checkedItem
     * @access public
     * @return array
     */
    public function updateChildDatasTest(string $module = '', string $checkedItem = '')
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
