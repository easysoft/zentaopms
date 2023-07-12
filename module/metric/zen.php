<?php
declare(strict_types=1);
/**
 * The zen file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin <zhouxin@easysoft.ltd>
 * @package     metric
 * @link        https://www.zentao.net
 */
class metricZen extends metric
{
    /**
     * 根据分类后的度量项，准备数据源句柄。
     * Prepare the data source handle based on the classified measures.
     *
     * @param  object    $classifiedCalc
     * @param  object    $dataset
     * @access protected
     * @return object
     */
    protected function prepareDataset($calcGroup)
    {
        $dataSource = $calcGroup->dataset;
        $calcList   = $calcGroup->clacList;

        if(empty($dataSource))
        {
            $calc = current($calcList);
            $calc->setDAO($this->dao);

            return $calc->getStatement();
        }

        $dataset   = $this->metric->getDataset();
        $fieldList = $this->metric->uniteFieldList($calcList);

        return $dataset->$dataSource($fieldList);
    }

    /**
     * 根据度量项计算的结果，构建可插入表的度量数据。
     * Build measurements that can be inserted into tables based on the results of the measurements computed.
     *
     * @param  array    $calcList
     * @access protected
     * @return array
     */
    protected function prepareMetricRecord($calcList)
    {
        $records = array();
        foreach($calcList as $code => $calc)
        {
            $calcResult = $calc->getResult();
            if($records) continue;

            foreach($calcResult as $record)
            {
                $record->metricID   = $calc->id;
                $record->metricCode = $code;
                $record->date       = helper::today();
                $record->year       = date('Y');
                $record->month      = date('Ym');
                $record->week       = date('W');
                $record->day        = date('Ymd');

                $records[] = $record;
            }
        }

        return $records;
    }

    /**
     * 遍历数据，对每个度量项计算每一行数据。
     * Calculate metric for every row.
     *
     * @param  array    $rows
     * @param  array    $calcList
     * @access protected
     * @return void
     */
    protected function calcMetric($rows, $calcList)
    {
        foreach($rows as $row)
        {
            foreach($calcList as $calc)
            {
                $calc->calculate((object)$row);
            }
        }
    }
}
