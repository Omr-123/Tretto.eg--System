<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config.php';
ensure_session();
require_once __DIR__ . '/../Model/admin.php';

class AdminController
{
    private Admin $admin;

    public function __construct()
    {
        $this->admin = new Admin();
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('MVC/View/GUI/login.php');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            redirect_to('MVC/View/GUI/login.php?error=1');
        }

        if ($this->admin->login($email, $password)) {
            $_SESSION['admin_id'] = $this->admin->getID();
            $_SESSION['admin_name'] = $this->admin->getName();
            $_SESSION['role'] = 'Admin';
            redirect_to('MVC/View/GUI/component/admin_dashboard.php');
        }

        redirect_to('MVC/View/GUI/login.php?error=1');
    }

    private function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['success' => false, 'message' => 'Invalid request method.'], 405);
        }
    }

    private function handleUpload(string $field = 'image_file'): string
    {
        if (empty($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return trim($_POST['image'] ?? '');
        }

        if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Image upload failed. Please try again.');
        }

        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $original = $_FILES[$field]['name'] ?? 'image';
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed, true)) {
            throw new RuntimeException('Invalid image type. Allowed: jpg, jpeg, png, webp, gif.');
        }

        $dir = __DIR__ . '/../View/assets/images';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $safeName = 'product_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $target = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $safeName;

        if (!move_uploaded_file($_FILES[$field]['tmp_name'], $target)) {
            throw new RuntimeException('Could not save uploaded image.');
        }

        return $safeName;
    }

    private function productDataFromPost(): array
    {
        return [
            'PID' => (int)($_POST['PID'] ?? $_POST['prod_ID'] ?? 0),
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => (float)($_POST['price'] ?? 0),
            'stock' => (int)($_POST['stock'] ?? 0),
            'category' => trim($_POST['category'] ?? ''),
            'image' => $this->handleUpload(),
            'color' => trim($_POST['color'] ?? 'Default'),
            'size' => (int)($_POST['size'] ?? $_POST['sizes'] ?? 0),
            'add_price' => (float)($_POST['add_price'] ?? 0),
            'capacityLiters' => (int)($_POST['capacityLiters'] ?? $_POST['capacity'] ?? 0),
            'numpackets' => (int)($_POST['numpackets'] ?? 0),
            'heelHeight' => (float)($_POST['heelHeight'] ?? 0),
            'strapType' => trim($_POST['strapType'] ?? $_POST['clog_string'] ?? ''),
            'materialsoftness' => trim($_POST['materialsoftness'] ?? $_POST['slipper_string'] ?? $_POST['sole_type'] ?? ''),
            'BranchID' => (int)($_POST['BranchID'] ?? 0),
        ];
    }

    private function validateProductData(array $data): bool
    {
        return $data['name'] !== '' && $data['category'] !== '' && $data['price'] > 0 && $data['stock'] >= 0;
    }

    public function addProduct(): void
    {
        $this->requirePost();
        try {
            $data = $this->productDataFromPost();
            if (!$this->validateProductData($data)) {
                json_response(['success' => false, 'message' => 'Please fill in the required product information: name, category, price and stock.'], 422);
            }
            $pid = $this->admin->addProduct($data);
            json_response(['success' => $pid > 0, 'PID' => $pid, 'message' => $pid > 0 ? 'Product added successfully.' : 'Failed to add product.'], $pid > 0 ? 200 : 500);
        } catch (Throwable $e) {
            json_response(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateProduct(): void
    {
        $this->requirePost();
        $data = $this->productDataFromPost();
        $data['image'] = trim($_POST['image'] ?? '');
        $success = $data['PID'] > 0 && $data['name'] !== '' && $data['category'] !== '' && $data['price'] > 0 && $this->admin->modifyProduct($data);
        json_response(['success' => $success, 'message' => $success ? 'Product updated successfully.' : 'Failed to update product.'], $success ? 200 : 422);
    }

    public function deleteProduct(): void
    {
        $this->requirePost();
        $pid = (int)($_POST['PID'] ?? $_POST['prod_ID'] ?? 0);
        $success = $pid > 0 && $this->admin->deleteProduct($pid);
        json_response(['success' => $success, 'message' => $success ? 'Product deleted.' : 'Invalid product ID.'], $success ? 200 : 422);
    }

    public function updateStock(): void
    {
        $this->requirePost();
        $pid = (int)($_POST['PID'] ?? $_POST['prod_ID'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 0);
        $success = $pid > 0 && $quantity >= 0 && $this->admin->updateStock($pid, $quantity);
        json_response(['success' => $success, 'message' => $success ? 'Stock updated.' : 'Invalid stock data.'], $success ? 200 : 422);
    }

    public function viewProducts(): array { return $this->admin->viewProducts(); }
    public function getProductByID(int $PID): ?array { return $this->admin->getProductByID($PID); }
    public function getProductVariants(int $PID): array { return $this->admin->getProductVariants($PID); }
    public function getVariantImages(int $pvid): array { return $this->admin->getVariantImages($pvid); }
    public function getProductImages(int $PID): array { return $this->admin->getProductImages($PID); }

    public function addProductVariant(): void
    {
        $this->requirePost();
        $success = $this->admin->addProductVariant($_POST);
        $pvid = 0;
        if ($success) {
            $variants = $this->admin->getProductVariants((int)($_POST['PID'] ?? 0));
            $pvid = (int)($variants[0]['pvid'] ?? 0);
            $img = $this->handleUpload();
            if ($img !== '' && $pvid > 0) {
                $this->admin->addVariantImage($pvid, $img);
            }
        }
        json_response(['success' => $success, 'message' => $success ? 'Variant added successfully.' : 'Failed to add variant.'], $success ? 200 : 422);
    }

    public function updateProductVariant(): void
    {
        $this->requirePost();
        $success = $this->admin->updateProductVariant($_POST);
        if ($success) {
            $img = $this->handleUpload();
            $pvid = (int)($_POST['pvid'] ?? 0);
            if ($img !== '' && $pvid > 0) {
                $this->admin->addVariantImage($pvid, $img);
            }
        }
        json_response(['success' => $success, 'message' => $success ? 'Variant updated successfully.' : 'Failed to update variant.'], $success ? 200 : 422);
    }

    public function deleteProductVariant(): void
    {
        $this->requirePost();
        $pvid = (int)($_POST['pvid'] ?? 0);
        $success = $this->admin->deleteProductVariant($pvid);
        json_response(['success' => $success, 'message' => $success ? 'Variant deleted successfully.' : 'Failed to delete variant.'], $success ? 200 : 422);
    }

    public function deleteProductImage(): void
    {
        $this->requirePost();
        $piid = (int)($_POST['piid'] ?? $_POST['id'] ?? 0);
        $success = $this->admin->deleteProductImage($piid);
        json_response(['success' => $success, 'message' => $success ? 'Image deleted successfully.' : 'Failed to delete image.'], $success ? 200 : 422);
    }

    public function viewOrders(): array { return $this->admin->viewOrders(); }
    public function viewRefunds(): array { return $this->admin->viewRefunds(); }
    public function viewReviews(): array { return $this->admin->viewReviews(); }
    public function viewExchanges(): array { return $this->admin->viewExchanges(); }
    public function viewStoreLocations(): array { return $this->admin->viewStoreLocations(); }
    public function viewSupport(): array { return $this->admin->viewSupport(); }
    public function getTopSelling(): array { return $this->admin->getTopSellingProducts(); }
    public function getMonthlyRevenue(): float { return $this->admin->calculateMonthlyRevenue(); }
    public function getLowStock(): array { return $this->admin->getLowStockAlerts(); }

    public function modifyOrder(): void
    {
        $this->requirePost();
        $orderID = (int)($_POST['orderID'] ?? $_POST['order_ID'] ?? 0);
        $status = $_POST['status'] ?? '';
        $success = $orderID > 0 && $this->admin->modifyOrder($orderID, $status);
        json_response(['success' => $success], $success ? 200 : 422);
    }

    public function deleteOrder(): void
    {
        $this->requirePost();
        $orderID = (int)($_POST['orderID'] ?? $_POST['order_ID'] ?? 0);
        $success = $orderID > 0 && $this->admin->deleteOrder($orderID);
        json_response(['success' => $success], $success ? 200 : 422);
    }

    public function applyRefund(): void { $this->requirePost(); $id = (int)($_POST['refundID'] ?? $_POST['refund_ID'] ?? 0); $success = $id > 0 && $this->admin->applyRefund($id); json_response(['success' => $success], $success ? 200 : 422); }
    public function denyRefund(): void { $this->requirePost(); $id = (int)($_POST['refundID'] ?? $_POST['refund_ID'] ?? 0); $success = $id > 0 && $this->admin->denyRefund($id); json_response(['success' => $success], $success ? 200 : 422); }
    public function applyExchange(): void { $this->requirePost(); $id = (int)($_POST['exchangeID'] ?? $_POST['exchange_ID'] ?? 0); $success = $id > 0 && $this->admin->applyExchange($id); json_response(['success' => $success], $success ? 200 : 422); }
    public function denyExchange(): void { $this->requirePost(); $id = (int)($_POST['exchangeID'] ?? $_POST['exchange_ID'] ?? 0); $success = $id > 0 && $this->admin->denyExchange($id); json_response(['success' => $success], $success ? 200 : 422); }
    public function deleteReview(): void
    {
        $this->requirePost();
    
        $id = (int)($_POST['reviewID'] ?? $_POST['review_ID'] ?? 0);
        $success = $id > 0 && $this->admin->deleteReview($id);
    
        json_response([
            'success' => $success,
            'message' => $success ? 'Review deleted.' : 'Failed to delete review.'
        ], $success ? 200 : 422);
    }
    
    public function addStoreLocation(): void
    {
        $this->requirePost();
    
        $success = $this->admin->addStoreLocation($_POST);
    
        json_response([
            'success' => $success,
            'message' => $success ? 'Location added.' : 'Failed to add location. Please fill city and address.'
        ], $success ? 200 : 422);
    }
    
    public function editStoreLocation(): void
    {
        $this->requirePost();
    
        $id = (int)($_POST['storeID'] ?? $_POST['BranchID'] ?? 0);
        $success = $id > 0 && $this->admin->editStoreLocation($id, $_POST);
    
        json_response([
            'success' => $success,
            'message' => $success ? 'Location updated.' : 'Failed to update location.'
        ], $success ? 200 : 422);
    }
    
    public function deleteStoreLocation(): void
    {
        $this->requirePost();
    
        $id = (int)($_POST['storeID'] ?? $_POST['BranchID'] ?? 0);
        $success = $id > 0 && $this->admin->deleteStoreLocation($id);
    
        json_response([
            'success' => $success,
            'message' => $success ? 'Location deleted.' : 'Failed to delete location.'
        ], $success ? 200 : 422);
    }
    
    public function addSupport(): void
    {
        $this->requirePost();
    
        $success = $this->admin->addSupport($_POST);
    
        json_response([
            'success' => $success,
            'message' => $success ? 'Support ticket added.' : 'Failed to add support ticket. Check user ID and issue.'
        ], $success ? 200 : 422);
    }
    
    public function modifySupport(): void
    {
        $this->requirePost();
    
        $id = (int)($_POST['supportID'] ?? $_POST['support_ID'] ?? 0);
        $success = $id > 0 && $this->admin->modifySupport($id, $_POST);
    
        json_response([
            'success' => $success,
            'message' => $success ? 'Support ticket updated.' : 'Failed to update support ticket.'
        ], $success ? 200 : 422);
    }
    
    public function deleteSupport(): void
    {
        $this->requirePost();
    
        $id = (int)($_POST['supportID'] ?? $_POST['support_ID'] ?? 0);
        $success = $id > 0 && $this->admin->deleteSupport($id);
    
        json_response([
            'success' => $success,
            'message' => $success ? 'Support ticket deleted.' : 'Failed to delete support ticket.'
        ], $success ? 200 : 422);
    }
}

if (is_direct_script(__FILE__)) {
    try {
        $controller = new AdminController();
        $action = $_GET['action'] ?? '';
        match ($action) {
            'login' => $controller->login(),
            'addProduct' => $controller->addProduct(),
            'updateProduct' => $controller->updateProduct(),
            'deleteProduct' => $controller->deleteProduct(),
            'updateStock' => $controller->updateStock(),
            'addProductVariant' => $controller->addProductVariant(),
            'updateProductVariant' => $controller->updateProductVariant(),
            'deleteProductVariant' => $controller->deleteProductVariant(),
            'deleteProductImage' => $controller->deleteProductImage(),
            'modifyOrder' => $controller->modifyOrder(),
            'deleteOrder' => $controller->deleteOrder(),
            'applyRefund' => $controller->applyRefund(),
            'denyRefund' => $controller->denyRefund(),
            'deleteReview' => $controller->deleteReview(),
            'applyExchange' => $controller->applyExchange(),
            'denyExchange' => $controller->denyExchange(),
            'addStoreLocation' => $controller->addStoreLocation(),
            'editStoreLocation' => $controller->editStoreLocation(),
            'deleteStoreLocation' => $controller->deleteStoreLocation(),
            'addSupport' => $controller->addSupport(),
            'modifySupport' => $controller->modifySupport(),
            'deleteSupport' => $controller->deleteSupport(),
            default => json_response(['success' => false, 'message' => 'Action not found.'], 404),
        };
    } catch (Throwable $e) {
        json_response(['success' => false, 'message' => $e->getMessage()], 500);
    }
}
