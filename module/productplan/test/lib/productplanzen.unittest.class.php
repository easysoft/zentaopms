<?php
declare(strict_types = 1);
class productplanZenTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('productplan');
        $this->objectZen   = $tester->loadZen('productplan');
    }

    /**
     * Test buildPlansForBatchEdit method.
     *
     * @access public
     * @return mixed
     */
    public function buildPlansForBatchEditTest()
    {
        $result = $this->objectZen->buildPlansForBatchEdit();
        if(dao::isError()) return dao::getError();

        return $result;
    }
}