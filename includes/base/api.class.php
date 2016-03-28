<?php
class API {

    protected $data;
    protected $responsecode = 200;

    public function __construct($data, $responsecode = 200) {
        $this->data = $data;
        $this->responsecode = $responsecode;
        echo $this;
    }

    public function __toString() {
        http_response_code($this->responsecode);
        if($this->data) {
            return json_encode($this->data);
        } else {
            return json_encode(new Exception("No data found"));
        }

    }

    public static function error($strError, $responsecode = 404) {
        if(gettype($strError) == "string") {
            $error = new Stdclass;
            $error->error = $strError;
            $error->responsecode = $responsecode;
        } else {
            $error = $strError;
        }
        $api = new API($error, $responsecode);
        //return $api;
    }

    public static function GetInput() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        return $request;
    }

}
?>
