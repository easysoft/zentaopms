<div class='block linkbox1' id='productbox'>
<?php if(empty($productStats)):?>
<table class='table-1 a-center bg-gray' height='138px'>
  <caption><span class='icon-title'></span><?php echo $lang->my->home->products;?></caption>
  <tr>
    <td valign='middle'>
      <table class='a-left bd-none' align='center'>
        <tr>
          <td valign='top'><span class='icon-notice'></span></td>
          <td><?php printf($lang->my->home->noProductsTip, $this->createLink('product', 'create'));?></td>
        </tr>
        <tr>
          <td><span class='icon-help'></span></td>
          <td><?php echo $lang->my->home->help; ?></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php else:?>
<table class='mainTable'>
  <tr>
    <td>
      <table class='headTable'>
        <tr class='colhead'>
          <th class='w-100px'><?php echo $lang->product->name;?></th>
          <th class='w-60px'><?php echo $lang->story->statusList['active']  . $lang->story->common;?></th>
          <th class='w-60px'><?php echo $lang->story->statusList['changed'] . $lang->story->common;?></th>
          <th class='w-60px'><?php echo $lang->story->statusList['draft']   . $lang->story->common;?></th>
          <th class='w-60px'><?php echo $lang->story->statusList['closed']  . $lang->story->common;?></th>
          <th class='w-60px'><?php echo $lang->product->plans;?></th>
          <th class='w-60px'><?php echo $lang->product->releases;?></th>
          <th class='w-60px'><?php echo $lang->product->bugs;?></th>
          <th class='w-60px'><?php echo $lang->bug->unResolved;?></th>
          <th class='w-60px'><?php echo $lang->bug->assignToNull;?></th>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <div class='contentDiv1'>
      <table class='table-1 fixed colored'>
        <?php foreach($productStats as $product):?>
        <tr class='a-center' style='height:30px'>
          <td class='a-left w-100px'><?php echo html::a($this->createLink('product', 'view', 'product=' . $product->id), $product->name);?></td>
          <td class='w-60px'><?php echo $product->stories['active']?></td>
          <td class='w-60px'><?php echo $product->stories['changed']?></td>
          <td class='w-60px'><?php echo $product->stories['draft']?></td>
          <td class='w-60px'><?php echo $product->stories['closed']?></td>
          <td class='w-60px'><?php echo $product->plans?></td>
          <td class='w-60px'><?php echo $product->releases?></td>
          <td class='w-60px'><?php echo $product->bugs?></td>
          <td class='w-60px'><?php echo $product->unResolved?></td>
          <td class='w-60px'><?php echo $product->assignToNull?></td>
        </tr>
        <?php endforeach;?>
      </table>
      </div>
    </td>
  </tr>
</table>
<?php endif;?>
</div>
