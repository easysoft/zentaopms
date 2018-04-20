<div class='panel'>
  <div class='panel-heading'>
    <div class='panel-title'><?php echo $lang->report->list;?></div>
  </div>
  <div class='panel-body'>
    <div class='list-group'>
      <?php
      ksort($lang->reportList->$submenu->lists);
      foreach($lang->reportList->$submenu->lists as $list)
      {
          $list .= '|';
          list($label, $module, $method, $params) = explode('|', $list);
          $class = $label == $title ? 'selected' : '';
          if(common::hasPriv($module, $method)) echo html::a($this->createLink($module, $method, $params), '<i class="icon icon-file-text"></i> ' . $label, '', "class='$class'");
      }
      ?>
    </div>
  </div>
</div>
