$(function()
{
    $('#needNotReview').on('change', function()
    {
        $('#assignedTo').attr('disabled', $(this).is(':checked') ? 'disabled' : null).trigger('chosen:updated');
        getStatus('create', "product=" + $('#product').val() + ",execution=" + executionID + ",needNotReview=" + ($(this).prop('checked') ? 1 : 0));
    });
    $('#needNotReview').change();

    // init pri selector
    $('#pri').on('change', function()
    {
        var $select = $(this);
        var $selector = $select.closest('.pri-selector');
        var value = $select.val();
        $selector.find('.pri-text').html('<span class="label-pri label-pri-' + value + '" title="' + value + '">' + value + '</span>');
    });
});

function refreshPlan()
{
    $('a.refresh').click();
}

$(window).unload(function(){
    if(blockID) window.parent.refreshBlock($('#block' + blockID));
});
