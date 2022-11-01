const knex = require('../knexlogdb.js');
const NodeCache = require("node-cache");
const cache = new NodeCache({ stdTTL:5 });

//get full characters
exports.getCharacters = async (req, res, next) => {
  let Characters = [];
  let CountCharacters = {};

  if (cache.has("characters") && cache.has("count")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      countCharacters: cache.get("count"),
      characters: cache.get("characters"),
    });
  } else {
    try {
      Characters = await knex.select().from('characters').orderBy('id','desc');
      CountCharacters = await knex.select().from('characters').count();
      CountCharacters = CountCharacters[0].count;
      cache.set("characters",Characters);
      cache.set("count",CountCharacters)
    } catch (error) {
      return res.status(404).json({
        statusCode: 404,
        message: 'Bad Request',
        errors:[{
          message:'failed to query database / 404',
        }],
      });
    }
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      countCharacters: CountCharacters,
      characters: Characters,
    });

  }
}

//get one character for id
exports.getOneCharacter = async (req,res,next) => {
  let OneCharacter = [];
  if (cache.has("OneCharacter")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      character: cache.get("OneCharacter"),
    });
  } else {
    try {
      OneCharacter = await knex.select().from('characters').where({ id: req.params.id });
      cache.set("OneCharacter",OneCharacter);
    } catch (error) {
      return res.status(404).json({
        statusCode: 404,
        message: 'Bad Request',
        errors:[{
          message:'failed to query database / 404',
        }],
      });
    }
    if (OneCharacter.length == 0) {
      return res.status(404).json({
        statusCode: 404,
        message: 'Bad Request',
        errors:[{
          message:'failed to query database / 404',
        }],
      });
    }else{
      return res.status(200).json({
        statusCode: 200,
        message: 'succcesful / OK',
        character: OneCharacter,
      });
    }
  }    
}

//get one character for name
exports.getOneCharacterName = async (req, res , next) => {
  let CharacterName = [];
  if (cache.has("CharacterName")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      characters: cache.get("CharacterName"),
    });
  } else {
    try {
      CharacterName = await knex('characters').where('name_characters','ILIKE',`%${req.params.names}%`);
      cache.set("CharacterName",CharacterName);
    } catch (error) {
      return res.status(404).json({
        statusCode: 404,
        message: 'Bad Request',
        errors:[{
          message:'failed to query database',
        }],
      });
    }
    if (CharacterName.length == 0) {
      return res.status(404).json({
        statusCode: 404,
        message: 'Bad Request',
        errors:[{
          message:'failed to query database',
        }],
      });
    }else{
      return res.status(200).json({
        statusCode: 200,
        message: 'succcesful / OK',
        characters: CharacterName,
      });
    }
  }
}

//get last character
exports.getLastCharacter = async (req, res , next) => {
  let LastCharacter = [];
  if (cache.has("LastCharacter")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      characters: cache.get("LastCharacter"),
    });
  } else {
    try {
      LastCharacter = await knex('characters').where({ id: knex('characters').max('id') });
      cache.get("LastCharacter",LastCharacter);
    } catch (error) {
      return res.status(404).json({
        statusCode: 404,
        message: 'Bad Request',
        errors:[{
          message:'failed to query database 1',
        }],
      });
    }
    return res.status(200).json({
        statusCode: 200,
        message: 'succcesful / OK',
        characters: LastCharacter,
    });
  }
}

// //delete data
// exports.deleteperso = async (req, res, next) =>{
//     try {
//       perso = await knex('characters').where('id',req.params.id).del();
//     } catch (error) {
//       return res.status(404).json({
//         statusCode: 400,
//         message: 'Bad Request',
//         errors:[{
//             message:'failed to query database',
//         }],
//       });
//     }
//     return res.status(200).json({
//       statusCode: 200,
//       message: 'succcesful / OK'
//     });
// }

// //create data
// exports.createperso = async (req, res, next)=>{
//     try {
//       perso = await knex('characters').insert({
//         id: req.body.id,
//         name: req.body.name,
//         description: req.body.description,
//         description1: req.body.description1,
//         attack1: req.body.attack1,
//         descriptionattack1: req.body.descriptionattack1,
//         mpattack1: req.body.mpattack1,
//         attack2: req.body.attack2,
//         descriptionattack2: req.body.descriptionattack2,
//         mpattack2: req.body.mpattack2,
//         attack3: req.body.attack3,
//         descriptionattack3: req.body.descriptionattack3,
//         mpattack3: req.body.mpattack3,
//         hp: req.body.hp,
//         mp: req.body.mp,
//         atk: req.body.atk,
//         matk: req.body.matk,
//         def: req.body.def,
//         mdef: req.body.mdef,
//         crit: req.body.crit,
//         spd: req.body.spd,
//         ultime: req.body.ultime,
//         descriptionultime: req.body.descriptionultime,
//         typeattack1: req.body.typeattack1,
//         typeattack2: req.body.typeattack2,
//         typeattack3: req.body.typeattack3
//       });
//     } catch (error) {
//       return res.status(400).json({
//         statusCode: 400,
//         message:error,
//       });
//     }
//     console.log(req.body);
//     return res.status(201).json({
//       statusCode: 201,
//       message:"Personege crée !!!!!!!",
//       Personage:perso
//     });
    
// }

// //update data
// exports.updateperso = async (req, res, next) => {
//     try {
//       perso = await knex('characters').where('id', req.params.id).update({
//         id: req.body.id,
//         name: req.body.name,
//         description: req.body.description,
//         description1: req.body.description1,
//         attack1: req.body.attack1,
//         descriptionattack1: req.body.descriptionattack1,
//         mpattack1: req.body.mpattack1,
//         attack2: req.body.attack2,
//         descriptionattack2: req.body.descriptionattack2,
//         mpattack2: req.body.mpattack2,
//         attack3: req.body.attack3,
//         descriptionattack3: req.body.descriptionattack3,
//         mpattack3: req.body.mpattack3,
//         hp: req.body.hp,
//         mp: req.body.mp,
//         atk: req.body.atk,
//         matk: req.body.matk,
//         def: req.body.def,
//         mdef: req.body.mdef,
//         crit: req.body.crit,
//         spd: req.body.spd,
//         ultime: req.body.ultime,
//         descriptionultime: req.body.descriptionultime,
//         typeattack1: req.body.typeattack1,
//         typeattack2: req.body.typeattack2,
//         typeattack3: req.body.typeattack3
//       });
//     } catch (error) {
//       return res.status(400).json({
//         statusCode: 400,
//         message: 'Bad Request',
//         errors:[{
//             message:'failed to query database',
//         }],
//       });
//     }
//     console.log(req.body);
//     return res.status(200).json({
//       statusCode: 200,
//       message: 'succcesful / OK'
//     });
   
// }