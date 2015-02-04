<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
if($config->debug)
{
    css::import($jsRoot . 'jquery/chosen/min.css');
    js::import($jsRoot . 'jquery/chosen/min.js');
}
?>
<script> 
noResultsMatch       = '<?php echo $lang->noResultsMatch;?>';
chooseUsersToMail    = '<?php echo $lang->chooseUsersToMail;?>';
defaultChosenOptions = {no_results_text: noResultsMatch, width:'100%', allow_single_deselect: true, disable_search_threshold: 1, placeholder_text_single: ' ', placeholder_text_multiple: ' ', search_contains: true};
$(document).ready(function()
{
    $("#mailto").attr('data-placeholder', chooseUsersToMail);
    $("#mailto, .chosen, #productID").each(function()
    {
        var $this = $(this);
        if($this.offset().top + 240 > $(document.body).height())
        {
            $this.attr('data-css-class', 'chosen-up');
        }
    }).chosen(defaultChosenOptions);
});
</script>
