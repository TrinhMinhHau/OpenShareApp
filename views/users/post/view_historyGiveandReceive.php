<?php include('../layout/header.php'); ?>
<div class="Menu" style="text-align:center; position:fixed">
    <a <?php if (isset($_GET['thamso']) and $_GET['thamso'] == '../history/view_historyGive.php') echo "class='active2'" ?>href="?thamso=../history/view_historyGive.php">Đã cho đi</a>
    <a <?php if (isset($_GET['thamso']) and $_GET['thamso'] == '../history/view_historyReceive.php') echo "class='active2'" ?> href="?thamso=../history/view_historyReceive.php">Đã nhận lại</a>
</div>
<div class="noidung">
    <?php if (isset($_GET['thamso'])) {
        include($_GET['thamso']);
    } else {
        include('../history/view_historyGive.php');
    }
    ?>

</div>

<?php include('../layout/footer.php'); ?>