<h3>Token successfully refreshed!</h3>
<p>Your access token was successfully refreshed and will stay valid for yet another <?php echo $minutes; ?> minutes.</p>
<p>You will soon be redirected back to were you came from.</p>
<?php if ($redirect_uri): ?>
<script>
    window.onload = function () {
        setTimeout(function () {
            window.location = '<?php echo $redirect_uri; ?>';
        }, 5000);
    };
</script>
<?php endif; ?>