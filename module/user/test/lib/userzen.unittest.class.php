<?php
declare(strict_types = 1);
class userZenTest
{
    public $userZenTest;
    public $tester;

    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('user');

        $this->objectModel = $tester->loadModel('user');
        $this->userZenTest = initReference('user');
    }

    /**
     * Test checkDirPermission method.
     *
     * @access public
     * @return mixed
     */
    public function checkDirPermissionTest()
    {
        try {
            $result = callZenMethod('user', 'checkDirPermission', array());
            if(dao::isError()) return dao::getError();
            return 'success';
        } catch (EndResponseException $e) {
            return 'permission_denied';
        } catch (Exception $e) {
            return 'exception: ' . $e->getMessage();
        }
    }

    /**
     * Test checkTmp method.
     *
     * @access public
     * @return mixed
     */
    public function checkTmpTest()
    {
        $result = callZenMethod('user', 'checkTmp', array());
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
        $result = callZenMethod('user', 'getUserForJSON', array($user));
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

        $result = callZenMethod('user', 'login', array($referer, $viewType, $loginLink, $denyLink, $locateReferer, $locateWebRoot));
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
        $result = callZenMethod('user', 'parseLoginModuleAndMethod', array($referer));
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
        $result = callZenMethod('user', 'prepareRolesAndGroups', array(), 'view');
        if(dao::isError()) return dao::getError();

        return $result;
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
        $result = callZenMethod('user', 'prepareCustomFields', array($method, $requiredMethod), 'view');
        if(dao::isError()) return dao::getError();

        return $result;
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
        $originalLang = $this->tester->app->getClientLang();

        $result = callZenMethod('user', 'reloadLang', array($lang));
        if(dao::isError()) return dao::getError();

        $currentLang = $this->tester->app->getClientLang();

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
        $result = callZenMethod('user', 'responseForLogon', array($referer, $viewType, $loginLink, $denyLink, $locateReferer, $locateWebRoot));
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
        $result = callZenMethod('user', 'responseForLocked', array($viewType));
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
        $result = callZenMethod('user', 'responseForLoginFail', array($viewType, $account));
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
        $result = callZenMethod('user', 'setReferer', array($referer));
        if(dao::isError()) return dao::getError();

        return $result;
    }
}