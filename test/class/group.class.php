<?php
class groupTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('group');
    }

    public function createTest()
    {
        $objects = $this->objectModel->create();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateTest($groupID)
    {
        $objects = $this->objectModel->update($groupID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function copyTest($groupID)
    {
        $objects = $this->objectModel->copy($groupID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function copyPrivTest($fromGroup, $toGroup)
    {
        $objects = $this->objectModel->copyPriv($fromGroup, $toGroup);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function copyUserTest($fromGroup, $toGroup)
    {
        $objects = $this->objectModel->copyUser($fromGroup, $toGroup);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getListTest($projectID = 0)
    {
        $objects = $this->objectModel->getList($projectID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getPairsTest($projectID = 0)
    {
        $objects = $this->objectModel->getPairs($projectID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getByIDTest($groupID)
    {
        $objects = $this->objectModel->getByID($groupID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getByAccountTest($account)
    {
        $objects = $this->objectModel->getByAccount($account);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getByAccountsTest($accounts)
    {
        $objects = $this->objectModel->getByAccounts($accounts);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getGroupAccountsTest($groupIdList = arrayTest())
    {
        $objects = $this->objectModel->getGroupAccounts($groupIdList = array());

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getPrivsTest($groupID)
    {
        $objects = $this->objectModel->getPrivs($groupID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserPairsTest($groupID)
    {
        $objects = $this->objectModel->getUserPairs($groupID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserProgramsTest($groupID)
    {
        $objects = $this->objectModel->getUserPrograms($groupID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getAccessProgramGroupTest()
    {
        $objects = $this->objectModel->getAccessProgramGroup();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function deleteTest($groupID, $null = null)
    {
        $objects = $this->objectModel->delete($groupID, $null = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updatePrivByGroupTest($groupID, $menu, $version)
    {
        $objects = $this->objectModel->updatePrivByGroup($groupID, $menu, $version);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateViewTest($groupID)
    {
        $objects = $this->objectModel->updateView($groupID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updatePrivByModuleTest()
    {
        $objects = $this->objectModel->updatePrivByModule();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateUserTest($groupID)
    {
        $objects = $this->objectModel->updateUser($groupID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateProjectAdminTest($groupID)
    {
        $objects = $this->objectModel->updateProjectAdmin($groupID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function sortResourceTest()
    {
        $objects = $this->objectModel->sortResource();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function checkMenuModuleTest($menu, $moduleName)
    {
        $objects = $this->objectModel->checkMenuModule($menu, $moduleName);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getMenuModulesTest($menu)
    {
        $objects = $this->objectModel->getMenuModules($menu);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}