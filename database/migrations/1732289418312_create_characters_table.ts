import { BaseSchema } from '@adonisjs/lucid/schema'

export default class extends BaseSchema {
  protected tableName = 'characters'

  async up() {
    this.schema.createTable(this.tableName, (table) => {
      table.increments('id')
      table.integer('banner_id')
      table.string('name')
      table.text('description')
      table.text('profile')
      table.enu('type', ['neutre', 'eau', 'feu', 'vent', 'terre', 'lumiere', 'tenebre'], {
        useNative: true,
        enumName: 'type',
        existingType: false,
      })
      table.integer('atk1')
      table.integer('atk2').nullable()
      table.integer('atk3').nullable()
      table.integer('hp')
      table.integer('mp')
      table.integer('atk')
      table.integer('matk')
      table.integer('def')
      table.integer('mdef')
      table.integer('crit')
      table.integer('spd')
      table.string('ultime')
      table.string('ultime_description')
      table.integer('enhance').nullable()
      table.integer('enhance_atk').nullable()
      table.integer('enhance_atk2').nullable()
      table.integer('start')
      table.integer('cost')
      table.text('image')
      table.timestamp('created_at')
      table.timestamp('updated_at')
    })
  }

  async down() {
    this.schema.dropTable(this.tableName)
  }
}
