window.canSortTo = function(event, from, to)
{
    if(!from || !to) return false;
    if(to.icon) return false;

    return true;
}
