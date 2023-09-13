/**
 * Determines whether its argument represents a JavaScript number.
 * @param {*} obj
 * @returns bool
 */
function isNumeric(obj)
{
    return (!isNaN(obj) && typeof obj === 'number') || $.isNumeric(obj);;
}

/**
 * Add new item.
 *
 * @param  obj e
 * @access public
 * @return void
 */
function addItem(e)
{
    const obj     = e.target
    const newItem = $(obj).closest('.form-row').clone();
    let index = 0;

    newItem.find('.add-btn').on('click', addItem);
    newItem.find('.del-btn').on('click', removeItem);

    let inputName = newItem.find('input').length > 0 ? newItem.find('input').first().attr('name') : newItem.find('select').first().attr('name');
    inputName = inputName.slice(0, inputName.indexOf('['));
    $('form').find("[name^='" + inputName + "']").each(function() {
        let $name = $(this).attr('name');

        let id = parseInt($name.slice($name.indexOf('[')+1, $name.indexOf(']')));
        if(isNumeric(id) && id >= index) index = id + 1;
    })

    /* Fix id and value. */
    newItem.addClass('newItem');
    newItem.find('.form-label').html('');
    newItem.find('input').each(function()
    {
        let name = $(this).attr('name');
        name = name.slice(0, name.indexOf('[')+1) + String(index) + name.slice(name.indexOf(']'));
        $(this).attr('name', name);
        $(this).attr('id', name);
        $(this).val('');
    });
    newItem.find('select').each(function()
    {
        let name = $(this).attr('name');
        name = name.slice(0, name.indexOf('[')+1) + String(index) + name.slice(name.indexOf(']'));
        $(this).attr('name', name);
        $(this).attr('id', name);
        $(this).val('');
    });

    $(obj).closest('.form-row').after(newItem);
}

/**
 * Remove item.
 *
 * @param  obj e
 * @access public
 * @return void
 */
function removeItem(e)
{
    const obj = e.target

    /* Dsiabled btn can't remove line. */
    if($(obj).closest('.btn').hasClass('disabled')) return false;

    $(obj).closest('.form-row').remove();

    let chosenProducts = 0;
    $("select[name^='products']").each(function()
    {
      if($(this).val() > 0) chosenProducts ++;
    });

    (chosenProducts.length > 1 && (model == 'waterfall' || model == 'waterfallplus')) ? $('.stageBy').removeClass('hide') : $('.stageBy').addClass('hide');
}
