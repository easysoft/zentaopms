function addItem(obj)
{
    var newRows = $('#son .row-module-new .btn-delete');
    if(newRows.length == 1) newRows.removeClass('hidden');
    var $inputRow = $(obj).closest('.row-module');
    var $newRow = $('#insertItemBox').children('.row-module').clone().insertAfter($inputRow).addClass('highlight');
    $newRow.find("input[type!='hidden']").val('');
    $newRow.find("select[name^='programs']").chosen();
    $newRow.find('div[class="table-col col-programs"]').addClass('required');
    setTimeout(function()
    {
        $newRow.removeClass('highlight');
    }, 1600);
}

function deleteItem(obj)
{
    var $inputRow = $(obj).closest('.row-module');
    var newRow    = $inputRow.siblings('.row-module.row-module-new');
    if(newRow.length == 2) newRow.find('.btn-delete').addClass('hidden');
    if(newRow.find('.btn-delete').length > 0)
    {
        $inputRow.addClass('highlight').fadeOut(500, function()
        {
            $inputRow.remove();
        });
    }
}

$('#lineForm').on('change', '.col-programs select', function()
{
    isProductLineEmpty(this);
})

function isProductLineEmpty(obj)
{
    var lineInputID = $(obj).closest('.col-programs').siblings('.col-module').find('input').attr('id');
    var lineID      = lineInputID.replace('modulesid', '');

    $.get(createLink('product', 'ajaxGetProductByLine', 'lineID=' + lineID), function(data)
    {
        if(data)
        {
            var confirmResult = confirm(changeProgramTip);
            if(!confirmResult)
            {
                $(obj).val(sessionStorage.getItem($(obj).attr('id'))).trigger('chosen:updated');
                return false;
            }
        }

        sessionStorage.setItem($(obj).attr('id'), $(obj).val());
    })
}

$(function()
{
    $("select[name^=programs]").each(function()
    {
        var selectId      = $(this).attr('id');
        var selectedValue = $(this).val();

        sessionStorage.setItem(selectId, selectedValue);
    })
})
