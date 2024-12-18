import { DateTime } from 'luxon/src/datetime.js'
import { BaseModel, column } from '@adonisjs/lucid/orm'

export default class Ability extends BaseModel {
  @column({ isPrimary: true })
  declare id: number

  @column()
  declare name: string

  @column()
  declare descripton: string

  @column()
  declare type: string

  @column()
  declare start: string

  @column.dateTime({ autoCreate: true })
  declare createdAt: DateTime

  @column.dateTime({ autoCreate: true, autoUpdate: true })
  declare updatedAt: DateTime
}
