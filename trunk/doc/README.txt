This extension connects caretaker [1] with the Redmine [2] issues tracking system.

It utilizes the REST interface that is provided by Redmine to fetch some data from
it. At the moment the following things can be retrieved:

- Number of all open tickets
- Number of all open tickets in a certain project

As you can imagine, the tests will simply fetch the number of open tickets and
compares them to a warning and an error threshold. It also tracks the actual
number to have something nice to show in the charts.


HOW TO USE

Redmine:

Before you start, you have to create an API key in your redmine installation. To do
that, log in to your Redmine installation and go to the administration panel.
Go to authorization and and enable the REST interface.

Now go to your own account (or create a new one) and on the right side you'll find
your personal API key now. Copy that somewhere, we'll need that soon.

TYPO3:

Just check out the caretaker project from forge.typo3.org [3] and move or link
the extension caretaker_redmine into your extension directory on your caretaker
SERVER.

Now log into your server backend and install the extension.

Now you can go ahead and create a new test. Select "Redmine Status tests" as
Test Service.

A few more input fields will appear. Here you have to enter the information how
the test can connect to the Redmine server. Enter the requested information and
depending on what you want to test, select either "Number of all open issues"
or "Number of all open issues in project".

If everything is correct, caretaker should now keep an eye on your open issues in
Redmine.


LINKS

[1] http://typo3-caretaker.org/
[2] http://www.redmine.org/
[3] http://forge.typo3.org/projects/extension-caretaker/repository