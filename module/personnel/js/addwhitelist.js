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
 * Hide the clicked item.
 *
 * @param  object ulObj
 * @param  string val
 * @return void
 */
function hideItem(ulObj, val)
{
    $(ulObj).children('li').each(function()
    {
        if($(this).attr('title').indexOf(val) != -1)
        {
            $(this).hide();
        }
    });
}

/**
 * Change user list.
 *
 * @return void
 */
function changeUsers()
{
    var ulObj = {};
    $('tbody').on("click", 'tr td div[class^="chosen-container"]', function()
    {
        /* Render the style of the user list when click the drop-down box. */
        $("ul[class='chosen-results']").each(function()
        {
            ulObj = this;

            /* Render the item of the value which is selected before the page loads.*/
            $("input[name='realnames[]']").each(function()
            {
                hideItem(ulObj, this.value);
            });

            /* Render the item of the value which is selected after the page loads. */
            $("a[class='chosen-single chosen-single-with-deselect']").each(function()
            {
                hideItem(ulObj, $(this).children('span').html());
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
                hideItem(ulObj, $(this).html());
            });
        });
    });
}

$(function()
{
    if(window.config.currentModule == 'personnel' && window.config.currentMethod == 'addwhitelist') $("li[data-id='set']").addClass('active');

    changeUsers();
})
