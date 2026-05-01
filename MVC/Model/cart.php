<?php
require '../../db.php';

class Cart {
    public $cartID;
    public $userID;
    public $total_amount;
    public $product_ID;
    public function calculateTotal() {
        $total = 0;
        foreach ($this->itemlist as $item) {
            $total += $item->price * $item->quantity;
        }
        $this->total_amount = $total;
        return $total;
    }
    public function addItem($item) {
        $this->itemlist[] = $item;
        $this->calculateTotal();
    }
    public function removeItem($itemID) {
        foreach ($this->itemlist as $index => $item) {
            if ($item->id == $itemID) {
                unset($this->itemlist[$index]);
                break;
            }
        }
        $this->calculateTotal();
    }
    public function updateItemQuantity($itemID, $quantity) {
        foreach ($this->itemlist as $item) {
            if ($item->id == $itemID) {
                $item->quantity = $quantity;
                break;
            }
        }
        $this->calculateTotal();
    }
    public function clearCart() {
        $this->itemlist = [];
        $this->total_amount = 0;
    }
    public function fetchCartItems($userID) {
        // This method would typically fetch cart items from the database based on the user's session or ID
        // For example:
        $stmt = $conn->prepare("SELECT * FROM cart WHERE userID = ?");
        $stmt->bind_param('i', $userID);
        $list=$stmt->execute();
        $stmt->close();
        foreach ($list as $row) {
            $item = new product();
            $item->cartID = $row['id'];
            $item->userID = $row['userID'];
            $item->total_amount = $row['name'];
            $item->product_ID = $row['prod_ID'];
            $this->addItem($item);
        }
        // Execute the query and populate $this->itemlist with the results
    }
}
