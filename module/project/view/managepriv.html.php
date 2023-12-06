<?php
/**
 * The manage privilege view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: managepriv.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<style>
.table-bymodule select.form-control {height:250px}
.group-item {display:block; width:220px; float:left; font-size: 14px}
.group-item .checkbox-inline label{padding-left:8px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;}
.table.table-form tbody > tr:last-child td {border-top: 1px solid #ddd}
@-moz-document url-prefix(){.table.table-form tbody > tr:last-child td, .table.table-form tbody > tr:last-child th {border-bottom: 1px solid #ddd}}

#mainMenu #groupName{line-height:33px; float: left}
.checkbox-right{padding-left:0px !important;}

.thWidth {width: 160px;}
td.menus {border-right: 0;padding-right: 0;width: 220px !important;}
td.menus + td {border-left: 0;}
.menus .checkbox-primary {float: left; width: 220px;}
.menus .checkbox-primary:first-child {float: left; width: auto;}
.menus a {margin-left: 10px;}
</style>

<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
  <span id='groupName'><i class='icon-lock'></i> <?php echo $group->name;?> <i class="icon icon-chevron-right"></i></span>
    <?php $params = "projectID=$projectID&type=byGroup&param=$groupID";?>
    <?php echo html::a(inlink('managepriv', $params), "<span class='text'>{$lang->group->all}</span>", '', "class='btn btn-link btn-active-text'")?>
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
            <?php echo html::checkbox("actions[{$moduleName}]", array($action => $lang->$moduleName->$actionLabel), isset($groupPrivs[$moduleName][$action]) ? $action : '', "title='{$lang->$moduleName->$actionLabel}'", 'inline');?>
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
          <?php echo html::submitButton();?>
          <?php echo html::backButton();?>
          <?php echo html::hidden('noChecked'); // Save the value of no checked.?>
        </td>
      </tr>
    </table>
  </form>
</div>
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

    <?php if(!$project->multiple):?>
    $('#project').append($('#execution').html());
    $('#execution').parent().remove();
    <?php endif;?>
});
</script>
<?php include '../../common/view/footer.html.php'; ?>
