<?php
class settingTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('setting');
    }

    public function getItemTest($paramString)
    {
        $objects = $this->objectModel->getItem($paramString);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getItemsTest($paramString)
    {
        $objects = $this->objectModel->getItems($paramString);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setItemTest($path, $value = '')
    {
        $objects = $this->objectModel->setItem($path, $value = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setItemsTest($path, $items)
    {
        $objects = $this->objectModel->setItems($path, $items);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function deleteItemsTest($paramString)
    {
        $objects = $this->objectModel->deleteItems($paramString);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function parseItemParamTest($paramString)
    {
        $objects = $this->objectModel->parseItemParam($paramString);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createDAOTest($params, $method = 'select')
    {
        $objects = $this->objectModel->createDAO($params, $method = 'select');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getSysAndPersonalConfigTest($account = '')
    {
        $objects = $this->objectModel->getSysAndPersonalConfig($account = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getVersionTest()
    {
        $objects = $this->objectModel->getVersion();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getURSRTest()
    {
        $objects = $this->objectModel->getURSR();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateVersionTest($version)
    {
        $objects = $this->objectModel->updateVersion($version);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setSNTest()
    {
        $objects = $this->objectModel->setSN();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function snNeededUpdateTest($sn)
    {
        $objects = $this->objectModel->snNeededUpdate($sn);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
