<?php
/**
 * 按全局统计的未关闭一级项目集数。
 * Count of unclosed top program.
 *
 * 范围：global
 * 对象：program
 * 目的：scale
 * 度量名称：按全局统计的未关闭一级项目集数
 * 单位：个
 * 描述：按全局统计的未关闭一级项目集总数表示未关闭的一级项目集数量。此度量项反映了组织中未关闭的一级项目集数量，可以用于评估组织的项目集管理的进展和挑战。
 * 定义：复用：;按全局统计的一级项目集总数;按全局统计的已关闭一级项目集数;公式：按全局统计的未关闭一级项目集数=按全局统计的一级项目集总数-按全局统计的已关闭一级项目集数;
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_unclosed_top_program extends baseCalc
{
    public $dataset = 'getTopPrograms';

    public $fieldList = array('status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status != 'closed') $this->result ++;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
