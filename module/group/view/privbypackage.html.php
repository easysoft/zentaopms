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
    <?php $params = "type=byPackage&param=$groupID&nav=%s&version=$version";?>
    <?php $active = empty($nav) ? 'btn-active-text' : '';?>
    <?php echo html::a(inlink('managePriv', sprintf($params, '')), "<span class='text'>{$lang->group->all}</span>", '', "class='btn btn-link $active'")?>

    <?php
    $i = 0;
    foreach($lang->mainNav as $navKey => $title)
    {
        if(!is_string($title)) continue;
        $i++;
        if($i == $config->group->maxToolBarCount) echo '<div class="btn-group"><a href="javascript:;" data-toggle="dropdown" class="btn btn-link">' . $lang->group->more . '<span class="caret"></span></a><ul class="dropdown-menu">';
        $active = $nav == $navKey ? 'btn-active-text' : '';
        if($i >= $config->group->maxToolBarCount) echo '<li>';
        echo html::a(inlink('managePriv', sprintf($params, $navKey)), "<span class='text'>" . strip_tags(substr($title, 0, strpos($title, '|'))) . '</span>', '', "class='btn btn-link $active'");
        if($i >= $config->group->maxToolBarCount) echo '</li>';
    }
    if($i >= $config->group->maxToolBarCount) echo '</ul></div>';
    ?>

    <?php $active = $nav == 'general' ? 'btn-active-text' : '';?>
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
        <?php echo html::a(inlink('managePriv', "type=byPackage&param=$groupID&nav=$nav&version=$version"), "<i class='icon-has-authority-pack'></i>", '', "class='btn btn-icon switchBtn text-primary'");?>
        <?php echo html::a(inlink('managePriv', "type=byGroup&param=$groupID&nav=$nav&version=$version"), "<i class='icon-without-authority-pack'></i>", '', "class='btn btn-icon switchBtn'");?>
      </div>
      <table class='table table-hover table-striped table-bordered' id='privPackageList'>
        <thead>
          <tr class='text-center'>
            <th class='module'><?php echo $lang->group->module;?></th>
            <th class='package'><?php echo $lang->privpackage->common;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($subsets as $subsetName => $subset):?>
          <?php if($subset->allCount == 0) continue;?>
          <tr class='<?php echo cycle('even, bg-gray');?>'>
            <th class='text-middle text-left module' data-module='<?php echo $subsetName;?>' all-privs='<?php echo $subset->allCount;?>' select-privs='<?php echo $subset->selectCount;?>'>
              <div class="checkbox-primary checkbox-inline checkbox-left check-all">
                <input type='checkbox' id='allChecker<?php echo $subsetName;?>' value='1' <?php if($subset->selectCount && $subset->allCount == $subset->selectCount) echo 'checked';?>>
                <?php $subsetTitle = isset($lang->$subsetName) && isset($lang->$subsetName->common) ? $lang->$subsetName->common : $subsetName;?>
                <label class='text-left <?php if($subset->selectCount && $subset->allCount != $subset->selectCount) echo 'checkbox-indeterminate-block';?>' for='allChecker<?php echo $subsetName;?>'><?php echo $subsetTitle;?></label>
              </div>
            </th>
            <td class='td-sm text-middle text-left package-column' data-module='<?php echo $subsetName;?>'>
              <?php foreach($packages[$subsetName] as $packageCode => $package):?>
              <div class="package" data-module='<?php echo $subsetName;?>' data-package='<?php echo $packageCode;?>' all-privs='<?php echo $package->allCount;?>' select-privs='<?php echo $package->selectCount;?>'>
                <div class="checkbox-primary checkbox-inline checkbox-left check-all">
                  <input type='checkbox' id='allCheckerModule<?php echo $subsetName;?>Package<?php echo $packageCode;?>' value='1' <?php if($package->allCount == $package->selectCount) echo 'checked';?>>
                  <label class='text-left <?php if($package->selectCount && $package->allCount != $package->selectCount) echo 'checkbox-indeterminate-block';?>' for='allCheckerPackage<?php echo $packageCode;?>'><?php echo zget($lang->group->package, $packageCode, $lang->group->other);?></label>
                </div>
                <i class="priv-toggle icon"></i>
              </div>
              <div class="privs hidden" data-module='<?php echo $subsetName;?>' data-package='<?php echo $packageCode;?>'>
                <div class="arrow"></div>
                <div class='popover-content'>
                  <?php foreach($package->privs as $privCode => $priv):?>
                  <div class="group-item" data-id='<?php echo $privCode;?>' data-module='<?php echo $subsetName;?>' data-package='<?php echo $packageCode;?>'>
                    <div class="checkbox-primary">
                      <?php echo html::checkbox("actions[$priv->module]", array($priv->method => $priv->name), isset($groupPrivs[$priv->module][$priv->method]) ? $priv->method : '', "title='{$priv->name}' id='actions[$priv->module]$priv->method' data-id='$privCode'");?>
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
<?php js::set('relatedPrivData', json_encode($relatedPrivData));?>
<?php js::set('allPrivList', $allPrivList);?>
<?php js::set('selectedPrivList', $selectedPrivList);?>
