<div class='panel-body scroll-table' style='padding: 0;'>
  <table class="table table-bordered">
    <thead>
      <tr>
        <?php count($stageList) === 0 ? $totalStoryTd = 4 : $totalStoryTd = 3;?>
        <th colspan="<?php echo $totalStoryTd + count($stageList);?>"><?php echo $lang->milestone->demandStatus;?></th>
      </tr>
      <tr>
        <th rowspan="2"><?php echo $lang->milestone->storyUnit;?></th>
        <th colspan="<?php echo count($stageList);?>" class="text-center"><?php echo $lang->milestone->engineeringStage;?></th>
        <th rowspan="2" colspan="2"><?php echo $lang->milestone->rateChange;?></th>
      </tr>
      <tr>
        <?php foreach($stageList as $stage):?>
        <th><?php echo $stage;?></th>
        <?php endforeach;?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><strong><?php echo $lang->milestone->originalStory;?></strong></td>
        <?php if(count($stageList) === 0) echo '<td rowspan="3"></td>';?>
        <?php foreach($stageList as $key => $stage):?>
        <td><?php echo $stageInfo['origin'][$key];?></td>
        <?php endforeach;?>
        <td colspan="2" rowspan="3">
        <?php
        $rateNumber = '0%';
        if(count($stageInfo['after']) && current($stageInfo['after']))
            $rateNumber = round((array_sum($stageInfo['change'])/current($stageInfo['origin'])) * 100, 2).'%';
            echo $rateNumber;
        ?>
        </td>
      </tr>
      <tr>
        <td><strong><?php echo $lang->milestone->modifyNumber;?></strong></td<>
        <?php foreach($stageList as $key => $stage):?>
        <td><?php echo $stageInfo['after'][$key];?></td>
        <?php endforeach;?>
      </tr>
      <tr>
        <td><strong><?php echo $lang->milestone->changeStory;?></strong></td>
        <?php foreach($stageList as $key => $stage):?>
        <td><?php echo $stageInfo['change'][$key];?></td>
        <?php endforeach;?>
      </tr>
    </tbody>
  </table>
</div>
