<?php include "../../common/view/datepicker.html.php"; ?>
<div id='featurebar'>
  <div class='f-left'>
  <?php
    if(!isset($type))   $type   = 'today';
    if(!isset($period)) $period = 'today';
    $date = isset($date) ? $date : helper::today();

    echo $userList . $lang->arrow;
    echo "<span id='todoTab'>";    common::printLink('user', 'todo',"account=$account", $lang->user->todo); echo '</span>';
    echo "<span id='taskTab'>";    common::printLink('user', 'task',"account=$account", $lang->user->task); echo '</span>';
    echo "<span id='bugTab'>" ;    common::printLink('user', 'bug', "account=$account", $lang->user->bug);  echo '</span>';
    echo "<span id='dynamicTab'>"; common::printLink('user', 'dynamic', "type=today&account=$account", $lang->user->dynamic); echo '</span>' ;
    echo "<span id='projectTab'>"; common::printLink('user', 'project', "account=$account", $lang->user->project); echo '</span>';
    echo "<span id='profileTab'>"; common::printLink('user', 'profile', "account=$account", $lang->user->profile); echo '</span>';

    $activedSpan = $this->app->getMethodName() . 'Tab';
    echo "<script>$('#$activedSpan').addClass('active')</script>";
    ?>
  </div>
</div>
<script>
var type   = '<?php echo $type;?>';
var period = '<?php echo $period;?>';
</script>
