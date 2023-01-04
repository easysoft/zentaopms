<?php js::set('options', json_encode($options));?>
<?php js::set('canSaveQuery', !empty($_SESSION[$module . 'Query']));?>
<?php js::set('formSession', $_SESSION[$module . 'Form'])?>
<?php js::set('onMenuBar', $onMenuBar);?>
<script>
$(function()
{
    var queryBox      = $('#queryBox');
    var groupItems    = <?php echo $config->search->groupItems;?>;
    var module        = '<?php echo $module;?>';
    var actionURL     = '<?php echo $actionURL;?>';

    options = JSON.parse(options);
    options.saveSearch.config = {
        'data-toggle' : 'modal',
        'data-url' : createLink('search', 'saveQuery', 'module=' + module)
    };
    var searchObject = new zui.SearchForm(queryBox[0], options);
    var searchForm   = $(searchObject.element).find('.search-form');
    var $searchForm  = $(searchForm);

    $searchForm.append('<input type="hidden" name="module" value="' + module +'" />');
    $searchForm.append('<input type="hidden" name="actionURL" value="' + actionURL +'" />');
    $searchForm.find('.btn-submit-form').attr('type', 'submit');
    $searchForm.find('.search-form-footer .save-query').attr('href', createLink('search', 'saveQuery', 'module=' + module + '&onMenuBar=' + onMenuBar));
    $(document).on('click', '#searchFormBtn', function()
    {
        queryBox.toggleClass('hidden');
    });

    $.each(formSession, function(key, value)
    {
        $searchForm.find('#' + key + ':visible').val(value);
    });

    $searchForm.on('click', '.history-record .lighter-pale .icon-close', function(e)
    {
        e.preventDefault(); // Fix bug #21572.
        var $query  = $(this).closest('.label-btn');
        var queryId = $query.attr('data-id');
        var deleteQueryLink = $.createLink('search', 'deleteQuery', 'queryID=' + queryId);
        $.get(deleteQueryLink, function(data)
        {
            if(data == 'success') $query.remove();
        });
        e.stopPropagation();
    });

    $(document).on('click', '.search-form .history-record .lighter-pale', function()
    {
        executeQuery($(this).parent().attr('data-id'));
    });

   // $(document).on('click', '.save-query', function()
   // {
   //     var name = prompt("请输入名称：");
   //     alert(name);
   // });

    /**
     * Execute query.
     *
     * @param  int $queryID
     * @access public
     * @return void
     */
    function executeQuery(queryID)
    {
        if(!queryID) return;
        location.href = actionURL.replace('myQueryID', queryID);
    }

    if(!canSaveQuery)
    {
        $('.btn.save-query').attr('disabled', 'disabled');
        $('.btn.save-query').css('pointer-events', 'none');
    }
});
</script>
