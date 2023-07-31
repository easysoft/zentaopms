$(document).ready(function()
{
    /**
     * 隐藏列表标签。
     * Hide tabs except the browse list tab.
     */
    $('.menus input[name^=actions]:not(input[value=browse])').parent('.checkbox-primary').hide();

    /**
     * 切换列表标签的显示。
     * Toggle display of tabs except the browse list tab.
     */
    $('#privList').on('click', '.menus .icon-plus', function()
    {
        $(this).toggleClass('icon-minus', 'icon-plus');
        $('.menus input[name^=actions]:not(input[value=browse])').parent('.checkbox-primary').toggle();
    })

    /**
     * 勾选浏览列表标签时，自动勾选下面的所有标签。
     * Check all tabs when the Browse list tab is selected.
     */
    $('#privList').on('change', '.menus input[value=browse]', function()
    {
        $(this).parents('.menus').find('[name^=actions]').prop('checked', $(this).prop('checked'));
    });

    /**
     * 勾选浏览列表标签下面的任意一个标签时，自动勾选浏览列表标签。
     * Check the browse list tab when any one of the tabs is selected.
     */
    $('#privList').on('click', '.menus input[name^=actions]:not(input[value=browse])', function()
    {
        let $parent = $(this).parents('.menus');

        $parent.find('input[value=browse]').prop('checked', $parent.find('input[name^=actions]:not(input[value=browse]):checked').length > 0);
    });

    $('#privList').on('change', '#allChecker', function()
    {
        let checked  = $(this).prop('checked');
        $('input[type=checkbox]').prop('checked', checked);
    });

    $('#privList').on('change', 'tbody > tr > th.module .check-all', function()
    {
        let checked  = $(this).find('input[type=checkbox]').prop('checked');
        let $actions = $(this).closest('tr').find('td > .group-item');
        $actions.find('input[type=checkbox]').prop('checked', checked);

        let moduleCount       = $('th.module input[type=checkbox]').length;
        let selectModuleCount = $('th.module input[type=checkbox]:checked').length;
        $('#allChecker').prop('checked', moduleCount == selectModuleCount);
    });

    $('#privList').on('change', 'tbody > tr .group-item input[type=checkbox]', function()
    {
        let actionsCount       = $(this).closest('td').find('.group-item').length;
        let selectActionsCount = $(this).closest('td').find('.group-item input[type=checkbox]:checked').length;
        $(this).closest('tr').find('th input[type=checkbox]').prop('checked', actionsCount == selectActionsCount);

        let moduleCount       = $('th.module input[type=checkbox]').length;
        let selectModuleCount = $('th.module input[type=checkbox]:checked').length;
        $('#allChecker').prop('checked', moduleCount == selectModuleCount);
    });
});
