<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class bugZenTest extends baseTest
{
    protected $moduleName = 'bug';
    protected $className  = 'zen';

    /**
     * Test afterBatchCreate method.
     *
     * @param  object $bug
     * @param  array  $output
     * @access public
     * @return bool
     */
    public function afterBatchCreateTest(object $bug, array $output = array()): bool
    {
        $result = $this->invokeArgs('afterBatchCreate', [$bug, $output]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test afterCreate method.
     *
     * @param  object $bug
     * @param  array  $params
     * @param  string $from
     * @access public
     * @return bool
     */
    public function afterCreateTest(object $bug, array $params = array(), string $from = ''): bool
    {
        $result = $this->invokeArgs('afterCreate', [$bug, $params, $from]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test afterUpdate method.
     *
     * @param  object $bug
     * @param  object $oldBug
     * @access public
     * @return bool
     */
    public function afterUpdateTest(object $bug, object $oldBug): bool
    {
        $result = $this->invokeArgs('afterUpdate', [$bug, $oldBug]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test assignBatchCreateVars method.
     *
     * @param  int    $executionID
     * @param  object $product
     * @param  string $branch
     * @param  array  $output
     * @param  array  $bugImagesFile
     * @access public
     * @return bool
     */
    public function assignBatchCreateVarsTest(int $executionID, object $product, string $branch = '0', array $output = array(), array $bugImagesFile = array()): bool
    {
        $this->invokeArgs('assignBatchCreateVars', [$executionID, $product, $branch, $output, $bugImagesFile]);
        if(dao::isError()) return false;
        return true;
    }

    /**
     * Test assignBatchEditVars method.
     *
     * @param  int    $productID
     * @param  string $branch
     * @access public
     * @return bool
     */
    public function assignBatchEditVarsTest(int $productID, string $branch): bool
    {
        ob_start();
        $this->invokeArgs('assignBatchEditVars', [$productID, $branch]);
        ob_end_clean();
        if(dao::isError()) return false;
        return true;
    }
}
