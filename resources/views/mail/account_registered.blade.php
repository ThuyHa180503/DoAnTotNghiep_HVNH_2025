<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chào mừng bạn</title>
</head>
<body>
    <h2>Chào {{ $customer->name }},</h2>
    <p>Bạn đã đăng ký tài khoản thành công.</p>
    <p>Thông tin tài khoản:</p>
    <ul>
        <li>Email: {{ $customer->email }}</li>
        <li>Số điện thoại: {{ $customer->phone }}</li>
        <li>Mật khẩu {{ $password }}</li>
    </ul>
    <p>Vui lòng đăng nhập để sử dụng dịch vụ.</p>
    <p>Cảm ơn bạn đã đăng ký!</p>
</body>
</html>
