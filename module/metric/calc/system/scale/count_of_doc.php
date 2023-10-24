<?php
/**
 * 按系统统计的文档总数。
 * Count of doc.
 *
 * 范围：system
 * 对象：doc
 * 目的：scale
 * 度量名称：按系统统计的文档总数
 * 单位：个
 * 描述：按系统统计的文档总数是指系统或组织中存在的所有文档数量的统计值。反映了整体文档管理的规模和复杂度。文档总数越大，代表着组织的信息量越丰富，也可能意味着需要更多的资源来维护和管理这些文档。
 * 定义：所有文档个数求和;过滤已删除的文档;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_doc extends baseCalc
{
    public $dataset = 'getDocs';

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
