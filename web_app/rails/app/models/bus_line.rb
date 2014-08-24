class BusLine < ActiveRecord::Base
  attr_accessible :description, :status, :title

  has_many :stops ,:dependent => :delete_all
end
