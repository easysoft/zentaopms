function changeModule($target)
{
    const name = $target.attr('name');
    if($target.prop('checked'))
    {
        $("input[type=hidden][name='" + name + "']").val('1').attr('disabled');
    }
    else
    {
        $("input[type=hidden][name='" + name + "']").val('0').removeAttr('disabled');
    }

    if(edition != 'ipd')
    {
        const enableER = $('[name="module[productER]"]').prop('checked');
        const URAndSR  = $('[name="module[productUR]"]').prop('checked');
        if(enableER && !URAndSR)
        {
            $('[name="module[productUR]"]').prop('checked', true);
            $('[name="module[productUR]"][type=hidden]').val('1');
            zui.Modal.alert(openUR);
        }
    }
};

function checkModule(event)
{
    changeModule($(event.target));
}

function checkGroup()
{
    const checked = $(this).prop('checked');
    $(this).closest('tr').find("input[type=checkbox][name^='module']").each(function()
    {
        $(this).prop('checked', checked);
        changeModule($(this));
    });
};

function checkAll()
{
    const checked = $(this).prop('checked');
    $('input[type=checkbox][name^=allChecker]').prop('checked', checked);
    $(this).closest('table').find("input[type=checkbox][name^='module']").each(function()
    {
        $(this).prop('checked', checked);
        changeModule($(this));
    });
};


window.submitForm = function()
{
    const isCheckedUR = $('[name="module[productUR]"]').prop('checked');
    const isCheckedER = $('[name="module[productER]"]').prop('checked');

    let message   = confirmDisableStoryType;
    let storyType = '';

    if(edition != 'ipd' && URAndSR && !isCheckedUR)
    {
        storyType += URCommon;
    }

    if(enableER && !isCheckedER)
    {
        storyType += ' ' + ERCommon;
    }

    if(storyType)
    {
        zui.Modal.confirm(
        {
            message: message.replace(/{type}/g, storyType),
            icon: 'icon-exclamation-sign',
            iconClass: 'warning-pale rounded-full icon-2x'
        }).then((res) =>
        {
            if(res)
            {
                $('#moduleproductUR[type=hidden]').val('0');
                $('#moduleproductER[type=hidden]').val('0');
                realSubmitForm();
                return false;
            }

            $('[name="module[productUR]"]').prop('checked', true);
            $('[name="module[productER]"]').prop('checked', true);
        });
        return false;
    }

    realSubmitForm();
    return false;
}

window.realSubmitForm = function()
{
    const formData = new FormData($('#setModuleForm form')[0]);
    const url      = $.createLink('admin', 'setmodule');
    $.ajaxSubmit({url: url, data: formData});
}
