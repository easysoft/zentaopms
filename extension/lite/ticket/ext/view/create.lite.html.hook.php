<script>
/**
 * Load modules by product.
 *
 * @param  int    productID
 *
 * @access public
 * @return void
 */
function loadModules(productID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=ticket&branch=all&rootModuleID=0&returnType=json&fieldID=&needManage=false');
    $.getJSON(link, function(data)
    {
        $('#module').empty();
        $.each(data, function(key, value)
        {
            $('#module').append('<option value=' + key + ' title="' + value + '">' + value + '</option>');
        });
        $('#module').trigger('chosen:updated');
    })
}
</script>
