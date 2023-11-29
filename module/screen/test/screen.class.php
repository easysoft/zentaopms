<?php
class screenTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('screen');
    }

    /**
     * 测试getList。
     * Test getList.
     *
     * @param  int   $dimensionID 维度ID。
     * @return array
     */
    public function getListTest(int $dimensionID): array
    {
        return $this->objectModel->getList($dimensionID);
    }
}
