<div class='block'>
<?php if(count($projectStats['charts']) == 0):?>
<table class='table-1 a-center' height='100%'>
  <caption><?php echo $lang->my->home->projects;?></caption>
  <tr>
    <td valign='middle'><?php printf($lang->my->home->noProjectsTip, $this->createLink('project', 'create'));?></td>
  </tr>
</table>
<?php else:?>
  <table class='table-1 tab-box' id='projectbox'>
    <tr>
      <td>
      <?php foreach($projectStats['charts'] as $projectID => $chart):?>
       <h2 class='tab-title' ><?php echo $lang->my->home->projects . $lang->colon . $projectStats['projects'][$projectID]->name;?></h2>
       <div class='pane a-center'>
       <?php
       echo $chart;
       echo html::a($this->createLink('project', 'browse', "projectid=$projectID"), $lang->my->home->projectHome);
       common::printLink('project', 'burn', "projectID=$projectID", $lang->project->largeBurnChart);
       common::printLink('project', 'computeBurn', 'reload=yes', $lang->project->computeBurn, 'hiddenwin');
       printf($lang->project->howToUpdateBurn, $this->createLink('help', 'field', 'module=project&method-burn&field=updateburn'));
       ?>
       </div>
       <?php endforeach;?>
      </td>
    </tr>
  </table>
<?php endif;?>
</div>
