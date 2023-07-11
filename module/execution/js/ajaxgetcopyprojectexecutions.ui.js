$(function()
{
    if(hasExecution) $('.confirmBtn').removeClass('hidden');
    if(!hasExecution) $('.confirmBtn').addClass('hidden');
})
