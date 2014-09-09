<div class='side-body'>
  <div class='panel panel-sm'>
    <div class='panel-heading nobr'><strong><?php echo $lang->report->list;?></strong></div>
    <ul id='report-list' class='list-group'>
    <?php
        ksort($lang->reportList->$submenu->lists);
        foreach($lang->reportList->$submenu->lists as $list)
        {
            $list .= '|';
            list($label, $module, $method, $params) = explode('|', $list);
            $class = $label == $title ? ' active' : '';
            echo html::a($this->createLink($module, $method, $params), "<i class='icon-bar-chart'></i> " . $label, '', "class='list-group-item $class'");
        }
    ?>
    </ul>
  </div>
</div>
