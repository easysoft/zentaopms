<div class='panel panel-block' id='projectbox'>
<?php if(count($projectStats) == 0):?>
<div class='panel-heading'>
  <i class='icon-folder-close-alt icon'></i> <strong><?php echo $lang->my->home->projects;?></strong>
</div>
<div class='panel-body text-center'><br><br>
  <?php echo html::a($this->createLink('project', 'create'), "<i class='icon-plus'></i> " . $lang->my->home->createProject,'', "class='btn btn-primary'");?> &nbsp; &nbsp; <?php echo " <i class='icon-question-sign text-muted'></i> " . $lang->my->home->help; ?>
</div>
<?php else:?>
<table class='table table-condensed table-hover table-striped table-borderless table-fixed'>
  <thead>
    <tr class='text-center'>
      <th class='w-150px'><div class='text-left'><i class="icon-folder-close-alt icon"></i> <?php echo $lang->project->name;?></div></th>
      <th><?php echo $lang->project->end;?></th>
      <th><?php echo $lang->statusAB;?></th>
      <th><?php echo $lang->project->totalEstimate;?></th>
      <th><?php echo $lang->project->totalConsumed;?></th>
      <th><?php echo $lang->project->totalLeft;?></th>
      <th class='w-150px'><?php echo $lang->project->progess;?></th>
      <th class='w-100px'><?php echo $lang->project->burn;?></th>
    </tr>
  </thead>
  <tbody>
   <?php $id = 0; ?>
   <?php foreach($projectStats as $project):?>
    <tr class='text-center'>
      <td class='text-left'><?php echo html::a($this->createLink('project', 'task', 'project=' . $project->id), $project->name, '', "title=$project->name");?></td>
      <td><?php echo $project->end;?></td>
      <td class='project-<?php echo $project->status?>'><?php echo $lang->project->statusList[$project->status];?></td>
      <td><?php echo $project->hours->totalEstimate;?></td>
      <td><?php echo $project->hours->totalConsumed;?></td>
      <td><?php echo $project->hours->totalLeft;?></td>
      <td class='text-left w-150px'>
        <img class='progressbar' src='<?php echo $webRoot;?>theme/default/images/main/green.png' alt='' height='16' width='<?php echo $project->hours->progress == 0 ? 1 : round($project->hours->progress);?>'>
        <small><?php echo $project->hours->progress;?>%</small>
      </td>
      <td id='spark-<?php echo $id++?>' class='spark text-left pd-0' values='<?php echo join(',', $project->burns);?>'></td>
   </tr>
   <?php endforeach;?>
  </tbody>
</table>
<?php endif;?>
</div>
<script>
$(function()
{
    var $projectbox = $('#projectbox');
    var $sparks = $projectbox.find('.spark');
    $sparks.filter(':lt(6)').addClass('sparked').projectLine();
    $sparks = $sparks.not(':lt(6)');
    var rowHeight = $sparks.first().closest('tr').outerHeight() - (window.browser.ie === 8 ? 0.3 : 0);

    var scrollFn = false, scrollStart, i, id, $spark;
    $projectbox.on('scroll.spark', function(e)
    {
        if(!$sparks.length)
        {
            $projectbox.off('scroll.spark');
            return;
        }
        if(scrollFn) clearTimeout(scrollFn);

        scrollFn = setTimeout(function()
        {
            scrollStart = Math.floor(($projectbox.scrollTop() - 30) / (rowHeight)) + 1;
            for(i = scrollStart; i <= scrollStart + 7; i++)
            {
                id = '#spark-' + i;
                $spark = $(id);
                if($spark.hasClass('sparked')) continue;
                $spark.addClass('sparked').projectLine();
                $sparks = $sparks.not(id);
            }
        },100);
    });
});
</script>
