<?php
/**
 * 按产品统计的激活Bug数。
 * Count of active bug in product.
 *
 * 范围：prod
 * 对象：bug
 * 目的：scale
 * 度量名称：按产品统计的激活Bug数
 * 单位：个
 * 描述：产品中激活Bug的个数求和
 *       过滤已删除的Bug
 *       过滤已删除的产品
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
class count_of_activated_bug_in_product extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.product', 't1.status', 't1.resolution');

    public $result = array();

    public function calculate($data)
    {
        $product = $data->product;
        if(!isset($this->result[$product])) $this->result[$product] = 0;

        $resolution = $data->resolution;
        $status     = $data->status;

        if($status == 'active') $this->result[$product] += 1;
    }

    public function getResult($options = null)
    {
        if(!empty($options) && isset($options['product']))
        {
            $productID = $options['product'];
            if(isset($this->result[$productID])) return $this->result[$productID];
            return false;
        }

        $records = array();
        foreach($this->result as $product => $value)
        {
            $records[] = array('product' => $product, 'value' => $value);
        }

        return $records;
    }
}
