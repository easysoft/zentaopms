$(function()
{
    $('input[name="type"]').change(function()
    {
        var libType = $(this).val();
        changeByLibType(libType);
    })
    changeByLibType($('input[name="type"]').val());
    toggleAcl($('[name=acl]').val(), 'lib');
});

function changeByLibType(libType)
{
    if(libType == 'product')
    {
        $('table tr.product').removeClass('hidden');
        $('table tr.project').addClass('hidden');
    }
    else if(libType == 'project')
    {
        $('table tr.product').addClass('hidden');
        $('table tr.project').removeClass('hidden');
    }
    else
    {
        $('table tr.product').addClass('hidden');
        $('table tr.project').addClass('hidden');
    }

    var acl    = $('[name=acl]').val();
    var notice = typeof(noticeAcl[libType][acl]) != 'undefined' ? noticeAcl[libType][acl] : '';
    $('#noticeAcl').html(notice);
}
