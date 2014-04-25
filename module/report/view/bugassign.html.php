<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['report-file']);?></span>
    <strong> <?php echo $title;?></strong>
  </div>
</div>
<div class='side'>
  <?php include 'blockreportlist.html.php';?>
  <div class='panel panel-body' style='padding: 10px 6px'>
    <div class='text proversion'>
      <strong class='text-danger small text-latin'>PRO</strong> &nbsp;<span class='text-important'><?php echo $lang->report->proVersion;?></span>
    </div>
  </div>
</div>
<div class='main'>
  <table class='table table-condensed table-striped table-bordered tablesorter table-fixed active-disabled' id='bugAssign'>
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
          <td><?php echo html::a($this->createLink('product', 'view', "product={$count['productID']}"), $product);?></td>
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
</div>
<?php include '../../common/view/footer.html.php';?>
