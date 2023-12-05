<?php
/**
* The editmanageprivbylist view file of group module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
* @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Feilong Guo <guofeilong@easycorp.ltd>
* @package     group
* @version     $Id: editmanageprivbylist.html.php 4769 2021-07-23 11:16:21Z $
* @link        https://www.zentao.net
*/
?>
<?php $canBatchChangePackage = common::hasPriv('group', 'batchChangePackage');?>
<?php if(empty($privList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
  </p>
</div>
<?php else:?>
<form class="main-table" method="post" id="privForm" data-ride="table">
  <table class="table has-sort-head" id='privListTable'>
    <thead>
      <tr>
        <th class="c-name"><?php echo ($canBatchChangePackage ? "<div class='checkbox-primary check-all' title='{$this->lang->selectAll}'><label></label></div>" : '') . $lang->group->privName;?></th>
        <th class="c-view"><?php echo $lang->group->view;?></th>
        <th class="c-module"><?php echo $lang->group->module;?></th>
        <th class="c-package"><?php echo $lang->privpackage->belong;?></th>
        <th class="c-privs"><?php echo $lang->group->dependentPrivs;?></th>
        <th class="c-privs"><?php echo $lang->group->recommendPrivs;?></th>
        <th class="c-desc"><?php echo $lang->group->privDesc;?></th>
        <th class="c-actions-2 text-center"><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($privList as $priv):?>
      <tr data-id='<?php echo $priv->id;?>'>
        <td title='<?php echo $priv->name;?>'><?php echo ($canBatchChangePackage ? html::checkbox('privIdList', array($priv->id => '')) : '') . $priv->name;?></td>
        <?php $view = isset($this->lang->navGroup->{$priv->module}) ? $this->lang->navGroup->{$priv->module} : $priv->module;?>
        <td title='<?php echo isset($lang->{$view}->common) ? $lang->{$view}->common : $view;?>'><?php echo isset($lang->{$view}->common) ? $lang->{$view}->common : $view;?></td>
        <td title='<?php echo isset($moduleLang[$priv->module]) ? $moduleLang[$priv->module] : $priv->module;?>'><?php echo isset($moduleLang[$priv->module]) ? $moduleLang[$priv->module] : $priv->module;?></td>
        <td title='<?php echo zget($packages, $priv->parent, '');?>'><?php echo zget($packages, $priv->parent, '');?></td>
        <td title='<?php echo zget($privRelations['depend'], $priv->id, '');?>'><?php echo zget($privRelations['depend'], $priv->id, '');?></td>
        <td title='<?php echo zget($privRelations['recommend'], $priv->id, '');?>'><?php echo zget($privRelations['recommend'], $priv->id, '');?></td>
        <td title='<?php echo $priv->desc;?>'><?php echo $priv->desc;?></td>
        <td class='c-actions'>
          <?php if(common::hasPriv('group', 'editPriv')) common::printIcon('group', 'editPriv', "privID=$priv->id", '', 'list', 'edit', '', 'iframe', true);?>
          <?php if(common::hasPriv('group', 'deletePriv') and empty($priv->system)) common::printIcon('group', 'deletePriv', "privID=$priv->id", '', 'list', 'trash', 'hiddenwin');?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'>
    <?php if($canBatchChangePackage):?>
    <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
    <div class="table-actions btn-toolbar">
      <div class="btn-group dropup">
        <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->privpackage->common;?> <span class="caret"></span></button>
        <div class="dropdown-menu search-list search-box-sink" data-ride="searchList">
          <div class="input-control search-box has-icon-left has-icon-right search-example">
            <input id="packageSearchBox" type="search" autocomplete="off" class="form-control search-input">
            <label for="packageSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
            <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
          </div>
          <div class="list-group">
            <?php
            $packagesPinYin = common::convert2Pinyin($modulePackages);
            foreach($modulePackages as $id => $name)
            {
                $id         = explode(',', $id);
                $module     = $id[0];
                $packageID  = isset($id[1]) ? $id[1] : 0;
                $actionLink = $this->createLink('group', 'batchChangePackage', "module=$module&packageID=$packageID");
                echo html::a('#', $name, '', "data-key='" . zget($packagesPinYin, $name, '') . "' title='{$name}' onclick=\"setFormAction('$actionLink', 'hiddenwin', '#privForm')\"");
            }
            ?>
          </div>
        </div>
      </div>
    </div>
    <?php endif;?>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
</form>
<?php endif;?>
