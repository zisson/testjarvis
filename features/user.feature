Feature:
  navigate in browser and CRUD action with api

  Scenario: Create new user
      Given I am on homepage
      Then I should see "Welcome Page"
      When I follow "create-user-link"
      Then I should see "Create new User"
      And I fill in the following:
        | user_firstname | testuser |
        | user_lastname | testpwd  |
      And I press "Save"
      Then the response status code should be 200
      Then I should be on "/user/"

  Scenario: Update user
    Given I am on "/user"
    When I follow "testuser"
    Then I should see "User"
    When I follow "edit"
    And I fill in the following:
      | user_firstname | testuser |
      | user_lastname | testpwd behat |
    And I press "update"
    And I should be on "/user/"

  Scenario: delete user
    Given I am on "/user"
    When I follow "testuser"
    Then I should see "User"
    When I press "Delete"
    Then the response status code should be 200
    And I should be on "/user/"
    And I should see "User Lists"

  Scenario: navigate with back list link
    Given I am on "/user"
    When I follow "tutu"
    Then I should see "User tutu"
    When I follow "back to list"
    And I should be on "/user/"
    And I should see "User Lists"