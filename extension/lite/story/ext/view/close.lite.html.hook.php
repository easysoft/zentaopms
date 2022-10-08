<script>
$('#closedReason option').each(function()
{
    if($(this).val() != '' && $(this).val() != 'done' && $(this).val() != 'duplicate' && $(this).val() != 'cancel')
    {
        $(this).remove();
    }
})
</script>
