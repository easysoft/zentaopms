<?php
class customTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('custom');
    }

    /**
     * 设置自定义语言项。
     * Test set value of an item.
     *
     * @param  string           $path  zh-cn.story.soucreList.customer.1
     * @param  string           $value
     * @access public
     * @return object|array|bool
     */
    public function setItemTest(string $path, string $value = ''): object|array|bool
    {
        $objects = $this->objectModel->setItem($path, $value);
        if(dao::isError()) return dao::getError();

        $level = substr_count($path, '.');
        if($level > 1)
        {
            if($level == 2) list($lang, $module, $key) = explode('.', $path);
            if($level == 3) list($lang, $module, $section, $key) = explode('.', $path);
            if($level == 4) list($lang, $module, $section, $key, $system) = explode('.', $path);

            $objects = $this->objectModel->dao->select('*')->from(TABLE_LANG)->where('`lang`')->eq($lang)->andWhere('`module`')->eq($module)->andWhere('`key`')->eq($key)->fetch();
        }

        return $objects;
    }

    /**
     * 获取自定义语言项。
     * Get value of custom items.
     *
     * @param  string $paramString
     * @access public
     * @return array
     */
    public function getItemsTest(string $paramString): array
    {
        $items = $this->objectModel->getItems($paramString);

        if(dao::isError()) return dao::getError();
        return $items;
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
     * 获取表单必填字段。
     * Test get form required fields.
     *
     * @param  string $moduleName
     * @param  string $method
     * @access public
     * @return array
     */
    public function getFormFieldsTest($moduleName, $method = ''): array
    {
        global $app;
        $app->loadLang($moduleName);

        $fields = $this->objectModel->getFormFields($moduleName, $method);
        if(dao::isError()) return dao::getError();

        return $fields;
    }

    /**
     * 获取需求概念集合。
     * Get UR and SR pairs.
     *
     * @access public
     * @return array
     */
    public function getURSRPairsTest(): array
    {
        $URSRPairs = $this->objectModel->getURSRPairs();
        if(dao::isError()) return dao::getError();

        return $URSRPairs;
    }

    /**
     * 获取用需求概念集合。
     * Test get UR pairs.
     *
     * @access public
     * @return array
     */
    public function getURPairsTest(): array
    {
        $URPairs = $this->objectModel->getURPairs();
        if(dao::isError()) return dao::getError();

        return $URPairs;
    }

    /**
     * 获取软需概念集合。
     * Test get SR pairs.
     *
     * @access public
     * @return array
     */
    public function getSRPairsTest(): array
    {
        $SRPairs = $this->objectModel->getSRPairs();
        if(dao::isError()) return dao::getError();

        return $SRPairs;
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

    /**
     * 检查系统中是否有敏捷项目的问题数据。
     * Check if there is scrum issue data in the system.
     *
     * @param  string    $edition
     * @access public
     * @return int|array
     */
    public function hasScrumIssueDataTest(string $edition): int|array
    {
        $oldEdition = $this->objectModel->config->edition;

        $this->objectModel->config->edition = $edition;
        $count = $this->objectModel->hasScrumIssueData();

        $this->objectModel->config->edition = $oldEdition;
        if(dao::isError()) return dao::getError();
        return $count;
    }

    /**
     * 检查系统中是否有敏捷项目的风险数据。
     * Check if there is scrum risk data in the system.
     *
     * @param  string    $edition
     * @access public
     * @return int|array
     */
    public function hasScrumRiskDataTest(string $edition): int|array
    {
        $oldEdition = $this->objectModel->config->edition;

        $this->objectModel->config->edition = $edition;
        $count = $this->objectModel->hasScrumRiskData();

        $this->objectModel->config->edition = $oldEdition;
        if(dao::isError()) return dao::getError();
        return $count;
    }

    /**
     * 检查系统中是否有机会数据。
     * Check if there is opportunity data in the system.
     *
     * @param  string    $edition
     * @access public
     * @return int|array
     */
    public function hasScrumOpportunityDataTest(string $edition): int|array
    {
        $oldEdition = $this->objectModel->config->edition;

        $this->objectModel->config->edition = $edition;
        $count = $this->objectModel->hasScrumOpportunityData();

        $this->objectModel->config->edition = $oldEdition;
        if(dao::isError()) return dao::getError();
        return $count;
    }

    /**
     * 检查系统中是否有会议数据。
     * Check if there is meeting data in the system.
     *
     * @param  string    $edition
     * @access public
     * @return int|array
     */
    public function hasScrumMeetingDataTest(string $edition): int|array
    {
        $oldEdition = $this->objectModel->config->edition;

        $this->objectModel->config->edition = $edition;
        $count = $this->objectModel->hasScrumMeetingData();

        $this->objectModel->config->edition = $oldEdition;
        if(dao::isError()) return dao::getError();
        return $count;
    }

    /**
     * 检查系统中是否有审计数据。
     * Check if there is auditplan data in the system.
     *
     * @param  string    $edition
     * @access public
     * @return int|array
     */
    public function hasScrumAuditplanDataTest(string $edition): int|array
    {
        $oldEdition = $this->objectModel->config->edition;

        $this->objectModel->config->edition = $edition;
        $count = $this->objectModel->hasScrumAuditplanData();

        $this->objectModel->config->edition = $oldEdition;
        if(dao::isError()) return dao::getError();
        return $count;
    }

    /**
     * 检查系统中是否有项目活动数据。
     * Check if there is project activity data in the system.
     *
     * @param  string    $edition
     * @access public
     * @return int|array
     */
    public function hasScrumProcessDataTest(string $edition): int|array
    {
        $oldEdition = $this->objectModel->config->edition;

        $this->objectModel->config->edition = $edition;
        $count = $this->objectModel->hasScrumProcessData();

        $this->objectModel->config->edition = $oldEdition;
        if(dao::isError()) return dao::getError();
        return $count;
    }

    /**
     * 获取更新项目权限的数据。
     * Get data for update project acl.
     *
     * @param  string $key
     * @access public
     * @return array
     */
    public function getDataForUpdateProjectAclTest(string $key): array
    {
        list($projectGroup, $programPM, $stakeholders) = $this->objectModel->getDataForUpdateProjectAcl();
        return ${$key};
    }

    /**
     * 处理项目权限为继承项目集的项目权限。
     * process project priv within a program set.
     *
     * @access public
     * @return array|object
     */
    public function processProjectAclTest(int $projectID): array|object
    {
        $this->objectModel->processProjectAcl();

        if(dao::isError()) return dao::getError();
        return $this->objectModel->loadModel('project')->getByID($projectID);
    }

    /**
     * 计算启用和不启用的功能。
     * Compute the enabled and disabled features.
     *
     * @param  string $edition open|ipd|max
     * @access public
     * @return array
     */
    public function computeFeaturesTest(string $edition): array
    {
        $oldEdition = $this->objectModel->config->edition;

        $this->objectModel->config->edition = $edition;
        $features = $this->objectModel->computeFeatures();

        $this->objectModel->config->edition = $oldEdition;
        if(dao::isError()) return dao::getError();
        return $features;
    }

    /**
     * 获取自定义语言项。
     * Get custom lang.
     *
     * @access public
     * @return array|false
     */
    public function getCustomLangTest(): array|false
    {
        $oldVision = $this->objectModel->config->vision;

        $this->objectModel->config->vision = 'rnd';
        $allCustomLang = $this->objectModel->getCustomLang();

        $this->objectModel->config->vision = $oldVision;
        if(dao::isError()) return dao::getError();
        return $allCustomLang;
    }

    /**
     * 获取自定义语言项。
     * Get custom lang.
     *
     * @access public
     * @return array|false
     */
    public function getAllLangTest(): array|false
    {
        $oldVision = $this->objectModel->config->vision;

        $this->objectModel->config->vision = 'rnd';
        $processedLang = $this->objectModel->getCustomLang();

        $this->objectModel->config->vision = $oldVision;
        if(dao::isError()) return dao::getError();
        return $processedLang;
    }

    /**
     * 构造自定义导航数据。
     * Build custom menu data.
     *
     * @access public
     * @return array
     */
    public static function buildCustomMenuMapTest(): array
    {
        global $config;

        $flowModule = $config->global->flow . '_main';
        $customMenu = isset($config->customMenu->$flowModule) ? $config->customMenu->$flowModule : array();
        if(!empty($customMenu) && is_string($customMenu) && substr($customMenu, 0, 1) === '[') $customMenu = json_decode($customMenu);

        return customModel::buildCustomMenuMap($customMenu, 'main')[0];
    }

    /**
     * 构造菜单数据。
     * Build menu data.
     *
     * @param  string $module main|product|my and so on
     * @static
     * @access public
     * @return array
     */
    public static function buildMenuItemsTest(string $module = 'main'): array
    {
        global $config, $lang;

        $allMenu = new stdclass();
        if($module == 'main' and !empty($lang->menu)) $allMenu = $lang->menu;
        if($module != 'main' and isset($lang->menu->$module) and isset($lang->menu->{$module}['subMenu'])) $allMenu = $lang->menu->{$module}['subMenu'];
        if($module == 'product' and isset($allMenu->branch)) $allMenu->branch = str_replace('@branch@', $lang->custom->branch, $allMenu->branch);
        if($module == 'my' && empty($config->global->scoreStatus)) unset($allMenu->score);

        $flowModule = $config->global->flow . '_main';
        $customMenu = isset($config->customMenu->$flowModule) ? $config->customMenu->$flowModule : array();
        if(!empty($customMenu) && is_string($customMenu) && substr($customMenu, 0, 1) === '[') $customMenu = json_decode($customMenu);
        $customMenuMap = customModel::buildCustomMenuMap($customMenu, 'main')[0];

        return customModel::buildMenuItems($allMenu, $customMenuMap, $module);
    }

    /**
     * 构造菜单数据项。
     * Build menu item.
     *
     * @static
     * @access public
     * @return object
     */
    public static function buildMenuItemTest(): object
    {
        global $config, $lang;

        $flowModule = $config->global->flow . '_main';
        $customMenu = isset($config->customMenu->$flowModule) ? $config->customMenu->$flowModule : array();
        if(!empty($customMenu) && is_string($customMenu) && substr($customMenu, 0, 1) === '[') $customMenu = json_decode($customMenu);
        $customMenuMap = customModel::buildCustomMenuMap($customMenu, 'main')[0];

        return customModel::buildMenuItem('', $customMenuMap);
    }

    /**
     * 构造菜单数据。
     * Build menu data.
     *
     * @param  string $module main|product|my and so on
     * @static
     * @access public
     * @return array
     */
    public static function setMenuByConfigTest(string $module = 'main'): array
    {
        global $config, $lang;

        $allMenu = new stdclass();
        if($module == 'main' and !empty($lang->menu)) $allMenu = $lang->menu;
        if($module != 'main' and isset($lang->menu->$module) and isset($lang->menu->{$module}['subMenu'])) $allMenu = $lang->menu->{$module}['subMenu'];
        if($module == 'product' and isset($allMenu->branch)) $allMenu->branch = str_replace('@branch@', $lang->custom->branch, $allMenu->branch);
        if($module == 'my' && empty($config->global->scoreStatus)) unset($allMenu->score);

        $flowModule = $config->global->flow . '_main';
        $customMenu = isset($config->customMenu->$flowModule) ? $config->customMenu->$flowModule : array();
        if(!empty($customMenu) && is_string($customMenu) && substr($customMenu, 0, 1) === '[') $customMenu = json_decode($customMenu);
        $customMenuMap = customModel::buildCustomMenuMap($customMenu, 'main')[0];

        return customModel::setMenuByConfig($allMenu, $customMenuMap, $module);
    }

    /**
     * 获取模块菜单数据，如果模块是'main'则返回主菜单。
     * Get module menu data, if module is 'main' then return main menu.
     *
     * @param  string $module
     * @static
     * @access public
     * @return array
     */
    public static function getModuleMenuTest(string $module = 'main'): array
    {
        return customModel::getModuleMenu($module);
    }

    /**
     * 获取主菜单数据。
     * Get main menu data.
     *
     * @static
     * @access public
     * @return array
     */
    public static function getMainMenuTest(): array
    {
        return customModel::getMainMenu();
    }

    /**
     * 获取模块的筛选标签。
     * Get feature menu.
     *
     * @param  string     $module
     * @param  string     $method
     * @static
     * @access public
     * @return array|null
     */
    public static function getFeatureMenuTest(string $module, string $method): array|null
    {
        return customModel::getFeatureMenu($module, $method);
    }

    /**
     * 将查询条件合并到筛选标签中。
     * Merge shortcut query in featureBar.
     *
     * @param  string     $module
     * @param  string     $method
     * @static
     * @access public
     * @return array|null
     */
    public static function mergeFeatureBarTest(string $module, string $method): array|null
    {
        global $lang;
        customModel::mergeFeatureBar($module, $method);

        return isset($lang->$module->featureBar[$method]) ? $lang->$module->featureBar[$method] : null;
    }
}
