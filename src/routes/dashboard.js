const express = require('express');
const router = express.Router();
const dashboardController = require('../controllers/dashboardController');
const authMiddleware = require('../middleware/authMiddleware');

// Ensure the user is authenticated before accessing the dashboard
router.get('/', authMiddleware, dashboardController.showDashboard);

module.exports = router;

