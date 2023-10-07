<?php
/**
 * 按系统统计的产品线总数。
 * Count of line.
 *
 * 范围：system
 * 对象：line
 * 目的：scale
 * 度量名称：按系统统计的产品线总数
 * 单位：个
 * 描述：按系统统计的产品线总数反映了组织中产品线的数量和广度，用于评估组织的产品组合策略和业务发展方向。
 * 定义：所有产品线的个数求和;过滤已删除的产品线;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_line extends baseCalc
{
    public $dataset = 'getLines';

    public $fieldList = array('t1.id');

    public $result = 0;

    public function calculate($row)
    {
        $this->result ++;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
