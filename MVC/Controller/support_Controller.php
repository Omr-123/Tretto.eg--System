<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../Model/support.php';

class SupportController
{
    private $model;

    public function __construct()
    {
        $this->model = new SupportModel();
    }

    public function loadPage()
    {
        return [
            'supportResult' => $this->model->getContacts(),
            'faqResult' => $this->model->getFAQ(),
        ];
    }
}