window.renderModuleItem = function(result, info)
{
    if(info.col.name == 'branch') result[0].children.props.items = info.row.data.branchItems;
    if(info.col.name == 'module') result[0].children.props.items = info.row.data.moduleItems;
    return result;
}

$(document).off('change', '[name^=branch]').on('change', '[name^=branch]', function()
{
    const branchID = $(this).val();
    const caseID   = $(this).attr('name').match(/\d+/)[0];
    const libID    = $('input[name="fromlib"]').val();
    const link     = $.createLink('testcase', 'ajaxGetCanImportModuleItems', 'productID=' + productID + '&libID=' + libID + '&branch=' + branchID + '&caseID=' + caseID);
    $.getJSON(link, function(data)
    {
        modulePicker = $('input[name="module[' + caseID + ']"]').zui('picker');
        if(modulePicker)
        {
            modulePicker.render({items: data});
        }
        else
        {
            new zui.Picker($('div[data-col="module"][data-row="' + caseID + '"]').closest('div[data-col="module"]'), {items: data, name: 'module[' + caseID + ']', defaultValue: ''});
        }
    });
});

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
    loadPage(link);
}

$(document).off('click', '.import-btn').on('click', '.import-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return false;

    const url        = $('#importFromLibForm').attr('action');
    const formData   = new FormData($("#importFromLibForm")[0]);
    const dtableData = dtable.$.getFormData();
    checkedList.forEach((id) =>
    {
        formData.append(`caseIdList[${id}]`, id);
        formData.append(`module[${id}]`, dtableData[`module[${id}]`]);
    });

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
