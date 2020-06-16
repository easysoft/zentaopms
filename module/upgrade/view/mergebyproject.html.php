<div class='table-row'>
  <div class='table-col' id='source'>
    <div class='alert alert-info'>
      <?php
      printf($lang->upgrade->mergeSummary, $noMergedProductCount, $noMergedProjectCount);
      echo '<br />' . $lang->upgrade->mergeByProject;
      ?>
    </div>
    <table class='table table-form'>
      <thead>
        <tr>
          <th><?php echo $lang->upgrade->project;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($noMergedProjects as $projectID => $project):?>
        <tr>
          <td><?php echo html::checkBox("projects", array($project->id => "{$lang->projectCommon} #{$project->id} {$project->name}"), $project->id);?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
  <div class='table-col divider strong'></div>
  <div class='table-col pgmWidth' id='programBox'><?php include "./createprogram.html.php";?></div>
</div>
