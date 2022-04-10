const express = require("express");
const router = express.Router();
const controlleur = require('../controllers/controllersperso.js');
//const auth = require('../auth.js');

// //delete data
// router.delete('/delete/:id',auth, controlleur.deleteperso);
  
//get full data
router.get('/', controlleur.getCharacters);
  
//get one data for id
router.get('/:id', controlleur.getOneCharacter);

//get one data for name
router.get('/search/:names', controlleur.getOneCharacterName);

//get last perso ppost
router.get('/last', controlleur.getLastCharacter);

// //create data
// router.post('/insert',auth, controlleur.createperso);

// //update data
// router.put('/update/:id',auth, controlleur.updateperso);


module.exports = router;