window.renderRowData = function($row, index, row)
{
    if(row.type == 'stage' || row.attribute != '')
    {
        const parentType = row.grade > 1 && parents[row.parent] ? parents[row.parent].attribute : '';
        /* If is stage, modify lifetime to attribute. */
        let stageItems = [];
        for(let key in stageList) stageItems.push({value: key, text: stageList[key]});

        $row.find('[data-name="lifetime"]').find('.picker-box').on('inited', function(e, info)
        {
            let $attribute = info[0];
            $attribute.render({items: stageItems, required: true, name: 'attribute', disabled: row.grade > 1 && parentType != 'mix'});
            $(e.target).attr('data-parent', row.parent);
        });
    }

    $row.find('[data-name="lifetime"]').find('.picker-box').on('inited', function(e, info)
    {
        let $lifetime = info[0];
        $lifetime.render({required: true});
    });

    $row.find('[data-name="project"]').attr('data-lastselected', row.project).attr('data-execution', row.id);
}

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

window.changeAttribute = function(e)
{
    const $attribute = $(e.target);
    const attribute  = $attribute.val();
    const parentID   = $attribute.closest('tr').find('input[name^=id]').val();
    $('[data-parent="' + parentID + '"]').each(function()
    {
        const $attributePicker = $(this).find('input[name^=attribute]').zui('picker');
        if(attribute == 'mix')
        {
            $attributePicker.render({disabled: false});
        }
        else
        {
            $attributePicker.render({disabled: true});
            $attributePicker.$.setValue(attribute);
        }
    })
}
