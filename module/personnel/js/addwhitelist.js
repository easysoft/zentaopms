/**
 * Users by department.
 *
 * @param  object  obj
 * @return void
 */
function setDeptUsers(dept)
{
    var deptID = $(dept).val();
    var link   = createLink(module, moduleMethod, 'objectID=' + objectID + '&deptID=' + deptID + '&objectType=' + objectType + '&module=' + module);
    location.href = link;
}

/**
 * Add an item.
 *
 * @param  object  obj
 * @return void
 */
function addItem(obj)
{
    var dataID = $(obj).parent().parent().attr("data-id");
    var item   = $("#addItem").html();
    index      = Number(index) + 1;
    $("#whitelist" + dataID).after("<tr id='whitelist" + index + "' data-id='" + index + "'> " + item + "</tr>");
    $("#whitelist" + index).find("select").chosen();
}

/**
 * Delete an item.
 *
 * @param  object  obj
 * @return void
 */
function deleteItem(obj)
{
    $(obj).parent().parent().remove();
}

/**
 * Change user list.
 *
 * @param  object ulObj
 * @param  string val
 * @return void
 */
function changeUsers(ulObj, val)
{
    $(ulObj).children('li').each(function()
    {
        if($(this).attr('title').indexOf(val) != -1)
        {
            $(this).addClass("not-clickable");
        }
    });
}

$(function()
{
    /* Render the style of the user list when click the drop-down box. */
    var ulObj = {};
    $("select[name='accounts[]']").siblings(".chosen-container").click(function()
    {
        $("ul[class='chosen-results']").each(function()
        {
            ulObj = this;

            /* Render the item of the value which is selected before the page loads.*/
            $("input[name='realnames[]']").each(function()
            {
                changeUsers(ulObj, this.value);
            });

            /* Render the item of the value which is selected after the page loads. */
            $("a[class='chosen-single chosen-single-with-deselect']").each(function()
            {
                changeUsers(ulObj, $(this).children('span').html());
            });
        });
    });

    /* Add the style to the select item. */
    $("a[class='chosen-single chosen-default']").each(function()
    {
        $(this).children('span').bind('DOMNodeInserted', function()
        {
            $("ul[class='chosen-results']").each(function()
            {
                changeUsers(ulObj, $(this).html());
            });
        });
    });
})
