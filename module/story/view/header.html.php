<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('reviewedBy', $lang->story->chosen->reviewedBy);?>
<?php js::set('mailto',     $lang->story->chosen->mailto);?>
<script language='Javascript'>
function loadProduct(productID)
{
    moduleLink = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story');
    planLink   = createLink('product', 'ajaxGetPlans', 'productID=' + productID + '&planID=' + $('#plan').val());
    $('#moduleIdBox').load(moduleLink);
    $('#planIdBox').load(planLink);
}

$(function() 
{
    $("#reviewedBy").chosen({no_results_text:noResultsMatch});                                                   
    $("#mailto").chosen({no_results_text:noResultsMatch});                                                   
    $("#reviewedBy_chzn .chzn-choices li.search-field input").attr('value', reviewedBy);      
    $("#mailto_chzn .chzn-choices li.search-field input").attr('value', mailto);      
})
</script>
