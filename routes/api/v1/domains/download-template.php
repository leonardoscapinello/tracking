<?php
$limit = 64;
//header download
header("Content-Disposition: attachment; filename=" . $domains->getVerificationKey() . ".html");
header("Content-Type: application/force-download");
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header("Content-Type: text/plain");
echo "<pre>" . PHP_EOL;
echo substr("DOMAIN: " . $domains->getDomain() . " -------------------------------------------------------------------------------", 0, $limit) . PHP_EOL;
echo substr("BEGIN JAMES --------------------------------------------------------------------", 0, $limit) . PHP_EOL;
echo substr($domains->getVerificationKey() . $text->random($limit)->output(), 0, $limit) . PHP_EOL;
for ($i = 0; $i < 31; $i++) {
    echo $text->random($limit)->output() . " " . PHP_EOL;
}
echo substr("END JAMES --------------------------------------------------------------------", 0, $limit) . PHP_EOL;
echo "</pre>";
?>

