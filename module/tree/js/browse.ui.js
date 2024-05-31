function syncModule()
{
    productID = $('[name=allProduct]').val();
    moduleID  = $('[name=productModule]').val();

    $.getJSON($.createLink('tree', 'ajaxGetSonModules', 'moduleID=' + moduleID + '&rootID=' + productID + '&type=story'), function(modules)
    {
        if(modules.length == 0)
        {
            zui.Modal.alert(noSubmodule);
            return false;
        }

        let $lastFormRow  = $('.form-row .row-module').last().closest('.form-row');
        let $lastBranch   = $lastFormRow.find('.picker-box [name^=branch]');
        let $cloneFormRow = $lastFormRow.clone().addClass('copy');
        let $branchPicker = null;
        if($lastBranch.length > 0)
        {
            $branchPicker = $lastBranch.zui('picker');
            $cloneFormRow.find('.picker-box').removeAttr('id').removeAttr('data-zui-picker').empty();
        }

        $.each(modules, function(key, module)
        {
            $('.form-row:not(.copyBox) .row-module').each(function()
            {
                moduleName = $(this).find('input[id^=modules]').val();
                if(moduleName == module.name) modules[key] = null;
            })
        });

        $.each(modules, function(key, module)
        {
            if(module)
            {
                $('.form-row:not(.copyBox):not(.sonModule):not(.copy)').first().before($cloneFormRow.prop('outerHTML'));

                let $copy   = $('.form-row.copy').last();
                let $branch = $copy.find('.picker-box');

                $copy.find('input[id^=modules]').attr('id', 'modules[]').attr('name', 'modules[]').val(module.name);
                $copy.find('input[id^=shorts]').attr('id', 'shorts[]').attr('name', 'shorts[]').val(module.short);
                $copy.find('.add-btn').on('click', addItem);
                $copy.find('.del-btn').on('click', removeItem);
                if($branch.length > 0) new zui.Picker($branch, $.extend({'name': 'branch[]'}, $branchPicker.options));
            }
        });
    });
}

function syncProduct(obj)
{
    link = $.createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + obj.value + "&viewType=" + viewType + "&branch=all&rootModuleID=0&returnType=json");
    $.getJSON(link, function(modules)
    {
        let $picker = $('[name=productModule]').zui('picker');
        let items   = [];
        if(obj.value)
        {
            $.each(modules, function(key, value){ items.push({'value': key, 'text': value}); });
        }

        $picker.render({items: items})
        $picker.$.setValue('');
    });
}

window.addItem = function(e)
{
    const obj     = e.target
    const thisRow = $(obj).closest('.form-row');
    const newItem = thisRow.clone();

    newItem.find('.add-btn').on('click', addItem);
    newItem.find('.del-btn').on('click', removeItem);
    $newBranch = newItem.find('.picker-box [name^=branch]');
    $pickerBox = null;
    if($newBranch.length > 0)
    {
        $pickerBox = $newBranch.closest('.picker-box');
        $pickerBox.removeAttr('id').removeAttr('data-zui-picker').empty();
    }

    $(obj).closest('.form-row').after(newItem);

    newItem.find('input[id^=modules]').attr('id', 'modules[]').attr('name', 'modules[]').val('');
    newItem.find('input[id^=shorts]').attr('id', 'shorts[]').attr('name', 'shorts[]').val('');

    if($pickerBox)
    {
        options = thisRow.find('.picker-box [name^=branch]').zui('picker').options;
        $pickerBox.picker($.extend({'name': 'branch[]'}, options));
    }
}

window.toggleCopy = function()
{
   $('.form-row.copyBox').toggleClass('hidden');
}
