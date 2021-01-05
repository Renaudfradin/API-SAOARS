const mongoose = require('mongoose');

const personagesScheme = mongoose.Schema({
    name: { type: String, required: true },
    description :{ type: String, required: true },
    description1 :{ type: String, required: true },
    attack1:{ type: String, required: true },
    descriptionattack1:{ type: String, required: true },
    mpattack1:{ type: String, required: true },
    attack2:{ type: String, required: true },
    descriptionattack2:{ type: String, required: true },
    mpattack2:{ type: String, required: true },
    attack3: { type: String, required: true },
    descriptionattack3: { type: String, required: true },
    mpattack3: { type: String, required: true },
    hp:{ type: String, required: true },
    mp:{ type: String, required: true },
    atk:{ type: String, required: true },
    matk:{ type: String, required: true },
    def:{ type: String, required: true },
    mdef:{ type: String, required: true },
    crit:{ type: String, required: true },
    spd:{ type: String, required: true }
});


module.exports = mongoose.model('Personages',personagesScheme);
