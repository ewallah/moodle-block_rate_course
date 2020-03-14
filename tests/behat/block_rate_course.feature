@block @block_rate_course
Feature: Enable block rate course in a course
  In order to use the block rate course block in a course
  As a teacher
  I can add the block rate course block to a course

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
      | student1 | Student   | 1        | student1@example.com |
      | student2 | Student   | 2        | student2@example.com |
    And the following "courses" exist:
      | fullname | shortname | idnumber |
      | Course 1 | C1 | ENPRO001 |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
      | student1 | C1 | student        |
      | student2 | C1 | student        |

  @javascript
  Scenario: Create a new private template.
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    When I add the "Course ratings" block
    Then I should see "Course rating: 0 stars." in the "Course ratings" "block"
    And I log out
    And I log in as "student1"
    And I am on "Course 1" course homepage
    Then I should see "Course rating: 0 stars." in the "Course ratings" "block"
    And I click on "[data-value='2']" "css_element" in the "Course ratings" "block"
    Then I should see "Rate again" in the "Course ratings" "block"
    And I log out
    And I log in as "student2"
    And I am on "Course 1" course homepage
    And I click on "[data-value='4']" "css_element" in the "Course ratings" "block"
    And I log out
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then I should see "Course rating: 3 stars." in the "Course ratings" "block"
    And I log out