<?

include_once 'parser.php';
include_once 'db.php';
include_once 'logger.php';

$parser = new Parser('https://yandex.ru/pogoda/moscow/details');
$doc = $parser->getDoc();
echo '<pre>';

$days = $doc->getElementsByTagName('dt');
foreach ($days as $day) {
//    var_dump($day->firstChild->nodeValue);
//    var_dump(each($day->childNodes));
//    foreach ($day->childNodes as $childNode) {
//        echo $childNode->nodeValue. PHP_EOL;
//    }
}

$db = new Database("mysql:dbname=parse;host=127.0.0.1", "user", "pass");
echo '1';