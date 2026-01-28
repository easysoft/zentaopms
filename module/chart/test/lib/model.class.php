<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class chartModelTest extends baseTest
{
    protected $moduleName = 'chart';
    protected $className  = 'model';

    /**
     * Test getByID method.
     *
     * @param  int $chartID
     * @access public
     * @return object|false
     */
    public function getByIdTest(int $chartID): object|false
    {
        $result = $this->instance->getByID($chartID);
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
        $result = $this->instance->isChartHaveData($options, $type);
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
        $chart = $this->getByIdTest($chartID);
        if(!$chart) return false;

        $result = chartModel::isClickable($chart, $action);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
