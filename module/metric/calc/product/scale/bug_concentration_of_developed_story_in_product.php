<?php
/**
 * 按产品统计的研发完毕研需规模的Bug密度。
 * Bug concentration of developed story in product.
 *
 * 范围：product
 * 对象：bug
 * 目的：scale
 * 度量名称：按产品统计的研发完毕研需规模的Bug密度
 * 单位：个
 * 描述：按产品统计的研发完毕研需规模的Bug密度表示按产品统计的有效Bug数相对于按产品统计的研发完成的研发需求规模数。该度量项反映了研发完毕的研需的质量表现，密度越低代表研发完毕的研需质量越高。
 * 定义：复用：;按产品统计的有效Bug数;按产品统计的研发完成的研发需求规模数;公式：;按产品统计的研发完成需求的Bug密度=按产品统计的有效Bug数/按产品统计的研发完成的研发需求规模数;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class bug_concentration_of_developed_story_in_product extends baseCalc
{
    public $result = array();

    public function getStatement()
    {
        $developedStory = $this->dao->select('product, count(id) AS storyNum')
            ->from(TABLE_STORY)
            ->where('deleted')->eq('0')
            ->andWhere("NOT FIND_IN_SET('or', vision)")
            ->andWhere('stage', true)->in('developed,testing,tested,verified,released')
            ->orWhere('closedReason')->eq('done')
            ->markRight(1)
            ->groupBy('product')
            ->get();

        $effectiveBug = $this->dao->select('product, count(id) AS bugNum')
            ->from(TABLE_BUG)
            ->where('resolution')->in('fixed,postponed')
            ->orWhere('status')->eq('active')
            ->groupBy('product')
            ->get();

        return $this->dao->select('t1.id, t1.name, t2.storyNum, t3.bugNum')
            ->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin("($developedStory)")->alias('t2')->on('t1.id = t2.product')
            ->leftJoin("($effectiveBug)")->alias('t3')->on('t1.id = t3.product')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.shadow')->eq(0)
            ->query();
    }

    public function calculate($data)
    {
        $product    = $data->id;
        $storyCount = $data->storyNum;
        $bugCount   = $data->bugNum;

        $this->result[$product] = (empty($storyCount) || empty($bugCount)) ? 0 : round($bugCount / $storyCount, 4);
    }

    public function getResult($options = null)
    {
        $records = array();
        foreach($this->result as $product => $value) $records[] = array('product' => $product, 'value' => $value);
        return $this->filterByOptions($records, $options);
    }
}
