<?php
/**
 * The browse view of budget module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     budget
 * @version     $Id: browse.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
.browse-active{color: #0c64eb;font-weight: 700;}
</style>
<div id="mainMenu" class="clearfix">
  <div class="pull-left">
    <?php common::printLink('budget', 'summary', '', "<i class='icon-common-report icon-bar-chart muted'></i> " . $lang->budget->summary, '', "class='btn btn-link'");?>
    <?php common::printLink('budget', 'browse', '', "<i class='icon icon-list-alt muted'></i> " . $lang->budget->list, '', "class='btn btn-link browse-active'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('budget', 'batchcreate', '', "<i class='icon icon-plus'></i>" . $lang->budget->batchCreate, '', "class='btn btn-secondary'");?>
    <?php common::printLink('budget', 'create', '', "<i class='icon icon-plus'></i>" . $lang->budget->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="main-col">
    <?php if(empty($budgets)):?>
    <div class="table-empty-tip">
      <p> 
        <span class="text-muted"><?php echo $lang->noData;?></span>
        <?php if(common::hasPriv('budget', 'create')):?>
        <?php echo html::a($this->createLink('budget', 'create', ''), "<i class='icon icon-plus'></i> " . $lang->budget->create, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <form class='main-table' method='post' id='dataForm'>
      <table class='table has-sort-head' id='dataList'>
        <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <thead>
          <tr>
            <th><?php echo common::printOrderLink('id', $orderBy, $vars, $lang->budget->id);?></th>
            <th><?php echo common::printOrderLink('name', $orderBy, $vars, $lang->budget->name);?></th>
            <th><?php echo common::printOrderLink('stage', $orderBy, $vars, $lang->budget->stage);?></th>
            <th><?php echo common::printOrderLink('subject', $orderBy, $vars, $lang->budget->subject);?></th>
            <th><?php echo common::printOrderLink('amount', $orderBy, $vars, $lang->budget->amount);?></th>
            <th><?php echo common::printOrderLink('createdBy', $orderBy, $vars, $lang->budget->createdBy);?></th>
            <th><?php echo common::printOrderLink('createdDate', $orderBy, $vars, $lang->budget->createdDate);?></th>
            <th><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($budgets as $budget):?>
          <tr>
            <td class='c-id'><?php printf('%03d', $budget->id);?></td>
            <td><?php echo html::a($this->createLink('budget', 'view', "id=$budget->id"), $budget->name);?></td>
            <td><?php echo zget($stages, $budget->stage);?></td>
            <td><?php echo zget($modules, $budget->subject);?></td>
            <td><?php echo $budget->amount;?></td>
            <td><?php echo zget($users, $budget->createdBy);?></td>
            <td><?php echo $budget->createdDate;?></td>
            <td class='c-actions'>
              <?php common::printIcon('budget', 'edit', "id=$budget->id", $budget, 'list');?>
              <?php common::printIcon('budget', 'delete', "id=$budget->id", $budget, 'list', '', 'hiddenwin');?>
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
    </form>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
