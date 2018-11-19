<?php
/*
Plugin Name: Reisishot Login Security
Version: 1.0
Description: Improve login security using Google captcha
Plugin URI: https://reisishot.pictures/
Author: Florian Reisinger
Author URI: https://github.com/reisi007
*/

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

global $prefixeTable;
add_event_handler('loc_begin_page_header', 'reisishot_login_js', 21);

function reisishot_login_js()
{
    global $template, $conf;
    if (!(defined('IN_ADMIN') and IN_ADMIN)) {
        $reisishot_recaptcha = '<script src="https://www.google.com/recaptcha/api.js?render=explizit"></script>';
        $reisishot_js = file_get_contents("recaptcha.min.js", FILE_USE_INCLUDE_PATH);

        $template->append('head_elements', $reisishot_recaptcha);
        $template->append('head_elements', '<script>var pk=' . $conf['recaptcha_public'] . ';' . $reisishot_js . '</script>');
    }
}

function reisishot_password_verify($password, $hash, $user_id = null)
{
    global $conf;
    if (!pwg_password_verify($password, $hash, $user_id))
        return false;
    if (!(isset($conf['recaptcha_secret']) and isset($conf['recaptcha_public'])))
        return true;

    $toVerify = $_POST['g-recaptcha-response'];
    $secret = $conf['recaptcha_secret'];

    $result = json_decode(reisishot_post_request('https://www.google.com/recaptcha/api/siteverify', array(
        'secret' => $secret,
        'response' => $toVerify,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    )));
    return $result->success;
}

function reisishot_post_request($url, array $params)
{
    $query_content = http_build_query($params);
    $fp = fopen($url, 'r', FALSE, // do not use_include_path
        stream_context_create([
            'http' => [
                'header' => [ // header array does not need '\r\n'
                    'Content-type: application/x-www-form-urlencoded',
                    'Content-Length: ' . strlen($query_content)
                ],
                'method' => 'POST',
                'content' => $query_content
            ]
        ]));
    if ($fp === FALSE) {
        fclose($fp);
        return json_encode(['error' => 'Failed to get contents...']);
    }
    $result = stream_get_contents($fp); // no maxlength/offset
    fclose($fp);
    return $result;
}


?>