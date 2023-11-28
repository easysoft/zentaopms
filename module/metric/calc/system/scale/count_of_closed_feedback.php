<?php
/**
 * 按系统统计的已关闭反馈数。
 * Count of closed feedback.
 *
 * 范围：system
 * 对象：feedback
 * 目的：scale
 * 度量名称：按系统统计的已关闭反馈数
 * 单位：个
 * 描述：按系统统计的已关闭反馈数是指已经处理完毕并关闭的用户反馈的数量。这个度量项可以反映团队对用户反馈的关注度和处理效率。较高的已关闭反馈总数可能意味着团队能够及时响应用户反馈，并持续改进产品以解决用户问题。
 * 定义：所有的反馈个数求和;状态为已关闭;过滤已删除的反馈;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_closed_feedback extends baseCalc
{
    public $dataset = 'getFeedbacks';

    public $fieldList = array('t1.status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status == 'closed') $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
