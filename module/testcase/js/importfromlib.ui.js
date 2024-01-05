window.renderModuleItem = function(result, info)
{
    if(info.col.name == 'branch') info.col.setting.control.props.items = info.row.data.branchItems;

    return result;
}

window.updateTable = function(name, value, formData)
{
    if (name.startsWith('branch['))
    {
        this.setFormData(name.replace('branch[', 'module['), '', true);

        this.update();
    }
}

window.getModuleCellProps = function(cell)
{
    const caseID   = cell.row.data.id;
    const branchID = this.getFormData(`branch[${cell.row.data.id}]`) != undefined ? this.getFormData(`branch[${cell.row.data.id}]`) : cell.row.data.branch;
    const modules  = canImportModules[branchID] != undefined && canImportModules[branchID][caseID] != undefined ? canImportModules[branchID][caseID] : {};
    return {items: modules, required: true};
}

window.toggleLib = function(event)
{
    const libID = $(event.target).val();
    const link  = $.createLink('testcase','importFromLib','productID=' + productID + '&branch=' + branch + '&libID=' + libID);
    loadPage(link, '#mainContent');
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

    const moduleLink = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case&branch=' + branchID + '&rootModuleID=0&returnType=html&fieldID=');

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
