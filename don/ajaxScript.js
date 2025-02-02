<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#search").keyup(function() {
        let query = $(this).val();
        if (query.length > 2) {
            $.ajax({
                url: "search_agents.php",
                method: "POST",
                data: { query: query },
                success: function(data) {
                    $("#results").html(data);
                }
            });
        } else {
            $("#results").html("");
        }
    });
});
</script>
