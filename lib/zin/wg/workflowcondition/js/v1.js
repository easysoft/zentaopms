window.changeConditionType = function()
{
    const type = $('input[name=conditionType]').val();
    $('[data-name=conditionsBox]').parent().toggleClass('hidden', type != 'data');
    $('[data-name=sql]').parent().toggleClass('hidden',           type == 'data');
    $('[data-name=sqlsBox]').parent().toggleClass('hidden',       type == 'data');
    $('[data-name=sqlResult]').parent().toggleClass('hidden',     type == 'data');
}
