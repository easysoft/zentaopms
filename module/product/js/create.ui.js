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
        $formRow.find('[name=lineName]').removeClass('hidden');
        $line.addClass('hidden');
        $line.attr('disabled', 'disabled');
    }
    else
    {
        $formRow.find('[name=lineName]').addClass('hidden');
        $line.removeClass('hidden');
        $line.removeAttr('disabled');
    }
}

function setParentProgram(e)
{
    var $obj = $(e.target);
    loadPage($.createLink('product', 'create', 'programID=' + $obj.val()));
}
