<?php
/**
 * 按系统统计待处理的上线计划总数。
 * Count of pending deployment.
 *
 * 范围：system
 * 对象：deployment
 * 目的：rate
 * 度量名称：按系统统计待处理的上线计划总数
 * 单位：无
 * 描述：按系统统计的待处理的上线计划总数是指所有尚未完成的计划数量。该度量反映了团队在软件交付和发布管理方面的任务积压和工作进展情况。
 * 定义：所有的未完成的上线计划个数求和;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_pending_deployment extends baseCalc
{
    public $dataset = 'getDeployment';

    public $fieldList = array('t1.status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status == 'wait') $this->result ++;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
