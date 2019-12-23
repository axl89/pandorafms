Feature: User creation
  In order to create a user
  As a pandora console administrator
  I need to be able to successfully create a user

  Background: 
    Given I am a pandora console administrator

  Scenario: create a regular user
    And I am in the user creation page
	When I fill id_user field with a positive integer
	And I fill fullname field with the value Rupert
	And I fill password_new field with the value verysecure1234
	And I fill password_confirm field with the value verysecure1234
	And I fill email field with a valid email
	And I click on the create button
    Then I should see a successful message
    And I should see the user in the user list page

  Scenario: create a regular user named Francis
    And I am in the user creation page
	When I fill id_user field with a positive integer
	And I fill fullname field with the value Francis
	And I fill email field with a valid email
	And I click on the create button
    Then I should see an unsuccessful message
