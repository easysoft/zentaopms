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
        dao::$errors = array();
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

    /**
     * Test checkSubversionFilePath method.
     *
     * @access public
     * @return mixed
     */
    public function checkSubversionFilePathTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('checkSubversionFilePath', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test checkSubversionHttpUrl method.
     *
     * @access public
     * @return mixed
     */
    public function checkSubversionHttpUrlTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('checkSubversionHttpUrl', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test checkSubversionSocket method.
     *
     * @access public
     * @return mixed
     */
    public function checkSubversionSocketTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('checkSubversionSocket', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test checkSubversionUrl method.
     *
     * @access public
     * @return mixed
     */
    public function checkSubversionUrlTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('checkSubversionUrl', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test isAccessibleSubversionUrl method.
     *
     * @access public
     * @return mixed
     */
    public function isAccessibleSubversionUrlTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('isAccessibleSubversionUrl', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }


    /**
     * Test isValidSubversionUrl method.
     *
     * @access public
     * @return mixed
     */
    public function isValidSubversionUrlTest(...$args)
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('isValidSubversionUrl', $args);
            $echoed = ob_get_clean();
            if($echoed !== '') return 'echo_yes';
            if(dao::isError()) return 'daoError:' . json_encode(dao::getError(), JSON_UNESCAPED_UNICODE);
            return $result;
        }
        catch(Throwable $e)
        {
            if(ob_get_level()) ob_end_clean();
            if($e instanceof EndResponseException) return 0;
            return 'error:' . get_class($e);
        }
    }

}
