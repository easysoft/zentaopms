<?php
/**
 * The import release view of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie<xieqiyu@cnezsoft.com>
 * @package     kanban
 * @version     $Id: importrelease.html.php 5090 2022-01-19 14:19:24Z xieqiyu@cnezsoft.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('kanbanID', $kanbanID);?>
<?php js::set('regionID', $regionID);?>
<?php js::set('groupID', $groupID);?>
<?php js::set('columnID', $columnID);?>
<?php js::set('methodName', $this->app->rawMethod);?>
<div id='mainContent' class='main-content importModal'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->kanban->importAB . $lang->kanban->importTicket;?></h2>
    </div>
  </div>
  <div class='input-group space'>
    <span class='input-group-addon'><?php echo $lang->kanban->selectedProduct;?></span>
    <?php echo html::select('product', $products, $selectedProductID, "onchange='reloadObjectList(this.value)' class='form-control chosen' data-drop_direction='down'");?>
    <span class='input-group-addon'><?php echo $lang->kanban->selectedLane;?></span>
    <?php echo html::select('lane', $lanePairs, '', "onchange='setTargetLane(this.value)' class='form-control chosen' data-drop_direction='down'");?>
  </div>
  <?php if($tickets2Imported):?>
  <form class='main-table' method='post' data-ride='table' target='hiddenwin' id='importTicketForm'>
    <table class='table table-fixed' id='ticketList'>
      <thead>
        <tr>
          <th class="c-id">
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php echo $lang->idAB;?>
          </th>
          <th class='c-name'><?php echo $lang->ticket->title;?></th>
          <th class='c-name'><?php echo $lang->ticket->product;?></th>
          <th class='c-pri'><?php echo $lang->ticket->priAB;?></th>
          <th class='c-status'><?php echo $lang->ticket->status;?></th>
          <th class='c-type'><?php echo $lang->ticket->type;?></th>
          <th><?php echo $lang->ticket->createdDate;?></th>
          <th class='c-name'><?php echo $lang->ticket->assignedTo;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($tickets2Imported as $ticket):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='tickets[]' value='<?php echo $ticket->id;?>'/>
              <label></label>
            </div>
            <?php printf('%03d', $ticket->id);?>
          </td>
          <?php if(common::hasPriv('ticket', 'view')):?>
          <td title='<?php echo $ticket->title;?>'>
            <a href='javascript:void(0);' onclick="locateView('ticket', <?php echo $ticket->id;?>)"><?php echo $ticket->title;?></a>
          </td>
          <?php else:?>
          <td title='<?php echo $ticket->title;?>'><?php echo $ticket->title;?></td>
          <?php endif;?>
          <td title='<?php echo zget($products, $ticket->product);?>'><?php echo zget($products, $ticket->product);?></td>
          <td><span class='label-pri label-pri-<?php echo $ticket->pri;?>' title='<?php echo zget($this->lang->ticket->priList, $ticket->pri, $ticket->pri);?>'><?php echo zget($this->lang    ->ticket->priList, $ticket->pri, $ticket->pri); ?></span></td>
          <td title='<?php echo zget($this->lang->ticket->statusList, $ticket->status, $ticket->status);?>'><?php echo zget($this->lang->ticket->statusList, $ticket->status, $ticket->status);?></td>
          <td title='<?php echo zget($this->lang->ticket->typeList, $ticket->type, $ticket->type);?>'><?php echo zget($this->lang->ticket->typeList, $ticket->type, $ticket->type);?></td>
          <td title='<?php echo $ticket->openedDate;?>'><?php echo $ticket->openedDate;?></td>
          <td title='<?php echo zget($users, $ticket->assignedTo);?>'><?php echo zget($users, $ticket->assignedTo);?></td>
        </tr>
        <?php endforeach;?>
        <tr><?php echo html::hidden('targetLane', key($lanePairs));?></tr>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar show-always"><?php echo html::submitButton($lang->kanban->importAB, '', 'btn btn-default');?></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php else:?>
  <div class='table-empty-tip'><?php echo $lang->noData;?></div>
  <?php endif;?>
</div>
<style>#product_chosen {width: 45% !important}</style>
<?php include '../../common/view/footer.lite.html.php';?>
