Feature: Authentication
  In order to get authenticated services and quit when finished on the platform
  As a person
  I should be able to sign in and out.

  Background:
    Given there are following enabled users:
    | email | username | password |
    | aywhenamolly@gmail.com | kendoctor | 123456 |

  Scenario: Successfully sign in
    Given I am on the "Sign In" page
    When I fill in the following:
    | Username | kendoctor|
    | Password | 123456   |
    And I press "Log in"
    Then I should  be on the "world home" page
