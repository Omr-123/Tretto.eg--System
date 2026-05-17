<?php

class SupportModel
{
    public $db;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    public function getContacts()
    {
        return $this->db->query("SELECT * FROM support_contacts ORDER BY id ASC");
    }

    public function getFAQ()
    {
        return $this->db->query("SELECT * FROM faq ORDER BY id ASC");
    }
}