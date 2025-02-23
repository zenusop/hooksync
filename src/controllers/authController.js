// Controller for authentication logic

exports.renderLogin = (req, res) => {
  res.render('login', { error: null });
};

exports.login = (req, res) => {
  const { username, password } = req.body;
  // Replace with proper authentication logic (e.g., DB lookup, bcrypt, etc.)
  if (username === 'user' && password === 'pass') {
    req.session.user = { username };
    res.redirect('/dashboard');
  } else {
    res.render('login', { error: 'Invalid credentials' });
  }
};

exports.logout = (req, res) => {
  req.session.destroy(err => {
    if (err) {
      console.error(err);
    }
    res.redirect('/auth/login');
  });
};

