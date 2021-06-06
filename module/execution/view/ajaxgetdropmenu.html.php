<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<?php
$iCharges = 0;
$others   = 0;
$dones    = 0;
$executionNames = array();
$myProjectsHtml     = '';
$normalProjectsHtml = '';
$closedProjectsHtml = '';
foreach($executions as $execution)
{
    if($execution->status != 'done' and $execution->status != 'closed' and ($execution->PM == $this->app->user->account or isset($execution->teams[$this->app->user->account]))) $iCharges++;
    if($execution->status != 'done' and $execution->status != 'closed' and $execution->PM != $this->app->user->account and !isset($execution->teams[$this->app->user->account])) $others++;
    if($execution->status == 'done' or $execution->status == 'closed') $dones++;
    $executionNames[] = $execution->name;
}
$executionsPinYin = common::convert2Pinyin($executionNames);

foreach($executions as $execution)
{
    $selected      = $execution->id == $executionID ? 'selected' : '';
    $executionName = $config->systemMode == 'new' ? zget($projects, $execution->project) . '/' . $execution->name : $execution->name;
    if($execution->status != 'done' and $execution->status != 'closed' and ($execution->PM == $this->app->user->account or isset($execution->teams[$this->app->user->account])))
    {
        $myProjectsHtml .= html::a(sprintf($link, $execution->id), $executionName, '', "class='text-important $selected' title='{$executionName}' data-key='" . zget($executionsPinYin, $execution->name, '') . "' data-app='{$this->app->openApp}'");
    }
    else if($execution->status != 'done' and $execution->status != 'closed' and $execution->PM != $this->app->user->account and !isset($execution->teams[$this->app->user->account]))
    {
        $normalProjectsHtml .= html::a(sprintf($link, $execution->id), $executionName, '', "class='$selected' title='{$executionName}' data-key='" . zget($executionsPinYin, $execution->name, '') . "' data-app='{$this->app->openApp}'");
    }
    else if($execution->status == 'done' or $execution->status == 'closed') $closedProjectsHtml .= html::a(sprintf($link, $execution->id), $executionName, '', "class='$selected' title='{$executionName}' data-key='" . zget($executionsPinYin, $execution->name, '') . "' data-app='{$this->app->openApp}'");
}
?>
<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
      <?php
      if(!empty($myProjectsHtml))
      {
          echo "<div class='heading'>{$lang->execution->involved}</div>";
          echo $myProjectsHtml;
          if(!empty($myProjectsHtml))
          {
              echo "<div class='heading'>{$lang->execution->other}</div>";
          }
      }
      echo $normalProjectsHtml;
      ?>
    </div>
    <div class="col-footer">
      <a class='pull-right toggle-right-col not-list-item'><?php echo $lang->execution->doneExecutions;?><i class='icon icon-angle-right'></i></a>
    </div>
  </div>
  <div class="table-col col-right">
   <div class='list-group'><?php echo $closedProjectsHtml;?></div>
  </div>
</div>
<script>scrollToSelected();</script>
