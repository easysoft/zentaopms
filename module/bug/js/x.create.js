$(function()
{
    $('.title-group .has-icon-right').css('min-width', '100px');        
    $('#buildBoxActions').remove();
    $('#openedBuild').parent().find('input-group-btn').remove();
    $('#openedBuild_chosen.chosen-container .chosen-drop').css('min-width', '100px');
    $('#tplBoxWrapper .btn-toolbar .btn-group').remove();
    $('table tbody tr').eq(3).remove();
    $('table tbody tr th').removeClass('w-110px');
    $('table tbody tr th').addClass('w-70px');
})
