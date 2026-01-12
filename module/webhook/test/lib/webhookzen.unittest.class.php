<?php
declare(strict_types = 1);
class webhookTest
{
    public $webhookZenTest;
    public $tester;

    public function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $this->objectModel = $tester->loadModel('webhook');
        $tester->app->setModuleName('webhook');

        $this->webhookZenTest = initReference('webhook');
    }

    /**
     * Test getResponse method.
     *
     * @param  object $webhook
     * @access public
     * @return mixed
     */
    public function getResponseTest(?object $webhook = null)
    {
        $method = $this->webhookZenTest->getMethod('getResponse');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->webhookZenTest->newInstance(), [$webhook]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getBoundUseridPairs method.
     *
     * @param  object $webhook
     * @param  array  $users
     * @param  array  $boundUsers
     * @param  array  $oauthUsers
     * @access public
     * @return mixed
     */
    public function getBoundUseridPairsTest(?object $webhook = null, array $users = array(), array $boundUsers = array(), array $oauthUsers = array())
    {
        $method = $this->webhookZenTest->getMethod('getBoundUseridPairs');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->webhookZenTest->newInstance(), [$webhook, $users, $boundUsers, $oauthUsers]);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}