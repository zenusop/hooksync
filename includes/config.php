<?php
// config.php

// For security, you can store these in Render's environment variables, 
// but here's a minimal example:
define('ADMIN_USERNAME', 'admin');

// This is a bcrypt hash for the password "secret"
define('ADMIN_HASH', '$2y$10$abcdefg...'); 
// Generate your own hash with password_hash('secret', PASSWORD_DEFAULT);
