<?php
require_once(__DIR__ . '/product.php');
require_once(__DIR__ . '/product_variants.php');
require_once(__DIR__ . '/product_imgs.php');
require_once(__DIR__ . '/ProductFactory.php');

class Clogs extends Product {
    public $heel_height;

    public function __construct($data) {
        parent::__construct($data); // Link basic data + variants
        $this->heel_height = $data['heel_height'] ?? '1cm';
    }
    public function getSpecifications() {
        return ['Heel Height' => $this->heel_height];

    }
    // Logic to get specific variant image based on user selection
    public function getVariantImage($color) {
        return $this->variants[$color]['image'] ?? $this->getDefaultImage();
    }

    // Logic to get all sizes available for a specific color
    public function getSizesByColor($color) {
        return $this->variants[$color]['sizes'] ?? [];
    }
}