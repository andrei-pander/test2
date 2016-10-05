<?

include_once 'db.php';
include_once 'logger.php';
include_once 'simple_html_dom.php';

function cTriml($dirtC, $str) {
    $pos = strpos($str, $dirtC);
    $str = substr($str, 0, $pos);
    return trim($str);
}

function cTrimr($dirtC, $str) {
    $pos = strlen($dirtC);
    $str = substr($str, $pos);
    return trim($str);
}

$html = file_get_html('https://yandex.ru/pogoda/moscow/details');
$daysArr = array();
foreach($html->find('dl.forecast-detailed dt') as $key => $el) {
    $daysArr[$key]['weekday'] = $el->children(0)->innertext;
    $daysArr[$key]['day'] = cTriml('<', $el->children(1)->innertext);
    $daysArr[$key]['month'] = $el->children(1)->children(0)->innertext;
}

$pref = '';
foreach($html->find('dl.forecast-detailed dd') as $key => $el) {
    $elArr = array();
    // цикл по времени суток
    foreach ($el->find('table .weather-table__body tr') as $td => $tr) {
        switch ($td) {
            case 0: $pref = 'morn_'; break;
            case 1: $pref = 'noon_'; break;
            case 2: $pref = 'eve_'; break;
            case 3: $pref = 'night_';
        }
        $tmpVar = '';
        $elArr[$pref.'temp'] = current($tr->find('.weather-table__body-cell_type_daypart .weather-table__temp'))->innertext;
        $tmpVar = '';
        $tmpVar = current($tr->find('.weather-table__body-cell_type_icon .icon'))->getAttribute('class');
        $tmpVar = explode(' ', $tmpVar);
        $tmpVar = array_pop($tmpVar);
        $tmpVar = cTrimr('icon_thumb_', $tmpVar);
        $tmpVar = str_replace('-', '_', $tmpVar). '.svg';
        $elArr[$pref.'weath_pic'] = $tmpVar;
        $elArr[$pref.'weath'] = current($tr->find('.weather-table__body-cell_type_condition .weather-table__value'))->innertext;
        $elArr[$pref.'press'] = current($tr->find('.weather-table__body-cell_type_air-pressure .weather-table__value'))->innertext;
        $elArr[$pref.'hum'] = current($tr->find('.weather-table__body-cell_type_humidity .weather-table__value'))->innertext;
        if (current($tr->find('.weather-table__body-cell_type_wind .wind-speed')) !== false) {
            $elArr[$pref.'wind_str'] = current($tr->find('.weather-table__body-cell_type_wind .wind-speed'))->innertext;
            $elArr[$pref.'wind_dir'] = current($tr->find('.weather-table__body-cell_type_wind .icon-abbr'))->innertext;
        } else {
            $elArr[$pref.'wind_str'] = null;
            $elArr[$pref.'wind_dir'] = null;
        }
    }

    $elArr['sunrise'] = current($el->find('.forecast-detailed__sunrise .forecast-detailed__value'))->innertext;
    $elArr['sunset'] = current($el->find('.forecast-detailed__sunset .forecast-detailed__value'))->innertext;
    $tmpVar = '';
    $tmpVar = current($el->find('.forecast-detailed__moon .icon'))->getAttribute('class');
    $tmpVar = $tmpVar = explode(' ', $tmpVar);
    $tmpVar = array_pop($tmpVar);
    $tmpVar = cTrimr('icon_moon_', $tmpVar);
    $elArr['moon_phase_pic'] = $tmpVar.'.svg';
    if (current($el->find('.forecast-detailed__geomagnetic-field_simple .forecast-detailed__value')) !== false) {
        $elArr['magnet'] = current($el->find('.forecast-detailed__geomagnetic-field_simple .forecast-detailed__value'))->innertext;
    } else {
        $elArr['magnet'] = null;
    }
    $daysArr[$key] = array_merge($daysArr[$key], $elArr);
}


print_r($daysArr);
//$db = new Database("mysql:dbname=parse;host=127.0.0.1", "user", "pass");
//echo '1';