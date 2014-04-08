<script language='Javascript'>
$(document).ready(function()
{
    $(".right a").modalTrigger({width:500, height:200, type:'iframe'});  // The create lib link.
    $("#modulemenu a:contains('<?php echo $lang->doc->editLib;?>')").modalTrigger({width:500, height:200, type:'iframe'});   // The edit lib link.
});
</script>
<?php include '../../common/view/footer.html.php';?>

