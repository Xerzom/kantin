<?php 
include 'db.php';
include 'header.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proses pemesanan
    $items = $_POST['items'];
    $quantities = $_POST['quantities'];
    $total = 0;
    
    // Hitung total
    foreach ($items as $index => $item_id) {
        $query = "SELECT harga FROM menu WHERE id = $item_id";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $total += $row['harga'] * $quantities[$index];
        
        // Kurangi stok
        $update_query = "UPDATE menu SET stok = stok - $quantities[$index] WHERE id = $item_id";
        mysqli_query($conn, $update_query);
    }
}
?>

<section class="order-section py-5">
    <div class="container">
        <h2 class="text-center mb-5">Cara Memesan</h2>
        
        <?php if(isset($total)): ?>
        <div class="order-summary mb-5">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="card-title">Pesanan Anda</h4>
                    <p class="card-text">Total Pembayaran: <strong>Rp <?= number_format($total, 0, ',', '.'); ?></strong></p>
                    <div id="qrcode" class="my-3"></div>
                    <p>Silahkan scan QR code di atas untuk melakukan pembayaran</p>
                    <a href="menu.php" class="btn btn-primary">Pesan Lagi</a>
                </div>
            </div>
        </div>
        <?php else: ?>
        <form action="order.php" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Kantin Ibu Rika</h5>
                        </div>
                        <div class="card-body">
                            <?php
                            $query = "SELECT * FROM menu WHERE kantin_id = 1";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)): ?>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="items[]" 
                                    value="<?= $row['id']; ?>" id="item<?= $row['id']; ?>">
                                <label class="form-check-label d-flex justify-content-between" for="item<?= $row['id']; ?>">
                                    <span><?= $row['nama']; ?> (Stok: <?= $row['stok']; ?>)</span>
                                    <span>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></span>
                                </label>
                                <input type="number" name="quantities[]" class="form-control mt-1" 
                                    min="1" max="<?= $row['stok']; ?>" value="1" style="width: 80px;">
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Kantin Batagor Mas Riki</h5>
                        </div>
                        <div class="card-body">
                            <?php
                            $query = "SELECT * FROM menu WHERE kantin_id = 2";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)): ?>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="items[]" 
                                    value="<?= $row['id']; ?>" id="item<?= $row['id']; ?>">
                                <label class="form-check-label d-flex justify-content-between" for="item<?= $row['id']; ?>">
                                    <span><?= $row['nama']; ?> (Stok: <?= $row['stok']; ?>)</span>
                                    <span>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></span>
                                </label>
                                <input type="number" name="quantities[]" class="form-control mt-1" 
                                    min="1" max="<?= $row['stok']; ?>" value="1" style="width: 80px;">
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Pesan Sekarang</button>
                    </div>
                </div>
            </div>
        </form>
        <?php endif; ?>
    </div>
</section>

<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script>
    <?php if(isset($total)): ?>
    new QRCode(document.getElementById("qrcode"), {
        text: "Pembayaran: Rp <?= number_format($total, 0, ',', '.'); ?>",
        width: 200,
        height: 200
    });
    <?php endif; ?>
</script>

<?php include 'footer.php'; ?>