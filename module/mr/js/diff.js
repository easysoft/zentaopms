$(document).ready(function()
{
    $("#inline").click(function(){$('#arrange').val('inline');this.form.submit();});
    $("#appose").click(function(){$('#arrange').val('appose');this.form.submit();});
    $(".label-exchange").click(function(){ $('#exchange').submit();});
});

function changeEncoding(encoding)
{
    $('#encoding').val(encoding);
    $('#encoding').parents('form').submit();
}
