<?php
/**
 * 按全局统计的年度关闭项目数。
 * count_of_annual_closed_project.
 *
 * 范围：global
 * 对象：project
 * 目的：scale
 * 度量名称：按全局统计的年度关闭项目数
 * 单位：个
 * 描述：按全局统计的年度关闭项目数是指在某年度完成并关闭的项目数量。这个度量项可以帮助团队了解某年度项目的执行情况和成果，并进行项目交付能力的评估。较高的年度关闭项目数表明团队在项目交付方面具有较高的效率。
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
class count_of_annual_closed_project extends baseCalc
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