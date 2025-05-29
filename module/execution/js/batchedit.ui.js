window.renderRowData = function($row, index, row)
{
    let options = {required: true};

    if(row.type == 'stage' || row.attribute != '')
    {
        let itemList     = [];
        let projectModel = 'stage';
        if(stageList[row.attribute] !== undefined) itemList = stageList;
        if(ipdTypeList[row.attribute] !== undefined)
        {
            itemList     = ipdTypeList;
            projectModel = 'ipd';
        }

        const parentType = row.grade > 1 && parents[row.parent] ? parents[row.parent].attribute : '';
        /* If is stage, modify lifetime to attribute. */
        let stageItems = [];
        for(let key in itemList) stageItems.push({value: key, text: itemList[key]});

        if(stageItems.length > 0)
        {
            options.items    = stageItems;
            options.name     = 'attribute[' + row.id + ']';
            options.disabled = row.grade > 1 && parentType != 'mix';
            if(projectModel == 'ipd') options.disabled = true;
        }

        $row.attr('data-parent', row.parent);

        $row.find('[data-name="lifetime"]').find('.picker-box').on('inited', function(e, info)
        {
            let $attribute = info[0];
            $attribute.render({items: stageItems, required: true, name: 'attribute[' + row.id + ']', disabled: row.grade > 1 && parentType != 'mix'});
            if(typeof row != 'undefined' && typeof row.hasDeliverable != 'undefined') $attribute.render({disabled: true});
            $(e.target).attr('data-parent', row.parent);
        });
    }

    $row.find('[data-name="lifetime"]').find('.picker-box').on('inited', function(e, info) { info[0].render(options); });
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
};

window.changeAttribute = function(obj)
{
    const $attribute = $(obj);
    const parentID   = $attribute.closest('tr').find('input[name^=id]').val();
    const attribute  = $attribute.val();
    const $children  = $('[data-parent="' + parentID + '"]');
    if($children.length == 0) return;

    if(attribute != 'mix') zui.Modal.alert(noticeChangeAttr.replace('%s', stageList[attribute]));
    $children.each(function()
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
    });
};
