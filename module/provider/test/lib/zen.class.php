<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class providerZenTest extends baseTest
{
    protected $moduleName = 'provider';
    protected $className  = 'zen';

    /**
     * Test checkServiceUrl method.
     *
     * @param  object $provider
     * @access public
     * @return bool|array
     */
    public function checkServiceUrlTest(object $provider): bool|array
    {
        $result = $this->invokeArgs('checkServiceUrl', array($provider));
        $errors = dao::getError();

        if($errors) return $errors;
        return $result;
    }

    /**
     * Test getCheckApiUrl method.
     *
     * @param  string $type
     * @param  string $url
     * @access public
     * @return string
     */
    public function getCheckApiUrlTest(string $type, string $url): string
    {
        return $this->invokeArgs('getCheckApiUrl', array($type, $url));
    }

    /**
     * Test getCheckHeaders method.
     *
     * @param  string $type
     * @param  string $token
     * @access public
     * @return array
     */
    public function getCheckHeadersTest(string $type, string $token): array
    {
        return $this->invokeArgs('getCheckHeaders', array($type, $token));
    }
}
