$(function()
{
    $('#top-submit').click(function()
    {
        $(this).addClass('disabled');
        $('form').submit();
    })
    toggleAcl($('input[name="acl"]:checked').val(), 'doc');
    $('input[name="type"]').change(function()
    {
        var type = $(this).val();
        if(type == 'text')
        {
            $('#contentBox').removeClass('hidden');
            $('#urlBox').addClass('hidden');
        }
        else if(type == 'url')
        {
            $('#contentBox').addClass('hidden');
            $('#urlBox').removeClass('hidden');
        }
    });

    $('#subNavbar li[data-id="doc"]').addClass('active');

    $(document).on("mousedown", 'span[data-name="fullscreen"]', function()
    {
        if($(this).hasClass('ke-selected'))
        {
            $('#submit').removeClass('fullscreen-save')
            $('#submit').addClass('btn-wide')
        }
        else
        {
            $('#submit').addClass('fullscreen-save')
            $('#submit').removeClass('btn-wide')
        }
    });

    $(document).on("mousedown", 'a[title="Fullscreen"],.icon-columns', function()
    {
        setTimeout(function()
        {
            if($('a[title="Fullscreen"]').hasClass('active'))
            {
                $('#submit').addClass('markdown-fullscreen-save')
                $('#submit').removeClass('btn-wide')
                $('.fullscreen').css('height', '50px');
                $('.fullscreen').css('padding-top', '15px');
                $('.CodeMirror-fullscreen').css('top', '50px');
                $('.editor-preview-side').css('top', '50px');
            }
            else
            {
                $('#submit').removeClass('markdown-fullscreen-save')
                $('#submit').addClass('btn-wide')
                $('.editor-toolbar').css('height', '30px');
                $('.editor-toolbar').css('padding-top', '1px');
                $('.CodeMirror').css('top', '0');
                $('.editor-preview-side').css('top', '30px');
            }
        }, 200);
    });
})
