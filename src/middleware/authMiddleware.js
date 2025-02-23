// Middleware to check if user is authenticated
module.exports = function(req, res, next) {
  if (req.session && req.session.user) {
    return next();
  } else {
    res.redirect('/auth/login');
  }
};

