<?php
/**
 * 按全局统计的未关闭项目数。
 * count_of_unclosed_project.
 *
 * 范围：global
 * 对象：project
 * 目的：scale
 * 度量名称：按全局统计的未关闭项目数
 * 单位：个
 * 描述：按全局统计的未关闭项目数是指在特定时间范围内未开始或仍然在进行中的项目数量。这个度量项可以衡量项目管理和执行的效率。
 * 定义：复用：
按全局统计的已关闭项目数
按全局统计的项目总数
公式：
按全局统计的未关闭项目数=按全局统计的项目总数-按全局统计的已关闭项目数
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_unclosed_project extends baseCalc
{
    public $dataset = null;

    public $fieldList = array();

    //public funtion getStatement($dao)
    //{
    //}

    public function calculate($data)
    {
    }

    public function getResult($options = array())
    {
        return $this->filterByOptions($this->result, $options);
    }
}