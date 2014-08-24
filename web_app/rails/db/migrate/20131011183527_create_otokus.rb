class CreateOtokus < ActiveRecord::Migration
  def change
    create_table :otokus do |t|
      t.string :place
      t.string :name
      t.datetime :period
      t.float :x
      t.float :y

      t.timestamps
    end
  end
end
