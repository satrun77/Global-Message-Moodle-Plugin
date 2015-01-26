@local @local_globalmessage
Feature: Test creating message rules
  As an admin
  I should be able to create and edit message rules

  @javascript
  Scenario: Create & edit message rules
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
      And I click on "//tr[contains(., 'Message 1')]/descendant::td/descendant::a[text()='Edit Rules']" "xpath_element"
      And I set the field "rules-left" to "courseid"
      And I set the field "rules-operator" to "1"
      And I set the field "rules-input" to "2"
      And the "rules-state" "field" should be disabled
      And I click on "//a[@id='gm-add-rule-button']" "xpath_element"
      And the "rules-state" "field" should be enabled
      And I set the field "rules-left" to "userid"
      And I set the field "rules-operator" to "3"
      And I set the field "rules-input" to "1"
      And I click on "//a[@id='gm-add-rule-button']" "xpath_element"
      And I press "Save rules"
      And I click on "//tr[contains(., 'Message 1')]/descendant::td/descendant::a[text()='Edit Rules']" "xpath_element"
      And I should see "Course ID" in the "#gm-rulestable tbody" "css_element"
      And I should see "Equal" in the "#gm-rulestable tbody" "css_element"
      And I should see "2" in the "#gm-rulestable tbody" "css_element"
      And I should see "Username" in the "#gm-rulestable tbody" "css_element"
      And I should see "Greater than" in the "#gm-rulestable tbody" "css_element"
      And I should see "1" in the "#gm-rulestable tbody" "css_element"
