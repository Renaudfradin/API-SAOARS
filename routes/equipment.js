const express = require("express");
const router = express.Router();
const controlleur = require('../controllers/controllerequipment.js');

router.get('/', controlleur.getEquipment);

router.get('/:id', controlleur.getEquipmentId);

module.exports = router;
