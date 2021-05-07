const express = require("express");
const router = express.Router();
const controlleur = require('../controllers/controllersperso.js');
const auth = require('../auth.js');

//delete data
router.delete('/delete/:id',auth, controlleur.deleteperso);
  
//get full data
router.get('/', controlleur.getperso);
  
//get one data for id 
router.get('/:id', controlleur.getoneperso);

//get one data for name
router.get('/p/:names', controlleur.getonepersoname);

//create data
router.post('/insert',auth, controlleur.createperso);

//update data
router.put('/update/:id',auth, controlleur.updateperso);




module.exports = router;