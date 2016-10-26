function loadModules(libID)
{
    link = createLink('doc', 'ajaxGetModules', 'libID=' + libID);
    $('#moduleBox').load(link, function(){$('#moduleBox').find('select').chosen(defaultChosenOptions)});
}

function toggleAcl(acl)
{
    if(acl == 'custom')
    {
        $('#whiteListBox').removeClass('hidden');
    }
    else
    {
        $('#whiteListBox').addClass('hidden');
    }
}

$(document).ready(function()
{
    $('[data-id="create"] a').modalTrigger({type: 'iframe', width: 800});
    $('#modulemenu .nav li').removeClass('active');
    if(typeof(type) != 'undefined') $('#modulemenu .nav li[data-id="' + type + '"]').addClass('active');
});
