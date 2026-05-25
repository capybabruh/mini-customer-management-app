<?php
// Load dữ liệu ban đầu
$customers = require __DIR__ . '/../src/Data/customers.php';
require __DIR__ . '/../src/Helpers/functions.php';

// --- XỬ LÝ HÀNH ĐỘNG TỪ NGƯỜI DÙNG ---

// Nếu người dùng nhấn nút "Lưu khách hàng" (Tạo mới)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    createUser(
        $customers, 
        $_POST['name'], 
        $_POST['email'], 
        (int)$_POST['phone'], 
        $_POST['address']
    );
}

// Nếu người dùng nhấn nút "Cập nhật" (Chỉnh sửa)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    updateUser(
        $customers, 
        (int)$_POST['id'], 
        $_POST['name'], 
        $_POST['email'], 
        $_POST['phone'], 
        $_POST['address']
    );
}

// Nếu người dùng nhấn nút "Xóa"
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    deleteUser($customers, (int)$_GET['id']);
}
if (isset($_GET['sort'])) {
    if ($_GET['sort'] === 'asc') {
        usort($customers, function($a, $b) {
            return $a['id'] - $b['id'];
        });
    } elseif ($_GET['sort'] === 'desc') {
        usort($customers, function($a, $b) {
            return $b['id'] - $a['id'];
        });
    }
}
$totalCustomer = count($customers);
?>

<!DOCTYPE html>
<html lang="vi">
    <meta charset="UTF-8">
    <title>Quản lý khách hàng</title>
    <style>
            body {
    font-family: sans-serif;
    max-width: 900px;
    margin: 20px auto;
    line-height: 1.5;

    background-image: url("pic.avif");
    background-size: cover;        
    background-position: center;  
    background-repeat: no-repeat;  
}
        body { font-family: sans-serif; max-width: 900px; margin: 20px auto; line-height: 1.5; }
        .box { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 8px; background: #fdfdfd; }
        .input-group { margin-bottom: 10px; }
        input { padding: 8px; width: 200px; margin-right: 5px; }
        .customer-item { border-bottom: 1px solid #eee; padding: 10px 0; display: flex; justify-content: space-between; align-items: center; }
        .edit-panel { display: none; background: #f0f0f0; padding: 15px; margin-top: 10px; border-radius: 5px; }
        .btn { cursor: pointer; padding: 6px 12px; border: none; border-radius: 4px; }
        .btn-add { background: #28a745; color: white; }
        .btn-edit { background: #007bff; color: white; }
        .btn-delete { background: #dc3545; color: white; text-decoration: none; font-size: 13px; }
    </style>
</head>
<body>

    <h1>Quản lý khách hàng</h1>

    <div class="box">
        <h3>+ Tạo khách hàng mới</h3>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            <input type="text" name="name" placeholder="Họ tên" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Số điện thoại" required>
            <input type="text" name="address" placeholder="Địa chỉ" required>
            <button type="submit" class="btn btn-add">Tạo mới</button>
        </form>
    </div>

    <div class="box">
        <a href="?sort=asc" class="btn btn-edit">Sort ID Descending</a>
        <a href="?sort=desc" class="btn btn-edit">Sort ID Ascending</a>
        <h3>Danh sách (Tổng: <?php echo $totalCustomer; ?>)</h3>
        
        <?php foreach ($customers as $customer): ?>
            <div style="border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                <div class="customer-item">
                    <div>
                        <strong>ID: <?php echo $customer['id']; ?> - <?php echo $customer['name']; ?></strong>
                        <br><small><?php echo $customer['email']; ?> | <?php echo $customer['phone']; ?></small>
                    </div>
                    <div>
                        <button class="btn btn-edit" onclick="toggleEdit(<?php echo $customer['id']; ?>)">Sửa</button>
                        
                        <a href="?action=delete&id=<?php echo $customer['id']; ?>" 
                           class="btn btn-delete" onclick="return confirm('Xóa khách hàng này?')">Xóa</a>
                    </div>
                </div>

                <div id="edit-box-<?php echo $customer['id']; ?>" class="edit-panel">
                    <form method="POST">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?php echo $customer['id']; ?>">
                        
                        <div class="input-group">
                            Tên: <input type="text" name="name" value="<?php echo $customer['name']; ?>">
                            Email: <input type="email" name="email" value="<?php echo $customer['email']; ?>">
                        </div>
                        <div class="input-group">
                            SĐT: <input type="text" name="phone" value="<?php echo $customer['phone']; ?>">
                            ĐC: <input type="text" name="address" value="<?php echo $customer['address']; ?>">
                        </div>
                        <button type="submit" class="btn btn-edit">Xác nhận cập nhật</button>
                        <button type="button" class="btn" onclick="toggleEdit(<?php echo $customer['id']; ?>)">Hủy</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        // Hàm JavaScript để ẩn/hiện Form chỉnh sửa
        function toggleEdit(id) {
            var el = document.getElementById('edit-box-' + id);
            el.style.display = (el.style.display === 'block') ? 'none' : 'block';
        }
    </script>

</body>
</html>