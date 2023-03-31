<?php if($this->config->edition != 'open'):?>
<style>#mainContent > .side-col.col-lg{width: 235px}</style>
<style>.hide-sidebar #sidebar{width: 0 !important}</style>
<?php endif;?>
<?php if(empty($assigns)):?>
<div class="cell">
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->error->noData;?></span></p>
  </div>
</div>
<?php else:?>
<div class='cell'>
  <div class='panel'>
    <div class="panel-heading">
      <div class="panel-title"><?php echo $title;?></div>
      <nav class="panel-actions btn-toolbar"></nav>
    </div>
    <div data-ride='table'>
      <table class='table table-condensed table-striped table-bordered table-fixed no-margin' id='bugAssign'>
        <thead>
          <tr class='colhead text-center'>
            <th><?php echo $lang->pivot->user;?></th>
            <th><?php echo $lang->pivot->product;?></th>
            <th><?php echo $lang->pivot->bugTotal;?></th>
            <th><?php echo $lang->pivot->total;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($assigns as $account => $assign):?>
          <?php if(!array_key_exists($account, $users)) continue;?>
          <tr class="a-center text-center">
            <td rowspan="<?php echo count($assign['bug']);?>"><?php echo $users[$account];?></td>
            <?php $id = 1;?>
            <?php foreach($assign['bug'] as $product => $count):?>
            <?php if($id != 1) echo '<tr class="a-center text-center">';?>
            <td>
              <?php $viewLink = empty($count['projectID']) ? $this->createLink('product', 'view', "product={$count['productID']}") : $this->createLink('project', 'view', "projectID={$count['projectID']}");?>
              <?php echo html::a($viewLink, $product);?>
            </td>
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
  </div>
</div>
<?php endif;?>
<script><?php helper::import('../js/bugassign.js');?></script>
