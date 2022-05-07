<?php
class apiTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('api');
    }

    public function publishLibTest($data)
    {
        $objectID = $this->objectModel->publishLib($data);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getRelease($data->lib);
        $this->objectModel->deleteRelease($objectID);
        return $objects;
    }

    public function deleteReleaseTest($id)
    {
        $objects = $this->objectModel->deleteRelease($id);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createTest($params)
    {
        global $tester;

        $_POST = $params;
        $objects = $this->objectModel->create($params['lib']);

        if(dao::isError()) return dao::getError();

        $tester->dao->delete()->from(TABLE_API)->where('id')->eq($objects->id)->exec();

        return $objects;
    }

    public function createStructTest($data, $confirm = true)
    {
        global $tester;

        $objectID = $this->objectModel->createStruct($data);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getStructByID($objectID);

        if($confirm) $tester->dao->delete()->from(TABLE_APISTRUCT)->where('id')->eq($objects->id)->exec();

        return $objects;
    }

    public function updateStructTest($id, $params, $confirm = true)
    {
        global $tester;

        $_POST = $params;
        $objects = $this->objectModel->updateStruct($id);

        if(dao::isError()) return dao::getError();

        if($confirm) $tester->dao->delete()->from(TABLE_APISTRUCT)->where('id')->eq($id)->exec();

        return $objects;
    }

    public function updateTest($apiID)
    {
        $objects = $this->objectModel->update($apiID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getStructListByLibIDTest($id)
    {
        $objects = $this->objectModel->getStructListByLibID($id);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getStructByIDTest($id)
    {
        $objects = $this->objectModel->getStructByID($id);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getReleaseTest($libID = 0, $type = '', $param = 0)
    {
        $objects = $this->objectModel->getRelease($libID = 0, $type = '', $param = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getReleaseListByApiTest($libID)
    {
        $objects = $this->objectModel->getReleaseListByApi($libID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getLibByIdTest($id, $version = 0, $release = 0)
    {
        $objects = $this->objectModel->getLibById($id, $version = 0, $release = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getApiListByReleaseTest($release, $where = '1 = 1 ')
    {
        $objects = $this->objectModel->getApiListByRelease($release, $where = '1 = 1 ');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getListByModuleIdTest($libID = 0, $moduleID = 0, $release = 0)
    {
        $objects = $this->objectModel->getListByModuleId($libID = 0, $moduleID = 0, $release = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getStructByQueryTest($libID, $pager = '', $orderBy = '')
    {
        $objects = $this->objectModel->getStructByQuery($libID, $pager = '', $orderBy = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getStructListByReleaseTest($release, $where = '1 = 1 ', $orderBy = 'id')
    {
        $objects = $this->objectModel->getStructListByRelease($release, $where = '1 = 1 ', $orderBy = 'id');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getReleaseByQueryTest($libID, $pager = '', $orderBy = '')
    {
        $objects = $this->objectModel->getReleaseByQuery($libID, $pager = '', $orderBy = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getStructTreeByLibTest($libID = 0, $structID = 0)
    {
        $objects = $this->objectModel->getStructTreeByLib($libID = 0, $structID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getMethodTest($filePath, $ext = '')
    {
        $objects = $this->objectModel->getMethod($filePath, $ext = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function requestTest($moduleName, $methodName, $action)
    {
        $objects = $this->objectModel->request($moduleName, $methodName, $action);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function sqlTest($sql, $keyField = '')
    {
        $objects = $this->objectModel->sql($sql, $keyField = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTypeListTest($libID)
    {
        $objects = $this->objectModel->getTypeList($libID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createDemoDataTest($name, $baseUrl, $version = '16.0')
    {
        $objects = $this->objectModel->createDemoData($name, $baseUrl, $version = '16.0');

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
