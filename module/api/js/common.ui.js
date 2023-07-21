$('.form-row').on('click', '.btn-add', function()
{
    let $newRow   = $(this).closest('tr').clone();

    $newRow.find('input').val('');
    $newRow.find('textarea').val('');

    let key = $newRow.data('key');

    $newRow.attr('data-key', genKey());

    let parentRow   = $(this).closest('.input-row')[0];
    let nextSibling = parentRow.nextElementSibling;
    while(nextSibling && parseInt(nextSibling.dataset.level) > parseInt(parentRow.dataset.level))
    {
        nextSibling = nextSibling.nextElementSibling;
    }

    if(nextSibling)
    {
        parentRow.parentNode.insertBefore($newRow[0], nextSibling);
    }
    else
    {
        parentRow.parentNode.appendChild($newRow[0]);
    }
});

$('.form-row').on('click', '.btn-split', function()
{
    let $newRow = $(this).closest('tr').clone();
    $newRow.find('input').val('');
    $newRow.find('textarea').val('');

    $newRow.attr('data-parent', $newRow.data('key'));
    $newRow.attr('data-key', genKey());
    $newRow.attr('data-level', $newRow.data('level') + 1);
    $newRow.addClass('child');
    $newRow.find('td').first().css('padding-left', $newRow.data('level') * 10 + 'px');

    $(this).closest('tr').after($newRow);
});

$('.form-row').on('click', '.btn-delete', function()
{
    if($(this).closest('table').find('.input-row').length == 1) return false;

    let $table = $(this).closest('table');
    let isResponse = $(this).closest('div.form-group').hasClass('response');
    $(this).closest('tr').remove();

    if(isResponse)
    {
        generateResponse($table);
    }
    else
    {
        generateParams($table);
    }
});
