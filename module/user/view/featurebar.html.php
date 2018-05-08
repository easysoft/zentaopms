<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
  <?php
    $date = isset($date) ? $date : helper::today();

    echo "<div class='input-control w-150px'>" . $userList . "</div>";

    $methodName = $this->app->getMethodName();

    if($config->global->flow == 'full')
    {
        $label  = "<span class='text'>{$lang->user->todo}</span>";
        $active = $methodName == 'todo' ? ' btn-active-text' : '';
        echo html::a($this->createLink('user', 'todo', "account=$account"), $label, '', "class='btn btn-link $active'");
    }

    if($config->global->flow != 'onlyTask' and $config->global->flow != 'onlyTest')
    {
        $label  = "<span class='text'>{$lang->user->story}</span>";
        $active = $methodName == 'story' ? ' btn-active-text' : '';
        echo html::a($this->createLink('user', 'story', "account=$account"), $label, '', "class='btn btn-link $active'");
    }

    if($config->global->flow == 'full' or $config->global->flow == 'onlyTask') 
    {
        $label  = "<span class='text'>{$lang->user->task}</span>";
        $active = $methodName == 'task' ? ' btn-active-text' : '';
        echo html::a($this->createLink('user', 'task', "account=$account"), $label, '', "class='btn btn-link $active'");
    }

    if($config->global->flow == 'full' or $config->global->flow == 'onlyTest') 
    {
        $label  = "<span class='text'>{$lang->user->bug}</span>";
        $active = $methodName == 'bug' ? ' btn-active-text' : '';
        echo html::a($this->createLink('user', 'bug', "account=$account"), $label, '', "class='btn btn-link $active'");

        $label  = "<span class='text'>{$lang->user->test}</span>";
        $active = ($methodName == 'testtask' or $methodName == 'testcase')? ' btn-active-text' : '';
        echo html::a($this->createLink('user', 'testtask', "account=$account"), $label, '', "class='btn btn-link $active'");
    }

    $label  = "<span class='text'>{$lang->user->dynamic}</span>";
    $active = $methodName == 'dynamic' ? ' btn-active-text' : '';
    echo html::a($this->createLink('user', 'dynamic',  "type=today&account=$account"), $label, '', "class='btn btn-link $active'");

    if($config->global->flow == 'full' or $config->global->flow == 'onlyTask')
    {
        $label  = "<span class='text'>{$lang->user->project}</span>";
        $active = $methodName == 'project' ? ' btn-active-text' : '';
        echo html::a($this->createLink('user', 'project',  "account=$account"), $label, '', "class='btn btn-link $active'");
    }

    $label  = "<span class='text'>{$lang->user->profile}</span>";
    $active = $methodName == 'profile' ? ' btn-active-text' : '';
    echo html::a($this->createLink('user', 'profile',  "account=$account"), $label, '', "class='btn btn-link $active'");
    ?>
  </div>
</div>
