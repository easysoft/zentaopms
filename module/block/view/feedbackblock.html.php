<?php if(empty($feedbacks)): ?>
<div class='empty-tip'><?php echo $lang->block->emptyTip;?></div>
<?php else:?>
<style>
.block-feedbacks .c-id {width: 50px;}
</style>
<div class='panel-body has-table scrollbar-hover'>
  <table class='table table-borderless table-fixed table-fixed-head table-hover tablesorter block-feedbacks <?php if(!$longBlock) echo 'block-sm'?>'>
    <thead>
      <tr>
        <th class='c-id'><?php echo $lang->idAB?></th>
        <th class='c-product'><?php echo $lang->feedback->product;?></th>
        <th class='c-title'><?php echo $lang->feedback->title?></th>
        <th class='c-type'><?php echo $lang->feedback->type;?></th>
        <th class='c-openedBy'><?php echo $lang->feedback->openedBy;?></th>
        <th class='c-openedDate'><?php echo $lang->feedback->openedDate;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($feedbacks as $feedback):?>
      <?php
      $appid = isset($_GET['entry']) ? "class='app-btn' data-id='{$this->get->entry}'" : '';
      ?>
      <tr>
        <td><?php echo sprintf('%03d', $feedback->id);?></td>
        <td><?php echo zget($products, $feedback->product);?></td>
        <td class='c-title' title='<?php echo $feedback->title?>'><?php echo html::a($this->createLink('feedback', 'view', "feedbackID=$feedback->id"), $feedback->title, '', "data-app='my'")?></td>
        <td><?php echo zget($lang->feedback->typeList, $feedback->type)?></td>
        <td><?php echo zget($users, $feedback->openedBy)?></td>
        <td><?php echo $feedback->openedDate;?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php endif;?>
