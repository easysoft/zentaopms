$(function()
{
    if(table)
    {
        $('.dataview-' + table).addClass('active');

        var $tree = $('#modules').tree({name:'tree-group'});
        var tree  = $tree.data('zui.tree');
        tree.expand($('.dataview-' + table).parents('li'));
    }

    $(document).on('click','.query-view', function()
    {
        var url    = createLink('dataview', 'query', 'id=' + table);
        var result = true;

        if(dataview.used) result = confirm(warningDesign);
        if(result) window.location.href = url;
    });

    initPager(pageID, recPerPage, recTotal, fieldCount);

    $('.first-page,.left-page,.last-page,.right-page').click(function()
    {
        pageID = $(this).data('page');
        queryTableData();
    });

    $('.dropup li a').click(function()
    {
        pageID     = 1;
        recPerPage = $(this).data('size');
        queryTableData();
    })

});

/**
 * Locate page.
 *
 * @param string module
 * @param string method
 * @param string params
 * @access public
 * @return void
 */
function locate(module, method, params)
{
    var link = createLink(module, method, params);
    window.location.href = link;
}

/**
 * Query table data.
 *
 * @return void
 */
function queryTableData()
{
    var params = {};
    params.pageID     = pageID;
    params.recPerPage = recPerPage;
    params.recTotal   = recTotal;
    params.table      = table;
    params.type       = type;
    $.post(createLink('dataview', 'ajaxGetTableData'), params,function(resp)
    {
        resp = JSON.parse(resp);
        $('#datas').empty();
        $('#datas').append(resp.html);

        recTotal   = resp.recTotal;
        fieldCount = resp.fieldCount;
        initPager(pageID, recPerPage, recTotal, fieldCount);
    });
}

/**
 * Init pager.
 *
 * @param  int pageID
 * @param  int recPerPage
 * @param  int recTotal
 * @access public
 * @return void
 */
function initPager(pageID, recPerPage, recTotal, fieldCount)
{
    var pageID     = parseInt(pageID);
    var recPerPage = parseInt(recPerPage);
    var recTotal   = parseInt(recTotal);
    var pageTotal  = parseInt(Math.ceil(recTotal / recPerPage));

    $('.recTotal').html(recTotalTip.replace('%s', recTotal));
    $('.recPerPage').html(recPerPageTip.replace('%s', recPerPage));

    $('.dropup li').removeClass('active');
    $('.dropup li a').each(function()
    {
        if($(this).data('size') == recPerPage)
        {
            $(this).parent().addClass('active');
            return;
        }
    });

    $('.page-number').html('<strong>' + pageID + '</strong>/<strong>' + pageTotal + '</strong>');

    $('.left-page').data('page', pageID - 1);
    $('.right-page').data('page', pageID + 1);
    $('.last-page').data('page', pageTotal);

    $('.first-page,.left-page,.last-page,.right-page').removeClass('disabled');
    if(pageID == 1) $('.first-page,.left-page').addClass('disabled');
    if(pageID == pageTotal) $('.last-page,.right-page').addClass('disabled');

    $('#queryResult').html(queryResult.replace('%s', recTotal).replace('%s', fieldCount));
}

