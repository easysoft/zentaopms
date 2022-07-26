$(function()
{
    loadList($('#type').val(), '', defaultType, idvalue);

    $('#pri').on('change', function()
    {   
        var $select = $(this);
        var $selector = $select.closest('.pri-selector');
        var value = $select.val();
        $selector.find('.pri-text').html('<span class="label-pri label-pri-' + value + '" title="' + value + '">' + value + '</span>');
    });

    resetDateWidth();

    $(window).resize(function()
    {
        resetDateWidth();
    });
});

function resetDateWidth()
{
    var dateWidth = $('#date').parent().width();
    $('.dateWidth').width(dateWidth);
}
