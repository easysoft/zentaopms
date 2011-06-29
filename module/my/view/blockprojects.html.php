<div class='block'>
<?php if(count($projectStats['projects']) == 0):?>
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
      <?php foreach($projectStats['projects'] as $id => $project):?>
       <h2 class='tab-title' ><?php echo $lang->my->home->projects . $lang->colon . $project->name;?></h2>
       <div class='pane a-center'>
       <?php
       echo $projectStats['burns'][$project->id];
       echo html::a($this->createLink('project', 'browse', "projectid=$project->id"), $lang->my->home->projectHome);
       common::printLink('project', 'burn', "projectID=$project->id", $lang->project->largeBurnChart);
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
