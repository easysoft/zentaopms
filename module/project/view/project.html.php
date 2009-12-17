<div class='bg-gray mb-10px padding-10px'>
<?php 
$products = join(', ', $products);
printf($lang->project->oneLineStats, 
    $project->name, 
    $project->code,
    $products,
    $project->begin,
    $project->end,
    $project->totalEstimate,
    $project->totalConsumed,
    $project->totalLeft);
?>
</div>
