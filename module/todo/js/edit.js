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

    alignWidth();

    $(window).resize(function()
    {
        alignWidth();
    });
});

/**
 * Align with the date width.
 *
 * @access public
 * @return void
 */
function alignWidth()
{
    var dateWidth = $('#date').parent().width();
    $('.dateWidth').width(dateWidth);
}
