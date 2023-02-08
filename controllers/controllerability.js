const knex = require('../knexlogdb.js');
const NodeCache = require("node-cache");
const cache = new NodeCache({ stdTTL:10000 });
const cacheId = new NodeCache({ stdTTL:5 });

//get all Ability
exports.getAbility = async (req,res,next) => {
  let abilitys = [];
  let CountAbilitys = {};

  try {
    abilitys = await knex.select("*").from('ability').orderBy('id','desc');
    CountAbilitys = await knex.select().from('ability').count();
    CountAbilitys = CountAbilitys[0].count;
    cache.set("abilitys",abilitys,10000);
    cache.set("countAbilitys",CountAbilitys,10000);
  } catch (error) {
    return res.status(404).json({
      statusCode: 404,
      message: 'Bad request',
      errors:[{
        message: 'failed to query database'
      }],
    })
  }

  if (cache.has("abilitys") == true && cache.has("countAbilitys") == true) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succesful / OK',
      countAbilitys: cache.get("countAbilitys"),
      abilitys: cache.get("abilitys")
    });
  }
    
  return res.status(200).json({
    statusCode: 200,
    message: 'succesful / OK',
    countAbilitys: CountAbilitys,
    abilitys: abilitys
  });
}

//get One Ability for one Id 
exports.getAbilityId = async (req,res,next) => {
  let ability = [];
  
  try {
    ability = await knex.select('*').from('ability').where({ id: req.params.id });
    cacheId.set("ability",ability);
  } catch (error) {
    return res.status(404).json({
      statusCode: 404,
      message: 'Bad Request',
      errors:[{
        message:'failed to query database',
      }],
    });
  }

  if (cacheId.has("ability") == true) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      Ability: cacheId.get("ability"),
    });
  }
  
  if (ability.length == 0) {
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
    Ability: ability,
  });
}
