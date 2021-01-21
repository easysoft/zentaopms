/**
 * Users by department.
 *
 * @param  object  dept
 * @return void
 */
function setDeptUsers(dept)
{
    var deptID = $(dept).val();
    var link   = createLink(module, moduleMethod, 'objectID=' + objectID + '&deptID=' + deptID + '&objectType=' + objectType + '&module=' + module);

    if(module == 'program') link = createLink(module, moduleMethod, 'objectID=' + objectID + '&deptID=' + deptID + '&programID=' + programID + '&from=' + from);

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
    if($('#whitelistForm .table tbody').children().length < 2) return false;

    var $tr = $(obj).parent().parent();
    var val = $tr.find('input[name^="realnames"]').val();
    showItem(val);

    $tr.remove();
}

/**
 * Show the removed item.
 *
 * @param  string val
 * @return void
 */
function showItem(val)
{
    $("#whitelistForm tr td select[name^='accounts']").each(function()
    {
        var select = this;
        $(this).children('option').each(function()
        {
            if($(this).html().indexOf(val) != -1)
            {
                $(this).css('display', '');
            }
        });
    });
}

/**
 * Hide the clicked item.
 *
 * @param  object select
 * @param  string val
 * @return void
 */
function hideItem(select, val)
{
    $(select).children('option').each(function()
    {
        if($(this).html().indexOf(val) != -1)
        {
            $(this).css("display", "none");
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
    var select = {};

    $('#whitelistForm').on("chosen:showing_dropdown", "tr td select[name^='accounts']", function()
    {
        select = this;

        /* Render the item of the value which is selected before the page loads. */
        $("input[name='realnames[]']").each(function()
        {
            hideItem(select, this.value);
        });

        /* Render the item of the value which is selected after the page loads. */
        $("a[class^='chosen-single chosen-single-with-deselect']").each(function()
        {
            var id = $(select).parent().parent().attr('data-id');
            userList[id - 1] = $(this).children('span').html();
            hideItem(select, $(this).children('span').html());
        });

        $(select).trigger("chosen:updated");
    });
}

$(function()
{
    if(window.config.currentModule == 'personnel' && window.config.currentMethod == 'addwhitelist') $("li[data-id='set']").addClass('active');

    userList = {};

    changeUsers();

    /* Listen the change of the select value. */
    $('#whitelistForm').on('change', "tr td select[name^='accounts']", function(e, data)
    {
        var select = this;
        $("#whitelistForm tr td select[name^='accounts']").each(function()
        {
            var id  = $(select).parent().parent().attr('data-id');
            var val = typeof(data.deselected == 'undefined') ? userList[id] : data.deselected;
            showItem(val);
        });
    });
})
