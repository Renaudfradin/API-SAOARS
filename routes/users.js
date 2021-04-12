const express = require("express");
const router = express.Router();
const controlleur = require('../controllers/users.js');
const auth = require('../auth.js');

//login
router.post('/login', controlleur.login);

//insert users
router.post('/signup',auth, controlleur.signup);

//get all users
router.get('/getusers',auth, controlleur.getuser);

module.exports = router;