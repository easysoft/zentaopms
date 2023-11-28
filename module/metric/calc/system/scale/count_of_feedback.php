<?php
/**
 * 按系统统计的反馈总数。
 * Count of feedback.
 *
 * 范围：system
 * 对象：feedback
 * 目的：scale
 * 度量名称：按系统统计的反馈总数
 * 单位：个
 * 描述：按系统统计的反馈总数是指收集到的所有用户反馈的数量。这个度量项可以帮助团队了解用户对产品的关注点和问题，并作为改进产品质量和用户满意度的依据。较高的反馈总数可能暗示着用户的活跃度和关注度较高，需要团队及时响应和处理，同时暗示产品问题可能有很多。
 * 定义：所有的反馈个数求和;过滤已删除的反馈;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_feedback extends baseCalc
{
    public $dataset = 'getFeedbacks';

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
