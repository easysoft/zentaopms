<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('reviewedBy', $lang->story->chosen->reviewedBy);?>
<?php js::set('mailto',     $lang->story->chosen->mailto);?>
<script language='Javascript'>
function loadProduct(productID)
{
    loadProductModules(productID);
    loadProductPlans(productID);
}

function loadProductModules(productID)
{
    moduleLink = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&rootModuleID=0&returnType=html&needManage=true');
    $('#moduleIdBox').load(moduleLink);
}

function loadProductPlans(productID)
{
    planLink = createLink('product', 'ajaxGetPlans', 'productID=' + productID + '&planID=' + $('#plan').val() + '&needCreate=true');
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
