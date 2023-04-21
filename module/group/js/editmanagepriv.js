/**
  * Init comp recomend-tree.
  *
  * @param  array  data
  * @access public
  * @return void
  */
function initRecomendTree(data)
{
    $(".menuTree.recommend").tree(
    {
        data: data,
        initialState: 'active',
        itemCreator: function($li, item)
        {
            $li.append('<a class="priv-item" data-has-children="' + (item.children ? !!item.children.length : false) + '" href="#" title="' + item.title + '">' + item.title + (item.children ? '' : '<i class="icon icon-close hidden" data-type="depend" data-privid=' + item.privID + ' data-relationpriv=' + item.relationPriv + '></i>') +  '</a>');
            if(item.active) $li.addClass('active open in');
        }
    });
}

/**
  * Init comp depend-tree.
  *
  * @param  array  data
  * @access public
  * @return void
  */
function initDependTree(data)
{
    $(".menuTree.depend").tree(
    {
        data: data,
        initialState: 'active',
        itemCreator: function($li, item)
        {
            $li.append('<a class="priv-item" data-has-children="' + (item.children ? !!item.children.length : false) + '" href="#" title="' + item.title + '">' + item.title + (item.children ? '' : '<i class="icon icon-close hidden" data-type="recommend" data-privid=' + item.privID + ' data-relationpriv=' + item.relationPriv + '></i>') + '</a>');
            if(item.active) $li.addClass('active open in');
        }
    });
}

/**
  * update comp tree after click checkbox-label.
  *
  * @param  obj objTree
  * @access public
  * @return void
  */
function updatePrivTree(objTree)
{
    $(".menuTree.depend").data('zui.tree').reload(objTree.dependData || [] );
    if(objTree.dependData && objTree.dependData.length)
    {
        $(".menuTree.depend + .empty-tip").addClass('hidden');

        var privCount = 0;
        $.each(objTree.dependData, function(index, item){if(item.children) privCount += item.children.length;});
        $(".menuTree.depend").closest('.priv-panel').find('.panel-title .priv-count').html('(' + privCount + ')');
    }
    else
    {
        $(".menuTree.depend + .empty-tip").removeClass('hidden');
        $(".menuTree.depend").closest('.priv-panel').find('.panel-title .priv-count').html('');
    }
    $(".menuTree.recommend").data('zui.tree').reload(objTree.recommendData || []);
    if(objTree.recommendData && objTree.recommendData.length)
    {
        $(".menuTree.recommend + .empty-tip").addClass('hidden');

        var privCount = 0;
        $.each(objTree.recommendData, function(index, item){if(item.children) privCount += item.children.length;});
        $(".menuTree.recommend").closest('.priv-panel').find('.panel-title .priv-count').html('(' + privCount + ')');
    }
    else
    {
        $(".menuTree.recommend + .empty-tip").removeClass('hidden');
        $(".menuTree.recommend").closest('.priv-panel').find('.panel-title .priv-count').html('');
    }
    if(!canDeleteRelation) $('.side a.priv-item .icon-close').remove();
}

/**
 * Get relation side.
 *
 * @param  int $privID
 * @access public
 * @return void
 */
function getSideRelation(privID)
{
    $.get(createLink('group', 'ajaxGetPrivRelations', "privID=" + privID), function(data)
    {
        var objTree = {};
        if(data)
        {
            var relatedPriv = JSON.parse(data);
            objTree.dependData    = relatedPriv.depend;
            objTree.recommendData = relatedPriv.recommend;
        }
        updatePrivTree(objTree);
    })
}

/**
 * Update depend and recommend privs.
 *
 * @param e $e
 * @access public
 * @return void
 */
function updateRelations(e)
{
    if($('.bg-primary-pale').length) $('.bg-primary-pale').removeClass('bg-primary-pale');
    $('#privListTable').length > 0 ? $(e.target).closest('tr').addClass('bg-primary-pale') : $(e.target).addClass('bg-primary-pale');

    var selectedID = $('#privListTable').length == 0 ? $(e.target).siblings('input:checkbox').data('id') : $(e.target).closest('tr').attr('data-id');
    $('.side a#addDependent').attr('href', createLink('group', 'addRelation', "privIdList=" + selectedID + '&type=depend')).removeAttr('disabled');
    $('.side a#addRecommendation').attr('href', createLink('group', 'addRelation', "privIdList=" + selectedID + '&type=recommend')).removeAttr('disabled');
    if(!$('.side a#addDependent').hasClass('modaled')) $('.side a#addDependent,.side a#addRecommendation').modal().addClass('modaled');
    getSideRelation(selectedID);
}

$(function()
{
    $(".menuTree").on('mouseover', ".priv-item", function()
    {
        $(this).addClass('text-primary');
        $(this).find('.icon').removeClass('hidden');
    }).on('mouseout', '.priv-item', function()
    {
        $(this).removeClass('text-primary');
        $(this).find('.icon').addClass('hidden');
    });

    initRecomendTree([]);
    initDependTree([]);

    $('li.has-list > ul').addClass("menu-active-primary menu-hover-primary");
    $('.sorter-group').sortable(
    {
        'selector': '.group-item',
        finish: function(e)
        {
            var orders = {};
            var arrGetItems = $(e.target).parent().data('zui.sortable').getItems();
            for(var i = 0; i < arrGetItems.length; i++)
            {
                var groupItem = arrGetItems[i];
                var privID    = $(groupItem.item).find('input').data('id');
                orders['orders[' + privID + ']'] = groupItem.order;
            }

            $.post(createLink('group', 'ajaxUpdatePrivOrder'), orders).error(function()
            {
                bootbox.alert(lang.timeout);
            });
        }
    });


    $('.btn-switch').on('click', function()
    {
        $.cookie('managePrivEditType', $(this).data('type'), {expires: config.cookieLife, path: config.webRoot});
        location.href = location.href;
    });

    $('.permission-row .checkbox-primary').on('click', 'label', function(e)
    {
        e.stopPropagation();
        if($(e.target).prop('tagName') == 'LABEL') updateRelations(e);
    });

    $('#privListTable tr').on('click', 'td', function(e)
    {
        if(!$(e.target.closest('td')).hasClass('c-actions') && $(e.target).attr('type') != 'checkbox')
        {
            e.stopPropagation();
            updateRelations(e);
        }
    });

    $(document).on('click', '.side .priv-item .icon-close', function()
    {
        var $this        = $(this);
        var privCount    = $this.closest('.menuTree').find('.priv-item[data-has-children="false"]').length;
        var type         = $this.data('type')
        var privID       = $this.data('privid')
        var relationPriv = $this.data('relationpriv');
        $.get(createLink('group', 'deleteRelation', 'type=' + type + '&privID=' + privID + '&relationPriv=' + relationPriv));
        $this.closest('.priv-panel').find('.panel-title .priv-count').html('(' + (privCount - 1) + ')');
        if($this.closest('ul').find('li').length == 1) $this.closest('ul').closest('li').remove();
        $this.closest('li').remove();
    })

    $(document).on('change', '.checkbox-primary input[type=checkbox], #privListTable input[type=checkbox]', function()
    {
        var privIdList = '';
        $('.checkbox-primary input[type=checkbox]:checked').each(function()
        {
            var privID = $(this).data('id');
            if(privID) privIdList += ',' + privID;
        });
        $('#privListTable input[type=checkbox]:checked').each(function()
        {
            var privID = $(this).closest('tr').data('id');
            if(privID) privIdList += ',' + privID;
        });

        if(privIdList == '')
        {
            $('#batchSetDepend,#batchSetRecommend,#batchDeleteDepend,#batchDeleteRecommend').attr('href', '#');
            $('#batchActions').addClass('disabled');
            return;
        }

        privIdList = privIdList.substr(1);
        $('#batchSetDepend').attr('href', createLink('group', 'addRelation', "privIdList=" + privIdList + '&type=depend'));
        $('#batchSetRecommend').attr('href', createLink('group', 'addRelation', "privIdList=" + privIdList + '&type=recommend'));
        $('#batchDeleteDepend').attr('href', createLink('group', 'batchDeleteRelation', "privIdList=" + privIdList + '&type=depend'));
        $('#batchDeleteRecommend').attr('href', createLink('group', 'batchDeleteRelation', "privIdList=" + privIdList + '&type=recommend'));
        $('#batchActions').removeClass('disabled');
    })
});
