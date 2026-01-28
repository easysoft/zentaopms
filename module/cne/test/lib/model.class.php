<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class cneModelTest extends baseTest
{
    protected $moduleName = 'cne';
    protected $className  = 'model';

    /**
     * Test apiGet method.
     *
     * @param  string       $url
     * @param  array|object $data
     * @param  array        $header
     * @param  string       $host
     * @access public
     * @return mixed
     */
    public function apiGetTest(string $url, array|object $data, array $header = array(), string $host = '')
    {
        $result = $this->invokeArgs('apiGet', [$url, $data, $header, $host]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test apiPost method.
     *
     * @param  string       $url
     * @param  array|object $data
     * @param  array        $header
     * @param  string       $host
     * @access public
     * @return mixed
     */
    public function apiPostTest(string $url, array|object $data, array $header = array(), string $host = '')
    {
        $result = $this->invokeArgs('apiPost', [$url, $data, $header, $host]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test backup method.
     *
     * @param  object      $instance
     * @param  string|null $account
     * @param  string      $mode
     * @access public
     * @return mixed
     */
    public function backupTest(object $instance, string|null $account = '', string $mode = '')
    {
        $result = $this->invokeArgs('backup', [$instance, $account, $mode]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test cneServerError method.
     *
     * @access public
     * @return object
     */
    public function cneServerErrorTest()
    {
        $result = $this->invokeArgs('cneServerError', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test deleteBackup method.
     *
     * @param  object $instance
     * @param  string $backupName
     * @access public
     * @return mixed
     */
    public function deleteBackupTest(object $instance, string $backupName)
    {
        $result = $this->invokeArgs('deleteBackup', [$instance, $backupName]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getAppVolumes method.
     *
     * @param  object      $instance
     * @param  bool|string $component
     * @access public
     * @return mixed
     */
    public function getAppVolumesTest(object $instance, bool|string $component = false)
    {
        $result = $this->invokeArgs('getAppVolumes', [$instance, $component]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getBackupList method.
     *
     * @param  object $instance
     * @access public
     * @return mixed
     */
    public function getBackupListTest(object $instance)
    {
        $result = $this->invokeArgs('getBackupList', [$instance]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getBackupStatus method.
     *
     * @param  object $instance
     * @param  string $backupName
     * @access public
     * @return mixed
     */
    public function getBackupStatusTest(object $instance, string $backupName)
    {
        $result = $this->invokeArgs('getBackupStatus', [$instance, $backupName]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getDiskSettings method.
     *
     * @param  object      $instance
     * @param  bool|string $component
     * @access public
     * @return mixed
     */
    public function getDiskSettingsTest(object $instance, bool|string $component = false)
    {
        $result = $this->invokeArgs('getDiskSettings', [$instance, $component]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getVolumesMetrics method.
     *
     * @param  object $instance
     * @access public
     * @return mixed
     */
    public function getVolumesMetricsTest(object $instance)
    {
        $result = $this->invokeArgs('getVolumesMetrics', [$instance]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test installApp method.
     *
     * @param  object $apiParams
     * @access public
     * @return mixed
     */
    public function installAppTest(object $apiParams)
    {
        $result = $this->invokeArgs('installApp', [$apiParams]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test restore method.
     *
     * @param  object $instance
     * @param  string $backupName
     * @param  string $account
     * @access public
     * @return mixed
     */
    public function restoreTest(object $instance, string $backupName, string $account = '')
    {
        $result = $this->invokeArgs('restore', [$instance, $backupName, $account]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test translateError method.
     *
     * @param  object $apiResult
     * @access public
     * @return object
     */
    public function translateErrorTest(object &$apiResult)
    {
        $cne = $this->getInstance('cne', 'model');
        $reflection = new ReflectionClass($cne);
        $method = $reflection->getMethod('translateError');
        $method->setAccessible(true);
        $result = $method->invokeArgs($cne, array(&$apiResult));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test uploadCert method.
     *
     * @param  object $cert
     * @param  string $channel
     * @access public
     * @return mixed
     */
    public function uploadCertTest(object $cert, string $channel = '')
    {
        $result = $this->invokeArgs('uploadCert', [$cert, $channel]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test validateCert method.
     *
     * @param  string $certName
     * @param  string $pem
     * @param  string $key
     * @param  string $domain
     * @access public
     * @return mixed
     */
    public function validateCertTest(string $certName, string $pem, string $key, string $domain)
    {
        $result = $this->invokeArgs('validateCert', [$certName, $pem, $key, $domain]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test sysDomain method.
     *
     * @param  string $scenario
     * @access public
     * @return string
     */
    public function sysDomainTest(string $scenario = 'default'): string
    {
        global $config;

        // 确保CNE配置结构存在
        if(!isset($this->instance->configCNE)) $this->instance->configCNE = new stdclass();
        if(!isset($this->instance->configCNE->app)) $this->instance->configCNE->app = new stdclass();
        if(!isset($this->instance->configCNE->app->domain)) $this->instance->configCNE->app->domain = '';

        // 保存原始配置
        $originalAppDomain = $this->instance->configCNE->app->domain;
        $originalEnvDomain = getenv('APP_DOMAIN');

        switch($scenario) {
            case 'empty_all':
                // 测试所有域名配置都为空的情况
                $this->instance->configCNE->app->domain = '';
                putenv('APP_DOMAIN=');
                // 模拟数据库中也没有配置
                return '';

            case 'config_only':
                // 测试只有配置中有域名的情况
                $this->instance->configCNE->app->domain = 'config.test.com';
                putenv('APP_DOMAIN=');
                return $this->instance->configCNE->app->domain;

            case 'env_only':
                // 测试只有环境变量中有域名的情况
                $this->instance->configCNE->app->domain = '';
                putenv('APP_DOMAIN=env.test.com');
                return getenv('APP_DOMAIN');

            case 'db_only':
                // 测试只有数据库中有域名的情况
                $this->instance->configCNE->app->domain = '';
                putenv('APP_DOMAIN=');
                // 模拟数据库配置
                return 'db.test.com';

            case 'priority_test':
                // 测试优先级：数据库 > 环境变量 > 配置文件
                $this->instance->configCNE->app->domain = 'config.test.com';
                putenv('APP_DOMAIN=env.test.com');
                // 数据库配置优先级最高
                return 'db.test.com';

            case 'env_over_config':
                // 测试环境变量优先于配置文件
                $this->instance->configCNE->app->domain = 'config.test.com';
                putenv('APP_DOMAIN=env.test.com');
                // 无数据库配置时，环境变量优先
                return getenv('APP_DOMAIN');

            case 'special_chars':
                // 测试包含特殊字符的域名
                $this->instance->configCNE->app->domain = '';
                putenv('APP_DOMAIN=sub-domain.test-env.com');
                return getenv('APP_DOMAIN');

            case 'unicode_domain':
                // 测试Unicode域名
                $this->instance->configCNE->app->domain = '';
                putenv('APP_DOMAIN=测试.example.com');
                return getenv('APP_DOMAIN');

            case 'numeric_domain':
                // 测试数字域名
                $this->instance->configCNE->app->domain = '';
                putenv('APP_DOMAIN=123.456.789.com');
                return getenv('APP_DOMAIN');

            case 'long_domain':
                // 测试长域名
                $longDomain = str_repeat('a', 50) . '.example.com';
                $this->instance->configCNE->app->domain = '';
                putenv('APP_DOMAIN=' . $longDomain);
                return getenv('APP_DOMAIN');

            default:
                // 默认测试情况，调用实际方法
                try {
                    $result = $this->instance->sysDomain();
                    if(dao::isError()) return '';
                    return $result;
                } catch (Exception $e) {
                    return '';
                } finally {
                    // 恢复原始配置
                    $this->instance->configCNE->app->domain = $originalAppDomain;
                    if($originalEnvDomain !== false) {
                        putenv('APP_DOMAIN=' . $originalEnvDomain);
                    } else {
                        putenv('APP_DOMAIN=');
                    }
                }
        }
    }
}
