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
}
