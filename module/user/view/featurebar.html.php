<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<div id='featurebar'>
  <ul class='nav'>
  <?php
    if(!isset($type))   $type   = 'today';
    if(!isset($period)) $period = 'today';
    $date = isset($date) ? $date : helper::today();

    echo "<li class='w-150px'>" . $userList . '</li>';
    echo '<li> &nbsp; ' . $lang->arrow . ' &nbsp; </li>';
    echo "<li id='todoTab'>";    common::printLink('user', 'todo',     "account=$account", $lang->user->todo);  echo '</li>';
    echo "<li id='storyTab'>";   common::printLink('user', 'story',    "account=$account", $lang->user->story); echo '</li>';
    echo "<li id='taskTab'>";    common::printLink('user', 'task',     "account=$account", $lang->user->task);  echo '</li>';
    echo "<li id='bugTab'>" ;    common::printLink('user', 'bug',      "account=$account", $lang->user->bug);   echo '</li>';
    echo "<li id='testTab'>";    common::printLink('user', 'testtask', "account=$account", $lang->user->test);  echo '</li>';
    echo "<li id='dynamicTab'>"; common::printLink('user', 'dynamic',  "type=today&account=$account", $lang->user->dynamic); echo '</li>' ;
    echo "<li id='projectTab'>"; common::printLink('user', 'project',  "account=$account", $lang->user->project); echo '</li>';
    echo "<li id='profileTab'>"; common::printLink('user', 'profile',  "account=$account", $lang->user->profile); echo '</li>';

    $activedSpan = $this->app->getMethodName() . 'Tab';
    echo "<script>$('#$activedSpan').addClass('active')</script>";
    ?>
  </ul>
</div>
<script>
var type   = '<?php echo $type;?>';
var period = '<?php echo $period;?>';
</script>
