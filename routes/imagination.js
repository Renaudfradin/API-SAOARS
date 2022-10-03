const express = require("express");
const router = express.Router();
const controlleur = require('../controllers/controllerimagination.js');

router.get('/', controlleur.getImaginations);

module.exports = router;