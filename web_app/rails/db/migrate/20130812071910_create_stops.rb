class CreateStops < ActiveRecord::Migration
  def change
    create_table :stops do |t|
      t.string :title
      t.float :x
      t.float :y
      t.integer :line_id
      t.text :times

      t.timestamps
    end
  end
end
