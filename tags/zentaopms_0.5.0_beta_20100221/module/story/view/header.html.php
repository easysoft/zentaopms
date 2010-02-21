<?php include '../../common/header.html.php';?>
<?php include '../../common/autocomplete.html.php';?>
<script language='Javascript'>
function loadProduct(productID)
{
    moduleLink = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=product');
    planLink   = createLink('product', 'ajaxGetPlans', 'productID=' + productID + '&planID=' + $('#plan').val());
    $('#moduleIdBox').load(moduleLink);
    $('#planIdBox').load(planLink);
}
var userList = "<?php echo join(',', array_keys($users));?>".split(',');
$(function() {
    $("#reviewedBy").autocomplete(userList,{multiple: true,mustMatch: true});
    $("#mailto").autocomplete(userList, { multiple: true, mustMatch: true});
})
</script>
