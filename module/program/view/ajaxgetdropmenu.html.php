<?php js::set('programID', $programID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<?php
$iCharges = 0;
$others   = 0;
$dones    = 0;
$programNames = array();
$myProgramsHtml     = '';
$normalProgramsHtml = '';
$closedProgramsHtml = '';
foreach($programs as $program)
{
    if($program->status != 'done' and $program->status != 'closed' and $program->PM == $this->app->user->account) $iCharges++;
    if($program->status != 'done' and $program->status != 'closed' and !($program->PM == $this->app->user->account)) $others++;
    if($program->status == 'done' or $program->status == 'closed') $dones++;
    $programNames[] = $program->name;
}
$programsPinYin = common::convert2Pinyin($programNames);

foreach($programs as $program)
{
    if($program->status != 'done' and $program->status != 'closed' and $program->PM == $this->app->user->account)
    {
        $myProgramsHtml .= html::a($link, "<i class='icon icon-folder-outline'></i> " . $program->name, '', "class='text-important transfer' title='{$program->name}' data-id={$program->id} data-key='" . zget($programsPinYin, $program->name, '') . "'");
    }
    else if($program->status != 'done' and $program->status != 'closed' and !($program->PM == $this->app->user->account))
    {
        $normalProgramsHtml .= html::a($link, "<i class='icon icon-folder-outline'></i> " . $program->name, '', "class='transfer' title='{$program->name}' data-id={$program->id} data-key='" . zget($programsPinYin, $program->name, '') . "'");
    }
    else if($program->status == 'done' or $program->status == 'closed') $closedProgramsHtml .= html::a($link, "<i class='icon icon-folder-outline'></i> " . $program->name, '', "title='{$program->name}' class='transfer' data-id={$program->id} data-key='" . zget($programsPinYin, $program->name, '') . "'");
}
?>
<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
    <?php
    if(!empty($myProgramsHtml))
    {
        echo "<div class='heading'>{$lang->project->mine}</div>";
        echo $myProgramsHtml;
        if(!empty($myProgramsHtml))
        {
            echo "<div class='heading'>{$lang->project->other}</div>";
        }
    }
    echo $normalProgramsHtml;
    ?>
    </div>
    <div class="col-footer">
      <?php echo html::a(helper::createLink('program', 'createGuide'), '<i class="icon icon-plus"></i>' . $lang->program->create, '', 'class="not-list-item text-primary" data-toggle=modal'); ?>
      <div class='pull-right'>
        <?php echo html::a(helper::createLink('program', 'index', 'status=all'), '<i class="icon icon-cards-view muted"></i> ' . $lang->project->all, '', 'class="not-list-item"'); ?>
        <span class='text-muted muted'> &nbsp; | &nbsp; </span>
        <a class='toggle-right-col not-list-item'><?php echo $lang->project->doneProjects?><i class='icon icon-angle-right'></i></a>
      </div>
    </div>
  </div>
  <div class="table-col col-right">
    <div class='list-group'>
    <?php
    echo $closedProgramsHtml;
    ?>
    </div>
  </div>
</div>
<script>
$(function()
{
    $('#swapper .transfer').click(function()
    {
        var programID = $(this).attr('data-id');        
        var link = createLink('program', 'ajaxGetEnterLink', "programID=" + programID);
        $.post(link, function(pgmLink)
        {
            location.href = pgmLink;
        })
    })

})
</script>
