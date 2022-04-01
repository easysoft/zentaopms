<?php
class caselibTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('caselib');
    }

    public function setLibMenuTest($libraries, $libID)
    {
        $objects = $this->objectModel->setLibMenu($libraries, $libID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function saveLibStateTest($libID = 0, $libraries = array())
    {
        $objects = $this->objectModel->saveLibState($libID, $libraries);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getByIdTest($libID, $setImgSize = false)
    {
        $objects = $this->objectModel->getById($libID, $setImgSize);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateTest($libID)
    {
        $objects = $this->objectModel->update($libID);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getById($libID);

        return $objects;
    }

    public function deleteTest($libID, $table = '')
    {
        $objects = $this->objectModel->delete($libID, $table);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getById($libID);

        return $objects;
    }

    public function getLibrariesTest()
    {
        $objects = $this->objectModel->getLibraries();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getListTest($orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getList($orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createTest($param)
    {
        foreach($param as $k => $v) $_POST[$k] = $v;
        $libID = $this->objectModel->create();
        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getById($libID);

        return $objects;
    }

    public function getLibCasesTest($libID, $browseType, $queryID = 0, $moduleID = 0, $sort = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getLibCases($libID, $browseType, $queryID, $moduleID, $sort, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function buildSearchFormTest($libID, $libraries, $queryID, $actionURL)
    {
        $objects = $this->objectModel->buildSearchForm($libID, $libraries, $queryID, $actionURL);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getLibLinkTest($module, $method, $extra)
    {
        $objects = $this->objectModel->getLibLink($module, $method, $extra);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createFromImportTest($libID)
    {
        $objects = $this->objectModel->createFromImport($libID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function batchCreateCaseTest($libID)
    {
        $objects = $this->objectModel->batchCreateCase($libID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
