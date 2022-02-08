<?php
$class       = $currentApp ? '' : "class='active'";
$method      = 'browseFlow';
$featurabar  = "<div id='featurebar'><ul class='nav'>";
$featurabar .= "<li $class>" . html::a(inlink($method, "status=&app=&orderBy=$orderBy"), $lang->workflow->all) . '</li>';

$flowApps = $this->workflow->getFlowApps();
foreach($flowApps as $appCode)
{
    $appName = zget($apps, $appCode, '');
    if($appCode == 'project') $appName = $lang->project->common;
    if($appCode == 'scrum') continue;
    if(!$appName) continue;

    $class = $appCode == $currentApp ? "class='active'" : '';

    $featurabar .= "<li $class>" . html::a(inlink($method, "status=&app=$appCode&orderBy=$orderBy"), $appName) . '</li>';
}
$featurabar .= "</ul></div>";
?>
<script>
$(function()
{
    if($('#featurebar').length == 0) $('#main .container').prepend(<?php echo json_encode($featurabar);?>);
    $('a[disabled=disabled]').addClass('disabled');
    $('.deleter').attr('data-toggle', 'ajax');
});
</script>
