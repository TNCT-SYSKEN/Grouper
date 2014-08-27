class UserController < ApplicationController
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
end
