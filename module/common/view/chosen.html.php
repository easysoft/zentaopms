<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
if($config->debug)
{
    css::import($jsRoot . 'jquery/chosen/min.css');
    js::import($jsRoot . 'jquery/chosen/min.js');
}
?>
<script>
var noResultsMatch       = '<?php echo $lang->noResultsMatch;?>';
var chooseUsersToMail    = '<?php echo $lang->chooseUsersToMail;?>';
var defaultChosenOptions = {no_results_text: noResultsMatch, width:'100%', allow_single_deselect: true, disable_search_threshold: 1, placeholder_text_single: ' ', placeholder_text_multiple: ' ', search_contains: true};
$(document).ready(function()
{
    $("#mailto").attr('data-placeholder', chooseUsersToMail);
    $("#mailto, .chosen, #productID").chosen(defaultChosenOptions).on('chosen:showing_dropdown', function()
    {
        var $this = $(this);
        var $chosen = $this.next('.chosen-container').removeClass('chosen-up');
        var $drop = $chosen.find('.chosen-drop');
        $chosen.toggleClass('chosen-up', $drop.height() + $drop.offset().top - $(document).scrollTop() > $(window).height());
    });
});
</script>
