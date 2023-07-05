<?php
/**
 * 每年完成的执行总数。
 * Count of execution per year.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_execution_in_year extends baseMetric
{
    public $dataset = 'getAllExecutions';

    public $fieldList = array('id', 'closedDate', 'status');

    public function calculate($data)
    {
        if($data->status == 'closed')
        {
            $closedYear = substr($data->closedDate, 0, 10);
            if(empty($this->result[$closedYear])) $this->result[$closedYear] = 0;
            $this->result[$closedYear] ++;
        }
    }

    public function getResult()
    {
        return $this->result;
    }
}
