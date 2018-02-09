<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('./simple_html_dom.php');
date_default_timezone_set('America/Denver');


function getTopOnes($args) {
    $now = date("F j, Y, g:i a");
    $html = file_get_html($args[0]);

    foreach( $html->find('article.feature-large-top a.article-title') as $toplink ) {
        $line[0] = $toplink->href;
        $line[1] = $toplink->title;
        $line[2] = $now;
    }

    $newfind = true;
    $output = '';

    if (($handlein = fopen($args[1], "r")) !== FALSE) {
        while (($data = fgetcsv($handlein, 1000, ",")) !== FALSE) {
            $output = '<div class="listing"><a href="'.$data[0].'">'.$data[1].'</a><span>Seen: '.$data[2].'</span></div>' . $output;
            if ($data[0] == $line[0]) {
                $newfind = false;
            }
        }
        fclose($handlein);
    }

    if ($newfind) {
        $output = '<div class="listing"><a href="'.$line[0].'">'.$line[1].'</a><span>Seen: '.$line[2].'</span></div>' . $output;
        $handleout = fopen($args[1], "a");
        fputcsv($handleout, $line, ',', '"');
        fclose($handleout);
    }
    return $output;
}

$news = array('http://www.denverpost.com/','top-ones.csv');
$sports = array('http://www.denverpost.com/sports/','top-sports.csv');
$broncos = array('http://www.denverpost.com/sports/denver-broncos/','top-broncos.csv');

?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="900">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Denver Post Homepage Top Story Tracker</title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" href="//extras.denverpost.com/transgender/css/normalize.css" />
    <link rel="stylesheet" href="//extras.denverpost.com/transgender/css/foundation.min.css" />
    <link href='http://fonts.googleapis.com/css?family=Noticia+Text:400,700,400italic,700italic|PT+Sans:400,700,400italic,700italic|PT+Sans+Narrow:400,700' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="https://plus.denverpost.com/favicon.ico" type="image/x-icon" />
    <style type="text/css">
        div.listing {
            width:100%;
            margin-bottom:.75em;
            padding:.5em;
            line-height:1.15;
        }
        div.listing {
            background-color:#f4f4f4;
        }
        div.listing:last-child {
            margin-bottom:0;
        }
        div.listing:nth-of-type(2n) {
            background-color:#ffffff;
        }
        div.listing a {
            font-size:120%;
            color:#2b76ab;
        }
        div.listing span {
            padding-top:.25em;
            color: #818181;
            display:block;
            clear:both;
            font-style:italic;
        }
        div.listings {
            border:2px solid #d9d9d9;
        }
        h1 {
            font-size:2em;
        }        
        h2 {
            font-size:1.75em;
            display:block;
        }
        h2 a {
            color:#222;
        }
        #main {
            padding-bottom:2em;
        }
    </style>
</head>
<body style="margin:0;">

    <section id="main" style="margin:0;">
    <div style="background:#efefef;border-bottom:6px solid #d9d9d9;padding:1em 0;margin:0 0 1em;text-align:center;">
        <h1>Denver Post Top Stories Tracker</h1>
    </div>
    <div class="row">
        <div class="large-4 columns">
            <h2><a href="./news.php">Homepage</a></h2>
            <div class="listings">
                <?php echo getTopOnes($news); ?>
            </div>
        </div>
        <div class="large-4 columns">
            <h2><a href="./news.php">Sports</a></h2>
            <div class="listings">
                <?php echo getTopOnes($sports); ?>
            </div>
        </div>
        <div class="large-4 columns">
            <h2><a href="./news.php">Broncos</a></h2>
            <div class="listings">
                <?php echo getTopOnes($broncos); ?>
            </div>
        </div>
    </div>
</section>
</body>
</html>