class Users::RegistrationsController < Devise::RegistrationsController
  def create
    super
    user = current_user
    current_user.regid = params[:regId]
    current_user.save!
  end

  def update
    super
    user = current_user
    current_user.regid = params[:regId]
    current_user.save!
  end
end
