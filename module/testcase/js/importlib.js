function reload(libID)
{ 
    link = createLink('testcase','importLib','productID='+ productID + '&branch=' + branch + '&libID='+libID);
    location.href = link;
}
$(function()
{
    setTimeout(function(){fixedTfootAction('#importFromLib')}, 500);
});
