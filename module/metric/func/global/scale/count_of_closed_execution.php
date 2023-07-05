<?php
/**
 * 关闭的执行总数。
 * Count of closed execution.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_closed_execution extends baseMetric
{
    public $dataset = 'getAllExecutions';

    public $fieldList = array('id', 'status');

    public function calculate($data)
    {
        if($data->status == 'closed') $this->result ++;
    }

    public function getResult()
    {
        return $this->result;
    }
}
