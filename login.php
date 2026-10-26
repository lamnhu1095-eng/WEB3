<?php
session_start();
$msg = "";

if (isset($_REQUEST['btnLogin'])) {
    $user = $_REQUEST['txtUsername'];
    $pass = $_REQUEST['txtPassword'];

    $link = mysqli_connect('localhost', 'root', '') or die('Could not connect: ' . mysqli_connect_error());
    mysqli_select_db($link, 'DULIEU');

    $sql = "SELECT * FROM admin WHERE username='$user' AND password='$pass'";
    $rs  = mysqli_query($link, $sql);

    if (mysqli_num_rows($rs) > 0) {
        $_SESSION['admin_user'] = $user;
        mysqli_close($link);
        header("Location: index.php");
        exit();
    } else {
        $msg = "Sai tên đăng nhập hoặc mật khẩu!";
    }
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập — Quản Lý Nhân Sự</title>
    <link href="https://fonts.googleapis.com/css2?family=Sedgwick+Ave&family=Lora:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        @font-face { font-family:'Lora'; src:url('font-lora/Lora.ttf') format('truetype'); font-weight:400; }
        @font-face { font-family:'Lora'; src:url('font-lora/Lora-Bold.ttf') format('truetype'); font-weight:700; }

        * { margin:0; padding:0; box-sizing:border-box; }
        html, body { width:100%; height:100%; overflow:hidden; background:#fffdf0; }

        .hero { position:relative; width:100vw; height:100vh; overflow:hidden; }

        /* Canvas 1920 × 900 + fitStage để fill viewport */
        .stage {
            position: absolute;
            left: 0; top: 0;
            width: 1920px; height: 900px;
            transform-origin: top left;
        }

        /* ── NỬA TRÁI: bgr3.png (1080×1080) cover, cắt phần thừa ── */
        .left-half {
            position: absolute;
            left: 0; top: 0;
            width: 50%; height: 100%;   /* phủ kín nửa trái của cửa sổ, không phụ thuộc canvas */
            background: url('icon/bgr3.png') center / cover no-repeat;
            overflow: hidden;
        }

        /* ── NỬA PHẢI: nền kem ── */
        .right-half {
            position: absolute;
            left: 50%; top: 0;
            width: 50%; height: 100%;   /* phủ kín nửa phải của cửa sổ */
            background: #fffdf0;
        }

        /* ── SVG tiêu đề trái ── */
        .left-svg {
            position: absolute;
            left: 0; top: 0;
            width: 960px; height: 900px;
            pointer-events: none;
            overflow: hidden;   /* không tràn sang nửa phải */
        }

        /* ── Card đăng nhập: nenloginn.png 923×664 ở kích thước gốc ──
           Căn giữa nửa phải: left = 960 + (960-923)/2 = 978
           Căn giữa dọc:      top  = (900-664)/2       = 118 */
        .login-card {
            position: absolute;
            left: 978px;
            top:  80px;      /* ← đổi từ 118px, giảm nữa thì card lên cao hơn */
            width: 923px;
            height: 664px;
        }
        .login-card .card-img {
            position: absolute;
            inset: 0;
            width: 923px;   /* kích thước gốc */
            height: 664px;  /* kích thước gốc */
            display: block;
        }

        /* login2.png (300×85) dùng làm nút submit */
        .btn-login {
            background: url('icon/login2.png') center / 100% 100% no-repeat;
            border: none;
            width: 255px;         /* ← đổi từ 300px (giữ tỉ lệ 300:85) */
            height: 72px;         /* ← đổi từ 85px */
            flex-shrink: 0;       /* không cho flex ép nút bị bẹp */
            font-family: 'Lora', serif;
            font-size: 0;         /* ẩn text, ảnh đã có chữ */
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
        }
        .btn-login:hover {
            opacity: 0.85;
            transform: translateY(-2px);
        }

        /* Form nằm trên card — inputs khớp vùng pre-drawn của nenloginn.png */
        .login-card form {
            position: absolute;
            top: 270px;       /* ← đổi từ 310px, giảm nữa thì cả cụm lên cao hơn */
            left: 80px;
            right: 80px;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 26px;
        }

        .login-card form > * { flex-shrink: 0; }
        .login-field { width: 74%; }   /* giảm chiều rộng */
        .login-field input {
            width: 100%;
            padding: 24px 28px;           /* tăng chiều cao */
            border: none;
            border-radius: 60px;
            background: #fffdf0;         /* cùng màu nền */
            font-family: 'Lora', serif;
            font-size: 24px;              /* chữ nhập to hơn */
            font-weight: 700;
            color: #555;
            letter-spacing: 0.04em;
            text-transform: none;         /* giữ nguyên chữ hoa / chữ thường khi nhập */
            outline: none;
            transition: box-shadow 0.2s;
        }
        .login-field input:focus {
            box-shadow: 0 0 0 3px rgba(249,85,159,0.25);
            color: #555;
        }
        .login-field input::placeholder { color: #bbb; }

        /* Cảnh báo lỗi: nằm dưới hẳn card, chữ to, màu hồng đậm cho nổi trên nền kem */
        .error-msg {
            position: absolute;
            top: 100%;
            left: 0; right: 0;
            margin-top: 14px;
            font-family: 'Lora', serif;
            font-size: 26px;
            color: #c0006a;
            font-weight: 700;
            text-align: center;
        }
        /* Link quay lại trang chủ: cùng màu nền */
        .back-home {
            margin-top: -6px;
            font-family: 'Lora', serif;
            font-size: 18px;
            font-weight: 700;
            color: #fffdf0;
            text-decoration: none;
            letter-spacing: 0.06em;
            transition: opacity 0.2s;
        }
        .back-home:hover { opacity: 0.75; text-decoration: underline; }

        /* Icon trang trí */
        .deco { position:absolute; pointer-events:none; z-index:12; }
    </style>
</head>
<body>
<div class="hero">

    <!-- NỀN: nằm ngoài canvas, phủ kín cửa sổ để không còn dải trắng -->
    <div class="left-half"></div>
    <div class="right-half"></div>

<div class="stage" id="stage">

    <!-- SVG: tiêu đề + icon trái -->
    <svg class="left-svg"
         viewBox="0 0 960 900"
         preserveAspectRatio="xMidYMid meet"
         xmlns="http://www.w3.org/2000/svg">
        <defs>
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Sedgwick+Ave&family=Lora:wght@700&display=swap');
                .txt-ql {
                    font-family: 'Sedgwick Ave', cursive;
                    font-size: 175px;
                    fill: #f6559d;
                    stroke: #fffdf0;
                    stroke-width: 30;
                    stroke-linejoin: round;
                    paint-order: stroke fill;
                    dominant-baseline: central;
                    text-anchor: middle;
                }
                .txt-ns {
                    font-family: 'Lora', serif;
                    font-size: 150px;
                    font-weight: 700;
                    fill: #f6559d;
                    stroke: #fffdf0;
                    stroke-width: 26;
                    stroke-linejoin: round;
                    paint-order: stroke fill;
                    dominant-baseline: central;
                    text-anchor: middle;
                    letter-spacing: 5;
                }
            </style>
        </defs>

        <!-- Home icon — link về index.php -->
        <a href="index.php" style="pointer-events:auto;">
            <image href="icon/home.png" x="46" y="38" width="72" height="68"
                   style="cursor:pointer;"/>
        </a>

        <!-- Icon trang trí -->
        <image href="icon/hoa.png"    x="130" y="700" width="130" height="118"/>
        <image href="icon/cherry.png" x="530" y="680" width="160" height="128"
               transform="rotate(-8 610 744)"/>

        <!-- Tiêu đề -->
        <text x="455" y="310" class="txt-ql">Quản Lý</text>
        <text x="445" y="555" class="txt-ns">NHÂN SỰ</text>
    </svg>

    <!-- CARD đăng nhập -->
    <div class="login-card">
        <!-- nenloginn.png ở kích thước gốc 923×664 -->
        <img src="icon/nenloginn.png" class="card-img" alt="">

        <!-- Form: chỉ inputs + button, KHÔNG có title (đã baked trong ảnh) -->
        <form action="login.php" method="post">

            <div class="login-field">
                <input type="text" name="txtUsername"
                       placeholder="USER NAME"
                       value="<?= isset($_REQUEST['txtUsername']) ? htmlspecialchars($_REQUEST['txtUsername']) : '' ?>"
                       required>
            </div>

            <div class="login-field">
                <input type="password" name="txtPassword"
                       placeholder="PASSWORD" required>
            </div>

            <!-- login2.png (300×85) làm nút submit -->
            <button type="submit" name="btnLogin" class="btn-login">ĐĂNG NHẬP</button>

            <!-- Link quay lại trang chủ -->
            <a href="index.php" class="back-home">Quay lại trang chủ</a>

        </form>

        <!-- Cảnh báo: nằm bên dưới khung card -->
        <?php if ($msg != ""): ?>
        <p class="error-msg">⚠ <?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>
    </div>

    <!-- icon2 (con trỏ) — góc dưới-phải card -->
    <img src="icon/icon2.png" alt=""
         class="deco"
         style="left:1840px; top:740px; width:80px;">

</div>
</div>

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