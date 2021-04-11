const express = require("express");
const router = express.Router();
const controlleur = require('../controllers/controllersperso.js');
const auth = require('../auth.js');

//delete data
router.delete('/delete/:id', controlleur.deleteperso);
  
//get full data
router.get('/',auth, controlleur.getperso);
  
//get one data for id 
router.get('/:id' , controlleur.getoneperso);

//create data
router.post('/insert', controlleur.createperso);

//update data
router.put('/update/:id', controlleur.updateperso);




module.exports = router;