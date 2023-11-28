<?php
/**
 * 按系统统计的已关闭研发需求数。
 * Count of closed story.
 *
 * 范围：system
 * 对象：story
 * 目的：scale
 * 度量名称：按系统统计的已关闭研发需求数
 * 单位：个
 * 描述：按系统统计的已关闭的产品研发需求数量反映了组织在特定时间段内已经关闭的产品研发需求数量，用于评估组织的研发决策效果、优化资源管理和提供绩效评估和成果。
 * 定义：所有的研发需求个数求和;状态为已关闭;过滤已删除的研发需求;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_closed_story extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.status');

    public $result = 0;

    public function calculate($data)
    {
        $status = $data->status;
        if($status == 'closed') $this->result ++;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
