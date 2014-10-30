module ApplicationHelper
  def user_name(userID)
    user = User.find(userID)
    user.username
  end
end
