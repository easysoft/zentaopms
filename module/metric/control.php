<?php
/**
 * The control file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin <zhouxin@easycorp.ltd>
 * @package     metric
 * @version     $Id: control.php 5145 2013-07-15 06:47:26Z zhouxin@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class metric extends control
{
    /**
     * __construct.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Excute metric.
     *
     * @access public
     * @return void
     */
    public function updateMetricLib()
    {
        $dataset          = $this->metric->getDataset($this->dao);
        $calcInstanceList = $this->metric->getCalcInstanceList();

        list($otherCalcList, $classifiedCalcList) = $this->metric->classifyCalc($calcInstanceList);

        /* 计算根据数据源归类后的度量项。*/
        foreach($classifiedCalcList as $dataSource => $calcList)
        {
            $fieldList = $this->metric->uniteFieldList($calcList);
            $rows = $dataset->$dataSource($fieldList)->fetchAll();

            foreach($rows as $row)
            {
                foreach($calcList as $calc)
                {
                    $calc->calculate((object)$row);
                }
            }
        }

        /* 处理无法归类的度量项，使用句柄获取数据源。*/
        foreach($otherCalcList as $calc)
        {
            $rows = $calc->getStatement($this->dao)->fetchAll();
            foreach($rows as $row) $calc->calculate((object)$row);
        }

        /* 获取度量项的计算结果并保存。*/
        foreach($calcInstanceList as $code => $calc)
        {
            $rows = $calc->getResult();
            if(empty($rows)) continue;

            foreach($rows as $row)
            {
                $row->metricID   = $calc->id;
                $row->metricCode = $code;
                $row->date       = helper::today();
                $row->year       = date('Y');
                $row->month      = date('Ym');
                $row->week       = date('W');
                $row->day        = date('Ymd');
                $this->dao->insert(TABLE_METRICBASELIB)->data($row)->exec();
            }
        }
    }
}
