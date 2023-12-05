<?php
/**
 * The manage view file of branch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     branch
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php js::set('orderBy', $orderBy)?>
<?php js::set('branchLang', $lang->branch);?>
<?php js::set('productID', $productID);?>
<?php js::set('branchPairs', $branchPairs);?>
<?php $canCreate      = common::hasPriv('branch', 'create');?>
<?php $canOrder       = common::hasPriv('branch', 'sort');?>
<?php $canBatchEdit   = common::hasPriv('branch', 'batchEdit');?>
<?php $canMergeBranch = common::hasPriv('branch', 'mergeBranch');?>
<?php $canBatchAction = ($canBatchEdit or $canMergeBranch);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach(customModel::getFeatureMenu($this->moduleName, $this->methodName) as $menuItem):?>
    <?php if(isset($menuItem->hidden)) continue;?>
    <?php $label  = "<span class='text'>{$menuItem->text}</span>";?>
    <?php $label .= $menuItem->name == $browseType ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : '';?>
    <?php $active = $menuItem->name == $browseType ? 'btn-active-text' : '';?>
    <?php echo html::a($this->inlink('manage', "productID=$productID&browseType={$menuItem->name}&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), $label, '', "class='btn btn-link $active' id='{$menuItem->name}'");?>
    <?php endforeach;?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if($canCreate) common::printLink('branch', 'create', "productID=$productID", "<i class='icon icon-plus'></i> " . $lang->branch->create, '', "class='btn btn-primary iframe'", true, true);?>
  </div>
</div>
<div id="mainContent">
  <?php if(empty($branchList)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->branch->noData;?></span>
      <?php if($canCreate and $browseType != 'closed') echo html::a($this->createLink('branch', 'create', "productID=$productID", '', true), "<i class='icon icon-plus'></i> " . $lang->branch->create, '', "class='btn btn-info iframe'");?>
    </p>
  </div>
  <?php else:?>
  <form class='main-table' data-ride='table' method='post' id='branchForm'>
    <table id="branchList" class="table has-sort-head">
      <thead>
        <tr>
          <?php $vars = "productID=$productID&browseType=$browseType&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID"; ?>
          <?php if($canBatchAction):?>
          <th class='c-check'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>"><label></label></div>
          </th>
          <?php endif;?>
          <?php if($canOrder):?>
          <th class='c-order sort-default'><?php echo $lang->branch->order;?></th>
          <?php endif;?>
          <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->branch->name);?></th>
          <th class='c-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->branch->status);?></th>
          <th class='c-date'><?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->branch->createdDate);?></th>
          <th class='c-date'><?php common::printOrderLink('closedDate', $orderBy, $vars, $lang->branch->closedDate);?></th>
          <th class='c-desc'><?php echo $lang->branch->desc;?></th>
          <th class='c-actions-2'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody id='branchTableList'>
        <?php foreach($branchList as $branch):?>
        <?php $isMain = $branch->id == BRANCH_MAIN;?>
        <tr data-id='<?php echo $branch->id;?>' data-status='<?php echo $branch->status;?>'>
          <?php if($canBatchAction):?>
          <td class='cell-id'>
            <?php echo html::checkbox('branchIDList', array($branch->id => ''));?>
          </td>
          <?php endif;?>
          <?php if($canOrder):?>
          <td class='c-actions <?php echo $isMain ? '' : 'sort-handler';?>'>
            <?php echo $isMain ? '' : '<i class="icon icon-move"></i>';?>
          </td>
          <?php endif;?>
          <td class='c-name branchName' title='<?php echo $branch->name;?>'>
            <span class="text-ellipsis"><?php echo $branch->name;?>&nbsp;</span>
            <?php
            /* Fix bug#17342,由于默认分支的后续功能没做,暂时隐藏设置默认分支的功能 */
            //if($branch->default)
            //{
            //    echo '<span class="label label-primary label-badge">' . $lang->branch->default . '</span>';
            //}
            //elseif($branch->status == 'active')
            //{
            //    $setDefaultLink = helper::createLink('branch', 'setDefault', "productID=$productID&branchID=$branch->id", '', true);
            //    $setDefaultHtml = html::a($setDefaultLink, "<span><i class='icon icon-hand-right'></i> {$lang->branch->setDefault}</span>", 'hiddenwin', "class='btn btn-icon-left btn-sm setDefault hidden'");

            //    echo common::hasPriv('branch', 'setDefault') ? $setDefaultHtml : '';
            //}
            ?>
          </td>
          <td><?php echo zget($lang->branch->statusList, $branch->status);?></td>
          <td><?php echo helper::isZeroDate($branch->createdDate) ? '' : $branch->createdDate;?></td>
          <td><?php echo helper::isZeroDate($branch->closedDate) ? '' : $branch->closedDate;?></td>
          <td class='c-name' title='<?php echo $branch->desc;?>'><?php echo $branch->desc;?></td>
          <td class='c-actions'>
          <?php
            if(!$isMain)
            {
                common::printIcon('branch', 'edit', "branchID=$branch->id&productID=$productID", $branch, 'list', '', '', 'iframe', true, '', $lang->branch->edit);
                if($branch->status == 'active')
                {
                    common::printIcon('branch', 'close', "branchID=$branch->id", $branch, 'list', 'off', 'hiddenwin');
                }
                else
                {
                    common::printIcon('branch', 'activate', "branchID=$branch->id", $branch, 'list', 'active', 'hiddenwin');
                }
            }
          ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <?php if($canBatchAction):?>
      <div class="checkbox-primary check-all">
        <label><?php echo $lang->selectAll?></label>
      </div>
      <?php if($canBatchEdit):?>
      <div class="table-actions btn-toolbar">
        <?php
        $batchEditLink = $this->createLink('branch', 'batchEdit', "productID=$productID");
        echo html::submitButton($lang->edit, "data-form-action='$batchEditLink'", 'btn');
        if(($browseType != 'closed' and common::hasPriv('branch', 'mergeBranch'))) echo html::a('#mergeBranch', $lang->branch->merge, '', "data-toggle='modal' class='btn' id='merge'");
        ?>
      </div>
      <?php endif;?>
      <?php endif;?>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>

<div class="modal fade" id="mergeBranch">
  <div class="modal-dialog mw-700px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <span class="modal-title"><?php echo $lang->branch->mergeBranch;?></span>
      </div>
      <div class="modal-body">
        <form method='post' enctype='multipart/form-data' class="form-ajax">
          <table class='table table-form'>
            <tr>
              <td colspan="8">
                <div class="alert no-margin tips">
                  <i class="icon-exclamation-sign icon"></i>
                  <div id="marginTips">
                    <p><?php echo $lang->branch->mergedMain;?></p>
                    <p><?php echo $lang->branch->mergeTips;?></p>
                    <p><?php echo $lang->branch->targetBranchTips;?></p>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <th><?php echo $lang->branch->mergeTo;?></th>
              <td colspan="7">
                <div class="input-group">
                  <?php echo html::select('targetBranch', $branchPairs, '', "class='form-control chosen'");?>
                  <span class='input-group-addon'>
                    <?php echo html::checkbox('createBranch', $lang->branch->create, '', "id='createBranch'")?>
                  </span>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan='8' class='text-center form-actions'>
                <?php echo html::commonButton($lang->save, "id='saveButton'", 'btn btn-primary btn-wide');?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
