<div id="mainMenu" class="clearfix menu-secondary">
  <div class="btn-toolbar pull-left">
  <?php
  $tabMenuList = isset($lang->admin->tabMenu[$activeMenu]) ? $lang->admin->tabMenu[$activeMenu] : array();
  foreach($tabMenuList as $tabMenuKey => $tabMenu)
  {
      $link = $this->admin->getHasPrivLink($tabMenu);
      if(empty($link)) continue;

      list($tabModule, $tabMethod, $tabParams) = $link;

      $name          = $lang->custom->object[$tabMenuKey];
      $paramName     = $this->app->rawParams ? reset($this->app->rawParams) : '';
      $currentMethod = $app->rawMethod;
      $active        = '';
      if(strpos(',required,set,', ",$currentMethod,",) === false)
      {
          if($currentMethod == $tabMenuKey) $active = 'btn-active-text';
          if(isset($tabMenu['alias']) and strpos(",{$tabMenu['alias']},", ",$currentMethod",) !== false) $active = 'btn-active-text';
      }
      elseif($paramName == $tabMenuKey)
      {
          $active = 'btn-active-text';
      }

      common::printLink($tabModule, $tabMethod, $tabParams, "<span class='text'>{$name}</span>", '', "class='btn btn-link {$active}' id='{$tabMenuKey}Tab'");
  }
  ?>
  </div>
</div>
