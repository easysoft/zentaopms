<?php
/**
 * The manage privilege by group view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
    <?php $params = "type=byPackage&param=$groupID&menu=%s&version=$version";?>
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
      <div class="btn-group">
        <?php echo html::a(inlink('managePriv', "type=byPackage&param=$groupID&menu=$menu&version=$version"), "<i class='icon-has-authority-pack'></i>", '', "class='btn btn-icon switchBtn text-primary'");?>
        <?php echo html::a(inlink('managePriv', "type=byGroup&param=$groupID&menu=$menu&version=$version"), "<i class='icon-without-authority-pack'></i>", '', "class='btn btn-icon switchBtn'");?>
      </div>
      <table class='table table-hover table-striped table-bordered' id='privPackageList'>
        <thead>
          <tr class='text-center'>
            <th class='module'><?php echo $lang->group->module;?></th>
            <th class='package'><?php echo $lang->privpackage->common;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($privList as $moduleName => $packages):?>
          <?php if(!count((array)$packages)) continue;?>
          <?php
          $i = 1;

          $modulePrivs  = count($privList[$moduleName], 1) - count($selectPrivs[$moduleName], 1);
          $moduleSelect = array_sum($selectPrivs[$moduleName]);
          ?>
          <tr class='<?php echo cycle('even, bg-gray');?>'>
            <th class='text-middle text-left module' data-module='<?php echo $moduleName;?>' all-privs='<?php echo $modulePrivs;?>' select-privs='<?php echo $moduleSelect;?>'>
              <div class="checkbox-primary checkbox-inline checkbox-left check-all">
                <input type='checkbox' id='allChecker<?php echo $moduleName;?>' value='1' <?php if(!empty($moduleSelect) and $modulePrivs == $moduleSelect) echo 'checked';?>>
                <?php $moduleTitle = $lang->$moduleName->common;?>
                <?php if(in_array($moduleName, array('doc', 'api'))) $moduleTitle = $lang->$moduleName->manage;?>
                <label class='text-left <?php if(!empty($moduleSelect) and $modulePrivs != $moduleSelect) echo 'checkbox-indeterminate-block';?>' for='allChecker<?php echo $moduleName;?>'><?php echo $moduleTitle;?></label>
              </div>
            </th>
            <td class='td-sm text-middle text-left package-column' data-module='<?php echo $moduleName;?>'>
              <?php foreach($packages as $packageID => $privs):?>
              <?php
              $packagePrivs  = count($privs);
              $packageSelect = $selectPrivs[$moduleName][$packageID];
              ?>
              <div class="package" data-module='<?php echo $moduleName;?>' data-package='<?php echo $packageID;?>' all-privs='<?php echo $packagePrivs;?>' select-privs='<?php echo $packageSelect;?>'>
                <div class="checkbox-primary checkbox-inline checkbox-left check-all">
                  <input type='checkbox' id='allCheckerModule<?php echo $moduleName;?>Package<?php echo $packageID;?>' value='1' <?php if($packagePrivs == $packageSelect) echo 'checked';?>>
                  <label class='text-left <?php if(!empty($packageSelect) and $packagePrivs != $packageSelect) echo 'checkbox-indeterminate-block';?>' for='allCheckerPackage<?php echo $packageID;?>'><?php echo zget($privPackages, $packageID, $lang->group->other);?></label>
                </div>
                <i class="priv-toggle icon"></i>
              </div>
              <div class="privs hidden" data-module='<?php echo $moduleName;?>' data-package='<?php echo $packageID;?>'>
                <div class="arrow"></div>
                <div class='popover-content'>
                  <?php if(isset($lang->$moduleName->menus)):?>
                  <?php
                  $menusPrivs  = count($lang->$moduleName->menus);
                  $menusSelect = count(array_intersect(array_keys($lang->$moduleName->menus), array_keys(zget($groupPrivs, $moduleName, array()))));
                  ?>
                  <div class="group-item menus-browse" data-id='0' data-module='<?php echo $moduleName;?>' data-package='0'>
                    <div class="checkbox-primary checkbox-inline checkbox-left check-all">
                      <input type='checkbox' value='browse' <?php if($menusPrivs == $menusSelect) echo 'checked';?>>
                      <label class='text-left <?php if(!empty($menusSelect) and $menusPrivs != $menusSelect) echo 'checkbox-indeterminate-block';?>' for='actions[<?php echo $moduleName;?>]browse'><?php echo $lang->$moduleName->browse;?></label>
                    </div>
                    <i class="priv-toggle icon"></i>
                    <div class='menus-privs hidden' data-module='<?php echo $moduleName;?>' data-package='<?php echo $packageID;?>'>
                      <div class="arrow"></div>
                      <div class='popover-content'>
                        <?php foreach($lang->$moduleName->menus as $method => $name):?>
                        <div class="group-item menus-item" data-id='<?php echo "$moduleName-$method";?>' data-module='<?php echo $moduleName;?>' data-package='0'>
                          <?php echo html::checkbox("actions[$moduleName]", array($method => $name), isset($groupPrivs[$moduleName][$method]) ? $groupPrivs[$moduleName][$method] : '', "title='{$name}' id='actions[$moduleName]$method' data-id='$moduleName-$method'");?>
                        </div>
                        <?php endforeach;?>
                      </div>
                    </div>
                  </div>
                  <?php endif;?>
                  <?php foreach($privs as $privID => $priv):?>
                  <?php if(!empty($lang->$moduleName->menus) and ($priv->method == 'browse' or in_array($priv->method, array_keys($lang->$moduleName->menus)))) continue;?>
                  <div class="group-item" data-id='<?php echo zget($priv, 'id', 0);?>' data-module='<?php echo $moduleName;?>' data-package='<?php echo $packageID;?>'>
                    <div class="checkbox-primary">
                      <?php echo html::checkbox("actions[$priv->module]", array($priv->method => $priv->name), isset($groupPrivs[$priv->module][$priv->method]) ? $priv->method : '', "title='{$priv->name}' id='actions[$priv->module]$priv->method' data-id='$priv->action'");?>
                    </div>
                  </div>
                  <?php endforeach;?>
              </div>
              </div>
              <?php endforeach;?>
            </td>
          </tr>
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
      <?php echo html::submitButton($lang->save, "onclick='setNoChecked()'", 'btn btn-primary btn-wide');?>
      <?php echo html::a($this->createLink('group', 'browse'), $lang->cancel, '', 'class="btn btn-wide"');?>
      <?php echo html::hidden('noChecked'); // Save the value of no checked.?>
    </div>
  </div>
</form>
<?php endif;?>
<?php js::set('type', $type);?>
<?php js::set('groupID', $groupID);?>
<?php js::set('menu', $menu);?>
<?php js::set('relatedPrivData', json_encode($relatedPrivData));?>
<?php js::set('selectedPrivIdList', $selectedPrivIdList);?>
<?php js::set('excludeIdList', $excludePrivsIdList);?>
