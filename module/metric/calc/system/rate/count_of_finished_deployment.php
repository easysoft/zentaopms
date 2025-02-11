<?php
/**
 * 按系统统计已上线的次数。
 * Count of finished deployment.
 *
 * 范围：system
 * 对象：deployment
 * 目的：rate
 * 度量名称：按系统统计的上线次数
 * 单位：个
 * 描述：按系统统计的上线次次数是指在一定时间内的进行上线的上线申请数量，反映了团队的快速迭代和持续交付的能力，较高的上线频率意味着团队能够更快地将新功能、修复或改进的版本交付给用户，实现更加灵活和快速的交付周期。
 * 定义：系统的上线中/上线成功/上线失败的上线申请个数求和; 不统计已删除上线申请;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    liyang <liyang@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_finished_deployment extends baseCalc
{
    public $dataset = 'getDeployment';

    public $fieldList = array('status');

    public $result = 0;

    public function calculate($row)
    {
        if(in_array($row->status, array('doing', 'success', 'fail'))) $this->result ++;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
