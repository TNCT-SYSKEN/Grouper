require 'test_helper'

class OtokusControllerTest < ActionController::TestCase
  setup do
    @otoku = otokus(:one)
  end

  test "should get index" do
    get :index
    assert_response :success
    assert_not_nil assigns(:otokus)
  end

  test "should get new" do
    get :new
    assert_response :success
  end

  test "should create otoku" do
    assert_difference('Otoku.count') do
      post :create, otoku: { name: @otoku.name, period: @otoku.period, place: @otoku.place, x: @otoku.x, y: @otoku.y }
    end

    assert_redirected_to otoku_path(assigns(:otoku))
  end

  test "should show otoku" do
    get :show, id: @otoku
    assert_response :success
  end

  test "should get edit" do
    get :edit, id: @otoku
    assert_response :success
  end

  test "should update otoku" do
    put :update, id: @otoku, otoku: { name: @otoku.name, period: @otoku.period, place: @otoku.place, x: @otoku.x, y: @otoku.y }
    assert_redirected_to otoku_path(assigns(:otoku))
  end

  test "should destroy otoku" do
    assert_difference('Otoku.count', -1) do
      delete :destroy, id: @otoku
    end

    assert_redirected_to otokus_path
  end
end
