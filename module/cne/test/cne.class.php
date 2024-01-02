<?php
declare(strict_types=1);
/**
 * The test class file of cne module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     cne
 * @link        https://www.zentao.net
 */
class cneTest
{
    private $objectModel;

    public function __construct()
    {
        su('admin');

        global $tester, $config;
        $config->CNE->api->host   = 'http://10.0.7.210:32380';
        $config->CNE->api->token  = 'JMz7HCoQ3WHoYbpNyYNpvMfHqde9ugtV';
        $config->CNE->app->domain = 'dops.corp.cc';

        $this->objectModel = $tester->loadModel('cne');
    }

    /**
     * Test upgradeToVersion method.
     *
     * @param  int $instanceID
     * @param  string $version
     * @access public
     * @return bool
     */
    public function upgradeToVersionTest(int $instanceID, string $version): bool
    {
        $instance = $this->objectModel->loadModel('instance')->getByID($instanceID);
        return $this->objectModel->upgradeToVersion($instance, $version);
    }
}
