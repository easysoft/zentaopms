<?php if(!$projects) echo sprintf($lang->product->noMatchedProject, $keywords);?>
<ul>
<?php
foreach($projects as $project)
{
    echo "<li>" . html::a(sprintf($link, $project->id), $project->name). "</li>";
}
?>
</ul>
