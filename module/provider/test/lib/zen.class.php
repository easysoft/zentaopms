<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class providerHttpClientStub
{
    public array $responses;
    public array $requestErrors;
    public array $requests = array();

    public function __construct(array $responses = array(), array $requestErrors = array())
    {
        $this->responses     = $responses;
        $this->requestErrors = $requestErrors;
    }

    public function request(string $url, mixed $data = null, array $options = array(), array $headers = array(), string $dataType = 'data', string $method = 'POST', int $timeout = 30, bool $httpCode = false, bool $log = true): string|array|bool
    {
        $response = zget($this->responses, $url, null);

        commonModel::$requestErrors = zget($this->requestErrors, $url, array());
        $this->requests[] = array('url' => $url, 'headers' => $headers, 'method' => $method, 'httpCode' => $httpCode);

        if($response !== null)
        {
            if($httpCode || !is_array($response)) return $response;

            return (string)zget($response, 'response', zget($response, 'body', zget($response, 0, '')));
        }

        return $httpCode ? array('', 404, 'body' => '{"message":"Not Found"}', 'header' => array(), 'errno' => 0, 'info' => array(), 'response' => '') : '{"message":"Not Found"}';
    }
}

class providerZenTest extends baseTest
{
    protected $moduleName = 'provider';
    protected $className  = 'zen';
    protected ReflectionClass $providerZenReflection;

    public function __construct()
    {
        global $app;

        $app->setModuleName($this->moduleName);
        require_once $app->getModulePath('', $this->moduleName) . 'control.php';
        require_once $app->getModulePath('', $this->moduleName) . 'zen.php';

        $this->providerZenReflection = new ReflectionClass('providerZen');
    }

    protected function getZenInstance(): object
    {
        global $tester;

        $tester->app->loadLang($this->moduleName);
        $instance = $this->providerZenReflection->newInstanceWithoutConstructor();

        $this->setPropertyValue($instance, 'app', $tester->app);
        $this->setPropertyValue($instance, 'config', $tester->config);
        $this->setPropertyValue($instance, 'lang', $tester->app->lang);

        return $instance;
    }

    protected function setPropertyValue(object $instance, string $property, mixed $value): void
    {
        $reflection = $this->providerZenReflection;
        while($reflection)
        {
            if($reflection->hasProperty($property))
            {
                $propertyReflection = $reflection->getProperty($property);
                $propertyReflection->setAccessible(true);
                $propertyReflection->setValue($instance, $value);
                return;
            }

            $reflection = $reflection->getParentClass();
        }
    }

    /**
     * Test checkServiceUrl method.
     *
     * @param  object $provider
     * @param  array  $responses
     * @param  array  $requestErrors
     * @access public
     * @return object|array
     */
    public function checkServiceUrlTest(object $provider, array $responses = array(), array $requestErrors = array()): object|array
    {
        $httpClient       = new providerHttpClientStub($responses, $requestErrors);
        $instance         = $this->getZenInstance();
        $method           = $this->providerZenReflection->getMethod('checkServiceUrl');
        $oldHttpClient    = common::$httpClient;
        $oldRequestErrors = commonModel::$requestErrors;

        $method->setAccessible(true);
        common::$httpClient      = $httpClient;
        commonModel::$requestErrors = array();
        dao::$errors = array();

        $result = $method->invoke($instance, $provider);
        $errors = dao::getError();

        common::$httpClient         = $oldHttpClient;
        commonModel::$requestErrors = $oldRequestErrors;

        if($errors) return $errors;

        $checkResult = new stdclass();
        $checkResult->result        = $result;
        $checkResult->requestCount  = count($httpClient->requests);
        $checkResult->requestUrl    = $httpClient->requests[0]['url'] ?? '';
        $checkResult->requestHeader = isset($httpClient->requests[0]['headers']) ? implode(',', $httpClient->requests[0]['headers']) : '';

        return $checkResult;
    }
}
