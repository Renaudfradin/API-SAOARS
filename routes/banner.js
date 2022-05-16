const express = require("express");
const router = express.Router();
const controller = require('../controllers/controllerbaner.js');

router.get('/', controller.getBanner);

router.get('/:idb', controller.getBannerId);

module.exports = router;