class UserController < ApplicationController
  protect_from_forgery except: :new_image
  def find_group
    if request.post? 
      groupID = params[:groupID]
      if Group.exists?(id: groupID)
        @result = true
        redirect_to add_member_group_path(groupID)
      else
        @result = false
        flash.now[:notice] = "そのグループは存在しません"
      end
    end
  end

  def new_image
    image = params[:upload]
    File.open('./public/img/user/' + current_user.id.to_s, 'wb') do |of|
      of.write(image[:file].read)
    end
    redirect_to edit_user_registration_path(current_user)
  end
end
