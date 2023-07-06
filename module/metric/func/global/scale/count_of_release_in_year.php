<?php
/**
 * 每年发布总数。
 * Count of release in year.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_release_in_year extends baseMetric
{
    public $dataset = 'getReleases';

    public $fieldList = array('t1.id', 't1.createdDate');

    public $result = array();

    public function calculate($data)
    {
        $closedYear = substr($data->createdDate, 0, 4);
        if(empty($this->result[$closedYear])) $this->result[$closedYear] = 0;
        $this->result[$closedYear] ++;
    }

    public function getResult()
    {
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
