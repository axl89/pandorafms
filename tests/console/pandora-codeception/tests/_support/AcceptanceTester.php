<?php


/**
* Inherited Methods
* @method void wantToTest($text)
* @method void wantTo($text)
* @method void execute($callable)
* @method void expectTo($prediction)
* @method void expect($prediction)
* @method void amGoingTo($argumentation)
* @method void am($role)
* @method void lookForwardTo($achieveValue)
* @method void comment($description)
* @method void pause()
*
* @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
    * @Given I am a pandora console administrator
    */
    public function iAmAPandoraConsoleAdministrator()
    {
        $this->amOnPage('/');
        $this->fillField('nick', 'admin');
        $this->fillField('pass', 'pandora');
        $this->click('Login');
    }

    /**
    * @Given I am in the user creation page
    */
    public function iAmInTheUserCreationPage()
    {
        $this->amOnPage('/index.php?sec=gusuarios&sec2=godmode/users/user_list&tab=user&pure=0');
        $this->see('Total items');
        $this->see('Description');

        $this->click('Create user');

        // Handy loop to avoid writting $this->see five thousand times
        $things_I_should_see = [
            'Comments',
            'Password',
            'Password confirmation',
            'Email',
            'Home screen',
            'Disabled newsletter'
        ];

        foreach ($things_I_should_see as $thing) {
            $this->see($thing);
        }
    }

    /**
    * @When I submit the form with valid data
    */
    public function iSubmitTheFormWithValidData()
    {
        $this->fillField('id_user', '1234');
        $this->fillField('fullname', 'Rupert');
        $this->fillField('password_new', 'mamasitaXd');
        $this->fillField('password_confirm', 'mamasitaXd');
        $this->fillField('email', 'rupert@artica.es');
        $this->click('Create');
    }

    /**
    * @Then I should see a successful message
    */
    public function iShouldSeeASuccessfulMessage()
    {
        $this->see('Successfully created');
    }

    /**
    * @Then I should see the user in the user list page
    */
    public function iShouldSeeTheUserInTheUserListPage()
    {
        $this->amOnPage('/index.php?sec=gusuarios&sec2=godmode/users/user_list&tab=user&pure=0');
        $this->see('Rupert');
    }
}
