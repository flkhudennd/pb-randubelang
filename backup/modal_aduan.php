<?php
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {

    $message = $_POST['message'];
    $senderName = isset($_POST['sender-name']) ? $_POST['sender-name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    if (isset($_POST['anonimCheckbox'])) {
        $senderName = "Anonim";
        $email = "anonim@example.com";
    }

    $query = "INSERT INTO complaints (name, email_sender, is_anonymous, complaint_message, created_at) VALUES ('$senderName', '$email', '" . (isset($_POST['anonimCheckbox']) ? 1 : 0) . "', '$message', NOW())";

    if ($conn->query($query) === TRUE) {
        $currentUrl = isset($_POST['currentUrl']) ? $_POST['currentUrl'] : '/portal-berita';
        header("Location: $currentUrl?success=true");
        exit;
    } else {
        $currentUrl = isset($_POST['currentUrl']) ? $_POST['currentUrl'] : '/portal-berita';
        header("Location: $currentUrl?error=true");
        exit;
    }
}
?>

<div class="modal fade" id="aduanModal" tabindex="-1" role="dialog" aria-labelledby="aduanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="aduanModalLabel">Form Aduan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <input type="hidden" name="currentUrl" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                    <div class="form-group">
                        <label for="message-text" class="col-form-label" rows="4">Pesan:</label>
                        <textarea class="form-control" id="message-text" name="message" placeholder="(Tuliskan aduan anda dengan detail, antumkan lokasi kejadian jika diperlukan)" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="sender-name" class="col-form-label">Nama Pengirim:</label>
                        <input type="text" class="form-control" id="sender-name" name="sender-name" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="anonimCheckbox" name="anonimCheckbox">
                        <label class="form-check-label" for="anonimCheckbox">
                            Kirim sebagai anonim
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Kirim Aduan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="aduan">
    <button class="btn btn-md btn-danger btn-block" data-toggle="modal" data-target="#aduanModal">
        <span>Pesan Aduan</span>
    </button>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const aduanButton = document.querySelector('.btn-danger');
        const anonimCheckbox = document.getElementById('anonimCheckbox');
        const senderNameInput = document.getElementById('sender-name');
        const senderEmailInput = document.getElementById('email');

        aduanButton.addEventListener('click', function(event) {
            event.preventDefault(); 
            $('#aduanModal').modal('show'); 
        });

        anonimCheckbox.addEventListener('change', function() {
            const isAnonim = this.checked;
            senderNameInput.disabled = isAnonim;
            senderEmailInput.disabled = isAnonim;
            senderNameInput.value = isAnonim ? '' : senderNameInput.value;
            senderEmailInput.value = isAnonim ? '' : senderEmailInput.value;
        });

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            alert('Pesan aduan berhasil dikirim!');
        } else if (urlParams.has('error')) {
            alert('Terjadi kesalahan saat mengirim aduan. Silakan coba lagi nanti.');
        }
    });
</script>
