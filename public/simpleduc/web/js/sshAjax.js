$("#erreur").hide();
$(".cmd").hide();
$(function () {
    $("#serverbox").submit(function () {
        $("#loader").show();
        server = $(this).find("input[name=server]").val();
        port = $(this).find("input[name=port]").val();
        login = $(this).find("input[name=login]").val();
        password = $(this).find("input[name=password]").val();

        $.post("index.php?page=ssh", {server: server, port: port, login: login, password: password}, function (data) {
            $("#loader").hide();
            console.log(data);
            if (data == 'ok') {
                $("#serverbox").hide();
                $(".cmd").show();
            } else {
                $("#erreur").empty().append(data);
            }
        });
        return false;
    });
});

var model = document.getElementById("empModal");
$(document).ready(function () {
    $('.pcInfo').click(function () {
        var idOrdinateur = $(this).data('id');

        // AJAX request
        $.ajax({
            url: 'index.php?page=modal',
            type: 'post',
            data: {idOrdinateur: idOrdinateur},
            success: function (response) {
                // Add response in Modal body
                $('.modal-body').html(response);

                // Display Modal
                $('#empModal').modal('show');
                model.style.display = 'block';
            }
        });
    });
});
model.style.display = 'none';