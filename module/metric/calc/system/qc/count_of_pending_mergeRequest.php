<?php
/**
 * 按系统统计代码库中待处理的合并请求总数。
 * Count of pending mergeRequest.
 *
 * 范围：system
 * 对象：codebase
 * 目的：qc
 * 度量名称：按系统统计代码库中待处理的合并请求总数
 * 单位：无
 * 描述：系统统计的待处理的合并请求总数是指代码库中等待合并的合并请求总数量，它反映了团队在合并代码方面的效率和进展情况，高数量可能意味着合并困难、合并冲突多、代码质量低等问题存在，需及时关注和处理以提升研发效能。
 * 定义：所有代码库的未关闭的合并请求个数求和;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_pending_mergeRequest extends baseCalc
{
    public $dataset = 'getMRs';

    public $fieldList = array('t1.status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status != 'closed') $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
