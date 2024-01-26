<?php
/**
 * 按人员统计的待评审研发需求数。
 * Count of reviewing story in user.
 *
 * 范围：user
 * 对象：story
 * 目的：scale
 * 度量名称：按人员统计的待评审研发需求数
 * 单位：个
 * 描述：按人员统计的待评审研发需求数表示每个人需要评审的研发需求数量之和。反映了每个人需要评审的研发需求的规模。该数值越大，说明需要投入越多的时间评审需求。
 * 定义：所有研发需求个数求和;评审人为某人;评审结果为空;评审状态为评审中或变更中;过滤已删除的需求;过滤已删除产品的需求;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_reviewing_story_in_user extends baseCalc
{
    public $dataset = 'getDevStoriesWithReview';

    public $fieldList = array('t1.reviewer', 't1.story', 't1.version', 't2.status', 't1.result');

    public $result = array();

    public function calculate($row)
    {
        $reviewer = $row->reviewer;
        $story    = $row->story;
        $version  = $row->version;
        $status   = $row->status;
        $result   = $row->result;

        if(empty($reviewer)) return false;
        if($status != 'reviewing' and $status != 'changing') return false;
        if(!empty($result)) return false;

        if(!isset($this->result[$story]))
        {
            $this->result[$story]['reviewer'] = array($reviewer);
            $this->result[$story]['version']  = $version;
            return false;
        }

        if($version > $this->result[$story]['version'])
        {
            $this->result[$story]['reviewer'] = array($reviewer);
            $this->result[$story]['version']  = $version;
        }

        if($version == $this->result[$story]['version'])
        {
            $this->result[$story]['reviewer'][] = $reviewer;
        }
    }

    public function getResult($options = array())
    {
        $userReview = array();
        foreach($this->result as $review)
        {
            $reviewers = $review['reviewer'];
            foreach($reviewers as $reviewer)
            {
                if(!isset($userReview[$reviewer])) $userReview[$reviewer] = 0;
                $userReview[$reviewer] += 1;
            }
        }
        $records = array();
        foreach($userReview as $user => $value)
        {
            $records[] = array('user' => $user, 'value' => $value);
        }
        return $this->filterByOptions($records, $options);
    }
}
