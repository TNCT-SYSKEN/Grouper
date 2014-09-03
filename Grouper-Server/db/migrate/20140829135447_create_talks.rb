class CreateTalks < ActiveRecord::Migration
  def change
    create_table :talks do |t|
      t.integer :group_id
      t.integer :user_id
      t.string :talk
      t.binary :media
      t.string :content_type

      t.timestamps
    end
  end
end
