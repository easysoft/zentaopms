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

    /**
     * Test parseZtfResult method.
     *
     * @param  object $post
     * @param  int    $taskID
     * @param  int    $productID
     * @param  int    $jobID
     * @param  int    $compileID
     * @access public
     * @return bool
     */
    public function parseZtfResultTest($post = null, $taskID = 0, $productID = 0, $jobID = 0, $compileID = 0)
    {
        $result = $this->invokeArgs('parseZtfResult', [$post, $taskID, $productID, $jobID, $compileID]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
