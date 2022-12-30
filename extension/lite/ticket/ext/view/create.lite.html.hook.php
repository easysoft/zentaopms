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
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=ticket&branch=all&rootModuleID=0&returnType=html&fieldID=&needManage=false');
    $('#moduleBox').load(link, function(data)
    {
        var $inputGroup = $(this);
        $inputGroup.find('select').chosen()
        $inputGroup.prepend("<span class='input-group-addon'>" + moduleLang + "</span>");
    });
}
</script>