@user_auth
Feature: User Authentication
  User can log into the site
  So they can see secured parts of the site

  Scenario: Logging in with Success
    Given I am on "/login"
      And I fill in "username" with "behat@example.com"
      And I fill in "password" with "1234567890"
    When I press "Login"
    Then I should be on "/admin"

#  @todo
#  Scenario: Logging in as an Admin
#    Given I am on "/login"