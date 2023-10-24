<?php
/**
 * 按系统统计的已交付研发需求数。
 * Count of delivered story.
 *
 * 范围：system
 * 对象：story
 * 目的：scale
 * 度量名称：按系统统计的已交付研发需求数
 * 单位：个
 * 描述：按系统统计的已交付的产品研发需求数量反映了组织在特定时间段内已交付的产品研发需求数量，用于评估组织的交付能力、项目执行效率、产品质量和客户满意度。
 * 定义：所有的研发需求个数求和;阶段为已发布或关闭原因为已完成;过滤已删除的研发需求;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_delivered_story extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.stage', 't1.closedReason');

    public $result = 0;

    public function calculate($data)
    {
        $stage        = $data->stage;
        $closedReason = $data->closedReason;

        if($stage == 'released' || $closedReason == 'done') $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
