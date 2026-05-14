<?php
require_once(__DIR__ . '/bag.php');
require_once(__DIR__ . '/cart.php');
require_once(__DIR__ . '/clogs.php');
require_once(__DIR__ . '/collection.php');
require_once(__DIR__ . '/product.php');
require_once(__DIR__ . '/slipper.php');

require_once(__DIR__ . '/../db.php');
class ProductFactory {
    public static function createProduct($type, $data) {
        switch (strtolower($type)) {
            case 'bag':
                return new Bag($data);
            case 'cart':
                return new Cart($data);
            case 'clogs':
                return new Clogs($data);
            case 'slipper':
                return new Slipper($data);
            case 'collection':
                return new Collection($data);
            case 'product':
                return new Product($data);
            default:
                throw new Exception("Product type '$type' not recognized.");
        }
    }
}

?>