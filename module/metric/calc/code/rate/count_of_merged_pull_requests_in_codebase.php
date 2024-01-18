<?php
/**
 * 按代码库统计合并请求合并次数。
 * Count of merged pull requests in codebase.
 *
 * 范围：code
 * 对象：mergeRequest
 * 目的：rate
 * 度量名称：按代码库统计合并请求合并次数
 * 单位：个
 * 描述：按代码库统计的合并请求合并次数是指在代码库中完成的全部合并请求的数量。该度量项反映了代码库的合并流程和代码合并质量，有助于评估团队的协作效率和代码更新情况。
 * 定义：代码库中合并请求合并次数相加。;不包含已删除。;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_merged_pull_requests_in_codebase extends baseCalc
{
    public $dataset = 'getMRs';

    public $fieldList = array('t2.id');

    public $result = array();

    public function calculate($row)
    {
        $codebase = $row->id;
        if(!isset($this->result[$codebase])) $this->result[$codebase] = 0;
        $this->result[$codebase] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('code', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
