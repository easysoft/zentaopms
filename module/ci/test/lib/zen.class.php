<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class ciZenTest extends baseTest
{
    protected $moduleName = 'ci';
    protected $className  = 'zen';

    /**
     * Test getProductIdAndJobID method.
     *
     * @param  array  $params
     * @param  object $post
     * @access public
     * @return array
     */
    public function getProductIdAndJobIDTest($params = array(), $post = null)
    {
        $result = $this->invokeArgs('getProductIdAndJobID', [$params, $post]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
