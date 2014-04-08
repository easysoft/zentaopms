<div class='side-body'>
  <div class='panel panel-sm'>
    <div class='panel-heading nobr'><strong><?php echo $lang->report->list;?></strong></div>
    <ul id='report-list' class='list-group'>
    <?php
        ksort($lang->reportList->$submenu->lists);
        foreach($lang->reportList->$submenu->lists as $list)
        {
            list($label, $module, $method) = explode('|', $list);
            echo html::a($this->createLink($module, $method), "<i class='icon-file-powerpoint'></i> " . $label, '', "class='list-group-item'");
        }
    ?>
    </ul>
  </div>
</div>
