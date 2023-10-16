function closeModeEditionSite(id_card) {
    $('#card-'+id_card+' > a').css('display', 'block');
    $('#edit-card-'+id_card).remove();
    $('#edit-card-title-'+id_card).remove();
    $('#edit-card-btn-'+id_card).remove();
    $('#card-'+id_card).css('transition', 'transform 1s');
    $('#card-'+id_card).css('backface-visibility', 'hidden');
    $('#card-'+id_card).css('transform', 'rotateY(360deg)');
    $('#card-'+id_card+' > .card-footer a').css('display', 'block');
}

function openModeEditionSite(id_card) {
    $.ajax({
        url: 'admin/php/admin_ajax.php', 
        method: 'post', 
        dataType: 'json', 
        data: {
            openModeEditionSite: 1, 
            id_card: id_card
        }, 
        success: function(r) {
            $('#card-'+id_card+' > a').css('display', 'none');
            $('#card-'+id_card).css('transition', 'transform 1s');
            $('#card-'+id_card).css('backface-visibility', 'hidden');
            $('#card-'+id_card).css('transform', 'rotateY(360deg)');
            $('#card-'+id_card+' > .card-footer').css('display', 'block');
            $('#card-'+id_card+' > .card-footer a').css('display', 'none');
            $('#card-'+id_card).prepend("\
                <div class='row' id='edit-card-title-"+id_card+"'>\
                    <div class='col-sm-12'>\
                        <h5 class='white-text' style='font-weight: 700; padding-top: 15px;'><i class='fas fa-arrow-circle-left' style='cursor: pointer; color: white;' onclick='closeModeEditionSite("+id_card+");'></i>\t\t<span class='text-center'>"+r.carte_titre+"</span></h5>\
                    </div>\
                </div>\
                <div class='row' id='edit-card-"+id_card+"'>\
                    <div class='col-sm-12'>\
                        <div class='container'>\
                            <div class='row' id='form_edit_logo'>\
                                <input type='file' name='form_edit_img' id='form_edit_img' class='form-control-sm'>\
                            </div>\
                        </div>\
                        <select class='form-control' name='form_edit_group_id' id='form_edit_group_id'>"+r.groupsLists+"</select>\
                        <input type='text' name='form_edit_titre' id='form_edit_titre' class='form-control' placeholder='Votre nouveau titre ici...' value='"+r.carte_titre+"'>\
                        <textarea class='form-control' rows='8' name='form_edit_resume' id='form_edit_resume' placeholder='Votre nouveau résumé ici...'>"+r.carte_description+"</textarea>\
                        <div class='container'>\
                            <div class='row' id='form_edit_languages'>\
                                "+r.langages+"\
                            </div>\
                        </div>\
                        <input type='text' name='form_edit_github' id='form_edit_github' class='form-control' placeholder='Le lien vers le github...' value='"+r.carte_github+"'>\
                    </div>\
                </div>");
            $('#card-'+id_card+' > .card-footer .row').append("<a class='btn btn-info' href='#' id='edit-card-btn-"+id_card+"' onclick='editerSite("+id_card+");'>Sauver</a>");
        }
    });
   
}

function editerSite(id_card) {
    var formData = new FormData();
    formData.append('editerSite', 1);
    formData.append('id_card', id_card);
    formData.append('form_edit_img', $('#card-'+id_card+' #form_edit_img')[0].files[0]);
    formData.append('form_edit_group_id', $('#card-'+id_card+' #form_edit_group_id').val());
    formData.append('form_edit_titre', $('#card-'+id_card+' #form_edit_titre').val());
    formData.append('form_edit_resume', $('#card-'+id_card+' #form_edit_resume').val());
    formData.append('form_edit_github', $('#card-'+id_card+' #form_edit_github').val());
    $('#card-'+id_card+' #form_edit_languages input').each(function() { 
        if($(this).is(':checked')) {
            formData.append("form_edit_languages[]", $(this).val());
        }
    });
    
    $.ajax({
        url: 'admin/php/admin_ajax.php', 
        method: 'post', 
        dataType: 'json', 
        processData: false,
        contentType: false,
        data: formData, 
        success: function(r) {
            if(r.bEstOk === true) {
                window.location.reload();
            }
        }
    });
}

function exporterDonneesVersOneDrive() {
    $.ajax({
        url: 'admin/php/admin_ajax.php', 
        method: 'post', 
        dataType: 'json', 
        data: {
            exporterDonneesVersOneDrive: 1
        }, 
        beforeSend: function(r) {
           $('#icone_spinner').css('display', 'inline-block');
           $('#icone_one_drive').css('display', 'none');
        }, 
        success: function(r) {
           $('#icone_spinner').css('display', 'none');
           $('#icone_one_drive').css('display', 'inline-block');
        }
    });
}