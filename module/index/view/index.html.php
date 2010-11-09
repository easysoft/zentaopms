<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/jquerytools.html.php';?>
<?php
function printStats($statusList, $stats)
{
    global $lang;
    $sum = array_sum($stats);
    $string = sprintf($lang->index->total, $sum);
    foreach($stats as $status => $value)
    {
        $percent = round($value / $sum, 2) * 100 . '%';
        $string .= strtolower($statusList[$status]) . " <strong>$value<small><i>($percent)</i></small></strong>$lang->comma ";
    }
    echo rtrim($string, $lang->comma) . $lang->dot;
}
?>
<script language='Javascript'>
$(function() 
{ 
    $("#projectbox").tabs("#projectbox .panes > div", {tabs: '.tabs a'});
    $("#mybox").tabs("#mybox .panes > div", {tabs: '.tabs a'});
});
</script>
<div class="yui-d0 yui-t6">  
  <div class='yui-main'>
    <div class='yui-b'>
      <div class='yui-g'>
        <table class='table-1' id='projectbox' height='240'>
          <caption><?php echo $lang->index->projects;?></caption>
          <tr>
            <td class='tabs' width='220'><?php foreach($projects as $project) echo "<a href='#' title='$project->name'>$project->name</a>";?></td>
            <td class='panes'>
              <?php foreach($projects as $key => $project):?>
              <div class='a-center'>
                <?php
                echo $burns[$project->id];
                echo html::a($this->createLink('project', 'browse', "projectid=$project->id"), $project->name);
                common::printLink('project', 'burn', "projectID=$project->id", $lang->project->largeBurnChart);
                common::printLink('project', 'computeBurn', 'reload=yes', $lang->project->computeBurn, 'hiddenwin');
                ?>
              </div>
              <?php endforeach;?>
            </td>
          </tr>
        </table>
      </div>
      <div class='yui-g'>
        <div class='yui-u first'>
          <table class='table-1' height='200'>
            <caption><?php echo $lang->index->stats;?></caption>
            <tr>
              <td><?php echo $lang->index->products;?></td>
              <td><?php printStats($lang->product->statusList, $stats['product']);?></td>
            </tr>
            <tr>
              <td><?php echo $lang->index->projects;?></td>
              <td><?php printStats($lang->project->statusList, $stats['project']);?></td>
            </tr>
            <tr>
              <td><?php echo $lang->index->tasks;?></td>
              <td><?php printStats($lang->task->statusList, $stats['task']);?></td>
            </tr>
            <tr>
              <td><?php echo $lang->index->stories;?></td>
              <td><?php printStats($lang->story->statusList, $stats['story']);?></td>
            </tr>
            <tr>
              <td><?php echo $lang->index->bugs;?></td>
              <td><?php printStats($lang->bug->statusList, $stats['bug']);?></td>
            </tr>
            <tr>
              <td><?php echo $lang->index->todos;?></td>
              <td><?php printStats($lang->todo->statusList, $stats['todo']);?></td>
            </tr>
          </table>
        </div>
        <div class='yui-u'>
          <table class='table-1 fixed' id='mybox' height='200'>
            <caption><?php echo $lang->index->my;?></caption>
            <tr>
              <td class='tabs' width='100'>
                <?php echo html::a('#', $lang->index->myTodo);?>
                <?php echo html::a('#', $lang->index->myTask);?>
                <?php echo html::a('#', $lang->index->myBug);?>
              </td>
              <td class='panes' valign='top' style='border-right:none'>
                <div class='mr-10px'>
                  <?php 
                  foreach($my['todos'] as $todo)
                  {
                      echo $lang->arrow . html::a($this->createLink('todo', 'view', "id=$todo->id"), $todo->name) . '<br />';
                  }
                  echo '<div class="a-right">' . html::linkButton($lang->more, $this->createLink('my', 'todo')) . '</div>';
                  ?>
                </div>
                <div class='mr-10px'>
                  <?php 
                  foreach($my['tasks'] as $taskID => $taskName)
                  {
                      echo $lang->arrow . html::a($this->createLink('task', 'view', "id=$taskID"), $taskName) . '<br />';
                  }
                  echo '<div class="a-right">' . html::linkButton($lang->more, $this->createLink('my', 'task')) . '</div>';
                  ?>
                </div>
                <div class='mr-10px'>
                  <?php 
                  foreach($my['bugs'] as $bugID => $bugTitle)
                  {
                      echo $lang->arrow . html::a($this->createLink('bug', 'view', "id=$bugID"), $bugTitle) . '<br />';
                  }
                  echo '<div class="a-right">' . html::linkButton($lang->more, $this->createLink('my', 'bug')) . '</div>';
                  ?>
                </div>
              </td>
              <td width='10' style='border-left:none'></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class='yui-b'>
    <table class='table-1 colored'>
      <caption><?php echo $lang->index->latest;?></caption>
      <?php 
      foreach($actions as $action)
      {
          $user = isset($users[$action->actor]) ? $users[$action->actor] : $action->actor;
          echo "<tr><td>";
          printf($lang->index->action, $action->date, $user, $action->actionLabel, $action->objectLabel, $action->objectLink);
          echo "</td></tr>";
      }
      ?>
    </table>
  </div>
</div>
<script language='Javascript'><?php for($i = 1; $i <= count($projects); $i ++) echo "createChart$i();"; ?></script>
<?php include '../../common/view/footer.html.php';?>
