<?php

function actionIndex($twig, $db) {
    $form = array();

    $category = new Category($db);
    $form['categories'] = $category->select();

    echo $twig->render('index.html.twig', array('form' => $form));
}

function actionMentions($twig) {
    header("Location: ../web/other/mentions.pdf");
    $html = $twig->render('mentions.html.twig', array('server_name' => $_SERVER['SERVER_NAME']));
    
    if(isset($_GET['html'])) {
        echo $html;
    } else {
        try {
            ob_end_clean();
            $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
            $html2pdf->writeHTML($html);
            $html2pdf->output("/var/www/html/symfony4-4017/public/lecoviddechaine/web/pdf/mentions.pdf", "F");
        } catch (Spipu\Html2Pdf\Exception\Html2PdfException $e) {
            echo 'error ' . $e;
        }
    }
}

function actionCgu($twig, $db) {
    header("Location: ../web/other/cgu.pdf");
}

function actionContact($twig, $db) {
    $form = array();

    if (isset($_POST['btSendForm'])) {
        $email = htmlspecialchars($_POST['email']);
        $reason = htmlspecialchars($_POST['reason']);

        if (isset($email) && !empty($email) && isset($reason) && !empty($reason)) {
            //Envoie d'un mail//
            $header = "MIME-Version: 1.0\r\n";
            $header .= "From:'$email'<$email>" . "\n";
            $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
            $header .= 'Content-Transfer-Encoding: 8bit';

            $message = "
                    <html>
                        <body>
                            <div align='center'>
                                <p>Mail provenant de : $email</p>
                                <p>Soucis: $reason</p>
                            </div>
                        </body>
                    </html>
                   ";

            mail("symfony4.4017@gmail.com", "Un client a besoin d'assistance", $message, $header);
//fin d'envoie du mail//
            $form['valide'] = true;
            $form['message'] = "Le webmaster a bien reçu votre mail, il vous répondra sous peu.";
        } else {
            $form['valide'] = false;
            $form['message'] = "Une erreur s'est produite. Réessayez dans quelques minutes...";
        }
    }

    echo $twig->render('contact.html.twig', array('form' => $form));
}

function actionData() {
    $data = file_get_contents("other/data.json");
    echo $data;
}
