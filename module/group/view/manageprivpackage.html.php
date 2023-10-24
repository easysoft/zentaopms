<?php
/**
 * The managePrivPackage view file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     group
 * @version     $Id: manageprivpackage.html.php 4769 2023-03-07 10:09:21Z liumengyi $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php js::set('canSortPackage', common::hasPriv('group', 'sortPrivPackages') ? 1 : 0);?>
<div id="mainMenu" class='clearfix'>
  <div class="btn-toolbar pull-left">
    <?php common::printBack(inlink('editManagePriv', ''), 'btn btn-primary');?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="text" title='<?php echo $lang->group->managePrivPackage;?>'><?php echo $lang->group->managePrivPackage;?></span>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('group', 'createPrivPackage')) echo html::a($this->createLink('group', 'createPrivPackage', '', '', true), $lang->group->createPrivPackage, '', 'class="btn btn-primary iframe" data-width="500"');?>
  </div>
</div>
<div id='mainContent' class='main-table'>
  <form class='main-table' id='privPackageForm' method='post' data-ride='table' data-nested='true' data-expand-nest-child='false' data-checkable='false' data-enable-empty-nested-row='true' data-replace-id='privPackageTableList' data-preserve-nested='true' data-nest-level-indent='22'>
    <table class='table has-sort-head table-fixed table-nested' id='privPackageList'>
      <thead>
        <tr>
          <th class='table-nest-title c-name'>
            <?php echo $lang->privpackage->common;?>
            <a class='table-nest-toggle table-nest-toggle-global' data-expand-text='<?php echo $lang->expand; ?>' data-collapse-text='<?php echo $lang->collapse;?>'></a>
          </th>
          <th class='c-desc'><?php echo $lang->privpackage->desc;?></th>
          <th class='text-center c-actions-2'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody id='privPackageTableList'>
        <?php foreach($packagesTreeList as $package):?>
        <tr <?php echo "data-id='$package->id' data-order='$package->order' data-parent='$package->parent' data-level='$package->grade' data-type='$package->type' data-nest-parent='$package->parent' data-nest-path='$package->path'"?>>
          <td class='sort-handler text-left has-prefix' title='<?php echo $package->name?>'><?php echo $package->name?></td>
          <td class='sort-handler text-left' title='<?php echo $package->desc?>'><?php echo $package->desc?></td>
          <td class='c-actions'>
            <?php
            if(common::hasPriv('group', 'editPrivPackage') and $package->grade == 3) common::printIcon('group', 'editPrivPackage', "packageID=$package->id", '', 'list', 'edit', '', 'iframe', true);
            if(common::hasPriv('group', 'deletePrivPackage') and $package->grade == 3) common::printIcon('group', 'deletePrivPackage', "packageID=$package->id", '', 'list', 'trash', 'hiddenwin');
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>

