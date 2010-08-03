<script language='Javascript'>
$(document).ready(function()
{
    $(".right a").colorbox({width:500, height:200, iframe:true, transition:'none'});
    $("#modulemenu a:contains('<?php echo $lang->doc->editLib;?>')").colorbox({width:500, height:200, iframe:true, transition:'none'});
});
</script>
<?php include '../../common/view/footer.html.php';?>

