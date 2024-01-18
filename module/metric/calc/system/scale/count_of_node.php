<?php
/**
 * 按系统统计节点总数。
 * Count of node.
 *
 * 范围：system
 * 对象：node
 * 目的：scale
 * 度量名称：按系统统计节点总数
 * 单位：无
 * 描述：按系统统计的节点总数是指在禅道DevOps平台中使用的全部节点总数。
 * 定义：所有节点的个数求和;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_node extends baseCalc
{
    public $dataset = 'getZaNodes';

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
