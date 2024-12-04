import { DateTime } from 'luxon/src/datetime.js'
import { BaseModel, column } from '@adonisjs/lucid/orm'

export default class Weapon extends BaseModel {
  @column({ isPrimary: true })
  declare id: number

  @column()
  declare name: string

  @column()
  declare type: string

  @column()
  declare element: string

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
  declare effect_1: string | null

  @column()
  declare effect_2: string | null

  @column()
  declare effect_3: string | null

  @column()
  declare characters_id: number

  @column()
  declare start: number

  @column.dateTime({ autoCreate: true })
  declare createdAt: DateTime

  @column.dateTime({ autoCreate: true, autoUpdate: true })
  declare updatedAt: DateTime
}
