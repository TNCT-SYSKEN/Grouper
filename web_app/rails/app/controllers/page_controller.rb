class PageController < ApplicationController
  layout 'otoku'
  require 'pp'
  def index
  end
  def push
    pp params 
    registration_id = "APA91bGuUlHkxeVT5fcGp3OBUkCy8I2gXo-4SQTrwcnpD3FjCAoYNRrurVS54aFJCAqGYgWCA_gheTESrDj6Bhv6EwGigY3mY5UExUNBv2NM9RicRXBOVJCJFX0wJUd_jJTeBDTaj-MQFJ8Fts27_5BH8WI5na5hGQ"

    destination = [registration_id]
    pp GCM.key
    data = { message: "hello world" }
    pp destination

    GCM.send_notification( destination, data )
    pp params
  end
  def push2
    pp params 
    registration_id = "APA91bEYC9pR7UIXISiUARsRY8DaQPT7FVuBtvp9-EIsB7eMqX1VdQIpSCTMLpBcG1tyyFk3WNPzlaso5HEXCAu9G2IEUvubFBM-as9c8pwuCsm3aQGFrYhOiU2x0452pXjF5RIhFf5qvRI20pyYv0Yq37-6CHxB-w"
    destination = [registration_id]
    pp GCM.key
    data = { message: "hello world" }
    pp destination

    GCM.send_notification( destination, data )
    pp params
  end

end
