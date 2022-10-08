<script>
$(function()
{
    $('#trip').closest('tr').hide();
    $('#begin, #end, #hours, #overtime').parent().addClass('required');
});
</script>