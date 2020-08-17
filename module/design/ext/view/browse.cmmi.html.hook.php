<?php
$featurebar = "<div class='btn-toolbar pull-left'>";
$labelPairs = $this->loadModel('workflowlabel')->getPairs($this->app->rawModule);
foreach($labelPairs as $labelID => $labelTitle)
{
    $class = ($mode == 'browse' and $labelID == $label) ? "btn-active-text" : '';

    $params      = "productID=$productID&mode=browse&label=$labelID&orderBy=$orderBy";
    $featurebar .= html::a($this->createLink($flow->module, 'browse', $params), "<span class='text'>" . $labelTitle . "</span>", '', "class='btn btn-link $class'");
}
?>

<script>
/* Add list label. */
$('#main #mainMenu').prepend(<?php echo helper::jsonEncode($featurebar);?>);
</script>
