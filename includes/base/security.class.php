<?php

class Security {

    // Hold an instance of the class
    //private static $id;

    // A private constructor; prevents direct creation of object
    private function __construct() {

    }

    /*
     * http://php.net/manual/en/book.filter.php
     * http://php.net/manual/en/filter.filters.sanitize.php
     * http://php.net/manual/en/filter.filters.validate.php
     * http://php.net/manual/en/filter.filters.flags.php
     */
    public static function sanitizeText($text){
        $text = filter_var($text,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //$text = filter_var($text,FILTER_SANITIZE_MAGIC_QUOTES);
        return $text;
    }

    public static function createToken($data) {
        $header = '{"typ": "JWT","alg": "HS256"}';
        $body = json_encode($data);
        $string = self::base64url_encode($header).".".self::base64url_encode($body);
        $signature = self::base64url_encode(hash_hmac('SHA256', $string, TOKEN_KEY));
        $token = $string.".".$signature;
        return $token;
    }

    public static function base64url_encode($data) {
      return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function base64url_decode($data) {
      return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    public static function Verify($jwtToken) {
        $token = explode(".", $jwtToken);
        $header = $token[0];
        $body = $token[1];
        $signature = $token[2];
        $test = $header.".".$body;
        $test = self::base64url_encode(hash_hmac('SHA256', $test, TOKEN_KEY));
        if($test==$signature) {
            return json_decode(self::base64url_decode($body));
        }
        //throw new Exception("Invalid token. (Logged)");
        return false;
    }

    public static function sanitizePost(){
        foreach ($_POST as $key => $value) {
            if(is_array($_POST[$key])) {
                foreach($_POST[$key] as $k=>$v) {
                    $_POST[$key][$k] = self::sanitizeText($_POST[$key][$k]);
                }
            } else {
                $_POST[$key] = self::sanitizeText($_POST[$key]);
            }
        }
    }

    /*
     * Database size 255 characters big
     * Use PASSWORD_DEFAULT so will use latest crypto at all times
     */
    public static function hashpassword($password) {
        if(!self::checkPasswordRequirements($password)){
            throw new Exception("Password doesn't meet requirements.");
        }
        return password_hash($password,PASSWORD_DEFAULT);
    }

    /*
     * Checks if password is correct
     * if correct: return True
     * if incorrect: return False
     */
    public static function checkpasswords($typedPassword, $hashedPassword) {
        // Verify stored hash against plain-text password
        return password_verify($typedPassword, $hashedPassword);
    }

    /*
     * Checks if password needs to be updated
     *
     * Check if a newer hashing algorithm is available
     * or the cost has changed
     *
     * Input: Hashed password from database
     */
    public static function checkNeedRehash($hashedPassword) {
        return password_needs_rehash($hashedPassword, PASSWORD_DEFAULT);
    }

    /*
     * Cryptographically secure hash
     * Makes SHA512 hash
     */
    public static function generateRandomHash(){
        $crypto_strong = false;
        $hash = hash("sha512",openssl_random_pseudo_bytes(1024,$crypto_strong));
        if(!$crypto_strong){
            throw new Exception("This system doens't generate cyptogrphicly strong hashes, please update your system.");
        }
        return $hash;
    }

    /*
     * Cryptographically secure randomnumber
     * http://php.net/manual/en/function.random-int.php
     */
    /*
     * Not supported yet: PHP 7
    public static function generateRandomNumber($min = 0, $max = 3000000){
        return random_int($min , $max);
    }*/

        /*
         * Cryptographically secure hash
         * Makes SHA128 hash
         */
        public static function generateRandomToken(){
            $crypto_strong = false;
            $hash = hash("sha256",openssl_random_pseudo_bytes(128,$crypto_strong));
            if(!$crypto_strong){
                throw new Exception("This system doens't generate cyptogrphicly strong hashes, please update your system.");
            }
            return $hash;
        }

    /*
     *
     */
    public static function validateEmail($email){
        if($email==null || $email==""){
            return false;
        }
        return $email==filter_var($email,FILTER_VALIDATE_EMAIL);
    }

    public static function sanitizeEmail($email){
        return strtolower(filter_var($email,FILTER_SANITIZE_EMAIL));
    }

    /*
     * Sanitize and limit URL
     */
    public static function sanitizeURL($url){
        $url = filter_var($url,FILTER_SANITIZE_URL);
        $url = preg_replace("/[^a-zA-Z0-9\/\.\_]/", "", $url);
        return $url;
    }

    public static function sanitizeFilename($filename){
        $filename = filter_var($filename,FILTER_SANITIZE_URL);
        $filename = preg_replace("/[^a-zA-Z0-9\.\_]/", "", $filename);
        return $filename;
    }

    /*
     * Sanitize and limit URL
     */
    public static function sanitizeURLFull($url){
        $url = filter_var($url,FILTER_SANITIZE_URL);
        $url = preg_replace("/[^a-zA-Z0-9\/\.\_\#\:]/", "", $url);
        return $url;
    }


    /*
     * Specifies needed length and character set of password
     *
     * Between Start -> ^
     * And End -> $
     * of the string there has to be at least one number -> (?=.*\d)
     * and at least one letter -> (?=.*[A-Za-z])
     * and it has to be a number, a letter or one of the following: !@#$%-.,+|?{}[]^/()*\~;:<>"'_`&<space>
     * and there have to be 8-40 characters -> {8,40}
     */
    public static function checkPasswordRequirements($password){
        if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%\-\.\,\+\|\?\{\}\[\]\^\/\(\)\*\\\~\;\:\<\>\"\'\_\=\`\&\ ]{8,40}$/', $password)){
            return false;
        }
        return true;
    }

    // Prevent users to clone the instance
    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

}

?>
