<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<link rel='stylesheet' href='<?php echo $clientTheme;?>autosuggest.css' />
<script src='<?php echo $jsRoot;?>jquery/autosuggest/min.js' type='text/javascript'></script>
<script language='javascript'>
$(function() {
    var data = {items: [
    {value: "21", name: "Mick Jagger"},
    {value: "43", name: "Johnny Storm"},
    {value: "46", name: "Richard Hatch"},
    {value: "54", name: "Kelly Slater"},
    {value: "55", name: "Rudy Hamilton"},
    {value: "79", name: "Michael Jordan"}
    ]};
    $("input[type=text]").autoSuggest(data.items, {selectedItem: "name", searchObj: "name"});
});
</script>
