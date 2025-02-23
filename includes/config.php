<?php
// config.php

// For security, you can store these in Render's environment variables, 
// but here's a minimal example:
define('ADMIN_USERNAME', 'admin');

// This is a bcrypt hash for the password "secret"
define('ADMIN_HASH', '$2a$10$QIsDDeMNjNbgRrlbvra5NeWNlRh9.G2wwMVF2Y4U5yJFWMuUTpxyi'); 
// Generate your own hash with password_hash('secret', PASSWORD_DEFAULT);
