const knex = require("knex")({
    client :"pg",
    connection:{
        connectionString: 'postgres://peohlgeivrzwcy:68848fb1718e4c0f24417a10d755ee629f11accba8d24d311889e34fdc3c7647@ec2-54-78-127-245.eu-west-1.compute.amazonaws.com:5432/d4g9hrsgfsus2',
        ssl:{
            rejectUnauthorized: false,
        }
    },
});

module.exports = knex;