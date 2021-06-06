<div class="sidebar-toggle">
  <i class="icon icon-angle-left"></i>
</div>
<div class="cell">
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
            if(common::hasPriv($module, $method)) echo html::a($this->createLink($module, $method, $params), '<i class="icon icon-file-text"></i> ' . $label, '', "class='$class' title='$label'");
        }
        ?>
      </div>
    </div>
  </div>
  <?php if(!isset($config->proVersion) and !isset($config->bizVersion) and !isset($config->maxVersion)):?>
  <div class='panel panel-body' style='padding: 10px 6px'>
    <div class='text proversion'>
      <strong class='text-danger small text-latin'>PRO</strong> &nbsp;<span class='text-important'><?php echo (!empty($config->isINT)) ? $lang->report->proVersionEn : $lang->report->proVersion;?></span>
    </div>
  </div>
  <?php endif;?>
</div>
