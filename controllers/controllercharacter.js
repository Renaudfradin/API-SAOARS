const knex = require('../knexlogdb.js');
const NodeCache = require("node-cache");
const cache = new NodeCache({ stdTTL:10000 });
const cacheId = new NodeCache({ stdTTL:5 });

//get full characters
exports.getCharacters = async (req, res, next) => {
  let Characters = [];
  let CountCharacters = {};
  
  try {
    Characters = await knex.select().from('characters').orderBy('id','desc');
    CountCharacters = await knex.select().from('characters').count();
    CountCharacters = CountCharacters[0].count;
    cache.set("characters",Characters,10000);
    cache.set("count",CountCharacters,10000)
  } catch (error) {
    return res.status(404).json({
      statusCode: 404,
      message: 'Bad Request',
      errors:[{
        message:'failed to query database / 404',
      }],
    });
  }

  if (cache.has("characters") == true && cache.has("count") == true) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      countCharacters: cache.get("count"),
      characters: cache.get("characters"),
    });
  }
}

//get one character for id
exports.getOneCharacter = async (req,res,next) => {
  let OneCharacter = [];

  try {
    OneCharacter = await knex.select().from('characters').where({ id: req.params.id });
    cacheId.set("OneCharacter",OneCharacter);
  } catch (error) {
    return res.status(404).json({
      statusCode: 404,
      message: 'Bad Request',
      errors:[{
        message:'failed to query database / 404',
      }],
    });
  }

  if (cacheId.has("OneCharacter")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      character: cacheId.get("OneCharacter"),
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
  }

  return res.status(200).json({
    statusCode: 200,
    message: 'succcesful / OK',
    character: OneCharacter,
  }); 
}

//get one character for name
exports.getOneCharacterName = async (req, res , next) => {
  let CharacterName = [];

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

  if (cache.has("CharacterName")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      characters: cache.get("CharacterName"),
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
  }

  return res.status(200).json({
    statusCode: 200,
    message: 'succcesful / OK',
    characters: CharacterName,
  });
}

//get last character
exports.getLastCharacter = async (req, res , next) => {
  let LastCharacter = [];

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

  if (cache.has("LastCharacter")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      characters: cache.get("LastCharacter"),
    });
  }

  return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      characters: LastCharacter,
  });
}
