<?php
$tieuchi = isset($_REQUEST['tieuchi']) ? $_REQUEST['tieuchi'] : 'IDNV';
$tukhoa  = isset($_REQUEST['tukhoa'])  ? trim($_REQUEST['tukhoa'])  : '';
?>

<div class="search-wrap">
    <p class="search-title">✦ Tìm Kiếm Nhân Viên ✦</p>

    <form action="index.php?page=timkiem" method="post">
        <div class="search-criteria">
            <label>
                <input type="radio" name="tieuchi" value="IDNV"
                    <?php if ($tieuchi == 'IDNV') echo 'checked'; ?>>
                Mã NV
            </label>
            <label>
                <input type="radio" name="tieuchi" value="Hoten"
                    <?php if ($tieuchi == 'Hoten') echo 'checked'; ?>>
                Họ Tên
            </label>
            <label>
                <input type="radio" name="tieuchi" value="Diachi"
                    <?php if ($tieuchi == 'Diachi') echo 'checked'; ?>>
                Địa Chỉ
            </label>
        </div>

        <div class="search-row">
            <input type="text" name="tukhoa"
                   placeholder="Nhập từ khóa..."
                   value="<?= htmlspecialchars($tukhoa) ?>">
            <input type="submit" name="btnSearch" value="Tìm kiếm">
        </div>
    </form>
</div>

<?php
if (isset($_REQUEST['btnSearch']) && $tukhoa != '') {
    $link = mysqli_connect('localhost', 'root', '') or die('Could not connect: ' . mysqli_connect_error());
    mysqli_select_db($link, 'DULIEU');

    $safe = mysqli_real_escape_string($link, $tukhoa);
    $rs   = mysqli_query($link, "SELECT * FROM NHANVIEN WHERE `$tieuchi` LIKE '%$safe%'");
    $num  = mysqli_num_rows($rs);
?>

<p class="search-result-title">
    <?= $num > 0
        ? "Tìm thấy $num kết quả cho &ldquo;" . htmlspecialchars($tukhoa) . "&rdquo;"
        : "Không tìm thấy kết quả nào cho &ldquo;" . htmlspecialchars($tukhoa) . "&rdquo;" ?>
</p>

<?php if ($num > 0): ?>
<table>
    <thead>
        <tr>
            <th>Mã NV</th>
            <th>Họ Tên</th>
            <th>Phòng Ban</th>
            <th>Địa Chỉ</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_array($rs, MYSQLI_BOTH)): ?>
        <tr>
            <td><?= htmlspecialchars($row['IDNV']) ?></td>
            <td><?= htmlspecialchars($row['Hoten']) ?></td>
            <td><?= htmlspecialchars($row['IDPB']) ?></td>
            <td><?= htmlspecialchars($row['Diachi']) ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php endif; ?>

<?php
    mysqli_free_result($rs);
    mysqli_close($link);
}
?>
