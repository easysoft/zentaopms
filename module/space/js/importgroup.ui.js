window.loadGroup = function()
{
    const spaceID = $('[name=sourceSpace]').val();
    if(!spaceID) return;

    const $groups = $('[name=sourceGroup]').zui('picker');
    toggleLoading('#sourceGroup', true);
    $.getJSON($.createLink('space', 'ajaxGetGroupsBySpace', 'spaceID=' + spaceID), function(data)
    {
        $groups.$.clear();
        $groups.render({items: data});
    });
    toggleLoading('#sourceGroup', false);
}

window.copyGroup = function()
{
    const groupID = $('[name=sourceGroup]').val();
    if(!groupID) return;

    $.getJSON($.createLink('space', 'ajaxGetGroupByID', 'groupID=' + groupID), function(data)
    {
        if(data)
        {
            $('[name=name').val(data.name);
            $('[name=desc]').val(data.desc);
        }
    });
}

window.waitDom('[name=sourceSpace]', loadGroup);
