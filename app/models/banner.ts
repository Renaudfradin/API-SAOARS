import { DateTime } from 'luxon/src/datetime.js'
import { BaseModel, column, hasMany } from '@adonisjs/lucid/orm'
import Character from './character.js'
import type { HasMany } from '@adonisjs/lucid/types/relations'

export default class Banner extends BaseModel {
  @column({ isPrimary: true })
  declare id: number

  @column()
  declare name: string

  @column()
  declare from: string

  @column()
  declare to: string

  @hasMany(() => Character)
  declare characters: HasMany<typeof Character>

  @column()
  declare img: string

  @column.dateTime({ autoCreate: true })
  declare createdAt: DateTime

  @column.dateTime({ autoCreate: true, autoUpdate: true })
  declare updatedAt: DateTime
}
