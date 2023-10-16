<!DOCTYPE html> 
<html lang="fr">
    <!-- Modification de la classe statut en cours avec le controleur etc -->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Sundry, le jeu</title>
    </head>

    <body>
        <form method="post" action="index.php?page=inscription">
            <!--Body-->
            <div class="modal-body" >
                <div class="md-form form-sm mb-5">
                    <i class="fas fa-envelope prefix"></i>
                    <input type="email" id="emailInscription" name='emailInscription' required autocomplete class="form-control form-control-sm validate">
                    <label data-error="Incorrect" data-success="Correct" for='emailInscription'>Votre email</label>
                </div>

                <div class="md-form form-sm mb-5">
                    <i class="fas fa-lock prefix"></i>
                    <input type="password" id="mdp1" name='mdp1' required autocomplete class="form-control form-control-sm validate">
                    <label data-error="Incorrect" data-success="Correct" for="mdp1">Votre mot de passe (8 caractères minimum)</label>
                </div>

                <div class="md-form form-sm mb-4">
                    <i class="fas fa-lock prefix"></i>
                    <input type="password" id="mdp2" name='mdp2' required autocomplete class="form-control form-control-sm validate">
                    <label data-error="Incorrect" data-success="Correct" for="mdp2">Répéter votre mot de passe</label>
                </div>

                <input type="hidden" id="idRole" name="idRole" value="1" required autocomplete>                    

                <div class="text-center form-sm mt-2">
                    <button class="btn btn-info" type='submit' name='btInscription'>S'inscrire<i class="fas fa-sign-in ml-1"></i></button>
                </div>
            </div>
        </form>
    </body>

</html>
