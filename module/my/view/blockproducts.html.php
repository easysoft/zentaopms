<div class='panel panel-block' id='productbox'>
<?php if(empty($productStats)):?>
<div class='panel-heading'>
  <i class='icon-cube icon'></i> <strong><?php echo $lang->my->home->products;?></strong>
</div>
<div class='panel-body text-center'><br><br>
  <?php echo html::a($this->createLink('product', 'create'), "<i class='icon-plus'></i> " . $lang->my->home->createProduct,'', "class='btn btn-primary'");?> &nbsp; &nbsp; <?php echo " <i class='icon-question-sign text-muted'></i> " . $lang->my->home->help; ?>
</div>
<?php else:?>
<table class='table table-condensed table-hover table-striped table-borderless table-fixed'>
  <thead>
    <tr class='text-center'>
      <th class='w-150px text-left'><i class="icon icon-cube"></i> <?php echo $lang->product->name;?></th>
      <th title='<?php echo $lang->story->common;?>'><?php echo $lang->story->statusList['active'];?></th>
      <th title='<?php echo $lang->story->common;?>'><?php echo $lang->story->statusList['changed'];?></th>
      <th title='<?php echo $lang->story->common;?>'><?php echo $lang->story->statusList['draft'];?></th>
      <th title='<?php echo $lang->story->common;?>'><?php echo $lang->story->statusList['closed'];?></th>
      <th><?php echo $lang->product->plans;?></th>
      <th><?php echo $lang->product->releases;?></th>
      <th><?php echo $lang->product->bugs;?></th>
      <th title='<?php echo $lang->bug->common;?>'><?php echo $lang->bug->unResolved;?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($productStats as $product):?>
    <tr class='text-center'>
      <td class='text-left'><?php echo html::a($this->createLink('product', 'browse', 'productID=' . $product->id), $product->name, '', "title=$product->name");?></td>
      <td><?php echo $product->stories['active']?></td>
      <td><?php echo $product->stories['changed']?></td>
      <td><?php echo $product->stories['draft']?></td>
      <td><?php echo $product->stories['closed']?></td>
      <td><?php echo $product->plans?></td>
      <td><?php echo $product->releases?></td>
      <td><?php echo $product->bugs?></td>
      <td><?php echo $product->unResolved?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
<?php endif;?>
</div>
