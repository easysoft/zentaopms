$('input[name=typeSwitcher]').closest('.panel-actions').addClass('row-reverse justify-between w-11/12');

window.setModuleAndPlanByBranch = function(e)
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

    var planLink = $.createLink('productPlan', 'ajaxGetProductPlans', 'productID=' + productID + '&branch=' + branchID);
    let $rows    = $branch.closest('tr');
    while($rows.length)
    {
        const $planPicker = $rows.find('[name^=plan]').zui('picker');
        const planID      = $rows.find('[name^=plan]').val();
        $.getJSON(planLink, function(data)
        {
            $planPicker.render({items: data})
            $planPicker.$.setValue(planID);
        });

        $rows = $rows.next('tr');
        if(!$rows.find('td[data-name="plan"][data-ditto="on"]').length) break;
    }
}

window.setGrade = function(e)
{
    const parent = e.target.value;
    const link   = $.createLink('story', 'ajaxGetGrade', 'parent=' + parent + '&type=' + storyType);
    $.get(link, function(data)
    {
        data = JSON.parse(data);
        const currentIndex = $(e.target).closest('tr').attr('data-index');
        $(e.target).closest('tbody').find('[name^=grade]').each(function(index)
        {
            if(index < currentIndex) return;
            let $grade = $(this).zui('picker');
            $grade.render({items: data.items});
            $grade.$.setValue(data.default);
        })
    })
}

window.switchType = function(e)
{
    const type = e.target.value;
    const link = $.createLink(type, 'batchCreate', `productID=${productID}&branchID=${branch}&module=0&storyID=${storyID}&executionID=${executionID}`);

    loadPage(link);
}

window.changeRegion = function(e)
{
    const $region  = $(e.target);
    const regionID = $region.val();
    const laneLink = $.createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=story&field=lane');
    $.getJSON(laneLink, function(data)
    {
        const laneID = data.items.length > 0 ? data.items[0].value : '';
        $region.closest('tr').find('[name^=lane]').zui('picker').render(data);
        $region.closest('tr').find('[name^=lane]').zui('picker').$.setValue(laneID);
    });
}
