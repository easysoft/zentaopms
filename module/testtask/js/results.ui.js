$(function()
{
    window.waitDom('#casesResults .result-item', function(){ $('#casesResults .result-item').first().trigger('click');})
});

/**
 * Set height of the file modal.
 *
 * @access public
 * @return void
 */
function setFileModalHeight()
{
    $($(this).attr('href')).find('.modal-body').css('max-height', $(this).closest('.modal-content').height() + 'px');
}
