$('#hostList tr[data-status="wait"]').hover(function(){
  $(this).find('.init').tooltip('toggle');
},function(){
  $(this).find('.init').tooltip('hide');
});

if(showFeature)
{
    $.apps.close('help');
    $.apps.open('https://www.zentao.net/book/zentaopms/978.html?fullScreen=zentao', 'help');
}

$('#helpTab').click(function()
{
    $.apps.close('help');
    $.apps.open('https://www.zentao.net/book/zentaopms/978.html?fullScreen=zentao', 'help');
})
