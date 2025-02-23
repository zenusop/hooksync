// Controller for dashboard logic

exports.showDashboard = (req, res) => {
  // Here you might fetch data to display on the dashboard.
  res.render('dashboard', { user: req.session.user });
};

