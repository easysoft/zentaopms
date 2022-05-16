<?php
class apiTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('api');
    }

    public function publishLibTest($data, $confirm = true)
    {
        $objectID = $this->objectModel->publishLib($data);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getRelease($data->lib);

        if($confirm) $this->objectModel->deleteRelease($objectID);

        return $objects;
    }

    public function deleteReleaseTest($id, $libID = 0)
    {
        $this->objectModel->deleteRelease($id);

        $objects = $this->objectModel->getRelease($libID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createTest($params, $confirm = true)
    {
        global $tester;

        $_POST = $params;
        $objects = $this->objectModel->create($params['lib']);

        if(dao::isError()) return dao::getError();

        if($confirm) $tester->dao->delete()->from(TABLE_API)->where('id')->eq($objects->id)->exec();

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

    public function updateTest($apiID, $params, $confirm = true)
    {
        global $tester;
        $_POST = $params;

        $objects = $this->objectModel->update($apiID);

        if(dao::isError()) return dao::getError();

        if($confirm) $tester->dao->delete()->from(TABLE_API)->where('id')->eq($apiID)->exec();

        return $objects;
    }

    public function getStructListByLibIDTest($id, $confirm = true)
    {
        global $tester;

        $objects = $this->objectModel->getStructListByLibID($id);

        if(dao::isError()) return dao::getError();

        if($confirm) $tester->dao->delete()->from(TABLE_APISTRUCT)->where('id')->eq($objects['0']->id)->exec();

        return $objects;
    }

    public function getStructByIDTest($id, $confirm = true)
    {
        global $tester;

        $objects = $this->objectModel->getStructByID($id);

        if(dao::isError()) return dao::getError();

        if($confirm) $tester->dao->delete()->from(TABLE_APISTRUCT)->where('id')->eq($objects->id)->exec();

        return $objects;
    }

    public function getReleaseTest($libID = 0, $type = '', $param = 0, $confirm = true)
    {
        $objects = $this->objectModel->getRelease($libID = 0, $type = '', $param = 0);

        if(dao::isError()) return dao::getError();

        if($confirm) $this->objectModel->deleteRelease($objects->id);

        return $objects;
    }

    public function getReleaseListByApiTest($libID, $id = 0, $confirm = true)
    {
        $objects = $this->objectModel->getReleaseListByApi($libID);
        a($objects);

        if(dao::isError()) return dao::getError();

        if($confirm) $this->objectModel->deleteRelease($objects[$id]->id);

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
        global $tester;

        $objects = $this->objectModel->getApiListByRelease($release, $where = '1 = 1 ');

        if(dao::isError()) return dao::getError();

        foreach($release->snap['apis'] as $api) $tester->dao->delete()->from(TABLE_API)->where('id')->eq($api['id'])->exec();
        $this->objectModel->deleteRelease($release->id);

        return $objects;
    }

    /**
     * Get list by module ID.
     *
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  int    $release
     * @access public
     * @return void
     */
    public function getListByModuleIdTest($libID = 0, $moduleID = 0, $release = 0)
    {
        $objects = $this->objectModel->getListByModuleId($libID, $moduleID, $release);

        if(dao::isError()) return dao::getError();

        return current($objects);
    }

    public function getStructListByReleaseTest($release, $where = '1 = 1 ', $orderBy = 'id')
    {
        $objects = $this->objectModel->getStructListByRelease($release, $where = '1 = 1 ', $orderBy = 'id');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getStructTreeByLibTest($libID = 0, $structID = 0)
    {
        $objects = $this->objectModel->getStructTreeByLib($libID = 0, $structID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

}
