import { DateTime } from 'luxon/src/datetime.js'
import { BaseModel, belongsTo, column, hasOne } from '@adonisjs/lucid/orm'
import type { BelongsTo, HasOne } from '@adonisjs/lucid/types/relations'
import Weapon from './weapon.js'
import Attack from './attack.js'
import Banner from './banner.js'
export default class Character extends BaseModel {
  @column({ isPrimary: true })
  declare id: number

  @belongsTo(() => Banner)
  declare banner: BelongsTo<typeof Banner>

  @column()
  declare name: string

  @column()
  declare description: string

  @column()
  declare type: number

  @hasOne(() => Weapon)
  declare weapon: HasOne<typeof Weapon>

  @hasOne(() => Attack)
  declare atk1: HasOne<typeof Attack>

  @hasOne(() => Attack)
  declare atk2: HasOne<typeof Attack>

  @hasOne(() => Attack)
  declare atk3: HasOne<typeof Attack>

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

  @hasOne(() => Attack)
  declare enhance: HasOne<typeof Attack>

  @hasOne(() => Attack)
  declare enhance_atk: HasOne<typeof Attack>

  @hasOne(() => Attack)
  declare enhance_atk2: HasOne<typeof Attack>

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
