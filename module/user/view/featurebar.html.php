<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
  <?php
    if(!isset($type))   $type   = 'today';
    if(!isset($period)) $period = 'today';
    $date = isset($date) ? $date : helper::today();

    echo "<li class='w-150px'>" . $userList . '</li>';

    if($config->global->flow == 'full')
    {
        $label  = "<span class='text'>{$lang->user->todo}</span>";
        echo html::a($this->createLink('user', 'todo', "account=$account"), $label);
    }

    if($config->global->flow != 'onlyTask' and $config->global->flow != 'onlyTest')
    {
        echo html::a($this->createLink('user', 'story', "account=$account"), $lang->user->story);
    }

    if($config->global->flow == 'full' or $config->global->flow == 'onlyTask') 
    {
        echo html::a($this->createLink('user', 'task', "account=$account"), $lang->user->task);
    }

    if($config->global->flow == 'full' or $config->global->flow == 'onlyTest') 
    {
        echo html::a($this->createLink('user', 'bug', "account=$account"), $lang->user->bug);
        echo html::a($this->createLink('user', 'testtask', "account=$account"), $lang->user->test);
    }
    echo html::a($this->createLink('user', 'dynamic',  "type=today&account=$account"), $lang->user->dynamic);

    if($config->global->flow == 'full' or $config->global->flow == 'onlyTask')
    {
        echo html::a($this->createLink('user', 'project',  "account=$account"), $lang->user->project);
    }
    echo html::a($this->createLink('user', 'profile',  "account=$account"), $lang->user->profile);

    $activedSpan = $this->app->getMethodName() . 'Tab';
    echo "<script>$('#$activedSpan').addClass('active')</script>";
    ?>
  </div>
</div>
<script>
var type   = '<?php echo $type;?>';
var period = '<?php echo $period;?>';
</script>
