$(function()
{
    toggleLine($('[name=newLine]'));
    setWhite($('[name=acl]:checked'));
});

function toggleLine(obj)
{
    var $obj = $(obj);
    if($obj.length == 0) return false;

    if($obj.prop('checked'))
    {
        $('[name=lineName]').removeClass('hidden');
        $('[name=line]').closest('.picker-box').addClass('hidden');
    }
    else
    {
        $('[name=lineName]').addClass('hidden');
        $('[name=line]').closest('.picker-box').removeClass('hidden');
    }
}

function setParentProgram(e)
{
    const $obj = $(e.target);
    loadPage($.createLink('product', 'create', 'programID=' + $obj.val()));
}
