<?php js::set('options', json_encode($options));?>
<script>
$(function()
{
    var queryBox = $('#queryBox');
    options = JSON.parse(options);
    new zui.SearchForm(queryBox[0], options);

    $(document).on('click', '#searchFormBtn', function()
    {
        queryBox.toggleClass('hidden');
    });
});
</script>
