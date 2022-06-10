/**
 * Set lane color.
 *
 * @param  string $color
 * @access public
 * @return void
 */
function setColor(color)
{
    $('.cp-tile').removeClass('active');
    $('.cp-tile[data-color="' + color + '"]').addClass('active');
    $('#color').val(color);
}
