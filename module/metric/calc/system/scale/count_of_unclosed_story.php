<?php
/**
 * 按系统统计的未关闭研发需求数。
 * Count of unclosed story.
 *
 * 范围：system
 * 对象：story
 * 目的：scale
 * 度量名称：按系统统计的未关闭研发需求数
 * 单位：个
 * 描述：按系统统计的未关闭的产品研发需求数量反映了组织在特定时间段内尚未关闭的产品研发需求数量，用于评估组织评估研发进度、需求管理和资源规划，并提供对需求可行性和商业价值的评估。
 * 定义：复用：;按系统统计的研发需求总数;按系统统计的已关闭研发需求数;公式：按系统统计的未关闭研发需求数=按系统统计的研发需求总数-按系统统计的已关闭研发需求数;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_unclosed_story extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.stage');

    public $result = 0;

    public function calculate($data)
    {
        $stage = $data->stage;
        if($stage != 'closed') $this->result ++;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
