<?php
declare(strict_types=1);
/**
 * The test class file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     group
 * @link        https://www.zentao.net
 */
class groupZenTest
{
    public $groupZenTest;
    public $tester;

    public function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('group');
        $tester->loadModel('group');

        $this->groupZenTest = initReference('group');
    }

    /**
     * 测试setDevOpsPrivInCompose方法。
     * Test setDevOpsPrivInCompose.
     *
     * @param  string    $packageCode
     * @access public
     * @return array
     */
    public function setDevOpsPrivInComposeTest($packageCode)
    {
        global $config;
        $method = $this->groupZenTest->getMethod('setDevOpsPrivInCompose');
        $method->invoke($this->groupZenTest->newInstance());

        return $config->group->package->{$packageCode}->privs;
    }

    /**
     * 测试appendResourcePackages方法。
     * Test appendResourcePackages.
     *
     * @access public
     * @return array
     */
    public function appendResourcePackagesTest()
    {
        global $config;
        $method = $this->groupZenTest->getMethod('appendResourcePackages');
        $method->invoke($this->groupZenTest->newInstance());

        $result = array();
        $result['subsets'] = $config->group->subset;
        $result['packages'] = $config->group->package;
        
        return $result;
    }

    /**
     * 测试appendWorkflowMenu方法。
     * Test appendWorkflowMenu method.
     *
     * @param  string $packageCode
     * @param  string $module
     * @param  string $method
     * @access public
     * @return array
     */
    public function appendWorkflowMenuTest(string $packageCode, string $module, string $method)
    {
        global $config;
        $appendMethod = $this->groupZenTest->getMethod('appendWorkflowMenu');
        $groupInstance = $this->groupZenTest->newInstance();
        
        $appendMethod->invoke($groupInstance, $packageCode, $module, $method);

        return isset($config->group->package->{$packageCode}->privs) ? $config->group->package->{$packageCode}->privs : array();
    }

    /**
     * 测试managePrivByGroup方法。
     * Test managePrivByGroup method.
     *
     * @param  int    $groupID
     * @param  string $nav
     * @param  string $version
     * @access public
     * @return array
     */
    public function managePrivByGroupTest(int $groupID = 0, string $nav = '', string $version = '')
    {
        ob_start();
        $method = $this->groupZenTest->getMethod('managePrivByGroup');
        $groupInstance = $this->groupZenTest->newInstance();
        
        $method->invoke($groupInstance, $groupID, $nav, $version);
        ob_end_clean();
        
        $result = array();
        $result['groupID'] = isset($groupInstance->view->groupID) ? $groupInstance->view->groupID : null;
        $result['nav'] = isset($groupInstance->view->nav) ? $groupInstance->view->nav : null;
        $result['version'] = isset($groupInstance->view->version) ? $groupInstance->view->version : null;
        $result['group'] = isset($groupInstance->view->group) ? (is_object($groupInstance->view->group) ? 'object' : 'null') : 'null';
        $result['title'] = isset($groupInstance->view->title) ? 'string' : 'null';
        $result['subsets'] = isset($groupInstance->view->subsets) ? count($groupInstance->view->subsets) : 0;
        $result['packages'] = isset($groupInstance->view->packages) ? count($groupInstance->view->packages) : 0;
        $result['allPrivList'] = isset($groupInstance->view->allPrivList) ? count($groupInstance->view->allPrivList) : 0;
        $result['selectedPrivList'] = isset($groupInstance->view->selectedPrivList) ? count($groupInstance->view->selectedPrivList) : 0;
        
        return $result;
    }

    /**
     * 测试managePrivByModule方法。
     * Test managePrivByModule method.
     *
     * @access public
     * @return array
     */
    public function managePrivByModuleTest()
    {
        ob_start();
        $method = $this->groupZenTest->getMethod('managePrivByModule');
        $groupInstance = $this->groupZenTest->newInstance();
        
        $method->invoke($groupInstance);
        ob_end_clean();
        
        $result = array();
        $result['title'] = isset($groupInstance->view->title) ? 'string' : 'null';
        $result['groups'] = isset($groupInstance->view->groups) ? count($groupInstance->view->groups) : 0;
        $result['subsets'] = isset($groupInstance->view->subsets) ? count($groupInstance->view->subsets) : 0;
        $result['packages'] = isset($groupInstance->view->packages) ? count($groupInstance->view->packages) : 0;
        $result['privs'] = isset($groupInstance->view->privs) ? count($groupInstance->view->privs) : 0;
        
        return $result;
    }

    /**
     * 测试buildUpdateViewForm方法。
     * Test buildUpdateViewForm method.
     *
     * @param array $actions
     * @param bool  $actionallchecker
     * @access public
     * @return array
     */
    public function buildUpdateViewFormTest($actions = array(), $actionallchecker = false)
    {
        $_POST['actions'] = $actions;
        if($actionallchecker) $_POST['actionallchecker'] = 1;
        
        $method = $this->groupZenTest->getMethod('buildUpdateViewForm');
        $groupInstance = $this->groupZenTest->newInstance();
        
        $result = $method->invoke($groupInstance);
        
        unset($_POST['actions']);
        unset($_POST['actionallchecker']);
        
        return $result;
    }

    /**
     * 测试buildProjectAdminForm方法。
     * Test buildProjectAdminForm method.
     *
     * @param array $postData
     * @access public
     * @return array
     */
    public function buildProjectAdminFormTest($postData = array())
    {
        foreach($postData as $key => $value)
        {
            $_POST[$key] = $value;
        }
        
        $method = $this->groupZenTest->getMethod('buildProjectAdminForm');
        $groupInstance = $this->groupZenTest->newInstance();
        
        $result = $method->invoke($groupInstance);
        
        foreach($postData as $key => $value)
        {
            unset($_POST[$key]);
        }
        
        return $result;
    }

    /**
     * 测试getNavGroup方法。
     * Test getNavGroup method.
     *
     * @access public
     * @return array
     */
    public function getNavGroupTest()
    {
        $method = $this->groupZenTest->getMethod('getNavGroup');
        $groupInstance = $this->groupZenTest->newInstance();
        
        $result = $method->invoke($groupInstance);
        
        return $result;
    }
}
