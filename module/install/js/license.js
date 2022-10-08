$(document).ready(function()
{
    $('#agree').change(function()
    {
        $('.btn-install').attr('disabled', !$(this).prop('checked'));
        $('.btn-install').css('pointer-events', $(this).prop('checked') ? 'auto' : 'none');
    }); 
});
