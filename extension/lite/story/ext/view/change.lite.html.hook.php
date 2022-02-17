<script>
$("a[href^='#affectedBugs']").parent().remove();
$("a[href^='#affectedCases']").parent().remove();
$("#mainContent").find("#verify").parent().parent().addClass("hide");
$("#mainContent").find("#affectedProjects").parent().parent().parent().addClass("hide");
</script>
