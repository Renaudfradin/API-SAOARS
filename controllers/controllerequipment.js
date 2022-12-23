const knex = require('../knexlogdb.js');
const NodeCache = require("node-cache");
const cache = new NodeCache({ stdTTL:100000 });

//get all equipement
exports.getEquipment = async (req,res,next) => {
  let equipments = [];
  let CountEquipments = {};

  if (cache.has("equipments")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succesful / OK',
      countEquipments: cache.get("countequipments"),
      equipments: cache.get("equipments")
    });
  } else {
    try {
      equipments = await knex.select().from('equipment').orderBy('id','desc');
      CountEquipments = await knex.select().from('equipment').count();
      CountEquipments = CountEquipments[0].count;

      cache.set("equipments",equipments);
      cache.set("countequipments",CountEquipments);
    } catch (error) {
      return res.status(404).json({
        statusCode: 404,
        message: 'Bad request',
        errors:[{
          message: 'failed to query database'
        }],   
      });
    }
    return res.status(200).json({
      statusCode: 200,
      message: 'succesful / OK',
      countEquipments: CountEquipments,
      equipments: equipments
    });
  }
}

//get on equipement for id
exports.getEquipmentId = async (req,res,next) => {
  let equipment = [];
  if (cache.has("equipment")) {
    return res.status(200).json({
      statusCode: 200,
      message: 'succcesful / OK',
      equipment: cache.get("equipment"),
    });
  } else {
    try {
      equipment = await knex.select('*').from('equipment').where({ id: req.params.id });
      cache.set("equipment",equipment);
    } catch (error) {
      return res.status(404).json({
        statusCode: 404,
        message: 'Bad Request',
        errors:[{
          message:'failed to query database',
        }],
      });
    }
    if (equipment.length == 0) {
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
        equipment: equipment,
      });
    }
  }
}