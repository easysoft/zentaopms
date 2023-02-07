<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
  <?php
  $tabMenuList = isset($lang->admin->tabMenu[$activeMenu]) ? $lang->admin->tabMenu[$activeMenu] : array();
  foreach($tabMenuList as $tabMenuKey => $tabMenu)
  {
      $link = $this->admin->getHasPrivLink($tabMenu);
      if(empty($link)) continue;

      list($tabModule, $tabMethod, $tabParams) = $link;

      $name      = $lang->custom->object[$tabMenuKey];
      $paramName = $this->app->rawParams ? reset($this->app->rawParams) : '';
      $active    = ($tabMethod == $tabMenuKey and strpos(',required,set,', ",$tabMethod",) === false) ? 'btn-active-text' : '';
      $active    = ($paramName == $tabMenuKey and strpos(',required,set,', ",$tabMethod",)) ? 'btn-active-text' : $active;
      common::printLink($tabModule, $tabMethod, $tabParams, "<span class='text'>{$name}</span>", '', "class='btn btn-link {$active}' id='{$tabMenuKey}Tab'");
  }
  ?>
  </div>
</div>
