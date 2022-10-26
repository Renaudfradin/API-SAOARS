const knex = require('../knexlogdb.js');
const NodeCache = require("node-cache");
const cache = new NodeCache({ stdTTL: 5});

//get full weapons
exports.getWeapons = async (req,res,next) => {
  let weapons = [];
  let Countweapons = {};
  
  if (cache.has("weapons") && cache.has("countweapons")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succesful / OK',
      countWeapons: cache.get("countweapons"),
      weapons: cache.get("weapons")
    });
  } else {
    try {
      weapons = await knex.select('*').from('weapon').orderBy('idw','desc');
      Countweapons = await knex.select().from('weapon').count();
      Countweapons = Countweapons[0].count;
      cache.set("weapons",weapons);
      cache.set("countweapons",Countweapons);
    } catch (error) {
      return res.status(400).json({
        statusCode: 400,
        message: 'Bad request',
        errors:[{
          message: 'failed to query database'
        }],   
      });
    }
    return res.status(200).json({
      statusCode: 200,
      message: 'succesful / OK',
      countWeapons: Countweapons,
      weapons: weapons
    });
  }
}

//get one weapon for id and get character info
exports.getWeaponsId = async (req,res,next) => {
  let weapon = [];
  if (cache.has("weapon")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succesful / OK',
      weapon: cache.get("weapon")
    });
  } else {
    try {
      weapon = await knex.select().from('weapon').where({ idw: req.params.idw }).leftJoin('characters', 'weapon.signature_weapon', 'characters.id');

      weapon = {
        idw : weapon[0].idw,
        name_weapon : weapon[0].name_weapon,
        weapon_type_w : weapon[0].weapon_type_w,
        weapon_element_w : weapon[0].weapon_element_w,
        hp_w: weapon[0].hp_w,
        mp_w: weapon[0].mp_w,
        atk_w: weapon[0].atk_w,
        matk_w: weapon[0].matk_w,
        def_w: weapon[0].def_w,
        mdef_w: weapon[0].mdef_w,
        crit_w: weapon[0].crit_w,
        spd_w: weapon[0].spd_w,
        effect_1: weapon[0].effect_1,
        effect_2: weapon[0].effect_2,
        effect_3: weapon[0].effect_3,
        signature_weapon: weapon[0].signature_weapon,
        stars: weapon[0].stars,
        id: weapon[0].id,
        description: weapon[0].description,
        weapon_type: weapon[0].weapon_type,
        character_type: weapon[0].character_type,
        img: weapon[0].img,
      };
      
      cache.set("weapon",weapon)
    } catch (error) {
      return res.status(400).json({
        statusCode: 400,
        message: 'Bad request',
        errors:[{
          message: 'failed to query database'
        }],   
      });
    }
    if (weapon.length == 0) {
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
        message: 'succesful / OK',
        weapon: weapon
      });
    }
  }
}