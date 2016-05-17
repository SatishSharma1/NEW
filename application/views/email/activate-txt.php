Welcome to <?php echo $siteName; ?>,

Thanks for joining <?php echo $siteName; ?>. We listed your sign in details below, make sure you keep them safe.
To verify your email address, please follow this link:

<?php echo site_url('/user/activate/'.$userId.'/'.$activationKey); ?>


Please verify your email within <?php echo $activationPeriod; ?> hours, otherwise your registration will become invalid and you will have to register again.
<?php if (strlen($username) > 0) { ?>

Your username: <?php echo $username; ?>
<?php } ?>

Your email address: <?php echo $userEmail; ?>
<?php if (isset($password)) { /* ?>

Your password: <?php echo $password; ?>
<?php */ } ?>



Have fun!
The <?php echo $siteName; ?> Team