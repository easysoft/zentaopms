<script>
window.waitDom('.sidebar a[href*="showFiles"]', function()
{
    this.closest('li.tree-item').remove();
});

window.waitDom('.sidebar .icon.icon-paper-clip', function()
{
    this.closest('li.tree-item').remove();
});
</script>
