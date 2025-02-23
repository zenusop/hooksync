const express = require('express');
const router = express.Router();
const axios = require('axios');

// Middleware to ensure the user is logged in (example)
const authMiddleware = require('../middleware/authMiddleware');

// GET /dashboard
router.get('/', authMiddleware, (req, res) => {
  res.render('dashboard'); // Renders dashboard.ejs
});

// GET /api/weather
router.get('/api/weather', authMiddleware, async (req, res) => {
  try {
    // Example with OpenWeatherMap
    // Replace 'YOUR_API_KEY' with your real key
    // and possibly get location from user or a default city
    const city = 'London';
    const apiKey = process.env.OPENWEATHER_API_KEY || 'YOUR_API_KEY';
    const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&appid=${apiKey}`;
    
    const response = await axios.get(url);
    return res.json(response.data);
  } catch (err) {
    console.error(err);
    return res.json({ error: 'Unable to fetch weather data.' });
  }
});

module.exports = router;
