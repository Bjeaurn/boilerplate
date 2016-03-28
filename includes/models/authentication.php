<?php
class Authentication extends Model {

    public $userID, $domainID;

    public function __construct($userID = null, $domainID = null, $ip = null, $useragent = null) {
        $this->userID = $userID;
        $this->domainID = $domainID;
        $this->ip = $ip;
        $this->useragent = $useragent;
        $this->iss = "Somnu.com";
        $this->exp = time() + 60 * 60 * 24 * 3;
    }

    public function FillObject($row) {
        die("Not allowed");
        parent::FillObject($row);
    }

    public function getID() {
        return $this->id;
    }

    public function getUserID() {
        return $this->userID;
    }

    public function getDomainID() {
        return $this->domainID;
    }

    public function create() {
        return Security::createToken($this);
    }

    public static function Attempt() {
        $cookies = Cookies::start();
        $session = Session::start();
        $user = null;
        if($session->IsLoggedIn() && !$cookies->Get(__SITE_NAME)) {
            $user = User::FindByID($session->getID());
            $user->token = Security::createToken($user);
        } else {
            $cookie = $cookies->Get(__SITE_NAME);
            $auth = self::Verify($cookie);
            if(!$auth)
                return false;

            if($cookie && !$auth) {
                error_log("Attempting login");
                error_log($cookie);
            }

            $userID = $auth->userID;
            if(!$userID)
                $session->delete(__SITE_NAME);
            $user = User::FindByID($userID);

            if($user) {
                $user->token = $cookie;
                $session->SetID($user->getID());
            } else {
                $session->error('User not found.');
                return false;
            }
        }
        return $user;
    }

    public static function Verify($token) {
        $session = Session::start();
        $result = Security::Verify($token);
        if(!$result) {
            return false;
        }

        if($result->exp<time()) {
            self::logout();
            $session->error("Token expired");
            return false;
        }

        if($result->exp<time()+(60*60)) {
            // REISSUE TOKEN?
        }

        $userID = $result->userID;
        $user = User::FindById($userID);
        if($result->domainID) {
            $user->domainID = $result->domainID;
        }
        if($user)
            return $user;
        return false;
    }

    public static function logout($userID = "") {
        $cookies = Cookies::start();
        $session = Session::start();
        if ($cookies->Get(__SITE_NAME)) {
            $cookies->Delete(__SITE_NAME);
        }
        $session->Logout();
    }
}

?>
