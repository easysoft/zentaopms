<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<script language='Javascript'>
function loadProduct(productID)
{
    loadProductModules(productID);
    loadProductPlans(productID);
}

function loadProductModules(productID)
{
    moduleLink = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&rootModuleID=0&returnType=html&needManage=true');
    $('#moduleIdBox').load(moduleLink, function(){$('#moduleIdBox #module').chosen(defaultChosenOptions);});
}

function loadProductPlans(productID)
{
    planLink = createLink('product', 'ajaxGetPlans', 'productID=' + productID + '&planID=' + $('#plan').val() + '&needCreate=true');
    $('#planIdBox').load(planLink, function(){$('#planIdBox #plan').chosen(defaultChosenOptions);});
}

$(function() 
{
    $("#reviewedBy").chosen(defaultChosenOptions);
    $("#mailto").chosen(defaultChosenOptions);
})
</script>
