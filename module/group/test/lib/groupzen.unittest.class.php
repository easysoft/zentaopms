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
}
