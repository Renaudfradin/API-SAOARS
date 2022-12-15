require('dotenv').config()
const knex = require("knex")({
  client : "pg",
  connection:{
    connectionString: process.env.DATABASE_URL_PROD,
    ssl:{
      rejectUnauthorized: false,
    }
  },
});
module.exports = knex;