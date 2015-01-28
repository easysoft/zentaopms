$(document).ready(function()
{
    $('#agree').change(function(){$('.btn-install').attr('disabled', !$(this).prop('checked'));}); 
});
