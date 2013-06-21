</div>
<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
</div>
<?php
if(isset($pageJS)) js::execute($pageJS);  // load the js for current page.

/* Load hook files for current page. */
$extPath      = dirname(dirname(dirname(realpath($viewFile)))) . '/common/ext/view/';
$extHookRule  = $extPath . 'm.footer.*.hook.php';
$extHookFiles = glob($extHookRule);
if($extHookFiles) foreach($extHookFiles as $extHookFile) include $extHookFile;
?>
<script>
$(function()
{
    $('#navbar li a').click(function(event)
     {
         // Prevent the usual navigation behavior
         //event.preventDefault();
         //location.hash = $(this).attr("href");
         //$.mobile.loadPage($(this).attr("href"));
    });
});
</script>
</body>
</html>
