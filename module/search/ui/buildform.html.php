<?php js::set('options', json_encode($options));?>
<script>
$(function()
{
    var queryBox = $('#queryBox');
    new zui.SearchForm(queryBox, options);
    function showSearchForm() queryBox.toggleClass('hidden');

    $(document).on('click', '#searchFormBtn', function()
    {
        showSearchForm();
    });
});
</script>
