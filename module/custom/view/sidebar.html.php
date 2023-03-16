<?php if(!empty($lang->custom->{$module}->fields)):?>
<div class='side-col' id='sidebar'>
  <div class='cell'>
    <div class='list-group tab-menu'>
      <?php
      foreach($lang->custom->{$module}->fields as $key => $value)
      {
          $currentModule = 'custom';
          $method        = $key;
          $params        = $key == 'required' ? "module=$module" : '';
          $active        = $app->rawMethod == strtolower($key) ? 'active' : '';
          if(!in_array($key, $config->custom->notSetMethods))
          {
              $params = "module=$module&field=$key";
              $method = 'set';
              $active = (isset($field) and $field == $key) ? 'active' : $active;
          }

          if($module == 'approvalflow')
          {
              $currentModule = 'approvalflow';
              if(in_array($key, array('project', 'workflow')))
              {
                  $method = 'browse';
                  $params = "type=$key";
                  $active = (isset($type) and $type == $key) ? 'active' : $active;
              }
          }

          if(common::hasPriv($currentModule, $method)) echo html::a(inlink($method, $params), $value, '', "class='$active'");
      }
      ?>
    </div>
  </div>
</div>
<?php endif;?>
