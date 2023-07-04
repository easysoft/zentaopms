<?php
include dirname(__FILE__, 4) . DS . 'func.class.php';
class count_of_marker_release extends func
{
    public $dataset = 'getReleaseList';

    public $fieldList = array('t1.id', 't1.marker');

    public function calculate($data)
    {
        if(!empty($data->marker) and $data->marker == 1) $this->result ++;
    }

    public function getResult()
    {
        return $this->result;
    }
}
