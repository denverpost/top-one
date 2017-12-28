<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Denver Post Broncos Top Story Tracker</title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" href="//extras.denverpost.com/transgender/css/normalize.css" />
    <link rel="stylesheet" href="//extras.denverpost.com/transgender/css/foundation.min.css" />
    <link href='http://fonts.googleapis.com/css?family=Noticia+Text:400,700,400italic,700italic|PT+Sans:400,700,400italic,700italic|PT+Sans+Narrow:400,700' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="http://extras.mnginteractive.com/live/media/favIcon/dpo/favicon.ico" type="image/x-icon" />
</head>
<body style="margin:0;">

    <section id="main" style="margin:0;">
    <div style="background:#e1e1e1;border-bottom:10px solid #b1b1b1;padding:2em 0;margin:0 0 2em;text-align:center;">
        <h1>Denver Post Broncos Top Story Tracker</h1>
    </div>
    <div class="row">
        <div class="large-10 large-centered columns">
            <table style="width:100%;">
                <tr>
                    <th style="font-size:120%;">Top Broncos story</th>
                    <th style="font-size:120%;width:30%;">Time first seen</th>
                </tr>

<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('./simple_html_dom.php');
date_default_timezone_set('America/Denver');

$now = date("F j, Y, g:i a");
$html = file_get_html('http://www.denverpost.com/sports/denver-broncos/');

foreach( $html->find('article.feature-large-top a.article-title') as $toplink ) {
	$line[0] = $toplink->href;
	$line[1] = $toplink->title;
	$line[2] = $now;
}

$newfind = true;
$output = '';

if (($handlein = fopen("top-broncos.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handlein, 1000, ",")) !== FALSE) {
		$output = '<tr><td><a style="font-size:140%;" href="'.$data[0].'">'.$data[1].'</a></td><td style="font-size:120%;">'.$data[2].'</td></tr>' . $output;
		if ($data[0] == $line[0]) {
			$newfind = false;
		}
	}
    fclose($handlein);
}

if ($newfind) {
    $output = '<tr><td><a style="font-size:140%;" href="'.$line[0].'">'.$line[1].'</a></td><td style="font-size:120%;">'.$line[2].'</td></tr>' . $output;
	$handleout = fopen("top-broncos.csv", "a");
	fputcsv($handleout, $line, ',', '"');
	fclose($handleout);
}

echo $output;

?>
            </table>
        </div>
    </div>
</section>
</body>
</html>