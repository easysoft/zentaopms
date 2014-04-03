<header><strong><?php echo $lang->report->list;?></strong></header>
<div class='side-body pd-0'>
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
