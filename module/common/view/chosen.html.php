<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<style>
.picker-selection-single:after, .picker-multi.picker-focus .picker-selections:before{font-family: ZentaoIcon !important; content: '\f0d7' !important;}
</style>
<script>
var chooseUsersToMail = '<?php echo $lang->chooseUsersToMail;?>';
if($.fn.picker)
{
    $(document).ready(function()
    {
        $(".picker-select[data-pickertype!='remote']").picker({chosenMode: true});
        $("[data-pickertype='remote']").each(function()
        {
            var pickerremote = $(this).attr('data-pickerremote');
            $(this).picker({chosenMode: true, remote: pickerremote});
        })
    });
}
</script>
