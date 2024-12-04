import { BaseSchema } from '@adonisjs/lucid/schema'

export default class extends BaseSchema {
  protected tableName = 'attacks'

  async up() {
    this.schema.createTable(this.tableName, (table) => {
      table.increments('id')
      table.string('name')
      table.text('description')
      table.integer('mp_cost')
      table.enu('type', ['A', 'B', 'C', 'H', 'D', 'E', 'EM'], {
        useNative: true,
        enumName: 'element',
        existingType: false,
      })
      table.timestamp('created_at')
      table.timestamp('updated_at')
    })
  }

  async down() {
    this.schema.dropTable(this.tableName)
  }
}
