<?php
declare(strict_types = 1);
class chartTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('chart');
        $this->objectTao   = $tester->loadTao('chart');
    }

    /**
     * Test getByID method.
     *
     * @param  int $chartID
     * @access public
     * @return object|false
     */
    public function getByIdTest(int $chartID): object|false
    {
        $result = $this->objectModel->getByID($chartID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test switchFieldName method.
     *
     * @param  array  $fields
     * @param  array  $langs
     * @param  array  $metrics
     * @param  string $index
     * @access public
     * @return string
     */
    public function switchFieldNameTest(array $fields, array $langs, array $metrics, string $index): string
    {
        $result = $this->objectTao->switchFieldName($fields, $langs, $metrics, $index);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test isChartHaveData method.
     *
     * @param  array  $options
     * @param  string $type
     * @access public
     * @return bool
     */
    public function isChartHaveDataTest(array $options, string $type): bool
    {
        $result = $this->objectModel->isChartHaveData($options, $type);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test isClickable method.
     *
     * @param  int    $chartID
     * @param  string $action
     * @access public
     * @return mixed
     */
    public function isClickableTest(int $chartID, string $action)
    {
        $chart  = $this->getByIdTest($chartID);
        $result = chartModel::isClickable($chart, $action);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
