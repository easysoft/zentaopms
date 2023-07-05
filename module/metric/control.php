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
        $dataset         = $this->metric->getDataset();
        $metricInstances = $this->metric->getInstanceList();

        list($otherInstances, $classifiedInstances) = $this->metric->classifyMetric($metricInstances);

        /* 计算根据数据源归类后的度量项。*/
        foreach($classifiedInstances as $dataSource => $metricInstances)
        {
            $fieldList = $this->uniteFieldList($metricInstances);
            $rows = $dataset->$dataSource($fieldList)->fetchAll();

            foreach($rows as $row)
            {
                foreach($metricInstances as $instance)
                {
                    $instance->calculate((object)$row);
                }
            }
        }

        /* 处理无法归类的度量项，直接赋值dao。*/
        foreach($otherInstances as $instance) $instance->dao = $this->dao;

        /* 获取度量项的计算结果并保存。*/
        foreach($metricInstances as $instance)
        {
            $resultSet = $instance->getResult();
            foreach($resultSet as $result)
            {
                $record = new stdclass();
                $record->value = $result;
            }
        }
    }
}
