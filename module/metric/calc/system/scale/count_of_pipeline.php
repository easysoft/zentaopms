<?php
/**
 * 按系统统计流水线总数。
 * Count of pipeline.
 *
 * 范围：system
 * 对象：pipeline
 * 目的：scale
 * 度量名称：按系统统计流水线总数
 * 单位：无
 * 描述：按系统统计的流水线总数是指系统中所有流水线的数量统计，它反映了项目或组织在软件开发和交付过程中采用自动化流程的程度。
 * 定义：所有流水线的个数求和;不统计已删除;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_pipeline extends baseCalc
{
    public $dataset = 'getPipeline';

    public $fieldList = array('t1.id');

    public $result = 0;

    public function calculate($row)
    {
        $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
