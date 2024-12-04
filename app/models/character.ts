import { DateTime } from 'luxon'
import { BaseModel, column } from '@adonisjs/lucid/orm'

export default class Character extends BaseModel {
  @column({ isPrimary: true })
  declare id: number

  @column()
  declare name: string

  @column()
  declare description: string

  @column()
  declare type: number

  @column()
  declare weaponType: number

  @column()
  declare atk1: number

  @column()
  declare atk2: number

  @column()
  declare atk3: number

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
  declare ultime: string

  @column()
  declare ultimeDescription: string

  @column()
  declare enhance: number

  @column()
  declare enhanceAtk: number

  @column()
  declare enhanceATk2: number

  @column()
  declare start: number

  @column()
  declare cost: number

  @column()
  declare image: string

  @column.dateTime({ autoCreate: true })
  declare createdAt: DateTime

  @column.dateTime({ autoCreate: true, autoUpdate: true })
  declare updatedAt: DateTime
}