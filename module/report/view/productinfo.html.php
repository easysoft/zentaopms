<?php include '../../common/view/header.html.php';?>
<table class="cont-lt1">
  <tr valign='top'>
    <td class='side'>
      <?php include 'blockreportlist.html.php';?>
      <div class='proversion'><?php echo $lang->report->proVersion;?></div>
    </td>
    <td class='divider'></td>
    <td>
      <table class='table-1 fixed colored tablesorter datatable border-sep' id='product'>
        <thead>
        <tr class='colhead'>
          <th width='150'><?php echo $lang->product->name;?></th>
          <th width='150'><?php echo $lang->product->PO;?></th>
          <th width="200"><?php echo $lang->productplan->common;?></th>
          <th class="w-100px"><?php echo $lang->productplan->begin;?></th>
          <th class="w-100px"><?php echo $lang->productplan->end;?></th>
          <th width="w-50px"><?php echo $lang->story->statusList['draft'];?></th>
          <th width="w-50px"><?php echo $lang->story->statusList['active'];?></th>
          <th width="w-50px"><?php echo $lang->story->statusList['changed'];?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($products as $product):?>
          <tr class="a-center">
            <?php $count = isset($product->plans) ? count($product->plans) : 1;?>
            <td align='left' rowspan="<?php echo $count;?>"><?php echo '<p>' . $product->name . "</p>";?></td>
            <td align='left' rowspan="<?php echo $count;?>"><?php echo "<p>" . $users[$product->PO] . '</p>';?></td>
            <?php if(isset($product->plans)):?>
            <?php $id = 1;?>
            <?php foreach($product->plans as $plan):?>
            <?php if($id != 1) echo "<tr class='a-center'>"?>
              <td align='left'><?php echo $plan->title;?></td>
              <td><?php echo $plan->begin;?></td>
              <td><?php echo $plan->end;?></td>
              <td><?php echo (isset($plan->status['draft']) ? $plan->status['draft'] : 0);?></td>
              <td><?php echo (isset($plan->status['active']) ? $plan->status['active'] : 0);?></td>
              <td><?php echo (isset($plan->status['changed']) ? $plan->status['changed'] : 0);?></td>
            <?php if($id != 1) echo "</tr>"?>
            <?php $id ++;?>
            <?php endforeach;?>
            <?php else:?>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            <?php endif;?>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table> 
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
