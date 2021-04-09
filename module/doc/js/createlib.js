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

/**
 * Change form by lib type.
 *
 * @param  string  libType
 * @access public
 * @return void
 */
function changeByLibType(libType)
{
    if(libType == 'product')
    {
        $('table tr.product').removeClass('hidden');
        $('#product').attr('disabled', false);

        $('table tr.project').addClass('hidden');
        $('#project').attr('disabled', true);

        $('table tr.execution').addClass('hidden');
        $('#execution').attr('disabled', true);

        changeDoclibAcl(libType);
    }
    else if(libType == 'project')
    {
        $('table tr.project').removeClass('hidden');
        $('#project').attr('disabled', false);

        $('table tr.product').addClass('hidden');
        $('#product').attr('disabled', true);

        $('table tr.execution').addClass('hidden');
        $('#execution').attr('disabled', true);

        changeDoclibAcl(libType);
    }
    else if(libType == 'execution')
    {
        $('table tr.execution').removeClass('hidden');
        $('#execution').attr('disabled', false);

        $('table tr.product').addClass('hidden');
        $('#product').attr('disabled', true);

        $('table tr.project').addClass('hidden');
        $('#project').attr('disabled', true);

        changeDoclibAcl(libType);
    }
    else
    {
        $('table tr.product').addClass('hidden');
        $('#product').attr('disabled', true);

        $('table tr.project').addClass('hidden');
        $('#project').attr('disabled', true);

        $('table tr.execution').addClass('hidden');
        $('#execution').attr('disabled', true);

        changeDoclibAcl(libType);
    }

    var acl    = $('form [name=acl]').val();
    var notice = typeof(noticeAcl[libType][acl]) != 'undefined' ? noticeAcl[libType][acl] : '';
    $('#noticeAcl').html(notice);
    $('#whiteListBox').addClass('hidden');
}

function changeDoclibAcl(libType)
{
    if(libType == 'product' || libType == 'execution')
    {
        $('form input[name="acl"]').closest('td').find('span:first').html($('#aclBoxA td').html());
    }
    else
    {
        $('form input[name="acl"]').closest('td').find('span:first').html($('#aclBoxB td').html());
    }
}
