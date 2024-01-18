<?php
/**
 * 按代码库统计代码中待处理的问题总数。
 * Count of pending issues in codebase.
 *
 * 范围：code
 * 对象：issue
 * 目的：qc
 * 度量名称：按代码库统计代码中待处理的问题总数
 * 单位：个
 * 描述：按代码库统计的代码问题库存总数是指在代码库中记录的所有未解决的代码问题的总数，它反映了代码质量和稳定性。
 * 定义：代码库中所有未关闭的问题数量相加。;不包含已删除。;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_pending_issues_in_codebase extends baseCalc
{
    public $dataset = 'getRepoIssues';

    public $fieldList = array('t1.resolvedBy', 't1.repo');

    public $result = array();

    public function calculate($row)
    {
        if(empty($row->resolvedBy))
        {
            if(!isset($this->result[$row->repo])) $this->result[$row->repo] = 0;

            $this->result[$row->repo] ++;
        }
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $repoID => $count) $records[] = array('repo' => $repoID, 'value' => $count);
        return $this->filterByOptions($records, $options);
    }
}
