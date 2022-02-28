<?php if($this->app->tab == 'project'):?>
<script>
$('#pageNav .btn-group #dropMenu .table-col .list-group a[href*="showFiles"]').remove();
$('#pageActions .btn-toolbar .btn-group:first').remove();
</script>
<?php endif;?>
<?php if($this->app->tab == 'doc'):?>
<script>
$('#pageNav .btn-group #dropMenu .table-col .list-group a[href*="showFiles"]').remove();
</script>
<?php endif;?>

<?php if(!empty($doc) and !$doc->deleted and $doc->version > 1 and common::hasPriv('doc', 'diff')):?>
<?php
$versions = array();
$i = 1;
foreach($actions as $action)
{
    if($action->action == 'created' or $action->action == 'deletedfile' or $action->action == 'commented')
    {
        $versions[$i] =  "#$i " . zget($users, $action->actor) . ' ' . substr($action->date, 2, 14);
        $i++;
    }
    elseif($action->action == 'edited')
    {
        foreach($action->history as $history)
        {
            if($history->field == 'content')
            {
                $versions[$i] = "#$i " . zget($users, $action->actor) . ' ' . substr($action->date, 2, 14);
                $i++;
                break;
            }
        }
    }
}
krsort($versions);

$diffHtml  = "<div class='btn-group versions'>";
$diffHtml .= "<button data-toggle='dropdown' type='button' class='btn btn-link dropdown-toggle'>{$lang->doc->diff} <span class='caret'></span></button>";
$diffHtml .= "<ul class='dropdown-menu pull-right'>";
foreach($versions as $i => $versionTitle)
{
    if($i == $doc->version) continue;
    $diffHtml .= '<li>' . html::a(inlink('diff', "docID=$doc->id&newVersion=$doc->version&version=$i"), $versionTitle) . '</li>';
}
$diffHtml .= "</ul>";
$diffHtml .= "</div>";
?>
<script>
$('#mainContent #content .detail-title .actions').append(<?php echo json_encode($diffHtml)?>);
</script>
<?php endif;?>
