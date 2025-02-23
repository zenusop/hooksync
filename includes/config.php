<?php
// config.php

// For security, you can store these in Render's environment variables, 
// but here's a minimal example:
define('ADMIN_USERNAME', 'admin');

// This is a bcrypt hash for the password "secret"
define('ADMIN_HASH', '$2y$10$W0v6x7r5YG1zV31ZihLNaOh2qC6flW5IVDI5X26Eux9q1D3Z2//Ei'); 
// Generate your own hash with password_hash('secret', PASSWORD_DEFAULT);
