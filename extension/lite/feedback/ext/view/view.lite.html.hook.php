<script>
$("#createCaseActionMenu").parent('.btn-group').remove();
$("#actionbox .histories-list li").each(function()
{
    var text = $(this).find('a').text();
    $(this).find('a').parent().text(text);
});
</script>
