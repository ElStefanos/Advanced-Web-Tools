<?php

namespace session;

use ErrorException;
use notifications\notifications;
class sessionHandler
{
    private $time;
    private $keyConfig;
    private $key;

    public function sessionHandler() : bool
    {
        $this->time = time();

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_set_cookie_params(["SameSite" => "Strict"]);
            session_set_cookie_params(["Secure" => "true"]); 
            session_set_cookie_params(["HttpOnly" => "true"]);
            session_start();
        }


        if (!isset($_SESSION['sessionInfo']['started'])) $_SESSION['sessionInfo']['started'] = $this->time;
        if (!isset($_SESSION['cache']['started'])) $_SESSION['cache']['started'] = $this->time;

        if (isset($_SESSION['sessionInfo']['expires'])) {
            if ($_SESSION['sessionInfo']['expires'] < $this->time) {
                $this->sessionClearing();
            } else {
                $_SESSION['sessionInfo']['expires'] += 900;
            }
        }

        if (isset($_SESSION['sessionInfo']['regenerate_id'])) {
            if ($this->time - $_SESSION['sessionInfo']['regenerate_id'] > 10) {
                $_SESSION['sessionInfo']['regenerate_id'] = $this->time;

                try {
                    session_regenerate_id(true);
                } catch (ErrorException $e) {
                    $notificiations = new notifications("Session Manager", $e, "notice");
                    $notificiations->pushNotification();
                }

   
            }
        } else {
            $_SESSION['sessionInfo']['regenerate_id'] = $this->time;
        }

        //EXPERIMENTAL NEEDS FURTHER TESTING 
        //if ($this->authKey() == false) $this->sessionClearing();

        if (isset($_SESSION['sessionInfo']['bind_to_ip'])) {


            if ($_SESSION['sessionInfo']['bind_to_ip'] === true) {

                if (!isset($_COOKIE['LAST_IP'])) setcookie('LAST_IP', $_SERVER['REMOTE_ADDR'], time() + 900, '/');

                if (!isset($_SESSION['sessionInfo']['ip_address'])) {

                    $_SESSION['sessionInfo']['ip_address'] = $_SERVER['REMOTE_ADDR'];
                } else {

                    if ($_SESSION['sessionInfo']['ip_address'] !== $_SERVER['REMOTE_ADDR']) {

                        if ($_SESSION['sessionInfo']['ip_address'] === $_COOKIE['LAST_IP']) {

                            setcookie('LAST_IP', $_SERVER['REMOTE_ADDR'], time() + 900);
                            $_SESSION['sessionInfo']['ip_address'] = $_SERVER['REMOTE_ADDR'];

                            return true;
                        }

                        $this->sessionClearing();

                        return false;
                    }
                }
            }
        }

        return true;
    }

    public function sessionRemover($name)
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }

    //EXPERIMENTAL NEEDS FURTHER TESTING
    // public function setKey()
    // {
    //     $this->keyConfig = array(
    //         "digest_alg" => "sha512",
    //         "private_key_bits" => 2048,
    //         "private_key_type" => OPENSSL_KEYTYPE_RSA,
    //     );

    //     if (php_uname('s') == 'Windows NT') {
    //         $this->keyConfig = array(
        //THIS IS HARDCODED FOR TESTING ONLY
    //             "config" => "C:/xampp/php/extras/openssl/openssl.cnf",
    //             "digest_alg" => "sha512",
    //             "private_key_bits" => 2048,
    //             "private_key_type" => OPENSSL_KEYTYPE_RSA,
    //         );
    //     }


    //     $private_key = openssl_pkey_new($this->keyConfig);
    //     $this->key = $public_key_pem = openssl_pkey_get_details($private_key)['key'];

    //     $public_key = openssl_pkey_get_public($public_key_pem);

    //     setcookie('UID', $this->key, time() + 5, '/');
    //     $_COOKIE['UID'] = $this->key;
    // }

    // public function authKey()
    // {
    //     $cipher = "AES-256-CTR";

    //     if (!isset($_COOKIE['UID']) || !isset($_COOKIE['UIDV'])) {

    //         $ivlen = openssl_cipher_iv_length($cipher);
    //         $iv = openssl_random_pseudo_bytes($ivlen);

    //         setcookie('UIDV', $iv, time() + 5, '/');

    //         $this->setKey();

    //         $_SESSION['sessionInfo']['UID'] = openssl_encrypt('auth', $cipher, $this->key, $options = 0, $iv);

    //         return true;
    //     }

    //     $iv  = " ";

    //     if (isset($_COOKIE['UIDV'])) $iv = $_COOKIE['UIDV'];

    //     if (!isset($_SESSION['sessionInfo']['UID'])) {
    //         $_SESSION['sessionInfo']['UID'] = openssl_encrypt('auth', $cipher, $this->key, $options = 0, $iv);
    //         return true;
    //     }

    //     if (openssl_decrypt($_SESSION['sessionInfo']['UID'], $cipher, $_COOKIE['UID'], $options = 0, $iv) == 'auth') {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    protected function sessionClearing() : void
    {

        foreach ($_SESSION as $key) {
            $_SESSION = [];
        }

        setcookie('PHPSESSID', 'session hijacking detected', time() + 10, '/');
        unset($_COOKIE['UID']);
        unset($_COOKIE['UIDV']);
        unset($_COOKIE['LAST_IP']);
        setcookie('UID', null, -1, '/');
        setcookie('UIDV', null, -1, '/');
        setcookie('LAST_IP', null, -1, '/');
        session_destroy();
        
    }

    public function sessionDumper() : void
    {
        var_dump($_SESSION);
    }
}
