<?php

?>
<h2 class="text-center">Reset Password</h2>
<form method="POST" action="" class="w-50 mx-auto">
    <div class="form-group">
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
    </div>
    <br>
    <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
</form>