<?php
/**
 * 按系统统计的研发需求总数。
 * Count of story.
 *
 * 范围：system
 * 对象：story
 * 目的：scale
 * 度量名称：按系统统计的研发需求总数
 * 单位：个
 * 描述：按系统统计的研发需求的数量反映了组织在特定时间段内的研发需求数量，用于评估组织的研发投入、技术创新能力和市场竞争力，并提供绩效评估。
 * 定义：所有的研发需求个数求和;过滤已删除的研发需求;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_story extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.id');

    public $result = 0;

    public function calculate($data)
    {
        $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
