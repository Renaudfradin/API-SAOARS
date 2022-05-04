const express = require("express");
const router = express.Router();
const controller = require("../controllers/controllerweapon.js");

router.get('/', controller.getWeapons);

router.get('/:idw' , controller.getWeaponsId);

module.exports = router;