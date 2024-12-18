import { DateTime } from 'luxon/src/datetime.js'
import { BaseModel, column } from '@adonisjs/lucid/orm'

export default class Equipment extends BaseModel {
  @column({ isPrimary: true })
  declare id: number

  @column()
  declare name: string

  @column()
  declare email: string

  @column()
  declare type: number

  @column()
  declare type_equipment: number

  @column()
  declare hp: number

  @column()
  declare mp: number

  @column()
  declare atk: number

  @column()
  declare matk: number

  @column()
  declare def: number

  @column()
  declare mdef: number

  @column()
  declare crit: number

  @column()
  declare spd: number

  @column()
  declare effect_1: string

  @column()
  declare effect_2: string

  @column()
  declare start: number

  @column.dateTime({ autoCreate: true })
  declare createdAt: DateTime

  @column.dateTime({ autoCreate: true, autoUpdate: true })
  declare updatedAt: DateTime
}
