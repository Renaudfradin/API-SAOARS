import { BaseSchema } from '@adonisjs/lucid/schema'

export default class extends BaseSchema {
  protected tableName = 'equipment'

  async up() {
    this.schema.createTable(this.tableName, (table) => {
      table.increments('id')
      table.string('name')
      table.enu('type', ['neutre', 'eau', 'feu', 'vent', 'terre', 'lumiere', 'tenebre'], {
        useNative: true,
        enumName: 'type',
        existingType: false,
      })
      table.enu('type_equipment', ['armor', 'accessory'], {
        useNative: true,
        enumName: 'type_equipment',
        existingType: false,
      })
      table.integer('hp')
      table.integer('mp')
      table.integer('atk')
      table.integer('matk')
      table.integer('def')
      table.integer('mdef')
      table.integer('crit')
      table.integer('spd')
      table.text('effect_1')
      table.text('effect_2')
      table.integer('start')
      table.timestamp('created_at')
      table.timestamp('updated_at')
    })
  }

  async down() {
    this.schema.dropTable(this.tableName)
  }
}
