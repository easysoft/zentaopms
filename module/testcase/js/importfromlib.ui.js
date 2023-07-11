window.renderModuleItem = function(result, info)
{
    if(info.col.name == 'module')
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

    if(info.col.name == 'branch')
    {
        const $branch = $('#branchSelect select').empty();

        $branch.attr('name', `branch[${info.row.data.id}]`);

        $.each(info.row.data.branchItems, function(branchID, branchName)
        {
            $branch.append('<option value="' + branchID + '">' + branchName + '</option>');
        });

        result[0] = null;
        result[result.length] = {html: $('#branchSelect').html()};
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

function updateModules()
{
    var branchID = $(this).val();
    var itemRow  = $(this).closest('.dtable-row');

    if(branchID == 'ditto')
    {
        itemRow.preAll().each(function()
        {
            var preSelectedBranch = $(this).find('#branch option:checked').val();
            if(preSelectedBranch != 'ditto')
            {
                branchID = preSelectedBranch;
                return false;
            }
        })
    }

    const moduleLink = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case&branch=' + branchID + '&rootModuleID=0&returnType=html&fieldID=&needManage=true');

    $.get(moduleLink, function(data)
    {
        var currentModuleItem = itemRow.find('[name^=module]');

        currentModuleItem.parent().html(data);

        itemRow.find('[name^=module]').css({'width': '176px'});

        var caseID = itemRow.attr('data-id');
        if(canImportModules[branchID][caseID] != undefined && Object.keys(canImportModules[branch][caseID]).length > 0)
        {
            currentModuleItem.children().each(function()
            {
                moduleID = $(this).val();
                if(canImportModules[branch][caseID][moduleID] == undefined) $(this).remove();
            });
        }

        currentModuleItem.attr({"name": 'module[' + caseID + ']'});

        itemRow.nextAll().each(function()
        {
            var nextSelectedBranch = $(this).find('#branch option:checked').val();
            if(nextSelectedBranch != 'ditto') return;

            var moduleItem = $(this).find('[name^=module]');

            moduleItem.html(itemRow.find('[name^=module]').html());

            moduleItem.attr({"name": 'module[' + caseID + ']'});
        })
    })
}
