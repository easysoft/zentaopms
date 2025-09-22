<?php
declare(strict_types = 1);
class qaTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('qa');
    }

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
        $result = $this->objectModel->setMenu($productID, $branch);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}