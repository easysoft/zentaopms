function reload(libID)
{ 
    link = createLink('testcase','importFromLib','productID='+ productID + '&branch=' + branch + '&libID='+libID);
    location.href = link;
}

function setModule(obj)
{
    var moduleID  = $(obj).val();
    var libModule = $(obj).closest('td').data('module');

    var index = $(obj).closest('tr').index();

      console.log(moduleID);
      console.log(libModule);
      console.log(index);
    $(obj).closest('tbody').find('tr').each(function(i)
    {
        if(i > index)
        {
            $(this).find("[data-module='" + libModule + "']").find("select[id^='module']").val(moduleID).trigger("chosen:updated");
        }
    })
}

$(function()
{
    setTimeout(function(){fixedTfootAction('#importFromLib')}, 500);
});
