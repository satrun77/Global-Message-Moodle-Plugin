@local @local_globalmessage
Feature: Test creating a new message & edit an exiting message
  As an admin
  I should be able to create and edit messages

  @javascript
  Scenario: Create & edit message
    Given I log in as "admin"
      And I navigate to "Messages" node in "Site administration > Plugins > Local plugins > Global Message"
     When I press "Create Global Message"
      And I set the field "id_name" to "Message 1"
      And I set the field "Summary" to "Summary of message 1"
      And I set the field "Description" to "Description of message 1"
      And I set the field "Enabled" to "No"
      And I press "Save message"
     Then I should see "Message 1"
      And I should see "Disabled"
      And I click on "//tr[contains(., 'Message 1')]/descendant::td/descendant::a[text()='Edit']" "xpath_element"
      And I wait "5" seconds
      And I set the field "Enabled" to "Yes"
      And I press "Save message"
      And I should see "Enabled"
