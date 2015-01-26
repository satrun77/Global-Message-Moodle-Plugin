@local @local_globalmessage
Feature: Test display a message
  As an student
  I should be able to see the correct message once

  @javascript
  Scenario: Make sure a student can see the correct message once
    Given the following "courses" exist:
        | fullname | shortname | category | groupmode |
        | Course 1 | C1        | 0        | 1         |
      And the following "users" exist:
        | username | firstname | lastname | email            |
        | student1 | Student   | 1        | student1@asd.com |
        | student2 | Student   | 2        | student2@asd.com |
      And the following "course enrolments" exist:
        | user     | course | role    |
        | student1 | C1     | student |
        | student2 | C1     | student |
      And I log in as "admin"
      And I navigate to "Messages" node in "Site administration > Plugins > Local plugins > Global Message"
     When I press "Manage Message Designs"
      And I expand all fieldsets
      And I set the field "id_designname" to "Design 1"
      And I set the field "id_padding_top" to "5"
      And I set the field "id_padding_right" to "5"
      And I set the field "id_padding_bottom" to "5"
      And I set the field "id_padding_left" to "5"
      And I set the field "id_innerpadding_top" to "10"
      And I set the field "id_innerpadding_right" to "10"
      And I set the field "id_innerpadding_bottom" to "10"
      And I set the field "id_innerpadding_left" to "10"
      And I set the field "id_bgcolor" to "yellow"
      And I set the field "id_bordersize" to "1"
      And I set the field "id_bordercolor" to "red"
      And I set the field "id_bordershape" to "solid"
      And I press "Save design"
      And I accept the currently displayed dialog
      And I click on "//div[@id='gm-design-message-dialog']/a[@class='container-close']" "xpath_element"
      And I press "Create Global Message"
      And I set the field "id_name" to "Message 1"
      And I set the field "Summary" to "Summary of message 1"
      And I set the field "Description" to "Description of message 1"
      And I set the field "Enabled" to "Yes"
      And I set the field "Design" to "Design 1"
      And I press "Save message"
      And I click on "//tr[contains(., 'Message 1')]/descendant::td/descendant::a[text()='Edit Rules']" "xpath_element"
      And I set the field "rules-left" to "courseid"
      And I set the field "rules-operator" to "1"
      And I set the field "rules-input" to "2"
      And the "rules-state" "field" should be disabled
      And I click on "//a[@id='gm-add-rule-button']" "xpath_element"
      And the "rules-state" "field" should be enabled
      And I set the field "rules-left" to "userid"
      And I set the field "rules-operator" to "1"
      And I set the field "rules-input" to "student1"
      And I click on "//a[@id='gm-add-rule-button']" "xpath_element"
      And I press "Save rules"
      And I log out
      And I log in as "student1"
      And I follow "Course 1"
     Then I should see "Description of message 1"
      And I reload the page
      And I should not see "Description of message 1"
      And I log out
      And I log in as "student2"
      And I follow "Course 1"
      And I should not see "Description of message 1"
