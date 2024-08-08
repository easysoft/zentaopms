<?php
class scale_of_developed_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.estimate', 't1.product', 't1.stage', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
        $stage        = $row->stage;
        $product      = $row->product;
        $closedReason = $row->closedReason;
        $estimate     = $row->estimate;

        if(!in_array($stage, array('developed', 'testing', 'tested', 'verified', 'released')) && $closedReason != 'done') return false;

        if(!isset($this->result[$row->product])) $this->result[$row->product] = 0;
        $this->result[$row->product] += $estimate;
    }

    public function getResult($options = array())
    {
    }
}
