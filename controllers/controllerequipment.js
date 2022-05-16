const knex = require('../knexlogdb.js');

exports.getEquipment = async (req,res,next) => {
    let equipments = [];
    try {
        equipments = await knex.select().from('equipment').orderBy('id','desc');
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
        Equipments: equipments
    });
}

exports.getEquipmentId = async (req,res,next) => {
    let equipment = [];
    try {
      equipment = await knex('equipment').where({ id: req.params.id })
    } catch (error) {
      return res.status(400).json({
        statusCode: 400,
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
        Equipment: equipment,
      });
    }
}