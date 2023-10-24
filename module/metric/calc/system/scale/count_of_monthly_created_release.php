<?php
/**
 * 按系统统计的月度新增发布数。
 * Count of monthly created release.
 *
 * 范围：system
 * 对象：release
 * 目的：scale
 * 度量名称：按系统统计的月度新增发布数
 * 单位：个
 * 描述：按系统统计的月度新增产品发布数量反映了组织在特定月份内新增发布的产品数量，用于评估组织的产品开发效率、市场适应能力和产品组合优化。
 * 定义：所有的发布个数求和;发布时间为某年某月;过滤已删除的发布;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_monthly_created_release extends baseCalc
{
    public $dataset = "getReleases";

    public $fieldList = array('t1.date');

    public $result = array();

    public function calculate($data)
    {
        if(empty($data->date)) return false;

        $date = $data->date;

        $year  = substr($date, 0, 4);
        $month = substr($date, 5, 2);

        if($year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$month])) $this->result[$year][$month] = 0;

        $this->result[$year][$month]  += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $months)
        {
            foreach($months as $month => $value)
            {
                $records[] = array('year' => $year, 'month' => $month, 'value' => $value);
            }
        }
        return $this->filterByOptions($records, $options);
    }
}
