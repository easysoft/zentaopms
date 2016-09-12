<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
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
  <div class='input-group w-400px input-group-sm'>
    <span class='input-group-addon'><?php echo $lang->projectCommon . $lang->report->beginAndEnd;?></span>
    <div class='datepicker-wrapper datepicker-date'><?php echo html::input('date', $begin, "class='w-100px form-control form-date' onchange='changeDate(this.value, \"$end\")'");?></div>
    <span class='input-group-addon'><?php echo $lang->report->to;?></span>
    <div class='datepicker-wrapper datepicker-date'><?php echo html::input('date', $end, "class='form-control form-date' onchange='changeDate(\"$begin\", this.value)'");?></div>
  </div>
  <table class='table table-condensed table-striped table-bordered tablesorter table-fixed active-disabled'>
    <thead>
        <tr class='colhead'>
          <th class='w-id'><?php echo $lang->report->id;?></th>
          <th><?php echo $lang->report->project;?></th>
          <th class="w-100px"><?php echo $lang->report->estimate;?></th>
          <th class="w-100px"><?php echo $lang->report->consumed;?></th>
          <th class="w-100px"><?php echo $lang->report->deviation;?></th>
          <th class="w-100px"><?php echo $lang->report->deviationRate;?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($projects as $id  =>$project):?>
      <tr class="a-center">
        <td><?php echo $id;?></td>
        <td align="left"><?php echo html::a($this->createLink('project', 'view', "projectID=$id"), $project->name);?></td>
        <td><?php echo $project->estimate;?></td>
        <td><?php echo $project->consumed;?></td>
        <?php $deviation = $project->consumed - $project->estimate;?>
        <td class="deviation">
        <?php 
            if($deviation > 0)
            {
                echo '<span class="up">&uarr;</span>' . $deviation;
            }
            else if($deviation < 0)
            {
                echo '<span class="down">&darr;</span>' . abs($deviation);
            }
            else
            {
                echo '<span class="zero">0</span>'; 
            }
        ?>
        </td>
        <td class="deviation">
          <?php 
            if($project->estimate)
            {
                $num = round($deviation / $project->estimate * 100, 2);
                if($num >= 50)
                {
                    echo '<span class="u50">' . $num . '%</span>';
                }
                else if($num >= 30)
                {
                    echo '<span class="u30">' . $num . '%</span>';
                }
                else if($num >= 10)
                {
                    echo '<span class="u10">' . $num . '%</span>';
                }
                else if($num > 0)
                {
                    echo '<span class="u0">' . abs($num) . '%</span>';
                }
                else if($num <= -20)
                {
                    echo '<span class="d20">' . abs($num) . '%</span>';
                }
                else if($num < 0)
                {
                    echo '<span class="d0">' . abs($num) . '%</span>';
                }
                else
                {
                    echo '<span class="zero">' . abs($num) . '%</span>';
                }
            }
            else
            {
                echo '<span class="zero">0%</span>';
            }
          ?>
        </td>
      </tr>
    <?php endforeach;?>
    </tbody>
  </table> 
</div>
<?php include '../../common/view/footer.html.php';?>
