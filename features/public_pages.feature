@public_static
Feature: Public Pages
  In order to use the site
  As an anonymous user
  I need to be able to view the public pages

  Scenario: Viewing the Home page
    Given I am on "/"
    Then the response status code should be 200
    # @todo-symfony
    Then I should see "Symfony Starter" in the "h1" element