<?php
/**
 * 按系统统计的年度新增发布数。
 * Count of annual created release.
 *
 * 范围：system
 * 对象：release
 * 目的：scale
 * 度量名称：按系统统计的年度新增发布数
 * 单位：个
 * 描述：按系统统计的年度新增产品发布数量反映了组织在特定年度内新增发布的产品数量，用于评估组织的出汗品的创新能力、市场竞争力，以及业务增长和收益潜力。
 * 定义：所有的发布个数求和;发布时间为某年;过滤已删除的发布;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_release extends baseCalc
{
    public $dataset = "getReleases";

    public $fieldList = array('t1.date');

    public $result = array();

    public function calculate($data)
    {
        if(empty($data->date)) return false;

        $date = $data->date;

        $year  = substr($date, 0, 4);

        if($year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = 0;

        $this->result[$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $value)
        {
            $records[] = array('year' => $year, 'value' => $value);
        }
        return $this->filterByOptions($records, $options);
    }
}
