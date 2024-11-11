<?php
/**
 * 按系统统计的年度新增研发需求数。
 * Count of annual created story.
 *
 * 范围：system
 * 对象：story
 * 目的：scale
 * 度量名称：按系统统计的年度新增研发需求数
 * 单位：个
 * 描述：按系统统计的年度新增的产品研发需求数量反映了组织在每年新增的产品研发需求数量，用于评估组织的创新能力、需求发现和优先级制定、投资决策以及绩效评估与持续改进。
 * 定义：所有的研发需求个数求和;创建时间为某年;过滤已删除的研发需求;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_story extends baseCalc
{
    public $dataset = 'getAllDevStories';

    public $fieldList = array('t1.openedDate');

    public $result = array();

    public function calculate($data)
    {
        $openedDate = $data->openedDate;

        $year = $this->getYear($openedDate);
        if(!$year) return false;

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
