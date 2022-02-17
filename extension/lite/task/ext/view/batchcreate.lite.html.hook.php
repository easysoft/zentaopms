<script>
$(function()
{
    for(var index = 0; index <= 9; index++) $('#batchCreateForm').append(`<input type="hidden" name="vision[${index}]" id="vision[${index}]" value="lite">`);
});
</script>
