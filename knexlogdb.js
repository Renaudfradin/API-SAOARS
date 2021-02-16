const knex = require("knex")({
    client :"pg",
    connection:{
        connectionString: '###',
        ssl:{
            rejectUnauthorized: false,
        }
    },
});

module.exports = knex;