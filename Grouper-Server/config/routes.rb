Rails.application.routes.draw do

  resources :groups do
    resources :members
    resources :alarms do
      member do
        post 'set'
      end
    end
    resources :boards do
      collection do
        get 'edit_index'
      end
    end

    member do
      get 'add_member'
      post 'add_member'
      get 'talk/index'
      post 'talk/index'
      post 'talk/new'
      post 'talk/new_image'
      post 'talk/new_address'
      post 'new_image'
    end
    collection do
      get 'talk_index'
      get 'board_index'
      get 'alarm_index'
      post 'talk/get_address'
    end
  end

  get 'user/find_group'
  post 'user/find_group'
  post 'user/new_image' => 'user#new_image'

  devise_for :users, :controllers => { 
    :sessions => "users/sessions",
    :registrations => 'users/registrations'
  }
  # The priority is based upon order of creation: first created -> highest priority.
  # See how all your routes lay out with "rake routes".

  # You can have the root of your site routed with "root"
  # root 'welcome#index'
  root 'user#index'

  # Example of regular route:
  #   get 'products/:id' => 'catalog#view'

  # Example of named route that can be invoked with purchase_url(id: product.id)
  #   get 'products/:id/purchase' => 'catalog#purchase', as: :purchase

  # Example resource route (maps HTTP verbs to controller actions automatically):
  #   resources :products

  # Example resource route with options:
  #   resources :products do
  #     member do
  #       get 'short'
  #       post 'toggle'
  #     end
  #
  #     collection do
  #       get 'sold'
  #     end
  #   end

  # Example resource route with sub-resources:
  #   resources :products do
  #     resources :comments, :sales
  #     resource :seller
  #   end

  # Example resource route with more complex sub-resources:
  #   resources :products do
  #     resources :comments
  #     resources :sales do
  #       get 'recent', on: :collection
  #     end
  #   end

  # Example resource route with concerns:
  #   concern :toggleable do
  #     post 'toggle'
  #   end
  #   resources :posts, concerns: :toggleable
  #   resources :photos, concerns: :toggleable

  # Example resource route within a namespace:
  #   namespace :admin do
  #     # Directs /admin/products/* to Admin::ProductsController
  #     # (app/controllers/admin/products_controller.rb)
  #     resources :products
  #   end
end
