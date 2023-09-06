<?php
/**
 * 按全局统计的已关闭研发需求数。
 * Count of closed story.
 *
 * 范围：global
 * 对象：story
 * 目的：scale
 * 度量名称：按全局统计的已关闭研发需求数
 * 单位：个
 * 描述：按全局统计的已关闭研发需求数表示已经关闭的研发需求的数量。该度量项反映了组织中已经关闭的研发需求的数量，可以用于评估组织的研发需求管理绩效和成果。
 * 定义：所有的研发需求个数求和;状态为已关闭;过滤已删除的研发需求;
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
