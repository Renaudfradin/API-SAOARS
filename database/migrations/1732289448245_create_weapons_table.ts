import { BaseSchema } from '@adonisjs/lucid/schema'

export default class extends BaseSchema {
  protected tableName = 'weapons'

  async up() {
    this.schema.createTable(this.tableName, (table) => {
      table.increments('id')
      table.string('name')
      table.string('type')
      table.integer('element_weapons')
      table.integer('hp')
      table.integer('mp')
      table.integer('atk')
      table.integer('matk')
      table.integer('def')
      table.integer('mdef')
      table.integer('crit')
      table.integer('spd')
      table.text('effect_1').nullable()
      table.text('effect_2').nullable()
      table.text('effect_3').nullable()
      table.integer('characters_id').nullable()
      table.integer('start')
      table.timestamp('created_at')
      table.timestamp('updated_at')
    })
  }

  async down() {
    this.schema.dropTable(this.tableName)
  }
}
