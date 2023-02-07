<div class='side-col' id='sidebar'>
  <div class='cell'>
    <div class='list-group tab-menu'>
      <?php
      foreach($lang->custom->{$module}->fields as $key => $value)
      {
          $params = $key == 'required' ? "module=$module" : '';
          $method = $key;
          $active = $app->rawMethod == $key ? 'active' : '';
          if(!in_array($key, $config->custom->notSetMethods))
          {
              $params = "module=$module&field=$key";
              $method = 'set';
              $active = (isset($field) and $field == $key) ? 'active' : $active;
          }

          if(common::hasPriv('custom', $method)) echo html::a(inlink($method, $params), $value, '', "class='$active'");
      }
      ?>
    </div>
  </div>
</div>
