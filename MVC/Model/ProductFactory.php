<?php
require_once(__DIR__ . '/bag.php');
require_once(__DIR__ . '/cart.php');
require_once(__DIR__ . '/clogs.php');
require_once(__DIR__ . '/collection.php');
require_once(__DIR__ . '/product.php');
require_once(__DIR__ . '/slipper.php');

require_once(__DIR__ . '/../../db.php');

class ProductFactory {
    public function create($type, $data) : ProductInterface {
        // Debugging: Log the type and data
        error_log("Creating product of type: $type");
        error_log("Data: " . print_r($data, true));

        switch (strtolower($type)) {
            case 'Bag':
            return new Bag($data);
            case 'Clogs':
                return new Clogs($data);
            case 'Slipper':
                return new Slipper($data);
            default:
                // Log the error before throwing exception
                error_log("Product type '$type' not recognized.");
                return new Clogs($data); // or throw new Exception("Product type '$type' not recognized.");
        }
    }
}


?>