<?php
foreach($reasonList as $key => $value)
{
    if(!in_array($key, array('done', 'duplicate', 'cancel'))) unset($reasonList[$key]);
}
