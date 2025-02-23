const express = require('express');
const router = express.Router();
const authController = require('../controllers/authController');

// Render the login page
router.get('/login', authController.renderLogin);

// Process login
router.post('/login', authController.login);

// Logout route
router.get('/logout', authController.logout);

module.exports = router;

