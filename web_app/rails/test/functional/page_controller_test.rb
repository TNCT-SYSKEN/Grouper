require 'test_helper'

class PageControllerTest < ActionController::TestCase
  test "should get push" do
    get :push
    assert_response :success
  end

end
