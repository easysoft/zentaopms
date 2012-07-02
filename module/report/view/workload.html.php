<?php include '../../common/view/header.html.php';?>
<table class="cont-lt1">
  <tr valign='top'>
    <td class='side'>
      <div class='box-title'><?php echo $lang->report->list;?></div>
      <div class='box-content'>
      <ul id="report-list">
        <li><?php echo html::a(inlink('workload'), $lang->report->workload, '', 'id="workload"');?></li>
      </ul>
      </div>
    </td>
    <td class='divider'></td>
    <td>
      <table class='table-1 fixed colored tablesorter datatable border-sep' id="workload">
        <thead>
        <tr class='colhead'>
          <th><?php echo $lang->report->user;?></th>
          <th colspan="4"><?php echo $lang->report->task;?></th>
          <th colspan="3"><?php echo $lang->report->bug;?></th>
        </tr>
        </thead>
        <tbody>
          <tr class="a-center">
            <td></td>
            <td><?php echo $lang->report->project;?></td>
            <td><?php echo $lang->report->task;?></td>
            <td><?php echo $lang->report->remain;?></td>
            <td><?php echo $lang->report->total;?></td>
            <td><?php echo $lang->report->product;?></td>
            <td><?php echo $lang->report->bug;?></td>
            <td><?php echo $lang->report->total;?></td>
          </tr>
        <?php foreach($workload as $account => $load):?>
        <?php
        $i = 1;
        $taskNum = empty($load['task']) ? 0 : count($load['task']);
        $bugNum  = empty($load['bug'])  ? 0 : count($load['bug']);
        $max     = $taskNum > $bugNum ? 'task' : 'bug';
        ?>
        <?php foreach($load[$max] as $key => $val):?>
          <tr class="a-center">
          <?php if($i == 1):?>
          <td rowspan="<?php echo count($load[$max]);?>"><?php echo $users[$account];?></td>
          <?php endif;?>
            <?php if($max == 'task'):?>
            <td><?php echo $key?></td>
            <td><?php echo $val['count']?></td>
            <td><?php echo $val['manhour']?></td>
            <?php if($i == 1):?>
            <td rowspan='<?php echo count($load[$max]);?>'>
            <?php
            $total = 0;
            foreach($load['task'] as $count) $total += $count['count'];
            echo $total;
            ?>
            </td>
            <?php endif;?>
            <td><?php echo $product = empty($load['bug']) ? '' : key($load['bug'])?></td>
            <td><?php echo empty($product) ? '' : $load['bug'][$product]['count']?></td>
            <?php if($i == 1):?>
            <td rowspan='<?php echo count($load[$max]);?>'>
            <?php
            $total = 0;
            if(!empty($load['bug']))
            {
                foreach($load['bug'] as $count) $total += $count['count'];
                reset($load['bug']);
            }
            echo $total;
            ?>
            </td>
            <?php endif;?>
            <?php unset($load['bug'][$product]);?>
            <?php else:?>
            <td><?php echo $project = empty($load['task']) ? '' : key($load['task'])?></td>
            <td><?php echo empty($project) ? '' : $load['task'][$project]['count']?></td>
            <td><?php echo empty($project) ? '' : $load['task'][$project]['manhour']?></td>
            <?php if($i == 1):?>
            <td rowspan='<?php echo count($load[$max]);?>'>
            <?php
            $total = 0;
            if(!empty($load['task']))
            {
                foreach($load['task'] as $count) $total += $count['count'];
                reset($load['task']);
            }
            echo $total;
            ?>
            </td>
            <?php endif;?>
            <td><?php echo $key?></td>
            <td><?php echo $val['count']?></td>
            <?php if($i == 1):?>
            <td rowspan='<?php echo count($load[$max]);?>'>
            <?php
            $total = 0;
            foreach($load['bug'] as $count) $total += $count['count'];
            echo $total;
            ?>
            </td>
            <?php endif;?>
            <?php unset($load['task'][$project]);?>
            <?php endif;?>
          </tr>
          <?php $i ++;?>
          <?php endforeach;?>
        <?php endforeach;?>
        </tbody>
      </table> 
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
