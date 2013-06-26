<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/autocomplete.html.php';?>
<?php if(isset($users)) js::set('userList', array_keys($users)); ?>
<script language='Javascript'>
function loadProduct(productID)
{
    moduleLink = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story');
    planLink   = createLink('product', 'ajaxGetPlans', 'productID=' + productID + '&planID=' + $('#plan').val());
    $('#moduleIdBox').load(moduleLink);
    $('#planIdBox').load(planLink);
}
$(function() {
    $("#reviewedBy").autocomplete(userList,{multiple: true,mustMatch: true});
    $("#mailto").autocomplete(userList, { multiple: true, mustMatch: true});
})
</script>
