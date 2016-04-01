<?php
class Example extends Model {
    public $text;

    public function __construct($text) {
        $this->text = $text;
    }

    public function __toString() {
        return $this->text;
    }

    // You have access to all "Base Model" functions, including fill($obj), findAll(), findById(id) and more.
}
?>
