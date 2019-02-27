$(function()
{
    $('input[name="type"]').change(function()
    {
        var libType = $(this).val();
        changeByLibType(libType);
    })
    changeByLibType($('input[name="type"]:checked').val());
    toggleAcl($('form [name=acl]:checked').val(), 'lib');
});

function changeByLibType(libType)
{
    if(libType == 'product')
    {
        $('table tr.product').removeClass('hidden');
        $('table tr.project').addClass('hidden');
        changeDoclibAcl(libType);
    }
    else if(libType == 'project')
    {
        $('table tr.product').addClass('hidden');
        $('table tr.project').removeClass('hidden');
        changeDoclibAcl(libType);
    }
    else
    {
        $('table tr.product').addClass('hidden');
        $('table tr.project').addClass('hidden');
        changeDoclibAcl(libType);
    }

    var acl    = $('form [name=acl]').val();
    var notice = typeof(noticeAcl[libType][acl]) != 'undefined' ? noticeAcl[libType][acl] : '';
    $('#noticeAcl').html(notice);
}

function changeDoclibAcl(libType)
{
    if(libType == 'product' || libType == 'project')
    {
        $('form input[name="acl"]').closest('td').find('span:first').html($('#aclBoxA td').html());
    }
    else
    {
        $('form input[name="acl"]').closest('td').find('span:first').html($('#aclBoxB td').html());
    }
}
