function reload(libID)
{ 
    link = createLink('testcase','importFromLib','productID='+ productID + '&branch=' + branch + '&libID='+libID);
    location.href = link;
}

function setModule(moduleID, obj)
{
    var index = $(obj).closest('tr').index() + 1;
    var $tr   = $(obj).closest('tbody').find('tr');

    while($tr.eq(index).length > 0)
    {
        $module = $tr.eq(index).find(obj).find("select[name*='module']");
        $module.val(moduleID);
        $module.trigger("chosen:updated");
        index ++;
    }
}

$(function()
{
    setTimeout(function(){fixedTfootAction('#importFromLib')}, 500);
});
