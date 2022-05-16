const express = require("express");
const router = express.Router();
const controller = require('../controllers/controllerability.js');

router.get('/', controller.getAbility);

router.get('/:id', controller.getAbilityId);

module.exports = router;