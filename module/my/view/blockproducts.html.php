<div class='block' id='productbox' style="height:180px; overflow-y:auto">
<?php if(empty($productStats)):?>
<table class='table-1 a-center' height='100%'>
  <caption><?php echo $lang->my->home->products;?></caption>
  <tr>
    <td valign='middle'>
    <?php 
    $productLink = $this->createLink('product', 'create');
    printf($lang->my->home->noProductsTip, $productLink);
    ?>
    </td>
  </tr>
</table>
<?php else:?>
  <table class='table-1 colored fixed'>
    <tr class='colhead'>
      <th class='w-150px'><?php echo $lang->product->name;?></th>
      <th><?php echo $lang->story->statusList['active']  . $lang->story->common;?></th>
      <th><?php echo $lang->story->statusList['changed'] . $lang->story->common;?></th>
      <th><?php echo $lang->story->statusList['draft']   . $lang->story->common;?></th>
      <th><?php echo $lang->story->statusList['closed']  . $lang->story->common;?></th>
      <th><?php echo $lang->product->plans;?></th>
      <th><?php echo $lang->product->releases;?></th>
    </tr>
    <?php foreach($productStats as $product):?>
    <tr class='a-center' style='height:30px'>
      <td class='a-left'><?php echo html::a($this->createLink('product', 'view', 'product=' . $product->id), $product->name);?></td>
      <td><?php echo $product->stories['active']?></td>
      <td><?php echo $product->stories['changed']?></td>
      <td><?php echo $product->stories['draft']?></td>
      <td><?php echo $product->stories['closed']?></td>
      <td><?php echo $product->plans?></td>
      <td><?php echo $product->releases?></td>
    </tr>
    <?php endforeach;?>
  </table>
<?php endif;?>
</div>
