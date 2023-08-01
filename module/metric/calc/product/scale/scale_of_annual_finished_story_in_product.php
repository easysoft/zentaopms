<?php
/**
 * 按产品统计的年度已完成研发需求规模数。
 * Scale of annual finished story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的年度已完成研发需求规模数
 * 单位：sp/工时/功能点
 * 描述：按产品统计的已完成研发需求规模数表示已经完成的研发需求的规模总数。该度量项反映了产品已经完成的研发需求的规模总数，可以用于评估产品团队的研发需求规模管理和成果。
 * 定义：产品中关闭时间在某年且关闭原因为已完成的研发需求的规模数求和;过滤已删除的研发需求;过滤已删除的产品;
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
class scale_of_annual_finished_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't1.closedDate', 't1.closedReason', 't1.estimate');

    public $result = array();

    public function calculate($data)
    {
        $product      = $data->product;
        $closedDate   = $data->closedDate;
        $closedReason = $data->closedReason;
        $estimate     = $data->estimate;

        if(empty($closedDate)) return false;

        $year  = substr($closedDate, 0, 4);

        if($year == '0000') return false;

        if(!isset($this->result[$product])) $this->result[$product] = array();
        if(!isset($this->result[$product][$year])) $this->result[$product][$year] = 0;

        if($closedReason == 'done') $this->result[$product][$year] += $estimate;
    }

    public function getResult($options = null)
    {
        $records = array();
        foreach($this->result as $product => $years)
        {
            foreach($years as $year => $value)
            {
                $records[] = array('product' => $product, 'year' => $year, 'value' => $value);
            }
        }

        return $this->filterByOptions($records, $options);
    }
}
