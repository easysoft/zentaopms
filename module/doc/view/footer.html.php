<style>
#modulemenu .nav > li > .dropdown > a#libType {display: inline-block;}
</style>
<?php $crumbs = $this->doc->getCrumbs($rootID, $currentModuleID, $docID = 0, $this->cookie->from)?>
<script>
$(function()
{
    $('#featurebar .heading').prepend(<?php echo json_encode($crumbs)?> + <?php echo json_encode($lang->arrow)?>);
})
</script>
<?php include '../../common/view/footer.html.php';?>

