$(function()
{
    $('.querybox-toggle').parent().addClass('active');

    $(document).on('click', '.chosen-with-drop', function()
    {
        var select = $(this).prev('select');
        if($(select).val() == 'ditto')
        {
            var index = $(select).closest('td').index();
            var row   = $(select).closest('tr').index();
            var table = $(select).closest('tr').parent();
            var value = '';
            for(i = row - 1; i >= 0; i--)
            {
                value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
                if(value != 'ditto') break;
            }
            $(select).val(value);
            $(select).trigger("chosen:updated");
        }
    });
})

/**
 * Reload.
 *
 * @param  int   $libID
 * @access public
 * @return void
 */
function reload(libID)
{
    link = createLink('testcase','importFromLib','productID='+ productID + '&branch=' + branch + '&libID='+libID);
    location.href = link;
}

/**
 * Load modules.
 *
 * @param  int $productID
 * @param  int $branch
 * @param  int $caseID
 * @access public
 * @return void
 */
function loadModules(productID, branch, caseID)
{
    if(typeof(branch) == 'undefined') branch = 0;

    moduleLink = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true');
    var $tr = $('#module' + caseID).closest('tr');
    $('#module' + caseID).parent('td').load(moduleLink, function(data)
    {
        $tr.find('#module').chosen();
        $tr.find('#module').attr({"id": 'module' + caseID, "name": 'module[' + caseID + ']'});
    });
}
