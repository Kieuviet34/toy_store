<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    // Add your password reset logic here
    // For example, send a reset link to the user's email
    echo "Liên kết reset password đã được gửi vào email của bạn.";
}
?>

<div class="rp-container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mt-5">Forgot Password</h2>
                <form method="POST" action="" class="mt-4">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                        <br>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                </form>
            </div>
        </div>
</div>