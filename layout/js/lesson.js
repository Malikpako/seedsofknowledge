$(document).ready(function () {
    $("td").parent().hover(function () {
        $(this).find("button").toggle();
    });
});

function del(id) {
    window.location.href = "deletelesson.php?id=" + id;
}
