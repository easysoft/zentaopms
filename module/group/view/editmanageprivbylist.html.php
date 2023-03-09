<?php
/**
* The editmanageprivbylist view file of group module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
* @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Feilong Guo <guofeilong@easycorp.ltd>
* @package     group
* @version     $Id: editmanageprivbylist.html.php 4769 2021-07-23 11:16:21Z $
* @link        https://www.zentao.net
*/

$lang->privp = new stdclass();
$lang->privp->p1      = '依赖权限';
$lang->privp->p2      = '推荐权限';
?>
<?php $canBatchChangePackage = common::hasPriv('group', 'batchChangePackage');?>
<form class="main-table" method="post" id="privForm" data-ride="table">
  <table class="table has-sort-head" id='privList'>
    <thead>
      <tr>
        <th class="c-name"><?php echo ($canBatchChangePackage ? "<div class='checkbox-primary check-all' title='{$this->lang->selectAll}'><label></label></div>" : '') . $lang->group->privName;?></th>
        <th class="c-view"><?php echo $lang->group->view;?></th>
        <th class="c-module"><?php echo $lang->group->module;?></th>
        <th class="c-package"><?php echo $lang->privpackage->belong;?></th>
        <th class="c-privs"><?php echo $lang->privp->p1;?></th>
        <th class="c-privs"><?php echo $lang->privp->p2;?></th>
        <th class="c-actions-1 text-center"><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($privList as $priv):?>
      <tr>
        <td title='<?php echo $priv->name;?>'><?php echo ($canBatchChangePackage ? html::checkbox('privIdList', array($priv->id => '')) : '') . $priv->name;?></td>
        <?php $view = isset($this->lang->navGroup->{$priv->module}) ? $this->lang->navGroup->{$priv->module} : $priv->module;?>
        <td title='<?php echo isset($lang->{$view}->common) ? $lang->{$view}->common : $view;?>'><?php echo isset($lang->{$view}->common) ? $lang->{$view}->common : $view;?></td>
        <td title='<?php echo isset($moduleLang[$priv->module]) ? $moduleLang[$priv->module] : $priv->module;?>'><?php echo isset($moduleLang[$priv->module]) ? $moduleLang[$priv->module] : $priv->module;?></td>
        <td title='<?php echo zget($packages, $priv->package, '');?>'><?php echo zget($packages, $priv->package, '');?></td>
        <td title='<?php echo '';?>'><?php echo '';?></td>
        <td title='<?php echo '';?>'><?php echo '';?></td>
        <td class='c-actions'><?php if(common::hasPriv('group', 'editPriv')) common::printIcon('group', 'editPriv', "privID=$priv->id", '', 'list', 'edit', '', 'iframe', true);?></td>
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
                $packageID = explode(',', $id);
                $packageID = isset($packageID[1]) ? $packageID[1] : $packageID[0];
                $actionLink = $this->createLink('group', 'batchChangePackage', "packageID=$packageID");
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
