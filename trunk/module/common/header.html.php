<?php
$clientTheme = $this->app->getClientTheme();
$webRoot     = $this->app->getWebRoot();
$jsRoot      = $webRoot . "js/";
$themeRoot   = $webRoot . "theme/";
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dli'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <?php
  if(isset($header['title']))   echo "<title>$header[title] - $lang->zentaoMS</title>\n";
  if(isset($header['keyword'])) echo "<meta name='keywords' content='$header[keyword]'>\n";
  if(isset($header['desc']))    echo "<meta name='description' content='$header[desc]'>\n";
  ?>
<?php echo js::exportConfigVars();?>
<script src="<?php echo $jsRoot;?>jquery/lib.js" type="text/javascript"></script>
<script src="<?php echo $jsRoot;?>my.js"         type="text/javascript"></script>
<link rel='stylesheet' href='<?php echo $clientTheme . 'yui.css';?>' type='text/css' media='screen' />
<link rel='stylesheet' href='<?php echo $clientTheme . 'style.css';?>' type='text/css' media='screen' />
<script type="text/javascript">loadFixedCSS();</script>
</head>
<body>
<div id='topbar' class='yui-d0 yui-t6'>
  <div class='yui-main'><div class='yui-b'><?php printf($lang->welcome, $app->company->name);?></div></div>
  <div class='yui-b a-right'>
    <?php if(isset($app->user)) echo $app->user->realname;?>
    <?php 
    if(isset($app->user) and $app->user->account != 'guest')
    {
        echo html::a($this->createLink('my', 'index'), $lang->myControl);
        echo html::a($this->createLink('user', 'logout'), $lang->logout);
    }
    else
    {
        echo html::a($this->createLink('user', 'login'), $lang->login);
    }
    ?>
    <a href='http://www.zentao.cn' target='_blank'><?php echo $lang->zentaoSite;?></a>
    <?php echo $lang->sponser;?>
  </div>
</div>
<div id='navbar' class='yui-d0'>
  <div id='mainmenu'>
    <ul>
      <?php 
      echo "<li>$lang->zentaoMS</li>";
      /* 设定当前的主菜单项。默认先取当前的模块名，如果有该模块所对应的菜单分组，则取分组名作为主菜单项。*/
      $moduleName = $this->app->getModuleName();
      $mainMenu   = $moduleName;
      if(isset($lang->menugroup->$moduleName)) $mainMenu = $lang->menugroup->$moduleName;

      /* 循环打印主菜单。*/
      foreach($lang->menu as $menuKey => $menu)
      {
          $active = $menuKey == $mainMenu ? 'id=active' : '';
          list($menuLabel, $module, $method) = explode('|', $menu);

          if(common::hasPriv($module, $method))
          {
              $link  = $this->createLink($module, $method);
              echo "<li $active><nobr><a href='$link'>$menuLabel</a></nobr></li>\n";
          }
      }
      ?>
    </ul>
  </div>

  <div id='submenu'>
    <ul>
      <?php
      if(isset($lang->menu->$mainMenu))
      {
          $submenus = $lang->submenu->$mainMenu;
          foreach($submenus as $submenu)
          {
              if($submenu == '|')
              {
                  echo "<li>$submenu</li>";
                  continue;
              }
              @list($menuLabel, $module, $method, $vars) = explode('|', $submenu);
              if(common::hasPriv($module, $method))
              {
                  $link = $this->createLink($module, $method, $vars);
                  echo "<li $active><a href='$link'>$menuLabel</a></li>\n";
              }
          }
      }
      ?>
      <li></li>
    </ul>
  </div>
</div>

<div id='posbar' class='yui-d0'>
  <?php
  echo $lang->currentPos;
  list($menuLabel, $module, $method) = explode('|', $lang->menu->index);
  echo html::a($this->createLink($module, $method), $lang->zentaoMS) . $lang->arrow;
  if($moduleName != 'index')
  {
      list($menuLabel, $module, $method) = explode('|', $lang->menu->$mainMenu);
      echo html::a($this->createLink($module, $method), $menuLabel);
  }
  else
  {
      echo $lang->index->common;
  }
  if(isset($position))
  {
      echo $lang->arrow;
      foreach($position as $key => $link)
      {
          echo $link;
          if(isset($position[$key + 1])) echo $lang->arrow;
      }
  }
  ?>
</div>
