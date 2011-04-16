/* Select All. */
function checkAll()
{
    var checkOBJ = $('input');
    for(var i = 0; i < checkOBJ.length; i++)
    {
        checkOBJ.get(i).checked = true;
    }
}

/* Check reverse. */
function checkReverse()
{
    var checkOBJ = $('input');
    for(var i = 0; i < checkOBJ.length; i++)
    {
        checkOBJ.get(i).checked = !checkOBJ.get(i).checked;
    }
    return;
}
