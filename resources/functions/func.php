<?php

function createRandomAGTNO()
{
    do {
        $agt_no = mt_rand(100000000, 900000000);
        $valid = true;
        if (preg_match('/(\d)\1\1/', $agt_no)) {
            $valid = false;
        } // Same digit three times consecutively
        elseif (preg_match('/(\d).*?\1.*?\1.*?\1/', $agt_no)) {
            $valid = false;
        } // Same digit four times in string
    } while ($valid === false);

    return $agt_no;
}

function getOutput($file)
{
    ob_start();
    include $file;
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}

function mb_str_replace($search, $replace, $string)
{
    $charset = mb_detect_encoding($string);
    $unicodeString = iconv($charset, 'UTF-8', $string);

    return str_replace($search, $replace, $unicodeString);
}

function my_mb_ucfirst($str)
{
    $fc = mb_strtoupper(mb_substr($str, 0, 1));

    return $fc.mb_substr($str, 1);
}

function mb_ucfirst2($string, $encoding)
{
    $firstChar = mb_substr($string, 0, 1, $encoding);
    $then = mb_substr($string, 1, null, $encoding);

    return mb_strtoupper($firstChar, $encoding).$then;
}
function sanitize($filename)
{
    // remove HTML tags
    $filename = strip_tags($filename);
    // remove non-breaking spaces
    $filename = preg_replace("#\x{00a0}#siu", ' ', $filename);
    // remove illegal file system characters
    $filename = str_replace(array_map('chr', range(0, 31)), '', $filename);
    // remove dangerous characters for file names
    $chars = ['?', '[', ']', '/', '\\', '=', '<', '>', ':', ';', ',', "'", '"', '&', '’', '%20',
        '+', '$', '#', '*', '(', ')', '|', '~', '`', '!', '{', '}', '%', '+', '^', chr(0)];
    $filename = str_replace($chars, '_', $filename);
    // remove break/tabs/return carriage
    $filename = preg_replace('/[\r\n\t -]+/', '_', $filename);
    // convert some special letters
    $convert = ['Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss',
        'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u'];
    $filename = strtr($filename, $convert);
    // remove foreign accents by converting to HTML entities, and then remove the code
    $filename = html_entity_decode($filename, ENT_QUOTES, 'utf-8');
    $filename = htmlentities($filename, ENT_QUOTES, 'utf-8');
    $filename = preg_replace('/(&)([a-z])([a-z]+;)/i', '$2', $filename);
    // clean up, and remove repetitions
    $filename = preg_replace('/_+/', '_', $filename);
    $filename = preg_replace(['/ +/', '/-+/'], '_', $filename);
    $filename = preg_replace(['/-*\.-*/', '/\.{2,}/'], '.', $filename);
    // cut to 255 characters
    // $filename = substr($data, 0, 255);
    // remove bad characters at start and end
    $filename = trim($filename, '.-_');

    return $filename;
}

/**
 * глубина вложенности массива
 * dimension of array.
 */
function array_depth(array $array)
{
    $max_depth = 1;

    foreach ($array as $value) {
        if (is_array($value)) {
            $depth = array_depth($value) + 1;

            if ($depth > $max_depth) {
                $max_depth = $depth;
            }
        }
    }

    return $max_depth;
}

function menu()
{
    /*
        // rout list from lavarel
        if (!empty($data['page_list'])) {
            $res = array_column($data['page_list'], 'page_alias', 'page_h1'); // get pages array: 'page_h1' => 'page_alias'
        } else {
            $res = [];
        }
        // url path from rout and controller
        if (!empty($data['nav'])) {
            if (is_array($data['nav'])) {
                foreach ($data['nav'] as $value) {
                    $ress[$value] = array_search($value, $res); // get array 'nav = page_alias' => 'page_h1'
                }
            }
        }
        // set empty value for main pages 'home' and 'admin'
        if (!empty($data['page_db_data'][0])) {
            $ress[$data['page_db_data'][0]['page_alias']] = $data['page_db_data'][0]['page_h1'];
            if ($data['page_db_data'][0]['page_alias'] == 'home' or $data['page_db_data'][0]['page_alias'] == 'adm') {
                $nav = '';
            } else {
                $nav = '<a href="'.url('/').'/adm">Главная</a>';
            }
        }
        // get full path for links
        if (!empty($ress)) {
            $prevk = '';
            foreach ($ress as $key => $value) {
                if (empty($value)) {
                    $value = (!empty($data['name'])) ? $data['name'] : $key;
                }
                if (!empty($prevk)) {
                    $nav .= ' / <a href="'.url('/').$prevk.'/'.$key.'/">'.$value.'</a>';
                    $prevk .= DIRECTORY_SEPARATOR.$key;
                } else {
                    if (empty($nav)) {
                        $nav = '<a href="'.url('/').'/'.$key.'/">'.$value.'</a>';
                    } else {
                        $nav .= ' / <a href="'.url('/').'/'.$key.'/">'.$value.'</a>';
                    }
                    $prevk .= DIRECTORY_SEPARATOR.$key;
                }
            }
        }
        return (isset($nav)) ? $nav : '';
        */
}

function imageFor($path_after_public_path_with_basename): string
{
    if (file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$path_after_public_path_with_basename))) {
        return $path_after_public_path_with_basename;
    } else {
        return 'images'.DIRECTORY_SEPARATOR.'ddd.jpg';
    }
}

function delete_file(string $path2file): string
{
    $mes = '';
    if (is_string($path2file)) {
        // $path2file = realpath($path2file);
        if (file_exists($path2file)) {
            if (is_writable($path2file)) {
                if (unlink($path2file)) {
                    $mes .= 'true';

                    return $mes;
                } else {
                    $mes .= 'ERROR! Not unlink "'.$path2file.'".';

                    return $mes;
                }
            } else {
                $mes .= 'ERROR! File "'.$path2file.'" is not writable.';

                return $mes;
            }
        } else {
            $mes .= 'WARNING! File "'.$path2file.'" is not exists.';

            return $mes;
        }
    } else {
        $mes .= 'ERROR! Input for delete_file(string $path2file) must be sring.';

        return $mes;
    }
}

/**
 * replaces all Cyrillic letters with Latin.
 *
 * @return string
 */
function translit_ostslav_to_lat($textcyr)
{
    $cyr = ['Ц', 'ц', 'а', 'б', 'в', 'ў', 'г', 'ґ', 'д', 'е', 'є', 'ё', 'ж', 'з', 'и', 'ï', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', 'А', 'Б', 'В', 'Ў', 'Г', 'Ґ', 'Д', 'Е', 'Є', 'Ё', 'Ж', 'З', 'И', 'Ї', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
    ];
    $lat = ['C', 'c', 'a', 'b', 'v', 'w', 'g', 'g', 'd', 'e', 'ye', 'io', 'zh', 'z', 'i', 'yi', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'i', 'y', 'e', 'yu', 'ya', 'A', 'B', 'V', 'W', 'G', 'G', 'D', 'E', 'Ye', 'Io', 'Zh', 'Z', 'I', 'Yi', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'I', 'Y', 'e', 'Yu', 'Ya',
    ];
    $textlat = str_replace($cyr, $lat, $textcyr);

    return $textlat;
}
/**
 * replaces all letters with Latin ASCII.
 *
 * @return string
 */
function translit_to_lat($text)
{
    $res = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', transliterator_transliterate('Any-Latin; Latin-ASCII', $text));

    return $res;
}

/**
 * @param string $file       - - path to txt file
 * @param string $new_string
 * @param int    $num_string - number of string for replace
 */
function replace_string($file, $new_string, int $num_string = 0)
{
    $array = file($file);
    if ($array) {
        $array[$num_string] = $new_string."\n";
    }
    if (!is_writable($file)) {
        return false;
    }
    if (file_put_contents($file, $array, LOCK_EX) === false) {
        return false;
    } else {
        return true;
    }
}

function get_n_lines_from_txt_file($path_to_file, $number_of_lines = 1000)
{
    $lines = [];
    $fp = fopen($path_to_file, 'r');
    while (!feof($fp)) {
        $line = fgets($fp);
        array_push($lines, $line);
        if (count($lines) > $number_of_lines) {
            array_shift($lines);
        }
    }
    fclose($fp);

    return $lines;
}
/**
 * @param string $path - dir for scan
 * @param string $ext  - extension of files eg 'png' or 'png, webp, jpg'
 *
 * @return array path to files
 */
function files_in_dir($path, $ext = '')
{
    $files = [];
    if (file_exists($path)) {
        $f = scandir($path);
        foreach ($f as $file) {
            if (is_dir($file)) {
                continue;
            }
            if (empty($ext)) {
                $files[] = $file;
            } else {
                $arr = explode(',', $ext);
                foreach ($arr as $value) {
                    $extt = mb_strtolower(trim($value));
                    if ($extt === mb_strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
                        $files[] = $file;
                    }
                }
            }
        }
    }

    return $files;
}
/**
 * @param string $dir - dir for scan
 * @param string $ext - extension of files eg 'png' or 'png, webp, jpg'
 *
 * @return array basename  of files
 */
function filesindir($dir, $ext = '')
{
    $files = [];
    if (file_exists($dir) && is_dir($dir) && is_readable($dir)) {
        foreach (new DirectoryIterator($dir) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }
            if (empty($ext)) {
                // $files[] = $fileInfo->getBasename();
                $files[] = $fileInfo->getPathname();
            } else {
                $arr = explode(',', $ext);
                foreach ($arr as $value) {
                    $extt = mb_strtolower(ltrim(trim($value), '.'));
                    if ($extt === $fileInfo->getExtension()) {
                        // $files[] = $fileInfo->getBasename();
                        $files[] = $fileInfo->getPathname();
                    }
                }
            }
        }
    }

    return $files;
}

/**
 * function for url validation.
 *
 * @param string $url
 *
 * @return bool
 */
function getResponseCode($url)
{
    $header = '';
    $options = [
        CURLOPT_URL => trim($url),
        CURLOPT_HEADER => false,
        CURLOPT_RETURNTRANSFER => true,
    ];

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    curl_exec($ch);
    if (!curl_errno($ch)) {
        $header = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    }
    curl_close($ch);

    if ($header > 0 && $header < 400) {
        return true;
    } else {
        return false;
    }
}

// возвращает true, если домен доступен, false если нет
function isDomainAvailible($domain)
{
    // проверка на валидность урла
    if (!filter_var($domain, FILTER_VALIDATE_URL)) {
        return false;
    }
    // инициализация curl
    $curlInit = curl_init($domain);
    curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curlInit, CURLOPT_HEADER, true);
    curl_setopt($curlInit, CURLOPT_NOBODY, true);
    curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
    // получение ответа
    $response = curl_exec($curlInit);
    curl_close($curlInit);
    if ($response) {
        return true;
    }

    return false;
}

/**
 * $url = 'http://ya.ru/';
 * $answer = check_http_status($url);
 * echo 'Код статуса HTTP: '.$answer.'. Ответ на запрос URL: '.$url;.
 */
function check_http_status($url)
{
    $user_agent = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0)';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $page = curl_exec($ch);

    $err = curl_error($ch);
    if (!empty($err)) {
        return $err;
    }

    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $httpcode;
}
function test_input($data)
{
    // obrezka do 300 znakov na vsak slu4aj
    $data = mb_substr($data, 0, 300);
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

function phone_number_to_db($sPhone)
{
    $sPhone = preg_replace('![^0-9]+!', '', $sPhone);

    return $sPhone;
}

function phone_number_view($sPhone)
{
    $sPhone = preg_replace('![^0-9]+!', '', $sPhone);
    // if(strlen($sPhone) != 11) return(False);
    if (strlen($sPhone) > 10 && strlen($sPhone) < 12) {
        $sArea = mb_substr($sPhone, 0, 1);
        $sPrefix = mb_substr($sPhone, 1, 3);
        $sNumber1 = mb_substr($sPhone, 4, 3);
        $sNumber2 = mb_substr($sPhone, 7, 2);
        $sNumber3 = mb_substr($sPhone, 9, 2);
        $sPhone = '+'.$sArea.' ('.$sPrefix.') '.$sNumber1.' '.$sNumber2.' '.$sNumber3;

        return $sPhone;
    } else {
        return $sPhone;
    }
}

/**
 * find file in dir by only filename without extension.
 *
 * @param string $path     to dir
 * @param string $filename only filename without extension
 *
 * @return string or false
 */
function find_by_filename($path, $filename)
{
    if (is_readable($path)) {
        $files = scandir($path);
        if (!empty($files)) {
            foreach ($files as $k => $v) {
                $fname = pathinfo($v, PATHINFO_FILENAME);
                $only_name[$k] = $fname;
            }
            $name_key_name = array_search($filename, $only_name);
            if (!empty($name_key_name)) {
                // return $path.DS.$files[$name_key_name];
                return $files[$name_key_name];
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function dir_is_empty($dir)
{
    $handle = opendir($dir);
    while (false !== ($entry = readdir($handle))) {
        if ($entry != '.' && $entry != '..') {
            closedir($handle);

            return false;
        }
    }
    closedir($handle);

    return true;
}

function del_empty_dir($dir)
{
    if (file_exists($dir) && is_dir($dir) && [] === array_diff(scandir($dir), ['.', '..'])) {
        if (dir_is_empty($dir)) {
            if (rmdir($dir)) {
                return true;
            } else {
                return false;
            }
        }
    }
}

function human_filesize($bytes, $decimals = 2)
{
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);

    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)).@$sz[$factor];
}

function en_dayweek_to_rus($dayweek)
{
    $cyr = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
    $lat = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    $dayrus = str_replace($lat, $cyr, $dayweek);

    return $dayrus;
}

function en_month_to_rus($month)
{
    $lat = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'sept', 'oct', 'nov', 'dec'];
    $cyr = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь',  'янв', 'фев', 'мар', 'апр', 'май', 'июн', 'июл', 'авг', 'сен', 'сент', 'окт', 'ноя', 'дек'];
    $ru_month = str_replace($lat, $cyr, $month);

    return $ru_month;
}

function my_mb_lcfirst($str)
{
    $fc = mb_strtolower(mb_substr($str, 0, 1));

    return $fc.mb_substr($str, 1);
}

function searchLine($filename, $string)
{
    $line = false;
    $fh = fopen($filename, 'rb');
    for ($i = 1; ($t = fgets($fh)) !== false; ++$i) {
        if (strpos($t, $string) !== false) {
            $line = $i;
            break;
        }
    }
    fclose($fh);

    return $line;
}
function in_array_recursive($needle, array $haystack, $strict = false)
{
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_recursive($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}
