<?php
session_start();

// Xu ly dang xuat
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'logout') {
    unset($_SESSION['admin_user']);
    session_destroy();
    header("Location: index.php");
    exit();
}

$isLogin = isset($_SESSION['admin_user']);
$pages = [
    'nhanvien' => ['file' => 'xemthongtinnv.php', 'title' => 'Nhân viên'],
    'phongban' => ['file' => 'xemthongtinpb.php', 'title' => 'Phòng ban'],
    'nhanvien_phongban' => ['file' => 'xemthongtinNVPB.php', 'title' => 'Nhân viên theo phòng ban'],
    'timkiem' => ['file' => 'timkiem.php', 'title' => 'Tìm kiếm'],
];
$page = isset($_GET['page'], $pages[$_GET['page']]) ? $_GET['page'] : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Nhân Sự</title>
    <link href="https://fonts.googleapis.com/css2?family=Sedgwick+Ave&family=Lora:wght@400;600;700&family=Alfa+Slab+One&display=swap" rel="stylesheet">
    <style>
        /* ===== LOCAL FONT: Lora ===== */
        @font-face { font-family: 'Lora'; src: url('font-lora/Lora.ttf') format('truetype'); font-weight: 400; font-style: normal; }
        @font-face { font-family: 'Lora'; src: url('font-lora/Lora-Italic.ttf') format('truetype'); font-weight: 400; font-style: italic; }
        @font-face { font-family: 'Lora'; src: url('font-lora/Lora-Bold.ttf') format('truetype'); font-weight: 700; font-style: normal; }
        @font-face { font-family: 'Lora'; src: url('font-lora/Lora-BoldItalic.ttf') format('truetype'); font-weight: 700; font-style: italic; }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body { width: 100%; height: 100%; overflow: hidden; }

        /* ===== HERO: FULL VIEWPORT ===== */
        .hero { position: relative; width: 100vw; height: 100vh; overflow: hidden; }

        /* --- Full-page background image --- */
        .hero-bg { position: absolute; inset: 0; z-index: 0; }
        .hero-bg img { width: 100%; height: 100%; object-fit: fill; display: block; }

        /* Khung thiết kế 1920x900, được JS thu phóng cho vừa cửa sổ */
        .stage {
            position: absolute;
            left: 0;
            top: 0;
            width: 1920px;
            height: 900px;
            transform-origin: top left;
            transform: scale(1);
            z-index: 10;
        }

        /* ===== NAVBAR ===== */
        .nav-area { position: absolute; inset: 0; z-index: 20; pointer-events: none; }

        .thanhtab-img {
            position: absolute;
            left: 172.1px;
            top: -14.0px;
            width: 1601.9px;
            height: 88.5px;
            display: block;
        }

        /* nav-pill: lớp phủ khớp đúng với thanhtab (top phải bằng .thanhtab-img) */
        .nav-pill {
            position: absolute;
            left: 172.1px;
            top: -14.0px;
            width: 1601.9px;
            height: 88.5px;
            display: flex;
            align-items: center;
            pointer-events: auto;
        }

        .nav-home {
            flex: 0 0 70px;
            margin-left: 75px;
            margin-right: 56px;
            display: block;
            transition: opacity 0.2s;
        }
        .nav-home:hover { opacity: 0.72; }
        .nav-home img {
            width: 63px;
            height: 59px;
            object-fit: contain;
            display: block;
            pointer-events: auto;
            transform: translateX(20px);
        }

        .tab-links {
            flex: 1 1 0;
            display: flex;
            align-items: center;
            height: 100%;
            transform: translateX(40px);
        }
        .tab-links a {
            flex: 1 1 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 50.9px;
            border-left: 4px solid #76c3ed;
            font-family: 'Lora', serif;
            font-weight: 700;
            font-size: 26px;
            color: #f6559d;
            text-decoration: none;
            letter-spacing: 0.1em;
            white-space: nowrap;
            transition: color 0.2s;
            pointer-events: auto;
        }
        .tab-links a:hover { color: #c0006a; }

        .login-area {
            flex: 0 0 264.7px;
            height: 85.8px;
            display: flex;
            align-items: center;
            pointer-events: auto;
        }
        .login-area a { display: block; width: 100%; text-decoration: none; }
        .login-area img { width: 100%; height: auto; display: block; transition: opacity 0.2s; }
        .login-area img:hover { opacity: 0.8; }

        .login-btn { position: relative; }
        .login-btn span {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Lora', serif;
            font-weight: 700;
            font-size: 26px;
            color: #f6559d;
            letter-spacing: 0.1em;
            white-space: nowrap;
            text-transform: uppercase;
            transition: color 0.2s;
            pointer-events: none;
        }
        .login-btn:hover span { color: #c0006a; }

        .logout-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: 'Lora', serif;
            font-size: clamp(0.6rem, 0.85vw, 0.85rem);
            color: #f6559d;
            font-weight: 600;
            white-space: nowrap;
        }
        .logout-wrap a { color: #c0006a; text-decoration: underline; }

        /* ===== SVG MAIN TITLE (viewBox 1920x900) ===== */
        .title-svg {
            position: absolute;
            inset: 0;
            width: 1920px;
            height: 900px;
            z-index: 15;
            pointer-events: none;
            overflow: visible;
        }

        /* ===== KHUNG NỘI DUNG =====
           top:135 + height:690 = đáy ở y=825. Phải khớp viewBox của SVG nền (1602 x 690) */
        .content-panel {
            position: absolute;
            top: 135px;
            left: 172.1px;
            width: 1601.9px;
            height: 690px;
            overflow: auto;
            z-index: 16;
            padding: 48px 56px;
            color: #333;
            font-family: 'Lora', serif;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1602 690'%3E%3Cpath d='M30,4 H1572 A30,30 0 0 0 1598,30 V660 A30,30 0 0 0 1572,686 H30 A30,30 0 0 0 4,660 V30 A30,30 0 0 0 30,4 Z' fill='%23fffdf0' stroke='%23f9559f' stroke-width='8'/%3E%3C/svg%3E") center / 100% 100% no-repeat;
        }

        /* ===== TABLE STYLES ===== */
        .content-panel .tbl-title {
            font-family: 'Lora', serif;
            font-size: 22px;
            font-weight: 700;
            color: #f6559d;
            text-align: center;
            margin-bottom: 14px;
            letter-spacing: 0.08em;
        }
        .content-panel table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
            border: 1.5px solid #76c3ed;
        }
        .content-panel thead tr { background: #ffc2dc; }
        .content-panel thead th {
            padding: 16px 18px;
            font-family: 'Lora', serif;
            font-size: 20px;
            font-weight: 700;
            color: #c0006a;
            letter-spacing: 0.07em;
            text-align: center;
            border: 1px solid #76c3ed;
        }
        .content-panel tbody tr { background: #fff; transition: background 0.15s; }
        .content-panel tbody tr:hover { background: rgba(118,195,237,0.12); }
        .content-panel tbody td {
            padding: 14px 18px;
            font-family: 'Lora', serif;
            font-size: 18px;
            font-weight: 500;
            color: #333;
            text-align: center;
            border: 1px solid #76c3ed;
        }
        .content-panel a {
            color: #f6559d;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s;
        }
        .content-panel a:hover { color: #c0006a; }

        /* ===== SEARCH FORM ===== */
        .search-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 36px;
            padding-top: 30px;
        }
        .search-title {
            font-family: 'Lora', serif;
            font-size: 28px;
            font-weight: 700;
            color: #f6559d;
            letter-spacing: 0.08em;
        }
        .search-criteria {
            display: flex;
            gap: 48px;
            align-items: center;
            margin-bottom: 24px;
        }
        .search-criteria label {
            font-family: 'Lora', serif;
            font-size: 20px;
            color: #555;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .search-criteria input[type="radio"] { accent-color: #f6559d; width: 22px; height: 22px; }
        .search-row { display: flex; gap: 18px; align-items: center; }
        .search-row input[type="text"] {
            padding: 11px 22px;
            border: 2px solid #f9559f;
            border-radius: 36px;
            background: #fff;
            font-family: 'Lora', serif;
            font-size: 17px;
            color: #333;
            outline: none;
            width: 340px;
            transition: background 0.3s, border-color 0.2s, box-shadow 0.2s;
        }
        .search-row input[type="text"]:focus {
            border-color: #c0006a;
            background: rgba(249, 85, 159, 0.10);
            box-shadow: 0 0 0 4px rgba(249,85,159,0.15);
        }
        .search-row input[type="text"]:not(:placeholder-shown) { background: rgba(249, 85, 159, 0.10); }
        .search-row input[type="submit"] {
            padding: 11px 32px;
            border: none;
            border-radius: 36px;
            background: #f9559f;
            color: #fff;
            font-family: 'Lora', serif;
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 0.07em;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
        }
        .search-row input[type="submit"]:hover { background: #c0006a; transform: translateY(-2px); }
        .search-result-title {
            font-family: 'Lora', serif;
            font-size: 20px;
            font-weight: 600;
            color: #f6559d;
            text-align: center;
            margin: 28px 0 14px;
            letter-spacing: 0.05em;
        }

        /* ===== DECORATIVE ICONS (nội dung trang) ===== */
        .deco-icon { position: absolute; pointer-events: none; z-index: 18; }

        /* ===== NÚT QUAY LẠI: dưới góc trái khung, rộng 200px (y=830 → ~898) ===== */
        .btn-back {
            position: absolute;
            left: 172.1px;
            top: 830px;
            z-index: 20;
            display: block;
            transition: transform 0.2s, opacity 0.2s;
        }
        .btn-back img { width: 200px; height: auto; display: block; }
        .btn-back:hover { transform: scale(1.12) translateX(-3px); opacity: 0.85; }
    </style>
</head>
<body>

<div class="hero">

    <!-- Background image -->
    <div class="hero-bg">
        <img src="icon/bgr.png" alt="">
    </div>

    <div class="stage" id="stage">

    <!-- ═══ NAVBAR ═══ -->
    <div class="nav-area">

        <img src="icon/thanhtab.png" class="thanhtab-img" alt="">

        <div class="nav-pill">

            <a href="index.php" class="nav-home">
                <img src="icon/home.png" alt="Trang chủ">
            </a>

            <nav class="tab-links">
                <a href="index.php?page=nhanvien">NHÂN VIÊN</a>
                <a href="index.php?page=phongban">PHÒNG BAN</a>
                <a href="index.php?page=timkiem">TÌM KIẾM</a>
            </nav>

            <div class="login-area">
                <?php if ($isLogin): ?>
                    <div class="logout-wrap">
                        Xin chào <b><?php echo htmlspecialchars($_SESSION['admin_user']); ?></b>
                        &nbsp;|&nbsp;
                        <a href="index.php?action=logout">Đăng xuất</a>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="login-btn">
                        <img src="icon/login.png" alt="">
                        <span>Đăng nhập</span>
                    </a>
                <?php endif; ?>
            </div>

        </div><!-- /nav-pill -->

    </div><!-- /nav-area -->

    <?php if ($page !== ''): ?>
        <!-- ═══ Icon trang trí quanh khung (khung: top 135, đáy 825, trái 172.1, phải 1774) ═══ -->

        <!-- Góc trên-trái: cherry -->
        <img src="icon/cherry.png" alt=""
             class="deco-icon"
             style="left:100px; top:80px; width:130px;
                    transform:rotate(-15deg);">

        <!-- Góc trên-phải: icon1 (kẹp giấy) -->
        <img src="icon/icon1.png" alt=""
             class="deco-icon"
             style="left:1720px; top:68px; width:80px;
                    transform:rotate(12deg);">

        <!-- Góc dưới-trái: hoa (dời lên để không đè nút quay lại) -->
        <img src="icon/hoa.png" alt=""
             class="deco-icon"
             style="left:108px; top:690px; width:120px;
                    transform:rotate(10deg);">

        <!-- Góc dưới-phải: icon2 (con trỏ) -->
        <img src="icon/icon2.png" alt=""
             class="deco-icon"
             style="left:1730px; top:740px; width:80px;">

        <!-- Nút quay lại: chỉ hiện ở trang nhân viên theo phòng ban, quay về danh sách phòng ban -->
        <?php if ($page === 'nhanvien_phongban'): ?>
        <a href="index.php?page=phongban" class="btn-back" title="Quay lại danh sách phòng ban">
            <img src="icon/back.png" alt="Quay lại">
        </a>
        <?php endif; ?>

        <main class="content-panel">
            <?php include $pages[$page]['file']; ?>
        </main>
    <?php else: ?>

    <!-- ═══ MAIN TITLE (SVG, canvas 1920x900) ═══ -->
    <svg class="title-svg"
         viewBox="0 0 1920 900"
         preserveAspectRatio="xMidYMid meet"
         xmlns="http://www.w3.org/2000/svg">
        <defs>
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Sedgwick+Ave&family=Alfa+Slab+One&display=swap');

                .txt-quan-ly {
                    font-family: 'Sedgwick Ave', cursive;
                    font-size: 200px;
                    fill: #f6559d;
                    stroke: #fffdf0;
                    stroke-width: 40;
                    stroke-linejoin: round;
                    paint-order: stroke fill;
                    dominant-baseline: central;
                    text-anchor: middle;
                }
                .txt-nhan-su {
                    font-family: 'Lora', serif;
                    font-size: 205px;
                    font-weight: 400;
                    font-style: normal;
                    fill: #f6559d;
                    stroke: #fffdf0;
                    stroke-width: 40;
                    stroke-linejoin: round;
                    paint-order: stroke fill;
                    dominant-baseline: central;
                    text-anchor: middle;
                    letter-spacing: 8;
                }
            </style>
        </defs>

        <!-- Icon trang trí (icon1 xoay -13.1° quanh tâm 227.95, 331.15) -->
        <image href="icon/icon1.png"
               x="168.9" y="212.2" width="118.1" height="237.9"
               transform="rotate(-13.1 227.95 331.15)"/>

        <image href="icon/hoa.png"
               x="168.9" y="635.4" width="156.6" height="140.6"/>

        <image href="icon/cherry.png"
               x="1502" y="199.4" width="208.4" height="160.5"/>

        <image href="icon/icon2.png"
               x="1530.9" y="746.1" width="102.1" height="178.8"/>

        <!-- Tiêu đề -->
        <text x="972.05" y="327.05" class="txt-quan-ly">Quản Lý</text>
        <text x="959.95" y="604.4"  class="txt-nhan-su">NHÂN SỰ</text>
    </svg>
    <?php endif; ?>

    </div><!-- /stage -->

</div><!-- /hero -->

<script>
function fitStage() {
    var s  = Math.min(window.innerWidth / 1920, window.innerHeight / 900);
    var st = document.getElementById('stage');
    st.style.transform = 'scale(' + s + ')';
    st.style.left = ((window.innerWidth  - 1920 * s) / 2) + 'px';
    st.style.top  = ((window.innerHeight -  900 * s) / 2) + 'px';
}
fitStage();
window.addEventListener('resize', fitStage);
</script>

</body>
</html>