<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
if($config->debug)
{
    css::import($jsRoot . 'jquery/chosen/min.css');
    js::import($jsRoot . 'jquery/chosen/min.js');
}
?>
<style>
#colorbox, #cboxOverlay, #cboxWrapper{z-index:9999;}
</style>
<script> 
noResultsMatch       = '<?php echo $lang->noResultsMatch;?>';
chooseUsersToMail    = '<?php echo $lang->chooseUsersToMail;?>';
selectAnOption       = '<?php echo $lang->selectAnOption;?>';
selectSomeOptions    = '<?php echo $lang->selectSomeOptions;?>';
defaultChosenOptions = {no_results_text: noResultsMatch, allow_single_deselect: true, disable_search_threshold: 10, width: '100%', placeholder_text_single: selectAnOption, placeholder_text_multiple: selectSomeOptions};
$(document).ready(function()
{
    $("#mailto").attr('data-placeholder', chooseUsersToMail);
    $("#productID").chosen({no_results_text: noResultsMatch, width: '100%'});
    $("#projectID").chosen({no_results_text: noResultsMatch, width: '100%'});
    $(".chosen").chosen(defaultChosenOptions);
});
</script>
