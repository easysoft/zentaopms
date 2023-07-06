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
    public function execMetric()
    {
        $dataset         = $this->metric->getDataset($this->dao);
        $metricInstances = $this->metric->getInstanceList();

        list($otherInstances, $classifiedInstances) = $this->metric->classifyMetric($metricInstances);

        /* 计算根据数据源归类后的度量项。*/
        foreach($classifiedInstances as $dataSource => $instances)
        {
            $fieldList = $this->metric->uniteFieldList($instances);
            $rows = $dataset->$dataSource($fieldList)->fetchAll();

            foreach($rows as $row)
            {
                foreach($instances as $instance)
                {
                    $instance->calculate((object)$row);
                }
            }
        }

        /* 处理无法归类的度量项，使用句柄获取数据源。*/
        foreach($otherInstances as $instance)
        {
            $rows = $instance->getStatement($this->dao)->fetchAll();
            foreach($rows as $row) $instance->calculate((object)$row);
        }

        /* 获取度量项的计算结果并保存。*/
        foreach($metricInstances as $code => $metricObj)
        {
            $resultSet = $metricObj->getResult();
            if(empty($resultSet)) continue;
            foreach($resultSet as $result)
            {
                $record             = $result;
                $record->mid        = $metricObj->id;
                $record->metricCode = $code;
                $record->date       = helper::today();
                $record->year       = date('Y');
                $record->month      = date('Ym');
                $record->week       = date('W');
                $record->day        = date('Ymd');
                $this->dao->insert(TABLE_METRICRECORDS)->data($record)->exec();
            }
        }
    }
}
