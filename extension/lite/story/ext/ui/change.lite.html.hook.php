<script>
window.waitDom("a[href='#affectedBugs']", function(){ $("a[href='#affectedBugs']").parent().remove();})
window.waitDom("a[href='#affectedCases']", function(){ $("a[href='#affectedCases']").parent().remove();})
window.waitDom("a[href='#affectedProjects']", function(){ $("a[href='#affectedProjects']").closest('div.form-group').hide();})
window.waitDom('[name=verify]', function(){ $('[name=verify]').closest('div.form-group').hide();})
</script>
