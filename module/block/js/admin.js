/**
 * Get all blocks.
 * 
 * @param  string|int $moduleID 
 * @access public
 * @return void
 */
function getBlocks(moduleID)
{
    var moduleBlock = $('#modules').parent().parent().next();
    $(moduleBlock).hide();

    $('#blockParam').empty();
    if(moduleID == '') return false;

    if(moduleID.indexOf('hiddenBlock') != -1)
    {
        getRssAndHtmlParams('html', moduleID.replace('hiddenBlock', ''));
        return true;
    }
    if(moduleID == 'html' || moduleID == 'dynamic')
    {
        getRssAndHtmlParams(moduleID);
        return true;
    }

    $.get(createLink('block', 'main', 'module=' + moduleID + '&index=' + index), {mode:'getblocklist'}, function(data)
    {
        $(moduleBlock).html(data);
        $(moduleBlock).show();
    })
}

/**
 * Get rss and html params.
 * 
 * @param  string $type 
 * @access public
 * @return void
 */
function getRssAndHtmlParams(type)
{
    $.get(createLink('block', 'set', 'index=' + index + '&type=' + type), function(data)
    {
        $('#blockParam').html(data);
    });
}

/**
 * Get block params.
 * 
 * @param  string $type 
 * @param  int    $moduleID 
 * @access public
 * @return void
 */
function getBlockParams(type, moduleID)
{
    $('#blockParam').empty();
    $.get(createLink('block', 'set', 'index=' + index + '&type=' + type + '&source=' + moduleID), function(data)
    {
        $('#blockParam').html(data);
    });
}

$(function()
{
    $('#modules').change(function(){getBlocks($(this).val())});
    if($('#modules').size() > 0) getBlocks($('#modules').val());

    $(document).on('click', '.dropdown-menu.buttons .btn', function()
    {
        var $this = $(this);
        var group = $this.closest('.input-group-btn');
        group.find('.dropdown-toggle').removeClass().addClass('btn dropdown-toggle btn-' + $this.data('id'));
        group.find('input[name^="params[color]"]').val($this.data('id'));
    });
})
