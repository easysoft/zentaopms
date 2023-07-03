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
    public function execMetric()
    {
        $dataset = $this->metric->getDataset();

        $metricInstances     = $this->metric->getInstanceList();
        $classifiedInstances = $this->metric->classifyMetric($metricInstances);

        foreach($classifiedInstances as $dataSource => $metricInstances)
        {
            $fieldList = $this->uniteFieldList($metricInstances);
            $data = $dataset->$dataSource($fieldList)->fetchAll();

            foreach($metricInstances as $metricInstance)
            {
                $value = $metricInstance->calculate($data);
            }
        }
    }
}
