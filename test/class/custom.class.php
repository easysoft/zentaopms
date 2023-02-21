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
     * Test delete items.
     *
     * @param  string   $paramString
     * @access public
     * @return int
     */
    public function deleteItemsTest($paramString)
    {
        $objects = $this->objectModel->deleteItems($paramString);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test parse the param string for select or delete items.
     *
     * @param  string    $paramString
     * @access public
     * @return array
     */
    public function parseItemParamTest($paramString)
    {
        $objects = $this->objectModel->parseItemParam($paramString);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test create a DAO object to select or delete one or more records.
     *
     * @param  string  $paramString
     * @param  string $method
     * @access public
     * @return array|int
     */
    public function prepareSQLTest($paramString, $method = 'select')
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

    public function getRequiredFieldsTest($moduleConfig)
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
     * Test get UR and SR list.
     *
     * @access public
     * @return array
     */
    public function getURSRListTest()
    {
        $objects = $this->objectModel->getURSRList();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test save required fields.
     *
     * @param  string  $moduleName
     * @param  string  $requiredFields
     * @param  string  $fieldsType
     * @access public
     * @return object
     */
    public function saveRequiredFieldsTest($moduleName, $requiredFields, $fieldsType)
    {
        global $app, $tester;
        $app->loadLang($moduleName);

        $_POST = $requiredFields;
        $this->objectModel->saveRequiredFields($moduleName);

        $objects = $tester->dao->select('`value`')->from(TABLE_CONFIG)
            ->where('`owner`')->eq('system')
            ->andWhere('`module`')->eq($moduleName)
            ->andWhere('`key`')->eq('requiredFields')
            ->andWhere('`section`')->eq($fieldsType)
            ->fetch();

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test set product and project and sprint concept.
     *
     * @param  int    $sprintConcept
     * @access public
     * @return object
     */
    public function setConceptTest($sprintConcept)
    {
        global $tester;

        $_POST['sprintConcept'] = $sprintConcept;
        $this->objectModel->setConcept();

        if(dao::isError()) return dao::getError();
        $objects = $tester->dao->select('`id`')->from(TABLE_BLOCK)->where('source')->eq('execution')->fetch();

        unset($_POST);

        return $objects;
    }

    /**
     * Test set UR and SR concept.
     *
     * @param  array  $SRName
     * @access public
     * @return int
     */
    public function setURAndSRTest($SRName)
    {
        $_POST = $SRName;
        $objects = $this->objectModel->setURAndSR();

        if(dao::isError()) return dao::getError();

        unset($_POST);

        return $objects;
    }

    /**
     * Test edit UR and SR concept.
     *
     * @param  int    $key
     * @param  string $SRName
     * @access public
     * @return object
     */
    public function updateURAndSRTest($key = 0, $SRName = '')
    {
        global $app, $tester;

        $_POST['URName'] = '用户需求';
        $_POST['SRName'] = $SRName;
        $this->objectModel->updateURAndSR($key);

        if(dao::isError()) return dao::getError();

        $lang = $app->getClientLang();

        $objects = $tester->dao->select('`value`')->from(TABLE_LANG)
            ->where('`key`')->eq($key)
            ->andWhere('section')->eq('URSRList')
            ->andWhere('lang')->eq($lang)
            ->andWhere('module')->eq('custom')
            ->fetch();

        unset($_POST);

        return $objects;
    }

    public function setStoryRequirementTest()
    {
        $objects = $this->objectModel->setStoryRequirement();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Check for waterfallplus project data..
     *
     * @param  string $param deleteproject
     * @access public
     * @return int
     */
    public function hasWaterfallplusDataTest($param = '')
    {
        if($param == 'deleteproject')
        {
            global $tester;
            $tester->dao->update(TABLE_PROJECT)
                ->set('deleted')->eq(1)
                ->where('type')->eq('project')
                ->andWhere('model')->eq('waterfallplus')
                ->exec();
        }
        return $this->objectModel->hasWaterfallplusData();
    }
}
