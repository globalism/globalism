Feature: Registration for guests
  In order to get authenticated services provided by the platform
  As a guest
  I should have an account on the platform at first.

  Scenario: Successfully create an account on the platform
    Given I am on the "signup" page
    When I fill in the following:
    | Email | aywhenamolly@gmail.com |
    | Username | kendoctor |
    | Password | 123456 |
    | Repeat password | 123456 |
    And I press "Register"
    Then I should see "Welcome to new world!"
