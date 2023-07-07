<?php
/**
 * 每年创建的执行总数。
 * Count of execution per year.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_execution_in_year extends baseCalc
{
    public $dataset = 'getAllExecutions';

    public $fieldList = array('id', 'openedDate');

    public function calculate($data)
    {
        if(!empty($data->openedDate))
        {
            $openedYear = substr($data->openedDate, 0, 4);
            if(empty($this->result[$openedYear])) $this->result[$openedYear] = 0;
            $this->result[$openedYear] ++;
        }
    }

    public function getResult()
    {
        if(empty($this->result)) return null;
        ksort($this->result);
        $records = array();
        foreach($this->result as $year => $value)
        {
            if($year == '0000') continue;
            $records[] = (object)array('year' => $year, 'value' => $value);
        }
        return $records;
    }
}
