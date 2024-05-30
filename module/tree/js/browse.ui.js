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

                $copy.find('input[id^=modules]').val(module.name);
                $copy.find('input[id^=shorts]').val(module.short);
                if($branch.length > 0) new zui.Picker($branch, $branchPicker.options);
            }
        })
    })
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
    })
}

window.toggleCopy = function()
{
   $('.form-row.copyBox').toggleClass('hidden');
}
