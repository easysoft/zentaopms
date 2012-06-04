<div class='block linkbox2'>
<table class='mainTable'>
  <tr>
    <td>
      <table class='headTable'>
        <caption>
          <div class='f-left'><span class='icon-doing'></span><?php echo $lang->my->task;?></div>
          <div class='f-right'><?php echo html::a($this->createLink('my', 'task'), $lang->more . "<span class='icon-more'></span>");?></div>
        </caption>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <div class='contentDiv2'>
      <table class='table-1 fixed colored'>
      <?php 
      foreach($tasks as $task)
      {
          echo "<tr><td class='nobr'>" . "#$task->id " . html::a($this->createLink('task', 'view', "id=$task->id"), $task->name) . "</td><td width='5'</td></tr>";
      }
      ?>
      </table>
      </div>
    </td>
  </tr>
</table>
</div>
