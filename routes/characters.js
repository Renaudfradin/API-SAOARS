const express = require("express");
const router = express.Router();
const controlleur = require('../controllers/controllercharacter.js');
//const auth = require('../auth.js');

// //delete data
// router.delete('/delete/:id',auth, controlleur.deleteperso);
  
//get full perso
router.get('/', controlleur.getCharacters);
  
//get one perso for id
router.get('/:id', controlleur.getOneCharacter);

//get one perso for name
router.get('/search/:names', controlleur.getOneCharacterName);

//get last perso 
router.get('/last/character', controlleur.getLastCharacter);

// //create data
// router.post('/insert',auth, controlleur.createperso);

// //update data
// router.put('/update/:id',auth, controlleur.updateperso);


module.exports = router;