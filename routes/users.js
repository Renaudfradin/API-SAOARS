const express = require("express");
const router = express.Router();
const controlleur = require('../controllers/users.js');

//login
router.post('/login', controlleur.login);

//insert users
router.post('/signup', controlleur.signup);

module.exports = router;