$(function()
{
    setWhite();
});

/* 切换项目管理模型的逻辑. */
$(document).on('click', '.model-drop', function()
{
    let text  = $(this).find('.listitem').attr('data-value');
    let model = $(this).find('.listitem').attr('data-key');

    const btnClass = labelList[model];

    $('#project-model .text').text(text);
    $('#project-model').removeClass('secondary-outline special-outline warning-outline');
    $('#project-model').addClass(btnClass);
    $('[name=model]').val(model);
})

window.toggleStoryType = function(e)
{
    if(!e.target.checked)
    {
        const link = $.createLink('project', 'ajaxGetStoryByType', 'projectID=' + currentProject + '&storyType=' + e.target.value)
        $.get(link, function(data)
        {
            if(data)
            {
                zui.Modal.confirm(confirmDisableStoryType).then((res) => {
                    if(!res)
                    {
                        if(storyType.includes('epic'))        $('input[value=epic]').prop('checked', true);
                        if(storyType.includes('requirement')) $('input[value=requirement]').prop('checked', true);
                        return false;
                    }
                })
            }
        });

        if(e.target.value == 'requirement')
        {
            $('input[value=epic]').prop('checked', false);
        }
    }

    if(e.target.value == 'epic' && e.target.checked)
    {
        $('input[value=requirement]').prop('checked', true);
    }
}

window.changeAcl = function(e)
{
    const parentID   = e.target.value;
    const defaultVal = $('.aclBox .check-list [name=acl]:checked').val();
    const useList    = parentID > 0 ? subAclList : aclList;
    const hasDefault = typeof(useList[defaultVal]) != 'undefined';

    let aclHtml = '';
    let checkedID = hasDefault ? defaultVal : 'open';
    for(key in useList)
    {
        let checked = key == checkedID;
        aclHtml += '<div class="radio-primary"><input type="radio" id="acl' + key + '" name="acl"' + (checked ? ' checked' : '') + ' value="' + key +'"><label for="acl' + key + '">' + useList[key] + '</label></div>';
    }
    $('.aclBox .check-list').html(aclHtml);
    setWhite();
}
