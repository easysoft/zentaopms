<?php
include '../../common/view/header.lite.html.php';
include '../../common/view/chart.html.php';
?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span><?php echo html::icon($lang->icons['report']);?></span>
      <?php echo $lang->story->tasks;?>
    </h2>
  </div>
  <div class='tasksList'>
    <table class='table table-fixed'>
      <thead>
        <tr class='text-center'>
          <th class='w-40px'>    <?php echo $lang->idAB;?></th>
          <th class='w-p30'>   <?php echo $lang->task->name;?></th>
          <th class='w-pri'>   <?php echo $lang->priAB;?></th>
          <th class='w-status'><?php echo $lang->statusAB;?></th>
          <th class='w-user'>  <?php echo $lang->task->assignedToAB;?></th>
          <th class='w-40px'>  <?php echo $lang->task->estimateAB;?></th>
          <th class='w-40px'>  <?php echo $lang->task->consumedAB;?></th>
          <th class='w-40px'>  <?php echo $lang->task->leftAB;?></th>
          <th class='w-40px'>  <?php echo $lang->task->progress;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($tasks as $key => $task):?>
        <tr class='text-center'>
          <td><?php echo $task->id;?></td>
          <td class='text-left' title="<?php echo $task->name?>"><?php echo $task->name;?></td>
          <td><span class='<?php echo 'pri' . zget($lang->task->priList, $task->pri, $task->pri)?>'><?php echo $task->pri == '0' ? '' : zget($lang->task->priList, $task->pri, $task->pri);?></span></td>
          <td><?php echo $this->processStatus('task', $task);?></td>
          <td><?php echo zget($users, $task->assignedTo, $task->assignedTo);?></td>
          <td><?php echo $task->estimate;?></td>
          <td><?php echo $task->consumed;?></td>
          <td><?php echo $task->left;?></td>
          <td><div class='progress-pie' title="<?php echo $task->progress?>%" data-value='<?php echo $task->progress;?>'></div></td>
        </tr>
          <?php if(!empty($task->children)):?>
          <?php $i = 0;?>
          <?php foreach($task->children as $key => $child):?>
          <?php $class  = $i == 0 ? ' table-child-top' : '';?>
          <?php $class .= ($i + 1 == count($task->children)) ? ' table-child-bottom' : '';?>
        <tr class='text-center<?php echo $class;?> parent-<?php echo $child->parent;?>'>
          <td><?php echo $child->id;?></td>
          <td class='text-left' title="<?php echo $child->name?>"><?php echo '<span class="label label-bedge label-light" title="' . $this->lang->task->children . '">' . $this->lang->task->childrenAB . '</span>' ?><?php echo $child->name;?></td>
          <td><span class='<?php echo 'pri' . zget($lang->task->priList, $child->pri, $child->pri)?>'><?php echo $child->pri == '0' ? '' : zget($lang->task->priList, $child->pri, $child->pri);?></span></td>
          <td><?php echo $this->processStatus('task', $child);?></td>
          <td><?php echo zget($users, $child->assignedTo, $child->assignedTo);?></td>
          <td><?php echo $child->estimate;?></td>
          <td><?php echo $child->consumed;?></td>
          <td><?php echo $child->left;?></td>
          <td><div class='progress-pie' title="<?php echo $child->progress?>%" data-value='<?php echo $child->progress;?>'></div></td>
        </tr>
          <?php $i ++;?>
          <?php endforeach;?>
          <?php endif;?>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="9"><?php echo $summary;?></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
