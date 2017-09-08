$(document).ready(function()
{
    $('#dataform').submit(function()
    {
        var password = $('input#verifyPassword').val();
        var rand = $('input#verifyRand').val();
        $('input#verifyPassword').val(md5(md5(password) + rand));
    });
});
