<script>
$("a[href^='#affectedBugs']").parent().remove();
$("a[href^='#affectedCases']").parent().remove();
$("#mainContent").find("#verify").closest("tr").addClass("hide");
$("#mainContent").find("#affectedProjects").closest("tr").addClass("hide");
$("#reviewer").parent().addClass("required");
</script>
