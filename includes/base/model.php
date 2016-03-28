<?php
class Model {

    public function toJson() {

    }

    public function FillObject($row) {
        $prefix = get_class($this);
        $vars = get_class_vars($prefix);
        $check = array();
        foreach($vars as $k=>$v) {
            array_push($check, $k);
        }

        try {
            if($row) {
                foreach ($row as $k=>$v) {
                    if(substr($k, 0, strlen($prefix)) == $prefix)
                        $k = lcfirst(substr($k, strlen($prefix)));
                    if(strlen($k)<3) $k = strtolower($k);
                    if(in_array($k, $check)) {
                        $this->$k = $v;
                    }
                }
            }
        } catch(Exception $e) {
            error_log("Exception occured during FillObject()\n\r");
            error_log($e);
        }
    }

    public function getID() {
        return $this->id;
    }
}
?>
