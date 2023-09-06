<?php
/**
 * 按全局统计的未关闭研发需求数。
 * Count of unclosed story.
 *
 * 范围：global
 * 对象：story
 * 目的：scale
 * 度量名称：按全局统计的未关闭研发需求数
 * 单位：个
 * 描述：按全局统计的未关闭研发需求数表示尚未完成的研发需求的数量。该度量项反映了组织中尚未完成并关闭的研发需求的数量，可以用于评估组织的研发需求执行进展和挑战。
 * 定义：复用：;按全局统计的研发需求总数;按全局统计的已关闭研发需求数;公式：按全局统计的未关闭研发需求数=按全局统计的研发需求总数-按全局统计的已关闭研发需求数;
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
