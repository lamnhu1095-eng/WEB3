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
        @font-face {
            font-family: 'Lora';
            src: url('font-lora/Lora.ttf') format('truetype');
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: 'Lora';
            src: url('font-lora/Lora-Italic.ttf') format('truetype');
            font-weight: 400;
            font-style: italic;
        }
        @font-face {
            font-family: 'Lora';
            src: url('font-lora/Lora-Bold.ttf') format('truetype');
            font-weight: 700;
            font-style: normal;
        }
        @font-face {
            font-family: 'Lora';
            src: url('font-lora/Lora-BoldItalic.ttf') format('truetype');
            font-weight: 700;
            font-style: italic;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        /* ===== HERO: FULL VIEWPORT ===== */
        .hero {
            position: relative;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }

        /* --- Full-page background image --- */
        .hero-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
        }
        .hero-bg img {
            width: 100%;
            height: 100%;
            object-fit: fill;
            display: block;
        }

        /* ===================================================
         * NAVBAR
         * Design canvas: 1920 × 900 px
         *
         * thanhtab : X:172.1  Y:24.6   W:1601.9  H:88.5
         * home     : X:276.7  Y:34.3   W:74.4    H:69.2
         * login    : X:1509.4 Y:26     W:264.7   H:85.8
         *
         * home offset from thanhtab left  = 276.7-172.1 = 104.6px → 6.53%
         * login offset from thanhtab left = 1509.4-172.1= 1337.3px → 83.5%
         * tab area  = 6.53%+home_w to 83.5%  (center flex)
         * =================================================== */

        /* Phantom image — drives the pill height via aspect-ratio */
        .nav-area {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 20;
            pointer-events: none;   /* children re-enable as needed */
        }

        /* thanhtab pill image — purely visual */
        .thanhtab-img {
            position: absolute;
            left:   172.1px;
            top:   44.6px;
            width:  1601.9px;
            height: 88.5px;
            display: block;
        }

        /*
         * nav-pill: overlay matching thanhtab exactly (px)
         * thanhtab  X:172.1  Y:24.6  W:1601.9  H:88.5
         */
        .nav-pill {
            position: absolute;
            left:   172.1px;
            top:    44.6px;   /* ← khớp với thanhtab-img top */
            width:  1601.9px;
            height: 88.5px;
            display: flex;
            align-items: center;
            pointer-events: auto;
        }

        /* home icon — offset from pill left
         * home X:276.7  → from pill left: 276.7-172.1 = 104.6px
         * home W:74.4  H:69.2
         */
        .nav-home {
            flex: 0 0 70px;            /* ← đổi từ 74.4px, phải bằng width của img */
            margin-left: 75px;         /* ← đổi từ 104.6px, giảm càng nhiều thì càng sang trái */
            margin-right: 56px;
            display: block;
            transition: opacity 0.2s;
        }
        .nav-home:hover { opacity: 0.72; }
        .nav-home img {
            width: 63px;               /* ← đổi từ 74.4px */
            height: 59px;              /* ← đổi từ 69.2px, giữ đúng tỉ lệ 74.4 : 69.2 */
            object-fit: contain;
            display: block;
            pointer-events: auto;
            transform: translateX(20px);
        }

        /* tab links — centered in remaining space between home and login */
        .tab-links {
            flex: 1 1 0;
            display: flex;
            align-items: center;
            height: 100%;
            transform: translateX(40px); 
            /* bỏ gap, không cần nữa */
        }
        .tab-links a {
            flex: 1 1 0;                   /* 3 ô rộng bằng nhau */
            display: flex;
            align-items: center;
            justify-content: center;       /* chữ nằm giữa ô */
            height: 50.9px;                /* chiều dài thanh ngăn */
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

        /* login / logout — right-pinned inside pill
         * login X:1509.4  → from pill left: 1509.4-172.1 = 1337.3px
         * login W:264.7  H:85.8
         * right margin: 1601.9 - 1337.3 - 264.7 = -0.1 ≈ 0 (flush right)
         */
        .login-area {
            flex: 0 0 264.7px;
            height: 85.8px;
            display: flex;
            align-items: center;
            pointer-events: auto;  /* đảm bảo click login được */
        }
        .login-area a {
            display: block;
            width: 100%;
            text-decoration: none;
        }
        .login-area img {
            width: 100%;
            height: auto;
            display: block;
            transition: opacity 0.2s;
        }
        .login-area img:hover { opacity: 0.8; }

        /* chữ "Đăng nhập" đè lên login.png */
        .login-btn {
            position: relative;
        }
        .login-btn span {
            position: absolute;
            inset: 0;                      /* phủ kín login.png */
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Lora', serif;
            font-weight: 700;
            font-size: 26px;
            color: #f6559d;                /* cùng màu với các tab */
            letter-spacing: 0.1em;
            white-space: nowrap;
            text-transform: uppercase;     /* thành "ĐĂNG NHẬP" giống "NHÂN VIÊN" */
            transition: color 0.2s;
            pointer-events: none;          /* click xuyên xuống thẻ <a> */
        }
        .login-btn:hover span { color: #c0006a; }

        /* logged-in state */
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
        .logout-wrap a {
            color: #c0006a;
            text-decoration: underline;
        }

        /* ===== DECORATIVE ICONS =====
         * Moved into SVG <image> elements — see title-svg
         * Coordinates match the 1920×900 viewBox exactly
         */

        /* ===== SVG MAIN TITLE =====
         * viewBox = 0 0 1920 900  (design canvas)
         *
         * "Quản Lý" bounding box: X:522.4 Y:279.7 W:901.3 H:294.7
         *    center: x = 522.4 + 901.3/2 = 972.05
         *            y = 279.7 + 294.7/2 = 427.05
         *    font-size: 185  stroke-width: 128  fill:#f6559d  stroke:#fffdf0
         *
         * "Nhân Sự" bounding box: X:338 Y:553.7 W:1243.9 H:301.4
         *    center: x = 338 + 1243.9/2 = 959.95
         *            y = 553.7 + 301.4/2 = 704.4
         *    font-size: 188  stroke-width: 128  fill:#f6559d  stroke:#fffdf0
         */
        .title-svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 15;
            pointer-events: none;
            overflow: visible;
        }
        .content-panel {
            position: absolute;
            top: 150px;
            left: 8%;
            width: 84%;
            height: calc(100vh - 180px);
            overflow: auto;
            z-index: 16;
            padding: 24px;
            border-radius: 18px;
            background: rgba(255, 253, 240, 0.94);
            color: #333;
            font-family: 'Lora', serif;
        }
        .content-panel table { border-collapse: collapse; background: #fff; }
        .content-panel th, .content-panel td { padding: 8px; }
        .content-panel a { color: #c0006a; }
    </style>
</head>
<body>

<div class="hero">

    <!-- Background image -->
    <div class="hero-bg">
        <img src="icon/bgr.png" alt="">
    </div>

    <!-- ═══════════════════════════════
         NAVBAR
         ═══════════════════════════════ -->
    <div class="nav-area">

        <!-- thanhtab pill image (visual only) -->
        <img src="icon/thanhtab.png" class="thanhtab-img" alt="">

        <!-- nav-pill: flex overlay matching thanhtab exactly -->
        <div class="nav-pill">

            <!-- Home icon -->
            <a href="index.php" class="nav-home">
                <img src="icon/home.png" alt="Trang chủ">
            </a>

            <!-- Tab links (centered) -->
            <nav class="tab-links">
                <a href="index.php?page=nhanvien">NHÂN VIÊN</a>
                <a href="index.php?page=phongban">PHÒNG BAN</a>
                <a href="index.php?page=timkiem">TÌM KIẾM</a>
            </nav>

            <!-- Login / Logout -->
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
        <main class="content-panel">
            <?php include $pages[$page]['file']; ?>
        </main>
    <?php else: ?>
    <!-- Decorative icons are now inside the SVG below -->

    <!-- ═══════════════════════════════
         MAIN TITLE (SVG for exact stroke)
         Design canvas: viewBox="0 0 1920 900"
         paint-order: stroke fill  →  cream outline, pink fill
         ═══════════════════════════════ -->
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

        <!-- ═══ DECORATIVE ICONS (SVG image elements) ═══
             All x, y, width, height = exact Figma px in 1920×900 canvas
             icon1 rotates -13.1° around its center (168.9+118.1/2, 212.2+237.9/2) = (227.95, 331.15) -->
        <image href="icon/icon1.png"
               x="168.9" y="212.2" width="118.1" height="237.9"
               transform="rotate(-13.1 227.95 331.15)"/>

        <image href="icon/hoa.png"
               x="168.9" y="635.4" width="156.6" height="140.6"/>

        <image href="icon/cherry.png"
               x="1502" y="199.4" width="208.4" height="160.5"/>

        <image href="icon/icon2.png"
               x="1530.9" y="746.1" width="102.1" height="178.8"/>

        <!-- ═══ MAIN TITLE TEXT ═══ -->
        <!-- "Quản Lý" -->
        <text x="972.05" y="327.05" class="txt-quan-ly">Quản Lý</text>

        <!-- "NHÂN SỰ" -->
        <text x="959.95" y="604.4"  class="txt-nhan-su">NHÂN SỰ</text>
    </svg>
    <?php endif; ?>

</div><!-- /hero -->

</body>
</html>
