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
    <?php $params = "type=byGroup&param=$groupID&nav=%s&version=$version";?>
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
        <?php echo html::a(inlink('managePriv', "type=byPackage&param=$groupID&nav=$nav&version=$version"), "<i class='icon-has-authority-pack'></i>", '', "class='btn btn-icon switchBtn'");?>
        <?php echo html::a(inlink('managePriv', "type=byGroup&param=$groupID&nav=$nav&version=$version"), "<i class='icon-without-authority-pack'></i>", '', "class='btn btn-icon switchBtn text-primary'");?>
      </div>
      <table class='table table-hover table-striped table-bordered' id='privList'>
        <thead>
          <tr class='text-center'>
            <th class='module'><?php echo $lang->group->module;?></th>
            <th class='package'><?php echo $lang->privpackage->common;?></th>
            <th class='method' colspan='2'><?php echo $lang->group->method;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($subsets as $subsetName => $subset):?>
          <?php if($subset->allCount == 0) continue;?>
          <?php $i = 1;?>
          <?php foreach($packages[$subsetName] as $packageCode => $package):?>
          <tr class='<?php echo cycle('even, bg-gray');?>'>
            <?php if($i == 1):?>
            <th class='text-middle text-left module' rowspan="<?php echo $i == 1 ? count($packages[$subsetName]) : 1;?>" data-module='<?php echo $subsetName;?>' all-privs='<?php echo $subset->allCount;?>' select-privs='<?php echo $subset->selectCount;?>'>
              <div class="checkbox-primary checkbox-inline checkbox-left check-all">
                <input type='checkbox' id='allChecker<?php echo $subsetName;?>' value='1' <?php if($subset->selectCount != 0 && $subset->selectCount == $subset->allCount) echo 'checked';?>>
                <?php $subsetTitle = isset($lang->$subsetName) && isset($lang->$subsetName->common) ? $lang->$subsetName->common : $subsetName;?>
                <label class='text-left <?php if($subset->selectCount != 0 && $subset->selectCount == $subset->allCount) echo 'checkbox-indeterminate-block';?>' for='allChecker<?php echo $moduleName;?>'><?php echo $subsetTitle;?></label>
              </div>
            </th>
            <?php endif;?>
            <th class='<?php echo $i == 1 ? 'td-sm' : 'td-md';?> text-middle text-left package' data-module='<?php echo $subsetName;?>' data-package='<?php echo $packageCode;?>' all-privs='<?php echo $package->allCount;?>' select-privs='<?php echo $package->selectCount;?>'>
              <div class="checkbox-primary checkbox-inline checkbox-left check-all">
                <input type='checkbox' id='allCheckerModule<?php echo $subsetName;?>Package<?php echo $packageCode;?>' value='browse' <?php if($package->allCount == $package->selectCount) echo 'checked';?>>
                <label class='text-left <?php if($package->selectCount != 0 && $package->allCount != $package->selectCount) echo 'checkbox-indeterminate-block';?>' for='allCheckerPackage<?php echo $packageCode;?>'><?php echo $lang->group->package->$packageCode;?></label>
              </div>
            </th>
            <td id='<?php echo $subsetName;?>' class='pv-10px' colspan='2'>
              <?php foreach($package->privs as $privCode => $priv):?>
              <div class="group-item" data-module='<?php echo $subsetName;?>' data-package='<?php echo $packageCode;?>' data-id='<?php echo $privCode;?>'>
                <div class="checkbox-primary">
                  <?php echo html::checkbox("actions[$priv->module]", array($priv->method => $priv->name), isset($groupPrivs[$priv->module][$priv->method]) ? $priv->method : '', "title='{$priv->name}' id='actions[$priv->module]$priv->method' data-id='$privCode'");?>
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
<?php js::set('selectedPrivList', $selectedPrivList);?>
<?php js::set('allPrivList', $allPrivList);?>
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
        $(this).closest('.menus').find('input[name^=actions]:not(input[value=browse])').parent('.checkbox-primary').toggle();
    })

    /**
     * 勾选浏览列表标签时，自动勾选下面的所有标签。
     * Check all tabs when the Browse list tab is selected.
     */
    $('.menus input[value=browse]').change(function()
    {
        var checked = $(this).prop('checked');
        if(checked)
        {
            $(this).parents('.menus').find('[name^=actions]').attr('checked', 'checked');
        }
        else
        {
            $(this).parents('.menus').find('[name^=actions]').removeAttr('checked');
        }
        $(this).closest('.menus').find('.checkbox-indeterminate-block').removeClass('checkbox-indeterminate-block');
        changeParentChecked($(this), $(this).closest('td').attr('data-module'), $(this).closest('td').attr('data-package'));
    });

    /**
     * 勾选浏览列表标签下面的任意一个标签时，自动勾选浏览列表标签。
     * Check the browse list tab when any one of the tabs is selected.
     */
    $('.menus input[name^=actions]:not(input[value=browse])').click(function()
    {
        var checked = $(this).prop('checked');
        if(!checked) $(this).removeAttr('checked');
        if(checked)  $(this).attr('checked', 'checked');

        var $parent     = $(this).parents('.menus');
        var $browse     = $parent.find('.check-all');
        var selectPrivs = $parent.find('.checkbox-primary').not('.check-all').find('[checked=checked]').length;
        var allPrivs    = $parent.find('.checkbox-primary').not('.check-all').length;

        if(allPrivs > 0 && selectPrivs == allPrivs)
        {
            $browse.find('input').attr('checked', 'checked');
            $browse.find('.checkbox-indeterminate-block').removeClass('checkbox-indeterminate-block');
        }
        else if(selectPrivs == 0)
        {
            $browse.find('input').removeAttr('checked');
            $browse.find('.checkbox-indeterminate-block').removeClass('checkbox-indeterminate-block');
        }
        else
        {
            $browse.find('input').removeAttr('checked');
            $browse.find('label').addClass('checkbox-indeterminate-block');
        }
        changeParentChecked($(this), $(this).closest('td').attr('data-module'), $(this).closest('td').attr('data-package'));
    })
});
</script>
