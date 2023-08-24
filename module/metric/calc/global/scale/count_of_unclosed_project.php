<?php
/**
 * 按全局统计的未关闭项目数。
 * Count of unclosed project.
 *
 * 范围：global
 * 对象：project
 * 目的：scale
 * 度量名称：按全局统计的未关闭项目数
 * 单位：个
 * 描述：按全局统计的未关闭项目数是指在特定时间范围内未开始或仍然在进行中的项目数量。这个度量项可以衡量项目管理和执行的效率。
 * 定义：复用：;按全局统计的已关闭项目数;按全局统计的项目总数;公式：;按全局统计的未关闭项目数=按全局统计的项目总数-按全局统计的已关闭项目数;
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
class count_of_unclosed_project extends baseCalc
{
    public $dataset = 'getAllProjects';

    public $fieldList = array('t1.status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status !== 'closed') $this->result ++;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
