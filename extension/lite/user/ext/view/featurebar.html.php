<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
  <?php
    if(!isset($type))   $type   = 'today';
    if(!isset($period)) $period = 'today';
    $date = isset($date) ? $date : helper::today();

    echo "<div class='input-control w-120px'>" . $userList . "</div>";

    $methodName = $this->app->getMethodName();

    $label = "<span class='text'>{$lang->user->schedule}</span>";
    if($this->config->edition != 'open' and common::hasPriv('user', 'todocalendar'))
    {
        $link = $this->createLink('user', 'todocalendar', "userID={$user->id}");
    }
    elseif($this->config->edition != 'open' and common::hasPriv('user', 'effortcalendar'))
    {
        $link = $this->createLink('user', 'effortcalendar', "userID={$user->id}");
    }
    elseif(common::hasPriv('user', 'todo'))
    {
        $link = $this->createLink('user', 'todo', "userID={$user->id}");
    }
    elseif(common::hasPriv('user', 'effort'))
    {
        $link = $this->createLink('user', 'effort', "userID={$user->id}");
    }

    if($link)
    {
        $active = '';
        if($methodName == 'todocalendar' or $methodName == 'todo' or $methodName == 'effortcalendar' or $methodName == 'effort') $active = ' btn-active-text';
        echo html::a($link, $label, '', "class='btn btn-link $active todoTab'");
    }

    $label  = "<span class='text'>{$lang->user->task}</span>";
    $active = $methodName == 'task' ? ' btn-active-text' : '';
    common::printLink('user', 'task', "userID={$user->id}", $label, '', "class='btn btn-link $active taskTab'");

    $label  = "<span class='text'>{$lang->SRCommon}</span>";
    $active = ($methodName == 'story' and $storyType == 'story')  ? ' btn-active-text' : '';
    common::printLink('user', 'story', "userID={$user->id}&storyType=story", $label, '', "class='btn btn-link $active SRTab'");

    if($this->config->systemMode == 'ALM')
    {
        $label  = "<span class='text'>{$lang->user->execution}</span>";
        $active = $methodName == 'execution' ? ' btn-active-text' : '';
        common::printLink('user', 'execution',  "userID={$user->id}", $label, '', "class='btn btn-link $active executionTab'");
    }

    $label  = "<span class='text'>{$lang->user->dynamic}</span>";
    $active = $methodName == 'dynamic' ? ' btn-active-text' : '';
    common::printLink('user', 'dynamic',  "userID={$user->id}&type=today", $label, '', "class='btn btn-link $active dynamicTab'");

    $label  = "<span class='text'>{$lang->user->profile}</span>";
    $active = $methodName == 'profile' ? ' btn-active-text' : '';
    common::printLink('user', 'profile',  "userID={$user->id}", $label, '', "class='btn btn-link $active profileTab'");
    ?>
  </div>
  <div class='actions'></div>
</div>
<script>
var type   = '<?php echo $type;?>';
var period = '<?php echo $period;?>';
</script>
