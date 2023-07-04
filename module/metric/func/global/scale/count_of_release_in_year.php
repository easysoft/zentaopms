<?php
include dirname(__FILE__, 4) . DS . 'func.class.php';
class count_of_release_in_year extends func
{
    public $dataset = 'getReleaseList';

    public $fieldList = array('t1.id', 't1.createdDate');

    public function calculate($data)
    {
        $value = $data->createdDate;
        if(empty($this->result[$value])) $this->result[$value] = 0;
        $this->result[$value] ++;
    }

    public function getResult()
    {
        return $this->result;
    }
}
