function setNoChecked()
{
    var noCheckValue = '';
    $(':checkbox').each(function(){
        if(!$(this).attr('checked') && $(this).next('span').attr('id') != undefined) noCheckValue = noCheckValue + ',' + $(this).next('span').attr('id');
    })
    $('#noChecked').val(noCheckValue);
}
function toggleProduct()
{ 
    $('#productBox').toggle($('#product').prop("checked"));
}

function toggleProject()
{
    $('#projectBox').toggle($('#project').prop("checked"));
}

$('input:checkbox[name^="allchecker"]').change(function()
{
    setTimeout(function(){toggleProduct(),toggleProject()}, 50);
});
$('#product').change(function(){toggleProduct();})
$('#project').change(function(){toggleProject();})

$(function()
{
    toggleProduct();
    toggleProject();
})
