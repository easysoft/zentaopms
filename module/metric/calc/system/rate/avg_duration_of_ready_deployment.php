<?php
/**
 * 按系统统计的上线准备平均时长
 * Count of average preparation deployment.
 *
 * 范围：system
 * 对象：deployment
 * 目的：rate
 * 度量名称：按系统统计的上线准备平均时长
 * 单位：个
 * 描述：上线准备平均时长是衡量团队在软件发布过程中从上线申请提交到准备完成所需时间的重要指标。通过统计在一定时间范围内的上线准备耗时，团队能够评估其准备流程的效率，及时识别潜在的瓶颈并进行优化。
 * 定义：上线成功的上线申请耗时/ 上线成功申请数;不统计已删除上线申请;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    liyang <liyang@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class avg_duration_of_ready_deployment extends baseCalc
{
    public $dataset = 'getDeployment';

    public $fieldList = array('status', 'createdDate', 'end');

    public $result = array('preparation' => 0, 'count' => 0);

    public function calculate($row)
    {
        if($row->status == 'success')
        {
            $preparation = strtotime($row->end) - strtotime($row->createdDate);
            if($preparation > 0) $this->result['preparation'] = $this->result['preparation'] + $preparation;

            $this->result['count'] += 1;
        }
    }

    public function getResult($options = array())
    {
        $preparation = $this->result['preparation'];
        $count       = $this->result['count'];
        $rate        = $count == 0 ? 0 : round($preparation / $count, 2);
        $rate        = round($rate / 3600, 1);
        $records     = array(array('value' => $rate));
        return $this->filterByOptions($records, $options);
    }
}
