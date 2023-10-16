<?php

function actionArticle($twig, $db) {
    $form = array();

    $category = new Category($db);
    $article = new Article($db);

    $listCategory = $category->select();
    $listA = $article->select();

    for ($i = 0; $i < sizeof($listA); $i++) {
        array_push($listA[$i], substr($listA[$i]["contentArticle"], 0, 100)); //Only send 30 caracters
        array_push($listA[$i], 100 - round(strlen($listA[$i][3]) * 100 / strlen($listA[$i][2]))); //Calculate de percentage of article left
    }

//    $pretty = function($v = '', $c = "&nbsp;&nbsp;&nbsp;&nbsp;", $in = -1, $k = null)use(&$pretty) {
//        $r = '';
//        if (in_array(gettype($v), array('object', 'array'))) {
//            $r .= ($in != -1 ? str_repeat($c, $in) : '') . (is_null($k) ? '' : "$k: ") . '<br>';
//            foreach ($v as $sk => $vl) {
//                $r .= $pretty($vl, $c, $in + 1, $sk) . '<br>';
//            }
//        } else {
//            $r .= ($in != -1 ? str_repeat($c, $in) : '') . (is_null($k) ? '' : "$k: ") . (is_null($v) ? '&lt;NULL&gt;' : "<strong>$v</strong>");
//        }return$r;
//    }; // vardump mais lisible
//    echo $pretty($listA); // vardump mais lisible

    echo $twig->render('list_article.html.twig', array('form' => $form, 'listA' => $listA, 'listCategory' => $listCategory));
}

function actionArticleCategory($twig, $db) {
    $form = array();

    if (isset($_GET['labelCategory']) && !empty($_GET['labelCategory']) || isset($_GET['date'])) {
        $article = new Article($db);

        if(isset($_GET['date'])) {
            $listA = $article->selectByCategoryDate($_GET['id_category'], $_GET['date']);
        } else {
            $listA = $article->selectByCategory($_GET['labelCategory']);
        }
        
        $form['article']['labelCategory'] = $listA[0]['labelCategory'];

        for ($i = 0; $i < sizeof($listA); $i++) {
            array_push($listA[$i], substr($listA[$i]["contentArticle"], 0, 100)); //Only send 30 caracters
            array_push($listA[$i], 100 - round(strlen($listA[$i][3]) * 100 / strlen($listA[$i][2]))); //Calculate de percentage of article left
        }
        if ($listA == null) {
            header('Location: ./');
        }
    } else {
        header('Location: ./');
    }

    echo $twig->render('category_article.html.twig', array('form' => $form, 'listA' => $listA));
}

function actionFullArticle($twig, $db) {
    $form = array();

    $article = new Article($db);

    $form['article'] = $article->selectById($_GET['idArticle']);
    
    if(!isset($form['article']['idArticle'])) {
        echo $twig->render('errors/error410.html.twig', array());
    }
    
    $form['host'] = parse_url($form['article']['sourceArticle'])['host'];

    echo $twig->render('full_article.html.twig', array('form' => $form));
}

function actionFullArticleToPdf($twig, $db) {
    $form = array();

    $article = new Article($db);
    $user = new User($db);

    $form['article'] = $article->selectByLast();
    $form['subs'] = $user->selectAllSubscribers();

    $html = $twig->render('full_article_pdf.html.twig', array('form' => $form));

    try {
        ob_end_clean();
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('L', 'A3', 'fr');
        $html2pdf->writeHTML($html);
        $html2pdf->output("/var/www/html/symfony4-4017/public/lecoviddechaine/web/pdf/article.pdf", "F");
        // Envoi d'un mail aux abonnés à la newsletter
        for ($i = 0; $i < sizeof($form['subs']); $i++) {
            $to = $form['subs'][$i]['email'];
            $cmd = "echo 'Ci-joint votre article au format PDF' | mail -s 'Votre PDF' -A /var/www/html/symfony4-4017/public/lecoviddechaine/web/pdf/article.pdf $to";
            shell_exec($cmd);
        }
        $html2pdf->output("/var/www/html/symfony4-4017/public/lecoviddechaine/web/pdf/article.pdf");
        // Fin envoi mail
    } catch (Spipu\Html2Pdf\Exception\Html2PdfException $e) {
        echo 'error ' . $e;
    }
}

function actionGetLastNewsCovid($twig, $db) {
    $form = array();
    $newsApiKey = '47133349aaf14a6e8d475bb43698c188';
    $article = new Article($db);
    $lastMonday = date('Y-m-d', strtotime('-1 weeks monday'));
    $lastSunday = date('Y-m-d', strtotime('-1 weeks sunday'));
    
    if(date_diff(date_create(date('Y-m-d', strtotime('-1 weeks monday'))), date_create(date('Y-m-d', strtotime('-1 weeks sunday'))))->invert == 1) {
        $lastSunday = date('Y-m-d');
    }
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://newsapi.org/v2/everything?q=Coronavirus&language=fr&from='.$lastMonday.'&to='.$lastSunday.'&sortBy=popularity&apiKey='.$newsApiKey,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => 'UTF-8',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT']
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);
    
    $form['articles']['nb_depart'] = count($article->select());
    
    $sites_exclus = array(
        "www.fark.com", 
        "en.kremlin.ru"
    );
    
    $articlesAJour = array();
    if($response['status'] === "ok" && $response['totalResults'] > 0) {
        foreach($response['articles'] as $a) {
            if(!in_array(parse_url($a['url'], PHP_URL_HOST), $sites_exclus)) {
                $dateArticle = explode('T', $a['publishedAt'])[0];
                foreach($article->selectArticlesImportes($dateArticle) as $value) {
                    $articlesAJour[] = $value['sourceArticle'];
                }
                if(!in_array($a['url'], $articlesAJour)) {
                    switch(parse_url($a['url'], PHP_URL_HOST)) {
//                        case 'www.lemonde.fr':
//                            $pattern = '#<p class="article__paragraph ">(.+)</p>#';
//                            break;
//                        case 'www.lefigaro.fr':
//                            $pattern = '#<article>(.+)</article>#';
//                            break;
//                        case 'www.journaldugeek.com': 
//                            $pattern = '#<p>(.+)</p>#';
//                            break;
//                        case 'www.presse-citron.net':
//                            $pattern = '#<div id="mvp-post-content"(.+)>(.+)</div>#';
//                            break;
                        default:
                            $pattern = '';
                    }
                    $contentArticle = explode('…', $a['content'])[0];
                    if(!empty($pattern)) {
                        $source = file_get_contents($a['url']);
                        preg_match($pattern, $source, $matches);   
                        $contentArticle = html_entity_decode(htmlentities($matches[0]));
                    }
                    $article->insert($a['title'], $a['description'], $contentArticle, $dateArticle, $a['url'], $a['urlToImage'], 1, 5, 3, $a['author']);
                }
            }
        }
    }
    $form['articles']['nb_fin'] = count($article->select());
    
    echo $twig->render('news_article_json.html.twig', array('form' => $form));
}