<div class='block linkbox1' id='projectbox'>
<?php if(count($projectStats) == 0):?>
<table class='table-1 a-center bg-gray' height='138px'>
  <caption><span class='icon-title'></span><?php echo $lang->my->home->projects;?></caption>
  <tr>
    <td valign='middle'>
      <table class='a-left bd-none' align='center'>
        <tr>
          <td><span class='icon-notice'></span></td>
          <td><?php printf($lang->my->home->noProjectsTip, $this->createLink('project', 'create'));?></td>
        </tr>
        <tr>
          <td></td>
          <td class='h-30px'><?php echo $lang->my->home->otherNoTip;?></td>
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
          <th class='w-150px'><?php echo $lang->project->name;?></th>
          <th><?php echo $lang->project->end;?></th>
          <th><?php echo $lang->statusAB;?></th>
          <th><?php echo $lang->project->totalEstimate;?></th>
          <th><?php echo $lang->project->totalConsumed;?></th>
          <th><?php echo $lang->project->totalLeft;?></th>
          <th class='w-120px'><?php echo $lang->project->progess;?></th>
          <th class='w-100px'><?php echo $lang->project->burn;?></th>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <div class='contentDiv1'>
      <table class='table-1 fixer colored'>
        <?php foreach($projectStats as $project):?>
        <tr class='a-center'>
          <td class='a-left'><?php echo html::a($this->createLink('project', 'task', 'project=' . $project->id), $project->name);?></td>
          <td><?php echo $project->end;?></td>
          <td><?php echo $lang->project->statusList[$project->status];?></td>
          <td><?php echo $project->hours->totalEstimate;?></td>
          <td><?php echo $project->hours->totalConsumed;?></td>
          <td><?php echo $project->hours->totalLeft;?></td>
          <td class='a-left w-150px'>
            <?php if($project->hours->progress):?><img src='<?php echo $defaultTheme;?>images/main/green.png' width=<?php echo $project->hours->progress;?> height='13' text-align: /><?php endif;?>
            <small><?php echo $project->hours->progress;?>%</small>
          </td>
          <td class='projectline a-left' values='<?php echo join(',', $project->burns);?>'></td>
        </tr>
        <?php endforeach;?>
      </table>
      </div>
    </td>
  </tr>
</table>
<?php endif;?>
</div>
