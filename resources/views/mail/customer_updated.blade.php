<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo cập nhật</title>
</head>
<body>
    <h2>Xin chào {{ $customer->name }},</h2>
    <p>Loại cộng tác viên của bạn đã được cập nhật thành công.</p>
    <p>Loại cộng tác viên mới: <strong>{{ $newCatalogue->name }}</strong></p>
    <p>Cảm ơn bạn đã đồng hành cùng chúng tôi!</p>
</body>
</html>
