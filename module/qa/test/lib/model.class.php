<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class qaModelTest extends baseTest
{
    protected $moduleName = 'qa';
    protected $className  = 'model';

    /**
     * Test setMenu method.
     *
     * @param  int         $productID
     * @param  int|string  $branch
     * @access public
     * @return mixed
     */
    public function setMenuTest($productID = 0, $branch = '')
    {
        $result = $this->instance->setMenu($productID, $branch);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}