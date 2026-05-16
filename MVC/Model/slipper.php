<?php
require_once(__DIR__ . '/product.php');
require_once(__DIR__ . '/product_variants.php');
require_once(__DIR__ . '/product_imgs.php');
require_once(__DIR__ . '/ProductFactory.php');

class Slipper extends Product {
    public $matrialsoftness;
    public $type;
    public function __construct($data) {
        parent::__construct($data); // Link basic data + variants
        $this->matrialsoftness = $data['matrialsoftness'] ?? null;
        $this->type = "slipper";
    }
     public function getSpecifications() {
        return ['Material Softness' => $this->matrialsoftness];

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