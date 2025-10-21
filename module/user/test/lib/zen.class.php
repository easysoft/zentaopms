<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class userZenTest extends baseTest
{
    protected $moduleName = 'user';
    protected $className  = 'zen';

    /**
     * Test checkDirPermission method.
     *
     * @access public
     * @return mixed
     */
    public function checkDirPermissionTest()
    {
        $result = $this->invokeArgs('checkDirPermission');
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkTmp method.
     *
     * @access public
     * @return mixed
     */
    public function checkTmpTest()
    {
        $result = $this->invokeArgs('checkTmp');
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getUserForJSON method.
     *
     * @param  object $user
     * @access public
     * @return mixed
     */
    public function getUserForJSONTest($user = null)
    {
        $result = $this->invokeArgs('getUserForJSON', [$user]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test login method.
     *
     * @param  string $referer
     * @param  string $viewType
     * @param  string $loginLink
     * @param  string $denyLink
     * @param  string $locateReferer
     * @param  string $locateWebRoot
     * @param  string $account
     * @param  string $password
     * @access public
     * @return mixed
     */
    public function loginTest($referer = '', $viewType = '', $loginLink = '', $denyLink = '', $locateReferer = '', $locateWebRoot = '', $account = '', $password = '')
    {
        if($account || $password)
        {
            $_POST['account'] = $account;
            $_POST['password'] = $password;
            $_POST['passwordStrength'] = 1;
        }

        $result = $this->invokeArgs('login', [$referer, $viewType, $loginLink, $denyLink, $locateReferer, $locateWebRoot]);
        if(dao::isError()) return dao::getError();

        unset($_POST['account']);
        unset($_POST['password']);
        unset($_POST['passwordStrength']);

        if(empty($result)) return '0';
        return $result;
    }

    /**
     * Test parseLoginModuleAndMethod method.
     *
     * @param  string $referer
     * @access public
     * @return mixed
     */
    public function parseLoginModuleAndMethodTest($referer = '')
    {
        $result = $this->invokeArgs('parseLoginModuleAndMethod', [$referer]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test prepareRolesAndGroups method.
     *
     * @access public
     * @return mixed
     */
    public function prepareRolesAndGroupsTest()
    {
        $this->invokeArgs('prepareRolesAndGroups');
        if(dao::isError()) return dao::getError();

        return $this->getProperty('view');
    }

    /**
     * Test prepareCustomFields method.
     *
     * @param  string $method
     * @param  string $requiredMethod
     * @access public
     * @return mixed
     */
    public function prepareCustomFieldsTest($method = 'batchCreate', $requiredMethod = 'create')
    {
        $this->invokeArgs('prepareCustomFields', [$method, $requiredMethod]);
        if(dao::isError()) return dao::getError();

        return $this->getProperty('view');
    }

    /**
     * Test reloadLang method.
     *
     * @param  string $lang
     * @access public
     * @return mixed
     */
    public function reloadLangTest($lang = 'zh-cn')
    {
        $originalLang = $this->instance->app->getClientLang();

        $this->invokeArgs('reloadLang', [$lang]);
        if(dao::isError()) return dao::getError();

        $currentLang = $this->instance->app->getClientLang();

        return array(
            'originalLang' => $originalLang,
            'currentLang' => $currentLang,
            'langSet' => $lang,
            'success' => true
        );
    }

    /**
     * Test responseForLogon method.
     *
     * @param  string $referer
     * @param  string $viewType
     * @param  string $loginLink
     * @param  string $denyLink
     * @param  string $locateReferer
     * @param  string $locateWebRoot
     * @access public
     * @return mixed
     */
    public function responseForLogonTest($referer = '', $viewType = '', $loginLink = '', $denyLink = '', $locateReferer = '', $locateWebRoot = '')
    {
        $result = $this->invokeArgs('responseForLogon', [$referer, $viewType, $loginLink, $denyLink, $locateReferer, $locateWebRoot]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test responseForLocked method.
     *
     * @param  string $viewType
     * @access public
     * @return mixed
     */
    public function responseForLockedTest($viewType = '')
    {
        $result = $this->invokeArgs('responseForLocked', [$viewType]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test responseForLoginFail method.
     *
     * @param  string $viewType
     * @param  string $account
     * @access public
     * @return mixed
     */
    public function responseForLoginFailTest($viewType = '', $account = '')
    {
        $result = $this->invokeArgs('responseForLoginFail', [$viewType, $account]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setReferer method.
     *
     * @param  string $referer
     * @access public
     * @return mixed
     */
    public function setRefererTest($referer = '')
    {
        $result = $this->invokeArgs('setReferer', [$referer]);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
