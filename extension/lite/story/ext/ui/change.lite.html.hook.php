<script>
window.waitDom("a[href='#affectedBugs']", function(){ $("a[href='#affectedBugs']").parent().remove();})
window.waitDom("a[href='#affectedCases']", function(){ $("a[href='#affectedCases']").parent().remove();})
window.waitDom("a[href='#affectedProjects']", function(){ $("a[href='#affectedProjects']").closest('div.form-row').hide();})
window.waitDom('#verify', function(){ $('#verify').closest('div.form-row').hide();})
</script>
