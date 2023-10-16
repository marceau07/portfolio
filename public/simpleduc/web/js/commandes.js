$(function () {
    $("#cmd").submit(function () {
        commande = $(this).find("input[name=cmd-line]").val();

        $.post("index.php?page=cmd", {commande: commande}, function (data) {
            console.log(data);
            $("#prevCmd").empty().append(data);
        });
        return false;
    });
});