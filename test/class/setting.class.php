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

    /**
     * Test create a DAO object to select or delete one or more records.
     *
     * @param  string $paramString
     * @param  string $method     select|delete.
     * @access public
     * @return array|int
     */
    public function createDAOTest($paramString, $method = 'select')
    {
        $params  = $this->objectModel->parseItemParam($paramString);
        if($method == 'delete')
        {
            $objects = $this->objectModel->createDAO($params, $method)->exec();
        }
        else
        {
            $objects = $this->objectModel->createDAO($params, $method)->orderBy('key')->fetch();
        }

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get config of system and one user.
     *
     * @param  string $account
     * @access public
     * @return bool
     */
    public function getSysAndPersonalConfigTest($account = '')
    {
        $objects = $this->objectModel->getSysAndPersonalConfig($account);

        if(dao::isError()) return dao::getError();

        return !empty($objects) ? true : false;
    }

    /**
     * Test get the version of current zentaopms.
     *
     * Since the version field not saved in db. So if empty, return 0.3 beta.
     *
     * @access public
     * @return void
     */
    public function getVersionTest()
    {
        $objects = $this->objectModel->getVersion();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get URSR.
     *
     * @access public
     * @return int
     */
    public function getURSRTest()
    {
        $objects = $this->objectModel->getURSR();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test update version
     *
     * @param  string $version
     * @access public
     * @return array
     */
    public function updateVersionTest($version)
    {
        $this->objectModel->updateVersion($version);

        if(dao::isError()) return dao::getError();

        $params['owner']   = 'system';
        $params['module']  = 'common';
        $params['section'] = 'global';
        $params['key']     = 'version';

        $objects = $this->objectModel->createDAO($params)->fetchAll();

        return $objects;
    }

    /**
     * Test set the sn of current zentaopms.
     *
     * @access public
     * @return bool
     */
    public function setSNTest()
    {
        $this->objectModel->setSN();

        if(dao::isError()) return dao::getError();

        $params['owner']   = 'system';
        $params['module']  = 'common';
        $params['section'] = 'global';
        $params['key']     = 'sn';

        $objects = $this->objectModel->createDAO($params)->fetchAll();

        return !empty($objects) ? true : false;
    }

    /**
     * Test judge a sn needed update or not.
     *
     * @param  string $sn
     * @access public
     * @return bool
     */
    public function snNeededUpdateTest($sn)
    {
        $objects = $this->objectModel->snNeededUpdate($sn);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
