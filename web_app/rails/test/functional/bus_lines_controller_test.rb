require 'test_helper'

class BusLinesControllerTest < ActionController::TestCase
  setup do
    @bus_line = bus_lines(:one)
  end

  test "should get index" do
    get :index
    assert_response :success
    assert_not_nil assigns(:bus_lines)
  end

  test "should get new" do
    get :new
    assert_response :success
  end

  test "should create bus_line" do
    assert_difference('BusLine.count') do
      post :create, bus_line: { description: @bus_line.description, status: @bus_line.status, title: @bus_line.title }
    end

    assert_redirected_to bus_line_path(assigns(:bus_line))
  end

  test "should show bus_line" do
    get :show, id: @bus_line
    assert_response :success
  end

  test "should get edit" do
    get :edit, id: @bus_line
    assert_response :success
  end

  test "should update bus_line" do
    put :update, id: @bus_line, bus_line: { description: @bus_line.description, status: @bus_line.status, title: @bus_line.title }
    assert_redirected_to bus_line_path(assigns(:bus_line))
  end

  test "should destroy bus_line" do
    assert_difference('BusLine.count', -1) do
      delete :destroy, id: @bus_line
    end

    assert_redirected_to bus_lines_path
  end
end
