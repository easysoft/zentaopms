$(function()
{
    toggleLine($('[name=newLine]'));
    setWhite($('[name=acl]:checked'));
});

function toggleLine(obj)
{
    var $obj = $(obj);
    if($obj.length == 0) return false;

    var $formRow = $obj.closest('.form-row');
    var $line    = $formRow.find('#line');

    if($obj.prop('checked'))
    {
        $formRow.find('#lineName').removeClass('hidden');
        $line.addClass('hidden');
        $line.attr('disabled', 'disabled');
    }
    else
    {
        $formRow.find('#lineName').addClass('hidden');
        $line.removeClass('hidden');
        $line.removeAttr('disabled');
    }
}

function setParentProgram(obj)
{
    var $obj = $(obj);
    loadPage($.createLink('product', 'create', 'programID=' + $obj.val()));
}
