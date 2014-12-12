<?php if(!$projects) echo sprintf($lang->project->noMatched, $keywords);?>
<ul>
<?php
foreach($projects as $project)
{
    echo "<li>" . html::a(sprintf($link, $project->id), "<i class='icon-cube'></i> " . $project->name, '', "class='$project->status'"). "</li>";
}
?>
</ul>
