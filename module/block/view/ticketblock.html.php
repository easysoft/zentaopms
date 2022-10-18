<?php if(empty($tickets)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<style>
.block-tickets .c-id {width: 50px;}
</style>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-fixed table-fixed-head table-hover tablesorter block-tickets <?php if(!$longBlock) echo 'block-sm'?>'>
    <thead>
      <tr>
        <th class='c-id'><?php echo $lang->idAB?></th>
        <th class='c-product'><?php echo $lang->ticket->product;?></th>
        <th class='c-title'><?php echo $lang->ticket->title?></th>
        <th class='c-type'><?php echo $lang->ticket->type;?></th>
        <th class='c-openedBy'><?php echo $lang->ticket->createdBy;?></th>
        <th class='c-openedDate'><?php echo $lang->ticket->createdDate;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($tickets as $ticket):?>
      <?php
      $appid = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      ?>
      <tr>
        <td><?php echo sprintf('%03d', $ticket->id);?></td>
        <td><?php echo zget($products, $ticket->product);?></td>
        <td class='c-title' title='<?php echo $ticket->title?>'><?php echo html::a($this->createLink('ticket', 'view', "ticketID=$ticket->id"), $ticket->title, '', "data-app='my'")?></td>
        <td><?php echo zget($lang->ticket->typeList, $ticket->type)?></td>
        <td><?php echo zget($users, $ticket->openedBy)?></td>
        <td><?php echo $ticket->openedDate;?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
