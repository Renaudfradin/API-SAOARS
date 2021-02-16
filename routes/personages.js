const express = require("express");
const router = express.Router();
//const knex = require('../knexlogdb.js');
const controlleur = require('../controllers/controllersperso.js');

//delete data
router.delete('/delete/:id', controlleur.deleteperso);
  
//get full data
router.get('/', controlleur.getperso);
  
//get one data for id 
router.get('/:id' , controlleur.getoneperso);

//create data
//router.post('/insert', controlleur.createperso);

//update data
//router.put('/update/:id', controlleur.updateperso);


module.exports = router;