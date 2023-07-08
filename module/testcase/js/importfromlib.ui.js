window.renderModuleItem = function(result, info)
{
    if(info.col.name == 'module' && info.col.type == 'html')
    {
        const $module = $('#moduleSelect select').empty();

        $module.attr('name', `module[${info.row.data.id}]`);

        $.each(info.row.data.moduleItems, function(moduleID, moduleName)
        {
            $module.append('<option value="' + moduleID + '">' + moduleName + '</option>');
        });

        result[0] = null;
        result[result.length] = {html: $('#moduleSelect').html()};
    }

    return result;
}

function reload()
{
    const libID = $(this).val();
    const link  = $.createLink('testcase','importFromLib','productID=' + productID + '&branch=' + branch + '&libID=' + libID);

    location.href = link;
}

$(document).off('click', '.import-btn').on('click', '.import-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return false;

    const url      = $('#importFromLibForm').attr('action');
    const formData = new FormData($("#importFromLibForm")[0]);
    checkedList.forEach((id) => formData.append(`caseIdList[${id}]`, id));

    $.ajaxSubmit({url: url, data: formData});

    return false;
});
