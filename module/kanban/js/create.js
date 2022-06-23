$(function()
{
    if(enableImport == 'off') $("input[name^='importObjectList']").attr('disabled', 'disabled');

    $("input[name='import']").change(function()
    {
        if($(this).val() == 'off')
        {
            $("input[name^='importObjectList']").attr('disabled', 'disabled');
        }
        else
        {
            $("input[name^='importObjectList']").removeAttr('disabled');
        }
    })

    $("input[name^='importObjectList']").change(function()
    {
        if($("input:checked[name^=importObjectList]").length != 0 && !$('#emptyTip').is('.hidden')) $('#emptyTip').addClass('hidden');
    })

    $('#submit').click(function()
    {
        var enableImport     = $("input:checked[name='import']").val();
        var objectListLength = $("input:checked[name^=importObjectList]").length;

        if(enableImport == 'on' && objectListLength == 0 && vision != 'lite')
        {
            $('#emptyTip').removeClass('hidden');
            return false;
        }
    })
})

/**
 * When space type or space value change.
 *
 * @oaram  int    spaceID
 * @param  string type
 * @access public
 * @return void
 */
function changeValue(spaceID, type)
{
    if(typeof type === 'undefined') type = spaceType;
    location.href = createLink('kanban', 'create', 'spaceID=' + spaceID + '&type=' + type);
}
