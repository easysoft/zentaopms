function changeModule($target)
{
    const name = $target.attr('name');
    if($target.prop('checked'))
    {
        $("input[type=hidden][name='" + name + "']").val('1').attr('disabled');
    }
    else
    {
        if(name == 'module[productUR]' || name == 'module[productER]')
        {
            zui.Modal.confirm(
            {
                message: confirmDisableStoryType,
                icon: 'icon-exclamation-sign',
                iconClass: 'warning-pale rounded-full icon-2x'
            }).then((res) =>
            {
               if(res)
                {
                    $("input[type=hidden][name='" + name + "']").val('0').removeAttr('disabled');
                    return false;
                }

                $target.prop('checked', true);
            });
            return false;
        }

        $("input[type=hidden][name='" + name + "']").val('0').removeAttr('disabled');
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
