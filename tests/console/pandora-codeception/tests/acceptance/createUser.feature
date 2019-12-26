Feature: User creation
  In order to create a user
  As a pandora console administrator
  I need to be able to successfully create a user

  Background: 
    Given I am a pandora console administrator
    And I am in the user creation page

  Scenario: create a regular user
	When I fill id_user field with a positive integer
	And I fill fullname field with the value Rupert
	And I fill password_new field with the value verysecure1234
	And I fill password_confirm field with the value verysecure1234
	And I fill email field with a valid email
	And I click on the create button
    Then I should see a successful message
    And I should see the user in the user list page

  Scenario: create a user without setting the password field
	When I fill id_user field with a positive integer
	And I fill fullname field with a valid name
	And I fill email field with a valid email
	And I click on the create button
    Then I should see an unsuccessful message

  Scenario: create a user but misstyping the password verification
	When I fill id_user field with the value admin
	And I fill fullname field with a valid name
	And I fill password_new field with the value verysecure1234
	And I fill password_confirm field with the value ohnoImessedup
	And I fill email field with a valid email
	And I click on the create button
    Then I should see an unsuccessful message

  Scenario: create a user with an existing user ID
	When I fill id_user field with the value admin
	And I fill fullname field with a valid name
	And I fill password_new field with the value verysecure1234
	And I fill password_confirm field with the value verysecure1234
	And I fill email field with a valid email
	And I click on the create button
    Then I should see an unsuccessful message

  Scenario: create a user with an emoji as name
	When I fill id_user field with the value 😫
	And I fill fullname field with the value Emojiman
	And I fill password_new field with the value verysecure1234
	And I fill password_confirm field with the value verysecure1234
	And I fill email field with a valid email
	And I click on the create button
    Then I should see a successful message

  Scenario: create a user cannot click KEKE button
    And I should not be able to click KEKE


