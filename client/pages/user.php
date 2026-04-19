<!doctype html>
<html lang="vi">

<head>
    <?php include './components/metadata.php'; ?>
    <title>PREHUB - Luyện Thi TOEIC</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>

<body>

    <!-- INCLUDE NAVBAR FILE -->
    <?php include './components/navBar.php'; ?>
    <!-- INCLUDE HEADER FILE -->
    <?php include './components/header.php'; ?>
    <main class="container mb-5">
        <section id="book-list-section">
            <h2 class="fw-bold mb-4">Danh sách đề thi</h2>
            <div class="test-grid">
                <!--Hiển thị danh sách đề thi lấy từ database
                Hàm load test ở main.js-->
            </div>
    </main>
    <!-- INCLUDE FOOTER FILE -->
    <?php include './components/footer.php'; ?>

    <script src="../js/main.js"></script>
</body>

</html>