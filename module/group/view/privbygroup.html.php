<?php
/**
 * The manage privilege by group view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
<div id='mainContent' class='main main-content'>
  <form class="load-indicator main-form form-ajax" id="managePrivForm" method="post">
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
          <?php echo html::a($this->inlink('browse'), $lang->cancel, '', "class='btn btn-back btn-wide'");?>
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

    <?php
    $i = 0;
    foreach($lang->mainNav as $module => $title)
    {
        if(!is_string($title)) continue;
        $i++;
        if($i == $config->group->maxToolBarCount) echo '<div class="btn-group"><a href="javascript:;" data-toggle="dropdown" class="btn btn-link">' . $lang->group->more . '<span class="caret"></span></a><ul class="dropdown-menu">';
        $active = $menu == $module ? 'btn-active-text' : '';
        if($i >= $config->group->maxToolBarCount) echo '<li>';
        echo html::a(inlink('managePriv', sprintf($params, $module)), "<span class='text'>" . strip_tags(substr($title, 0, strpos($title, '|'))) . '</span>', '', "class='btn btn-link $active'");
        if($i >= $config->group->maxToolBarCount) echo '</li>';
    }
    if($i >= $config->group->maxToolBarCount) echo '</ul></div>';
    ?>

    <?php $active = $menu == 'general' ? 'btn-active-text' : '';?>
    <?php echo html::a(inlink('managePriv', sprintf($params, 'general')), "<span class='text'>{$lang->group->general}</span>", '', "class='btn btn-link $active'");?>

    <div class='input-control space w-150px'>
      <?php echo html::select('version', $this->lang->group->versions, $version, "onchange=showPriv(this.value) class='form-control chosen'");?>
    </div>
  </div>
</div>
<form class="load-indicator main-form form-ajax" id="managePrivForm" method="post">
  <div id='mainContainer'>
    <div class='main main-content'>
      <table class='table table-hover table-striped table-bordered' id='privList'>
        <thead>
          <tr class='text-center'>
            <th class='module'><?php echo $lang->group->module;?></th>
            <th class='package'><?php echo $lang->privpackage->common;?></th>
            <th class='method' colspan='2'><?php echo $lang->group->method;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($privList as $moduleName => $packages):?>
          <?php if(!count((array)$packages)) continue;?>
          <?php
          $moduleActions = !empty($lang->resource->{$moduleName}) ? $lang->resource->{$moduleName} : null;

          /* Check method in select version. */
          if($version)
          {
              $hasMethod = false;
              foreach($privMethods as $module => $methods)
              {
                  foreach($methods as $method)
                  {
                      if(strpos($changelogs, ",$module-$method,") !== false)
                      {
                          $hasMethod = true;
                          break;
                      }
                      if(!$hasMethod) continue;
                  }
              }
          }

          $i = 1;

          $modulePrivs  = count($privList[$moduleName], 1) - count($selectPrivs[$moduleName], 1);
          $moduleSelect = array_sum($selectPrivs[$moduleName]);
          ?>
          <?php foreach($packages as $packageID => $privs):?>
          <tr class='<?php echo cycle('even, bg-gray');?>'>
            <?php if($i == 1):?>
            <th class='text-middle text-left module' rowspan="<?php echo $i == 1 ? count($packages) : 1;?>" data-module='<?php echo $moduleName;?>' all-privs='<?php echo $modulePrivs;?>' select-privs='<?php echo $moduleSelect;?>'>
              <div class="checkbox-primary checkbox-inline checkbox-left check-all">
                <input type='checkbox' id='allChecker<?php echo $moduleName;?>' value='1' <?php if(!empty($moduleSelect) and $modulePrivs == $moduleSelect) echo 'checked';?>>
                <label class='text-left <?php if(!empty($moduleSelect) and $modulePrivs != $moduleSelect) echo 'checkbox-indeterminate-block';?>' for='allChecker<?php echo $moduleName;?>'><?php echo $lang->$moduleName->common;?></label>
              </div>
            </th>
            <?php endif;?>
            <?php
            $packagePrivs  = count($privs);
            $packageSelect = $selectPrivs[$moduleName][$packageID];
            ?>
            <th class='<?php echo $i == 1 ? 'td-sm' : 'td-md';?> text-middle text-left package' data-module='<?php echo $moduleName;?>' data-package='<?php echo $packageID;?>' all-privs='<?php echo $packagePrivs;?>' select-privs='<?php echo $packageSelect;?>'>
              <div class="checkbox-primary checkbox-inline checkbox-left check-all">
                <input type='checkbox' id='allCheckerModule<?php echo $moduleName;?>Package<?php echo $packageID;?>' value='1' <?php if($packagePrivs == $packageSelect) echo 'checked';?>>
                <label class='text-left <?php if(!empty($packageSelect) and $packagePrivs != $packageSelect) echo 'checkbox-indeterminate-block';?>' for='allCheckerPackage<?php echo $packageID;?>'><?php echo zget($privPackages, $packageID, $lang->group->other);?></label>
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
              <?php foreach($privs as $privID => $priv):?>
              <div class="group-item" data-module='<?php echo $moduleName;?>' data-package='<?php echo $packageID;?>' data-id='<?php echo zget($priv, 'id', 0);?>'>
                <div class="checkbox-primary">
                  <?php echo html::checkbox("actions[$priv->module]", array($priv->method => $priv->name), isset($groupPrivs[$priv->module][$priv->method]) ? $priv->method : '', "title='{$priv->name}' id='actions[$priv->module]$priv->method' data-id='$priv->action'");?>
                </div>
              </div>
              <?php endforeach;?>
            </td>
          </tr>
          <?php $i ++;?>
          <?php endforeach;?>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
    <div class="side">
      <div class="priv-panel">
        <div class="panel-title">
          <?php echo $lang->group->dependentPrivs;?>
          <icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content='<?php echo $lang->group->dependPrivTips;?>'></icon>
        </div>
        <div class="panel-content">
          <div class="menuTree depend menu-active-primary menu-hover-primary"></div>
          <div class="table-empty-tip <?php if(count($relatedPrivData['depend']) > 0) echo 'hidden';?>">
            <p><span class="text-muted"><?php echo $lang->noData;?></span></p>
          </div>
        </div>
      </div>
      <div class="priv-panel mt-m">
        <div class="panel-title">
          <?php echo $lang->group->recommendPrivs;?>
          <icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content='<?php echo $lang->group->recommendPrivTips;?>'></icon>
        </div>
        <div class="panel-content">
          <div class="menuTree recommend menu-active-primary menu-hover-primary"></div>
          <div class="table-empty-tip <?php if(count($relatedPrivData['recommend']) > 0) echo 'hidden';?>">
            <p><span class="text-muted"><?php echo $lang->noData;?></span></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class='priv-footer'>
    <div class='text-center text-middle'>
      <div class="checkbox-primary checkbox-inline check-all">
        <input type='checkbox' id='allChecker'>
        <label class='text-right' for='allChecker'><?php echo $lang->selectAll;?></label>
      </div>
      <?php echo html::hidden('actions[][]');?>
      <?php echo html::submitButton($lang->save, '', 'btn btn-primary btn-wide');?>
      <?php echo html::a($this->createLink('group', 'browse'), $lang->cancel, '', 'class="btn btn-wide"');?>
    </div>
  </div>
</form>
<?php endif;?>
<?php js::set('groupID', $groupID);?>
<?php js::set('menu', $menu);?>
<?php js::set('relatedPrivData', json_encode($relatedPrivData));?>
<?php js::set('selectedPrivIdList', $selectedPrivIdList);?>
<?php js::set('excludeIdList', $excludePrivsIdList);?>
<script>
$(document).ready(function()
{
    $(".icon-help").popover();

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
