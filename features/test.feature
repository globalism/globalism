Feature: Test behat
  In order to know behat environment is ok
  As a developer
  I need to use behat to check pages of symfony

  Scenario: check of homepage
    Given I am on "/"
    Then I should see "Welcome to"