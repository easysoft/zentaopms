<div class='box-title'><?php echo $lang->report->list;?></div>
<div class='box-content'>
<ul id="report-list">
<?php
    ksort($lang->reportList->$submenu->lists);
    foreach($lang->reportList->$submenu->lists as $list)
    {
        list($label, $module, $method) = explode('|', $list);
        echo '<li>' . html::a($this->createLink($module, $method), $label) . '</li>';
    }
?>
</ul>
</div>
