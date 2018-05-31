$(function()
{
    var $form = $('#blockAdminForm');
    $form.find('.chosen').chosen();

    // 用于动态加载区块列表及设置参数
    var $blocksList = $('#blocksList');
    var $blockParams = $('#blockParams');

    var updateParams = function(data)
    {
        $blockParams.html(data);
        $blockParams.find('.chosen').chosen();
        $.zui.ajustModalPosition();
        $form.removeClass('loading');
        $form.addClass('form-inited');
        $blockParams.find('input:first').focus();
        if($blockParams.find('#actionLink').size() > 0) $form.attr('action', $blockParams.find('#actionLink').val());
    };

    // 获取 html 和 rss 区块参数
    var getNotSourceParams = function(type, blockID)
    {
        if (blockID === undefined) blockID = 0;

        $blockParams.empty();
        $form.removeClass('form-inited');

        $.get(createLink('block', 'set', 'id=' + blockID + '&type=' + type), function(data)
        {
            updateParams(data);
            if (type === 'welcome')
            {
                $blockParams.find('#title').closest('.form-group').hide();
            }
        });
    };

    // 用于获取指定区块的设置参数
    var getBlockParams = function(type, moduleID)
    {
        $blockParams.empty();
        $form.removeClass('form-inited');

        if(type == '') return;
        $form.addClass('loading');
        $.get(createLink('block', 'set', 'id=' + blockID + '&type=' + type + '&source=' + moduleID), updateParams);
    };

    // 获取指定模块下所有可用的区块
    var getBlocks = function(moduleID)
    {
        $blocksList.data('module', moduleID).empty();
        $form.removeClass('form-inited');
        $blockParams.empty();
        if(!moduleID) return;

        $form.addClass('loading');

        var onFinish = function()
        {
            $form.removeClass('loading');
            if($blocksList.find('#moduleBlock').val() != '') getBlockParams($blocksList.find('#moduleBlock').val(), $blocksList.data('module'));
        };

        if(moduleID.indexOf('hiddenBlock') != -1)
        {
            getNotSourceParams('html', moduleID.replace('hiddenBlock', ''), onFinish);
            return true;
        }

        if(moduleID == 'html' || moduleID == 'dynamic' || moduleID == 'flowchart' || moduleID == 'assigntome' || moduleID == 'welcome')
        {
            getNotSourceParams(moduleID, blockID, onFinish);
            return true;
        }

        $.get(createLink('block', 'main', 'module=' + moduleID + '&id=' + blockID), {mode:'getblocklist'}, function(data)
        {
            $blocksList.html(data);
            $blocksList.find('.chosen').chosen();
            onFinish();
            $.zui.ajustModalPosition();
        });
    };

    // 当模块选择变更时，刷新参数列表
    $('#modules').on('change', function()
    {
        getBlocks($(this).val());
    });

    $blocksList.on('change', '#moduleBlock', function()
    {
        getBlockParams($(this).val(), $blocksList.data('module'));
    });

    if(blockID) setTimeout(function(){getBlocks($('#modules').val());}, 200);
    if(module) $blocksList.data('module', module);
});
