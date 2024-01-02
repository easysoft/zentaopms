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
 * 定义：所有研发需求个数求和;状态为评审中;指派给为某人;过滤已删除的研发需求;过滤已删除产品的研发需求;
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
    public $result = array();

    public function getStatement()
    {
        return $this->dao->select('t1.reviewer, t1.story, t1.version')
            ->from(TABLE_STORYREVIEW)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product=t3.id')
            ->where('t2.deleted')->eq('0')
            ->andWhere('t2.type')->eq('story')
            ->andWhere('t3.deleted')->eq('0')
            ->andWhere('t2.status')->eq('reviewing')
            ->andWhere("NOT FIND_IN_SET('or', t2.vision)")
            ->query();
    }

    public function calculate($row)
    {
        $reviewer = $row->reviewer;
        $story    = $row->story;
        $version  = $row->version;

        if(empty($reviewer)) return false;

        if(!isset($this->result[$story]))
        {
            $this->result[$story]['reviewer'] = $reviewer;
            $this->result[$story]['version']  = $version;
            return false;
        }

        if($version > $this->result[$story]['version'])
        {
            $this->result[$story]['reviewer'] = $reviewer;
            $this->result[$story]['version']  = $version;
        }
    }

    public function getResult($options = array())
    {
        $userReview = array();
        foreach($this->result as $review)
        {
            $reviewer = $review['reviewer'];
            if(!isset($userReview[$reviewer])) $userReview[$reviewer] = 0;
            $userReview[$reviewer] += 1;
        }
        $records = array();
        foreach($userReview as $user => $value)
        {
            $records[] = array('user' => $user, 'value' => $value);
        }
        return $this->filterByOptions($records, $options);
    }
}
