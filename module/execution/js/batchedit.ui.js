window.renderRowData = function($row, index, row)
{
    if(row.type == 'stage')
    {
        /* If is stage, modify lifetime to attribute. */

        let stageItems = [];
        for(let key in stageList) stageItems.push({value: key, text: stageList[key]});

        $row.find('[data-name="lifetime"]').find('.picker-box').on('inited', function(e, info)
        {
            let $attribute = info[0];
            $attribute.render({items: stageItems, required: true, name: 'attribute'});
        });
    }

    $row.find('[data-name="lifetime"]').find('.picker-box').on('inited', function(e, info)
    {
        let $lifetime = info[0];
        $lifetime.render({required: true});
    });

    $row.find('[data-name="project"]').attr('data-lastselected', row.project).attr('data-execution', row.id);
}

$().ready(function()
{
    new zui.Tooltip('#tooltipHover', {trigger: 'hover', placement: 'right', type: 'white', 'className': 'text-gray border border-light'});
})

window.changeProject = function(e)
{
    const $project    = $(e.target);
    const projectID   = $project.val();
    const $td         = $project.closest('td');
    const executionID = $td.data('execution');
    let lastSelected  = $td.data('lastselected');

    if($td.find('[id^="syncStories"]').length == 0)
    {
        $td.append("<input type='hidden' id='syncStories" + executionID + "' name='syncStories[" + executionID + "]' value='no' />");
    }

    if(projectID != lastSelected)
    {
        zui.Modal.confirm(confirmSync).then((res) => {
            if(res)
            {
                $td.data("lastselected", projectID);
            }
            else
            {
                $project.zui('picker').$.changeState({value: lastSelected});
            }
            $("#syncStories" + executionID).val(res ? 'yes' : 'no');
        });
    }
}
