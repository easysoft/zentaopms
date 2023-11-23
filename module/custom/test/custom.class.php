<?php
class customTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('custom');
    }

    /**
     * Test get all custom lang.
     *
     * @access public
     * @return int
     */
    public function getAllLangTest()
    {
        $objects = $this->objectModel->getAllLang();

        if(dao::isError()) return dao::getError();
        $counts = count($objects);

        return $counts;
    }

    /**
     * Test set value of an item.
     *
     * @param  string      $path
     * @param  string      $value
     * @access public
     * @return object|int
     */
    public function setItemTest($path, $value = '')
    {
        $objects = $this->objectModel->setItem($path, $value);

        if(dao::isError()) return dao::getError();

        $level = substr_count($path, '.');
        if($level > 1)
        {
            if($level == 2) list($lang, $module, $key) = explode('.', $path);
            if($level == 3) list($lang, $module, $section, $key) = explode('.', $path);
            if($level == 4) list($lang, $module, $section, $key, $system) = explode('.', $path);

            global $tester;
            $objects = $tester->dao->select('*')->from(TABLE_LANG)->where('`lang`')->eq($lang)->andWhere('`module`')->eq($module)->andWhere('`key`')->eq($key)->fetch();
        }

        return $objects;
    }

    /**
     * Test get some items
     *
     * @param  string   $paramString
     * @access public
     * @return array
     */
    public function getItemsTest($paramString)
    {
        $objects = $this->objectModel->getItems($paramString);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 删除自定义项。
     * Test delete items.
     *
     * @param  string     $paramString
     * @access public
     * @return array|bool
     */
    public function deleteItemsTest($paramString): array|bool
    {
        $result = $this->objectModel->deleteItems($paramString);

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * 解析选择或删除项的参数字符串。
     * Test parse the param string for select or delete items.
     *
     * @param  string $paramString lang=xxx&module=story&section=sourceList&key=customer and so on.
     * @access public
     * @return array
     */
    public function parseItemParamTest(string $paramString): array
    {
        $params = $this->objectModel->parseItemParam($paramString);

        if(dao::isError()) return dao::getError();
        return $params;
    }

    /**
     * 创建一个DAO对象来选择或删除一条或多条记录。
     * Test create a DAO object to select or delete one or more records.
     *
     * @param  string $paramString
     * @param  string $method
     * @access public
     * @return array|int
     */
    public function prepareSQLTest(string $paramString, string $method = 'select'): array|int
    {
        $params  = $this->objectModel->parseItemParam($paramString);
        if($method == 'delete')
        {
            $objects = $this->objectModel->prepareSQL($params, $method)->exec();
        }
        else
        {
            $objects = $this->objectModel->prepareSQL($params, $method)->orderBy('lang,id')->fetchAll('key');
        }

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function saveCustomMenuTest($menu, $module, $method = '')
    {
        $objects = $this->objectModel->saveCustomMenu($menu, $module, $method);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 获取必填字段。
     * Test get required fields.
     *
     * @param  object $moduleConfig
     * @access public
     * @return array
     */
    public function getRequiredFieldsTest(object $moduleConfig): array
    {
        $objects = $this->objectModel->getRequiredFields($moduleConfig);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get module fields.
     *
     * @param  string $moduleName
     * @param  string $method
     * @access public
     * @return array
     */
    public function getFormFieldsTest($moduleName, $method = '')
    {
        global $app;
        $app->loadLang($moduleName);

        $objects = $this->objectModel->getFormFields($moduleName, $method);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get UR and SR pairs.
     *
     * @access public
     * @return array
     */
    public function getURSRPairsTest()
    {
        $objects = $this->objectModel->getURSRPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get UR pairs.
     *
     * @access public
     * @return array
     */
    public function getURPairsTest()
    {
        $objects = $this->objectModel->getURPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get SR pairs.
     *
     * @access public
     * @return array
     */
    public function getSRPairsTest()
    {
        $objects = $this->objectModel->getSRPairs();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 获取需求概念列表。
     * Test get UR and SR list.
     *
     * @access public
     * @return array
     */
    public function getURSRListTest(): array
    {
        $URSRList = $this->objectModel->getURSRList();

        if(dao::isError()) return dao::getError();
        return $URSRList;
    }

    /**
     * 保存表单必填字段设置。
     * Test save required fields.
     *
     * @param  string       $moduleName product|story|productplan|release|execution|task|bug|testcase|testsuite|testtask|testreport|caselib|doc|user|project|build
     * @param  array        $requiredFields
     * @param  string       $fieldsType
     * @access public
     * @return array|object
     */
    public function saveRequiredFieldsTest(string $moduleName, array $requiredFields, string $fieldsType): array|object
    {
        global $app;
        $app->loadLang($moduleName);

        $this->objectModel->saveRequiredFields($moduleName, $requiredFields);
        $objects = $this->objectModel->dao->select('`value`')->from(TABLE_CONFIG)
            ->where('`owner`')->eq('system')
            ->andWhere('`module`')->eq($moduleName)
            ->andWhere('`key`')->eq('requiredFields')
            ->andWhere('`section`')->eq($fieldsType)
            ->fetch();

        if(dao::isError()) return dao::getError();

        $this->objectModel->loadModel('setting')->deleteItems("owner=system&module={$moduleName}&key=requiredFields&vision={$app->config->vision}");
        return $objects;
    }

    /**
     * Test set product and project and sprint concept.
     *
     * @param  string    $sprintConcept
     * @access public
     * @return int|array
     */
    public function setConceptTest(string $sprintConcept): int|array
    {
        $this->objectModel->setConcept($sprintConcept);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->loadModel('setting')->getItem('owner=system&module=custom&key=sprintConcept');
    }

    /**
     * 设置需求概念。
     * Test set UR and SR concept.
     *
     * @param  array      $data
     * @access public
     * @return bool|array
     */
    public function setURAndSRTest(array $data): bool|array
    {
        $objects = $this->objectModel->setURAndSR($data);

        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * 编辑需求概念。
     * Test edit UR and SR concept.
     *
     * @param  int    $key
     * @param  array  $data
     * @access public
     * @return array
     */
    public function updateURAndSRTest(int $key = 0, array $data = array()): array
    {
        $this->objectModel->updateURAndSR($key, '', $data);
        if(dao::isError()) return dao::getError();

        $concept = $this->objectModel->getURSRConcept($key);

        if(!$concept) return array();
        return json_decode($concept, true);
    }

    public function setStoryRequirementTest()
    {
        $objects = $this->objectModel->setStoryRequirement();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 获取需求概念。
     * Get UR and SR concept.
     *
     * @param  int    $key
     * @access public
     * @return array
     */
    public function getURSRConceptTest(int $key): array
    {
        $concept = $this->objectModel->getURSRConcept($key);

        if(dao::isError()) return dao::getError();
        return json_decode($concept, true);
    }

    /**
     * 根据管理模式禁用相关功能。
     * Disable related features based on the management mode.
     *
     * @param  string       $mode
     * @access public
     * @return array|string
     */
    public function disableFeaturesByModeTest(string $mode): array|string
    {
        $this->objectModel->disableFeaturesByMode($mode);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->loadModel('setting')->getItem('oner=system&module=common&key=disabledFeatures');
    }

    /**
     * 处理定时任务。
     * Process measrecord cron.
     *
     * @param  string $disabledFeatures
     * @access public
     * @return array|string
     */
    public function processMeasrecordCronTest(string $disabledFeatures): array|string
    {
        $this->objectModel->loadModel('setting')->setItem('system.common.disabledFeatures', $disabledFeatures);
        $this->objectModel->processMeasrecordCron();
        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('status')->from(TABLE_CRON)->where('command')->like('%methodName=execCrontabQueue')->fetch('status');
    }

    /**
     * 检查系统中是否有用户需求数据。
     * Check if there is requirement data in the system.
     *
     * @access public
     * @return int|array
     */
    public function hasProductURDataTest(): int|array
    {
        $count = $this->objectModel->hasProductURData();

        if(dao::isError()) return dao::getError();
        return $count;
    }

    /**
     * 检查系统中是否有瀑布项目数据。
     * Check if there is waterfall project data in the system.
     *
     * @access public
     * @return int|array
     */
    public function hasWaterfallDataTest(): int|array
    {
        $count = $this->objectModel->hasWaterfallData();

        if(dao::isError()) return dao::getError();
        return $count;
    }

    /**
     * 检查系统中是否有融合瀑布项目数据。
     * Check whether there is waterfallplus project data in the system.
     *
     * @access public
     * @return int|array
     */
    public function hasWaterfallplusDataTest(): int|array
    {
        $count = $this->objectModel->hasWaterfallplusData();

        if(dao::isError()) return dao::getError();
        return $count;
    }

    /**
     * 检查系统中是否有资产库数据。
     * Check if there is assetlib data in the system.
     *
     * @param  string    $edition
     * @access public
     * @return int|array
     */
    public function hasAssetlibDataTest(string $edition): int|array
    {
        $oldEdition = $this->objectModel->config->edition;

        $this->objectModel->config->edition = $edition;
        $count = $this->objectModel->hasAssetlibData();

        $this->objectModel->config->edition = $oldEdition;
        if(dao::isError()) return dao::getError();
        return $count;
    }
}
