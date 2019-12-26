Feature: User modification
  In order to modify a user
  As a pandora console administrator
  I need to be able to successfully modify it

  Background: 
    Given I am a pandora console administrator
    And I am in the user creation page
    And the tusuario entity has values '{"id_user":1234,"fullname":"Tu madre","password":"1da7ee7d45b96d0e1f45ee4ee23da560"}'
    And the tusuario_perfil entity has values '{"id_up":null,"id_usuario":1234,"id_perfil":5,"assigned_by":"Codeception","tags":""}'
    Scenario:
        When I am in the user detail editor page of id 1234
        And I fill fullname field with the value Rupert
        And I should be able to click submit-uptbutton
        Then I should see the text User information successfully updated
