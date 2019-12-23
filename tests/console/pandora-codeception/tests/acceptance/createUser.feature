Feature: createUser
  In order to create a user
  As a pandora console administrator
  I need to be able to successfully create a user

  Scenario: create a regular user
    Given I am a pandora console administrator
    And I am in the user creation page
    When I submit the form with valid data
    Then I should see a successful message
    And I should see the user in the user list page




