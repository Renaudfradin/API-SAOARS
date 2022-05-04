const knex = require('../knexlogdb.js');

exports.getWeapons = async (req,res,next) => {
    let weapons = [];
    try {
        weapons = await knex.select().from('weapon').orderBy('idw','desc');
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
        Weapons: weapons
    });
}

exports.getWeaponsId = async (req,res,next) => {
    let weapon = [];
    try {
        weapon = await knex.select().from('weapon').where({ idw: req.params.idw }).leftJoin('characters', 'weapon.signature-weapon', 'characters.id');
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
            Weapon: weapon
        });
    }
}