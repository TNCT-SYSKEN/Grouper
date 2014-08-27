module ApplicationHelper
  def user_email(userID)
    user = User.find(userID)
    user.email
  end
end
