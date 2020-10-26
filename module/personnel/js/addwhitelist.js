/**
 * Users by department.
 *
 * @param  object  obj
 * @return void
 */
function setDeptUsers(dept)
{
    var deptID = $(dept).val();
    var link   = createLink(module, 'addWhitelist', 'objectID=' + objectID + '&deptID=' + deptID + '&objectType=' + objectType + '&module=' + module);
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
