<?php
class scale_of_developed_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.estimate', 't1.product', 't1.stage', 't1.closedReason');

    public $result = array();

    public function calculate($row)
    {
    }

    public function getResult($options = array())
    {
    }
}
