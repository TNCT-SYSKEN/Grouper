class Group < ActiveRecord::Base
  belongs_to :user
  has_many :members, :dependent => :destroy
  has_many :talks, :dependent => :destroy

  validates_presence_of :name, message: "グループ名は必須です"
end
