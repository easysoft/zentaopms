<?php include '../../common/view/header.html.php';?>
<style>
#product td, tr, th{ border:1px solid #E4E4E4;}
</style>
<table class="cont-lt1">
  <tr valign='top'>
    <td class='side'>
      <div class='box-title'><?php echo $lang->report->list;?></div>
      <div class='box-content'>
      <ul id="report-list">
        <li><?php echo html::a(inlink('productInfo'), $lang->report->productInfo);?></li>
      </ul>
      </div>
    </td>
    <td class='divider'></td>
    <td>
      <table class='table-1 fixed colored tablesorter datatable border-sep' id='product'>
        <thead>
        <tr class='colhead'>
          <th width='150'><?php echo $lang->product->name;?></th>
          <th class="w-100px"><?php echo $lang->productplan->common;?></th>
          <th><?php echo $lang->productplan->desc;?></th>
          <th class="w-80px"><?php echo $lang->productplan->begin;?></th>
          <th class="w-80px"><?php echo $lang->productplan->end;?></th>
          <th width="300" colspan='4'><?php echo $lang->report->details;?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($products as $product):?>
          <tr class="a-center">
            <?php $count = isset($product->plans) ? count($product->plans) : 1;?>
            <td align='left' rowspan="<?php echo $count;?>"><?php echo '<p>' . $product->name . "</p><p>{$lang->product->PO}: " . $product->PO . '</p>';?></td>
            <?php if(isset($product->plans)):?>
            <?php foreach($product->plans as $id => $plan):?>
            <?php if($id != 0) echo "<tr class='a-center'>"?>
              <td align='left'><?php echo $plan->title;?></td>
              <td align='left'><?php echo $plan->desc;?></td>
              <td><?php echo $plan->begin;?></td>
              <td><?php echo $plan->end;?></td>
              <td align='left'><?php echo $lang->story->statusList['draft']   . ' : ' . (isset($plan->status['draft']) ? $plan->status['draft'] : 0);?></td>
              <td align='left'><?php echo $lang->story->statusList['active']  . ' : ' . (isset($plan->status['active']) ? $plan->status['active'] : 0);?></td>
              <td align='left'><?php echo $lang->story->statusList['closed']  . ' : ' . (isset($plan->status['closed']) ? $plan->status['closed'] : 0);?></td>
              <td align='left'><?php echo $lang->story->statusList['changed'] . ' : ' . (isset($plan->status['changed']) ? $plan->status['changed'] : 0);?></td>
            <?php if($id != 0) echo "</tr>"?>
            <?php endforeach;?>
            <?php else:?>
              <td></td>
              <td></td>
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
