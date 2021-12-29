$(document).on('click', '.task-toggle', function(e)
{
    var $toggle = $(this);
    var id = $(this).data('id');
    var isCollapsed = $toggle.toggleClass('collapsed').hasClass('collapsed');
    $toggle.closest('[data-ride="table"]').find('tr.parent-' + id).toggle(!isCollapsed);

    e.stopPropagation();
    e.preventDefault();
});

$(function()
{
    $('#productplanList tbody tr').each(function()
    {
        var $content = $(this).find('td.content');
        var content  = $content.find('div').html();
        if(content.indexOf('<br') >= 0)
        {
            $content.append("<a href='###' class='more'><i class='icon icon-chevron-double-down'></i></a>");
        }
    });

    $('#createExecutionButton').on('click', function()
    {
        var projectID = $('#project').val();
        var planID    = $('#planID').val();
        if(!projectID)
        {
            alert(projectNotEmpty);
            return false;
        }
        else
        {
            $.apps.open(createLink('execution', 'create', 'projectID=' + projectID + '&executionID=&copyExecutionID=&planID=' + planID + '&confirm=&productID=' + productID), 'project')
        }
        $('#projects').modal('hide');
    });

    $('.switchButton').click(function()
    {
        var viewType = $(this).attr('data-type');
        $.cookie('viewType', viewType, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });

    $('#branch').change(function()
    {
        var branchID = $(this).val();
        var link = createLink('productplan', 'browse', "productID=" + productID + '&branch=' + branchID);
        location.href = link;
    });

    if(viewType == 'bykanban')
    {
        $('#branch_chosen .chosen-single span').prepend('<i class="icon-delay"></i>');
        $('#kanban').kanban(
        {
            data:          kanbanData,
            minColWidth:   290,
            maxColWidth:   290,
            itemRender:    renderKanbanItem,
            virtualize:    true,
            droppable:
            {
                target:       findDropColumns,
                finish:       handleFinishDrop,
                mouseButton: 'left'
            }
        });

        $('#kanban').on('scroll', function()
        {
            $.zui.ContextMenu.hide();
        });
    }
});

$(document).on('click', 'td.content .more', function(e)
{
    var $toggle = $(this);
    if($toggle.hasClass('open'))
    {
        $toggle.removeClass('open');
        $toggle.closest('.content').find('div').css('height', '25px');
        $toggle.css('padding-top', 0);
        $toggle.find('i').removeClass('icon-chevron-double-up').addClass('icon-chevron-double-down');
    }
    else
    {
        $toggle.addClass('open');
        $toggle.closest('.content').find('div').css('height', 'auto');
        $toggle.css('padding-top', ($toggle.closest('.content').find('div').height() - $toggle.height()) / 2);
        $toggle.find('i').removeClass('icon-chevron-double-down').addClass('icon-chevron-double-up');
    }
});

/*
* Find drop columns
* @param {JQuery} $element Drag element
* @param {JQuery} $root Dnd root element
*/
function findDropColumns($element, $root)
{
    var $col        = $element.closest('.kanban-col');
    var col         = $col.data();
    var lane        = $col.closest('.kanban-lane').data();
    var kanbanID    = $root.data('id');
    var kanbanRules = window.kanbanDropRules ? window.kanbanDropRules[kanbanID] : null;

    if(!kanbanRules) return $root.find('.kanban-lane[data-id="' + lane.id + '"] .kanban-lane-col:not([data-type="project"],[data-type="' + col.type + '"])');

    var colRules = kanbanRules[col.type];
    var lane     = $col.closest('.kanban-lane').data('lane');
    return $root.find('.kanban-lane-col').filter(function()
    {
        if(!colRules) return false;
        if(colRules === true) return true;

        var $newCol = $(this);
        var newCol = $newCol.data();
        if(newCol.id === col.id) return false;

        var $newLane = $newCol.closest('.kanban-lane');
        var newLane = $newLane.data('lane');
        return colRules.indexOf(newCol.type) > -1 && newLane.id === lane.id;
    });
}

/**
 * Handle finish drop task
 * @param {Object} event Event object
 * @returns {void}
 */
function handleFinishDrop(event)
{
    var $card = $(event.element); // The drag card
    var $dragCol = $card.closest('.kanban-lane-col');
    var $dropCol = $(event.target);

    /* Get d-n-d(drag and drop) infos  获取拖放操作相关信息 */
    var card = $card.data('item');
    var fromColType = $dragCol.data('type');
    var toColType = $dropCol.data('type');
    var kanbanID = $card.closest('.kanban').data('id');

    changeCardColType(card, fromColType, toColType, kanbanID);
}

/**
 * Change column type for a card

 * @param {Object} card        Card object
 * @param {String} fromColType The column type before change
 * @param {String} toColType   The column type after change
 * @param {String} kanbanID    Kanban ID
 */
function changeCardColType(card, fromColType, toColType, kanbanID)
{
    if(typeof card == 'undefined') return false;
}

/**
 * The function for rendering kanban item
 */
function renderKanbanItem(item, $item)
{
    var $title = $item.children('.title');
}

/**
 * Get planID
 *
 * @param  object $obj
 * @param  int    $branch
 * @access public
 * @return void
 */
function getPlanID(obj, branch)
{
    var planID = $(obj).attr("data-id");
    $('#planID').val(planID);

    link = createLink('productplan', 'ajaxGetProjects', 'productID=' + productID + '&branch=' + branch);
    $.get(link, function(projects)
    {
        $('#project').replaceWith(projects);
        $("#project_chosen").remove();
        $("#project").chosen();

        var projectList = $("#project").val();
        if(!projectList)
        {
            $("#project").attr('disabled', true);
            $("#project").trigger('chosen:updated');
            $(".tips").removeClass('hidden');

            var locateLink   = createLink('product', 'project', 'status=all&productID=' + productID + '&branch=' + branch);
            var locateButton = "<a href=" + locateLink + " class='btn btn-primary' data-app='product'>" + enterProjectList + "</a>";
            $("#projects .btn-primary").replaceWith(locateButton);
        }
        else
        {
            $(".tips").addClass('hidden');
        }
    });
}
