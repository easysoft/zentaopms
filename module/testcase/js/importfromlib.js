function reload(libID)
{ 
    link = createLink('testcase','importFromLib','productID='+ productID + '&branch=' + branch + '&libID='+libID);
    location.href = link;
}
$(function()
{
    setTimeout(function(){fixedTfootAction('#importFromLib')}, 500);
});
