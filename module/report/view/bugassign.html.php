<?php include '../../common/view/header.html.php';?>
<table class="cont-lt1">
  <tr valign='top'>
    <td class='side'>
      <?php include 'blockreportlist.html.php';?>
    </td>
    <td class='divider'></td>
    <td>
      <table class='table-1 fixed colored tablesorter datatable border-sep' id="workload">
        <thead>
        <tr class='colhead'>
          <th><?php echo $lang->report->user;?></th>
          <th><?php echo $lang->report->product;?></th>
          <th><?php echo $lang->report->bug;?></th>
          <th><?php echo $lang->report->total;?></th>
        </tr>
        </thead>
        <tbody>
          <?php foreach($assigns as $account => $assign):?>
            <?php if(!array_key_exists($account, $users)) continue;?>
            <tr class="a-center">
              <td rowspan="<?php echo count($assign['bug']);?>"><?php echo $users[$account];?></td>
              <?php $id = 1;?>
              <?php foreach($assign['bug'] as $product => $count):?>
              <?php if($id != 1) echo '<tr class="a-center">';?>
              <td><?php echo $product;?></td>
              <td><?php echo $count['count'];?></td>
              <?php if($id == 1):?>
              <td rowspan="<?php echo count($assign['bug']);?>">
                  <?php echo $assign['total']['count'];?>
              </td>
              <?php endif;?>
              <?php if($id != 1) echo '</tr>'; $id ++;?>
              <?php endforeach;?>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table> 
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
