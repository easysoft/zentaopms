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
     * Test updateConfig method.
     *
     * @param  string $version
     * @param  bool   $restart
     * @param  array  $snippets
     * @param  object $maps
     * @access public
     * @return bool|object
     */
    public function updateConfigTest(string|null $version = null, bool|null $restart = null, array|null $snippets = null, object|null $maps = null): bool|object
    {
        $this->objectModel->error = new stdclass();
        $instance = $this->objectModel->loadModel('instance')->getByID(2);
        if(!is_null($version)) $instance->version = $version;

        $settings = new stdclass();
        if(!is_null($restart)) $settings->force_restart = $restart;
        if(!is_null($snippets)) $settings->settings_snippets = $snippets;
        if(!is_null($maps)) $settings->settings_map = $maps;
        $result = $this->objectModel->updateConfig($instance, $settings);
        if(!empty($this->objectModel->error->message)) return $this->objectModel->error;

        return $result;
    }

    /**
     * Test certInfo method.
     *
     * @param  string $certName
     * @access public
     * @return object
     */
    public function certInfoTest(string $certName): ?object
    {
        return $this->objectModel->certInfo($certName);
    }

    /**
     * Test getDefaultAccount method.
     *
     * @param  string $component
     * @access public
     * @return object|null
     */
    public function getDefaultAccountTest(string $component = ''): object|null
    {
        $this->objectModel->error = new stdclass();
        $instance = $this->objectModel->loadModel('instance')->getByID(2);

        $result = $this->objectModel->getDefaultAccount($instance, $component);
        if(!empty($this->objectModel->error->message)) return $this->objectModel->error;

        return $result;
    }

    /**
     * Test getDomain method.
     *
     * @param  string $component
     * @access public
     * @return object|null
     */
    public function getDomainTest(string $component = ''): object|null
    {
        $this->objectModel->error = new stdclass();
        $instance = $this->objectModel->loadModel('instance')->getByID(2);

        $result = $this->objectModel->getDomain($instance, $component);
        if(!empty($this->objectModel->error->message)) return $this->objectModel->error;

        return $result;
    }

    /**
     * Test instancesMetrics method.
     *
     * @access public
     * @return array
     */
    public function instancesMetricsTest(): array
    {
        $this->objectModel->error = new stdclass();
        $instances = $this->objectModel->loadModel('instance')->getList();

        return $this->objectModel->instancesMetrics($instances);
    }

    /**
     * Test startApp method.
     *
     * @access public
     * @return object|null
     */
    public function startAppTest(): object|null
    {
        $this->objectModel->error = new stdclass();
        $instance = $this->objectModel->loadModel('instance')->getByID(2);

        $apiParams = new stdclass();
        $apiParams->cluster   = '';
        $apiParams->name      = $instance->k8name;
        $apiParams->chart     = $instance->chart;
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->channel   = $instance->channel;
        $result = $this->objectModel->startApp($apiParams);
        if(!empty($this->objectModel->error->message)) return $this->objectModel->error;

        return $result;
    }

    /**
     * Test stopApp method.
     *
     * @access public
     * @return object|null
     */
    public function stopAppTest(): object|null
    {
        $this->objectModel->error = new stdclass();
        $instance = $this->objectModel->loadModel('instance')->getByID(2);

        $apiParams = new stdclass();
        $apiParams->cluster   = '';
        $apiParams->name      = $instance->k8name;
        $apiParams->chart     = $instance->chart;
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->channel   = $instance->channel;
        $result = $this->objectModel->stopApp($apiParams);
        if(!empty($this->objectModel->error->message)) return $this->objectModel->error;

         $this->objectModel->startApp($apiParams);
        return $result;
    }
}
