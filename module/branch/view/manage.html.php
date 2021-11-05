<?php
/**
 * The manage view file of branch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     branch
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach(customModel::getFeatureMenu($this->moduleName, $this->methodName) as $menuItem):?>
    <?php if(isset($menuItem->hidden)) continue;?>
    <?php $label   = "<span class='text'>{$menuItem->text}</span>";?>
    <?php $label  .= $menuItem->name == $browseType ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : '';?>
    <?php $active  = $menuItem->name == $browseType ? 'btn-active-text' : '';?>
    <?php echo html::a($this->inlink('manage', "productID=$productID&browseType={$menuItem->name}&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), $label, '', "class='btn btn-link $active' id='{$menuItem->name}'");?>
    <?php endforeach;?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('branch', 'create')):?>
    <?php common::printLink('branch', 'create', "productID=$productID", "<i class='icon icon-plus'></i>" . $lang->branch->create, '', "class='btn btn-primary'");?>
    <?php endif;?>
  </div>
</div>
<div id="mainContent">
  <?php if(empty($branchList)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->branch->noData;?></span>
      <?php if(common::hasPriv('branch', 'create')):?>
      <?php echo html::a($this->createLink('branch', 'create', "productID=$productID"), "<i class='icon icon-plus'></i> " . $lang->branch->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class='main-table table-branch' data-ride='table' method='post' id='branchForm'>
    <table id="branchList" class="table has-sort-head">
      <thead>
        <tr>
          <?php $vars = "productID=$productID&browseType=$browseType&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID"; ?>
          <?php if(common::hasPriv('branch', 'batchEdit') or common::hasPriv('branch', 'mergeBranch')):?>
          <th class='w-40px'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>"><label></label></div>
          </th>
          <?php endif;?>
          <th class='w-70px sort-default'><?php echo $lang->branch->order;?></th>
          <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->branch->name);?></th>
          <th class='w-200px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->branch->status);?></th>
          <th class='w-150px'><?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->branch->createdDate);?></th>
          <th class='w-150px'><?php common::printOrderLink('closedDate', $orderBy, $vars, $lang->branch->closedDate);?></th>
          <th class='w-500px'><?php echo $lang->branch->desc;?></th>
          <th class='c-actions-2'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody class="sortable">
        <?php foreach($branchList as $branch):?>
        <tr>
          <?php if(common::hasPriv('branch', 'batchEdit') or common::hasPriv('branch', 'mergeBranch')):?>
          <td class='cell-id'>
            <?php echo html::checkbox('branchIDList', array($branch->id => ''));?>
          </td>
          <?php endif;?>
          <td class='c-actions sort-handler'><i class="icon icon-move"></i></td>
          <td class='c-name' title='<?php echo $branch->name;?>'><?php echo $branch->name;?></td>
          <td><?php echo zget($lang->branch->statusList, $branch->status);?></td>
          <td><?php echo helper::isZeroDate($branch->createdDate) ? '' : $branch->createdDate;?></td>
          <td><?php echo helper::isZeroDate($branch->closedDate) ? '' : $branch->closedDate;?></td>
          <td class='c-name' title='<?php echo $branch->desc;?>'><?php echo $branch->desc;?></td>
          <td class='c-actions'>
          <?php
            common::printIcon('branch', 'edit', "branchID=$branch->id", $branch, 'list');
            if($branch->status == 'active')
            {
                common::printIcon('branch', 'closed', "branchID=$branch->id", $branch, 'list', 'off');
            }
            else
            {
                common::printIcon('branch', 'active', "branchID=$branch->id", $branch, 'list', 'active');
            }
          ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <?php if(common::hasPriv('branch', 'batchEdit') or common::hasPriv('branch', 'mergeBranch')):?>
      <div class="checkbox-primary check-all">
        <label><?php echo $lang->selectAll?></label>
      </div>
      <div class="table-actions btn-toolbar">
        <?php echo html::submitButton($lang->edit, '', 'btn');?>
      </div>
      <div class="table-actions btn-toolbar">
        <?php echo html::submitButton($lang->branch->merge, '', 'btn');?>
      </div>
      <?php endif;?>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
