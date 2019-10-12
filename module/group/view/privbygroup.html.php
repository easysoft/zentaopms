<?php
/**
 * The manage privilege by group view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: managepriv.html.php 1517 2011-03-07 10:02:57Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php if($group->role == 'limited'):?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <a href='javascript:;' class='btn btn-link btn-active-text'><span class='text'><?php echo $group->name;?></span></a>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <form class="load-indicator main-form form-ajax" id="managePrivForm" method="post" target='hiddenwin'>
    <table class='table table-hover table-striped table-bordered'>
      <thead>
        <tr class='text-center'>
          <th><?php echo $lang->group->module;?></th>
          <th><?php echo $lang->group->method;?></th>
        </tr>
      </thead>
      <tr class='<?php echo cycle('even, bg-gray');?>'>
        <th class='text-right thWidth'><?php echo $lang->my->common;?></th>
        <td id='my' class='pv-10px'>
          <div class='checkbox-primary'>
            <input type='checkbox' name='actions[my][]' value='limited' <?php if(isset($groupPrivs['my']['limited'])) echo "checked";?> />
            <label class='priv' id="my-limited"><?php echo $lang->my->limited;?></label>
          </div>
        </td>
      </tr>
      <tr>
        <th class='text-right'></th>
        <td class='form-actions'>
          <?php echo html::submitButton('', "onclick='setNoChecked()'");?>
          <?php echo html::backButton();?>
          <?php echo html::hidden('noChecked'); // Save the value of no checked.?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php else:?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
  <span id='groupName'><i class='icon-lock'></i> <?php echo $group->name;?> <i class="icon icon-chevron-right"></i></span>
    <?php $params = "type=byGroup&param=$groupID&menu=%s&version=$version";?>
    <?php $active = empty($menu) ? 'btn-active-text' : '';?>
    <?php echo html::a(inlink('managePriv', sprintf($params, '')), "<span class='text'>{$lang->group->all}</span>", '', "class='btn btn-link $active'")?>

    <?php foreach($lang->menu as $module => $title):?>
    <?php if(!is_string($title)) continue;?>
    <?php $active = $menu == $module ? 'btn-active-text' : '';?>
    <?php echo html::a(inlink('managePriv', sprintf($params, $module)), "<span class='text'>" . substr($title, 0, strpos($title, '|')) . '</span>', '', "class='btn btn-link $active'")?>
    <?php endforeach;?>

    <?php $active = $menu == 'other' ? 'btn-active-text' : '';?>
    <?php echo html::a(inlink('managePriv', sprintf($params, 'other')), "<span class='text'>{$lang->group->other}</span>", '', "class='btn btn-link $active'");?>

    <div class='input-control space w-150px'>
    <?php echo html::select('version', $this->lang->group->versions, $version, "onchange=showPriv(this.value) class='form-control chosen'");?>
    </div>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <form class="load-indicator main-form form-ajax" id="managePrivForm" method="post" target='hiddenwin'>
    <table class='table table-hover table-striped table-bordered' id='privList'>
      <thead>
        <tr class='text-center'>
          <th class='thWidth'><?php echo $lang->group->module;?></th>
          <th colspan='2'><?php echo $lang->group->method;?></th>
        </tr>
      </thead>
      <?php foreach($lang->resource as $moduleName => $moduleActions):?>
      <?php if(!count((array)$moduleActions)) continue;?>
      <?php if(!$this->group->checkMenuModule($menu, $moduleName)) continue;?>
      <?php
      /* Check method in select version. */
      if($version)
      {
          $hasMethod = false;
          foreach($moduleActions as $action => $actionLabel)
          {
              if(strpos($changelogs, ",$moduleName-$actionLabel,") !== false)
              {
                  $hasMethod = true;
                  break;
              }
          }
          if(!$hasMethod) continue;
      }
      ?>
      <tr class='<?php echo cycle('even, bg-gray');?>'>
        <th class='text-middle text-right thWidth'>
          <div class="checkbox-primary checkbox-inline checkbox-right check-all">
            <input type='checkbox' id='allChecker<?php echo $moduleName;?>'>
            <label class='text-right' for='allChecker<?php echo $moduleName;?>'><?php echo $lang->$moduleName->common;?></label>
          </div>
        </th>
        <?php if(isset($lang->$moduleName->menus)):?>
        <td class='menus'>
          <?php echo html::checkbox("actions[$moduleName]", array('browse' => $lang->$moduleName->browse), isset($groupPrivs[$moduleName]) ? $groupPrivs[$moduleName] : '');?>
          <a href='javascript:;'><i class='icon icon-plus'></i></a>
          <?php echo html::checkbox("actions[$moduleName]", $lang->$moduleName->menus, isset($groupPrivs[$moduleName]) ? $groupPrivs[$moduleName] : '');?>
        </td>
        <?php endif;?>
        <td id='<?php echo $moduleName;?>' class='pv-10px' colspan='<?php echo !empty($lang->$moduleName->menus) ? 1 : 2?>'>
          <?php $i = 1;?>
          <?php foreach($moduleActions as $action => $actionLabel):?>
          <?php if(!empty($lang->$moduleName->menus) and $action == 'browse') continue;;?>
          <?php if(!empty($version) and strpos($changelogs, ",$moduleName-$actionLabel,") === false) continue;?>
          <div class='group-item'>
            <?php echo html::checkbox("actions[{$moduleName}]", array($action => $lang->$moduleName->$actionLabel), isset($groupPrivs[$moduleName][$action]) ? $action : '', '', 'inline');?>
          </div>
          <?php endforeach;?>
        </td>
      </tr>
      <?php endforeach;?>
      <tr>
        <th class='text-right'>
          <div class="checkbox-primary checkbox-inline checkbox-right check-all">
            <input type='checkbox' id='allChecker'>
            <label class='text-right' for='allChecker'><?php echo $lang->selectAll;?></label>
          </div>
        </th>
        <td class='form-actions' colspan='2'>
          <?php echo html::submitButton('', "onclick='setNoChecked()'", 'btn btn-wide btn-primary');?>
          <?php echo html::backButton();?>
          <?php echo html::hidden('noChecked'); // Save the value of no checked.?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php endif;?>
<?php js::set('groupID', $groupID);?>
<?php js::set('menu', $menu);?>
<script>
$(document).ready(function()
{
    /**
     * 隐藏列表标签。
     * Hide tabs except the browse list tab.
     */
    $('.menus input[name^=actions]:not(input[value=browse])').parent('.checkbox-primary').hide();

    /**
     * 切换列表标签的显示。
     * Toggle display of tabs except the browse list tab.
     */
    $('.menus .icon-plus').click(function()
    {
        $(this).toggleClass('icon-minus', 'icon-plus');
        $('.menus input[name^=actions]:not(input[value=browse])').parent('.checkbox-primary').toggle();
    })

    /**
     * 勾选浏览列表标签时，自动勾选下面的所有标签。 
     * Check all tabs when the Browse list tab is selected.
     */
    $('.menus input[value=browse]').change(function()
    {
        $(this).parents('.menus').find('[name^=actions]').prop('checked', $(this).prop('checked'));
    });

    /**
     * 勾选浏览列表标签下面的任意一个标签时，自动勾选浏览列表标签。
     * Check the browse list tab when any one of the tabs is selected.
     */
    $('.menus input[name^=actions]:not(input[value=browse])').click(function()
    {
        var $parent = $(this).parents('.menus');

        $parent.find('input[value=browse]').prop('checked', $parent.find('input[name^=actions]:not(input[value=browse]):checked').length > 0);
    })
});
</script>
