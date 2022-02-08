$(function()
{
    $("#name").bind("input propertychange", function(event){
        $("#path").val($(this).val().toLowerCase());
    });

    $('input:radio[name="visibility"]').change(function()
    {
        var visibility = $('input:radio[name="visibility"]:checked').val();
        if(visibility == 'public') $('#publicTip').removeClass('hidden');
        if(visibility != 'public') $('#publicTip').addClass('hidden');
    })
})
