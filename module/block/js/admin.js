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
    if(moduleID == 'html' || moduleID == 'modules' || moduleID == 'dynamic')
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
 * @param  int    $blockID 
 * @access public
 * @return void
 */
function getRssAndHtmlParams(type, blockID)
{
    blockID = typeof(blockID) == 'undefined' ? 0 : blockID;
    $.get(createLink('block', 'set', 'index=' + index + '&type=' + type + '&blockID=' + blockID), function(data)
    {
        $('#blockParam').html(data);
    });
}

/**
 * Get block params.
 * 
 * @param  string $blockID 
 * @param  int    $moduleID 
 * @access public
 * @return void
 */
function getBlockParams(blockID, moduleID)
{
    $('#blockParam').empty();
    if(blockID == '') return false;

    $.get(createLink('block', 'set', 'index=' + index + '&type=' + moduleID + '&blockID=' + blockID), function(data)
    {
        $('#blockParam').html(data);
    });
}

$(function()
{
    $('#modules').change(function(){getBlocks($(this).val())});
    getBlocks($('#modules').val());

    $(document).on('click', '.dropdown-menu.buttons .btn', function()
    {
        var $this = $(this);
        var group = $this.closest('.input-group-btn');
        group.find('.dropdown-toggle').removeClass().addClass('btn dropdown-toggle btn-' + $this.data('id'));
        group.find('input[name^="params[color]"]').val($this.data('id'));
    });
})
