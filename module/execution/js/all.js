$(function()
{
    $("#" + status + "Tab").addClass('btn-active-text');
    $('.table-footer .check-all').on('click', function()
    {
        if($(this).hasClass('checked'))
        {
            $(this).removeClass('checked');
            $('.dtable-row').click().removeClass('is-checked');
            $('.has-checkbox input[type="checkbox"]').prop('checked', false);
        }
        else
        {
            $(this).addClass('checked');
            $('.dtable-row').click().addClass('is-checked');
            $('.has-checkbox input[type="checkbox"]').prop('checked', true);
        }
    });

    var table = $('#dtable .dtable');
    table.css('height', table.height() - 10 + 'px');
});

/**
 * Location to product list.
 *
 * @param  int    productID
 * @param  int    projectID
 * @param  string status
 * @access public
 * @return void
 */
function byProduct(productID, projectID, status)
{
    location.href = createLink('project', 'all', "status=" + status + "&project=" + projectID + "&orderBy=" + orderBy + '&productID=' + productID);
}

/**
 * Set the color of the badge to white.
 *
 * @param  object  obj
 * @param  bool    isShow
 * @access public
 * @return void
 */
function setBadgeStyle(obj, isShow)
{
    var $label = $(obj);
    if(isShow == true)
    {
        $label.find('.label-badge').css({"color":"#fff", "border-color":"#fff"});
    }
    else
    {
        $label.find('.label-badge').css({"color":"#838a9d", "border-color":"#838a9d"});
    }
}

function buildForm(action, target)
{
    var tempform           = document.createElement("form");
    tempform.action        = action;
    tempform.method        = "post";
    tempform.target        = typeof(target) == 'undefined' ? '' : target;
    tempform.style.display = "none";

    var opt   = document.createElement("input");
    opt.name  = 'executionIDList';
    opt.value = checkItems;

    tempform.appendChild(opt);
    document.body.appendChild(tempform);
    tempform.submit();
}
