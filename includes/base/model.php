<?php
abstract class Model {

    public function toJson() {

    }

    public function fill($row) {
        $prefix = get_called_class();
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

    public static function findById($id) {
        $prefix = get_called_class();
        $table = ucfirst($prefix."s");
        try {
            $db = DatabasePDO::start();
            $result = $db->prepare("SELECT * FROM ".$table." WHERE ". ucfirst($prefix)."ID = :id");
            $result->bindParam(":id", $id);
            if(!@$result->execute()) {
                throw new Exception("There's something wrong with the SQL query in 'Model->FindByID(".$id."). Model was used by '". $prefix."'");
            }
        } catch(Exception $e) {
            error_log($e->getMessage());
        }
        if($result->rowCount()==1) {
            $row = $result->fetch();
            $obj = new static();
            $obj->fill($row);
            return $obj;
        }
        return false;
    }

    public static function findAll() {
        $prefix = get_called_class();
        $table = ucfirst($prefix."s");
        try {
            $db = DatabasePDO::start();
            $result = $db->prepare("SELECT * FROM ".$table."");
            $result->bindParam(":id", $id);
            if(!@$result->execute()) {
                throw new Exception("There's something wrong with the SQL query in 'Model->FindAll()'. Model was used by '". $prefix."'");
            }
            $array = array();
            if($result->rowCount()>0) {
                while($row = $result->fetch()) {
                    $obj = new static();
                    $obj->fill($row);
                    array_push($array, $obj);
                }
            }
        } catch(Exception $e) {
            error_log($e->getMessage());
        }
        return $array;
    }

    public function getID() {
        return $this->id;
    }
}
?>
