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
  <table class='table table-condensed table-striped table-bordered tablesorter table-fixed active-disabled' id="workload">
    <thead>
    <tr class='colhead'>
      <th><?php echo $lang->report->user;?></th>
      <th><?php echo $lang->report->project;?></th>
      <th><?php echo $lang->report->task;?></th>
      <th><?php echo $lang->report->remain;?></th>
      <th><?php echo $lang->report->taskTotal;?></th>
      <th><?php echo $lang->report->manhourTotal;?></th>
    </tr>
    </thead>
    <tbody>
    <?php $color = false;?>
    <?php foreach($workload as $account => $load):?>
      <?php if(!array_key_exists($account, $users)) continue;?>
      <tr class="a-center">
        <td rowspan="<?php echo count($load['task']);?>"><?php echo $users[$account];?></td>
        <?php $id = 1;?>
        <?php foreach($load['task'] as $project => $info):?>
        <?php $class = $color ? 'rowcolor' : '';?>
        <?php if($id != 1) echo '<tr class="a-center">';?>
        <td class="<?php echo $class;?>"><?php echo html::a($this->createLink('project', 'view', "projectID={$info['projectID']}"), $project);?></td>
        <td class="<?php echo $class;?>"><?php echo $info['count'];?></td>
        <td class="<?php echo $class;?>"><?php echo $info['manhour'];?></td>
        <?php if($id == 1):?>
        <td rowspan="<?php echo count($load['task']);?>">
            <?php echo $load['total']['count'];?>
        </td>
        <td rowspan="<?php echo count($load['task']);?>">
            <?php echo $load['total']['manhour'];?>
        </td>
        <?php endif;?>
        <?php if($id != 1) echo '</tr>'; $id ++;?>
        <?php $color = !$color;?>
        <?php endforeach;?>
      </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
