window.setModuleByBranch = function(e)
{
    const $branch  = $(e.target);
    const branchID = $branch.val();
    let $row       = $branch.closest('tr');

    var moduleLink = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&branch=' + branchID + '&rootModuleID=0&returnType=html&fieldID=&extra=nodeleted');

    while($row.length)
    {
        const $modulePicker = $row.find('[name^=module]').zui('picker');
        const moduleID      = $row.find('[name^=module]').val();
        $.getJSON(moduleLink, function(data)
        {
            $modulePicker.render({items: data.items})
            $modulePicker.$.setValue(moduleID);
        });

        $row = $row.next('tr');
        if(!$row.find('td[data-name="module"][data-ditto="on"]').length) break;
    }
}
