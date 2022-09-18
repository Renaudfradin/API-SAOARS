const knex = require('../knexlogdb.js');
// const NodeCache = require('node-cache');
// const cache = new NodeCache({ stdTTL:5 });

exports.getImaginations = async (req, res, next) => {
    let Imaginations = {};
    try {
        Imaginations = await knex.select().from('imagination').orderBy('idconst','desc');
        console.log(Imaginations);
    } catch (error) {
    return res.status(400).json({
        statusCode: 400,
        message: 'Bad Request',
        errors:[{
        message:'failed to query database',
        }],
    });
    }
        return res.status(200).json({
        statusCode: 200,
        message: 'succcesful / OK',
        imaginations: Imaginations,
    });
}