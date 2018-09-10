<?php
session_start();

// Set up the actions, messaging and login status
// -----------------------------------------
$action = isset($_GET['action']) ? $_GET['action'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;
$errorMessage = $message = '';
$validUser = (isset($_SESSION['login']) && $_SESSION['login'] === true);

// Set up the hash tokens
// -----------------------------------------
setToken();


// Get the current Domain / folder
// -----------------------------------------
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
define('DOMAIN', $protocol . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['SCRIPT_NAME'])) .'/');
define('DATA_DIR', '../data/');


/************************************************************
 * SETUP AND LOGIN
 ************************************************************/

// Check for setup
// ---------------------------------------
if($action == 'settings'){
    $pwHash = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $success = file_put_contents(DATA_DIR . 'config.cfg', "<?php
return [
    'user' => '{$_POST["username"]}',
    'pass' => '{$pwHash}',
    'domain' => null,
];");
    if($success){
        $message = 'Settings Configured! You can now log in!';
        require_once ('tpl/login.php');
        exit();
    } else {
        $errorMessage = 'There was an error creating the config file';
        require_once ('tpl/settings.php');
        exit();
    }


} elseif (!file_exists(DATA_DIR . 'config.cfg')) {

    // Try to set up the data directory
    mkdir(DATA_DIR);

    require_once ('tpl/settings.php');
    exit();
}

// Use Config File
// ---------------------------------------
$config = require_once DATA_DIR . 'config.cfg';


// Perform Login
// ---------------------------------------
if($action == 'login'){
    checkToken();
    $validUser = $_POST["username"] == $config['user'] && password_verify($_POST["password"], $config['pass']);
    if(!$validUser) { $errorMessage = "Invalid username or password."; }
    else {
        $_SESSION["login"] = true;
        redirect();
    }
}

// Perform Logout
// ---------------------------------------
if($action == 'logout'){
    checkToken();
    $validUser = $_SESSION["login"] = false;
    redirect();
}


// Show Login Page if not logged in
// ---------------------------------------
if(!$validUser){ require_once ('tpl/login.php'); exit(); }


/************************************************************
 * ROUTING
 ************************************************************/

switch($action){
    case 'create':
        checkToken();
        die(json_encode(createLink($_POST['dest'])));
        break;
    case 'delete':
        checkToken();
        die(json_encode(deleteLink($_POST['id'])));
        break;
    case 'edit':
        checkToken();
        deleteLink($_POST['old']);
        die(json_encode(createLink($_POST['dest'], $_POST['link'])));
        break;
}

require_once ('tpl/app.php');


/************************************************************
 * CRUD FUNCTIONS
 ************************************************************/


/**
 * Delete an exisiting Link File
 * @param $link
 */
function deleteLink($link)
{
    $fileName = str_replace(['..', '/', '.'], '', $link);
    $filePath = "../data/{$fileName}.url";
    if(!file_exists($filePath)){
        $response = ['error' => 'Link file does not exist'];
    } elseif(!unlink($filePath)){
        $response = ['error' => 'Link file could not be deleted.'];
    } else{
        $response = ['data' => ['link' => $link, 'path' => $filePath]];
    }
    return $response;
}

/**
 * Create a new Link file
 * @param $dest
 * @param null $link
 * @return array
 */
function createLink($dest, $link = null)
{
    // Generate a link if we need one
    // ---------------------------------------
    if($link == null){
        $link = generateSlug();
        // Make sure the file doesn't already exist
        while(file_exists("../data/{$link}.url")){
            $link = generateSlug();
        }
    }

    // Create the file
    // ---------------------------------------
    if(file_put_contents("../data/{$link}.url", $dest)){
        $response = ['data' => ['link' => $link, 'dest' => $dest, 'url'=>DOMAIN.$link]];
    } else {
        $response = ['error' => 'Link file could not be created.'];
    }
    return $response;
}

/************************************************************
 * HELPER FUNCTIONS
 ************************************************************/

/**
 * Set up the Token
 * @throws Exception
 */
function setToken()
{
    if (empty($_SESSION['token'])) {
        if (function_exists('random_bytes')) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        } elseif (function_exists('mcrypt_create_iv')) {
            $_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        } else {
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
        }
    }
    define('TOKEN', $_SESSION['token']);
}

/**
 * Check that the token is valid
 */
function checkToken()
{
    if (empty($_POST['_token']) || !hash_equals(TOKEN, $_POST['_token'])) {
        die(json_encode([
            'error' => 'Invalid CSRF Token'
        ]));
    }
}

/**
 * Redirect people an internal page
 * @param string $url
 */
function redirect($url = ''){
    header('Location: ' . DOMAIN . 'admin/' . $url);
}

/**
 * Generate a random slug
 * @param int $length
 * @return bool|string
 */
function generateSlug($length = 7){
    return substr( str_shuffle( str_repeat( 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 10 ) ), 0, $length );
}