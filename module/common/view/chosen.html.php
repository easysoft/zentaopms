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
selectAnOption       = '<?php echo $lang->selectAnOption;?>';
selectSomeOptions    = '<?php echo $lang->selectSomeOptions;?>';
defaultChosenOptions = {no_results_text: noResultsMatch, allow_single_deselect: true, disable_search_threshold: 10, width: '100%', placeholder_text_single: selectAnOption, placeholder_text_multiple: selectSomeOptions, search_contains: true};
$(document).ready(function()
{
    $("#mailto").attr('data-placeholder', chooseUsersToMail);
    $(".chosen, #productID").chosen(defaultChosenOptions);
});
</script>
