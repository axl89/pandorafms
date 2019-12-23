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
        self::amOnPage('/');
        self::fillField('nick', 'admin');
        self::fillField('pass', 'pandora');
        self::click('Login');
    }


    /**
    * @When /I fill (.+) field with the value (.*)/
    */
    public function iFillFieldWithValue($field_name, $fixed_value)
    {
        self::fillField($field_name, $fixed_value);
    }

    /**
    * @When /I fill (.+) field with a (.+)/
    */
    public function iFillFieldWithData($field_name, $data_type)
    {
        switch($data_type) {
            case 'positive integer':
                $data = random_int(1, 9000); // Nothing is over 9000. Only Goku.
                break;
            case 'valid password':
                $data = str_shuffle(base64_encode(date('mdyhis').date('mdyhis'))); //TODO: Fix this piece of crap
                break;
            case 'valid name':
                $valid_names = [
                    'Frijolito',
                    'Angustias',
                    'Demetario',
                    'Nemesio',
                    'Artura',
                    'Bartolo',
                    'Policarpo',
                    'Piedrasantas',
                    'Heradio'
                ];
                $data = $valid_names[array_rand($valid_names)];
                break;
            case 'valid email':
                $data = 'pleasefixme@artica.es'; //TODO: Please google better than I did for 'php random email generator'
                break;
        }

        self::fillField($field_name, $data);
    }

    /**
     * @When I click on the create button
     */
    public function iClickOnTheCreateButton()
    {
        self::click('Create');
    }



    /**
    * @Given I am in the user creation page
    */
    public function iAmInTheUserCreationPage()
    {
        self::amOnPage('/index.php?sec=gusuarios&sec2=godmode/users/user_list&tab=user&pure=0');
        self::see('Total items');
        self::see('Description');

        self::click('Create user');

        // Handy loop to avoid writting self::see five thousand times
        $things_I_should_see = [
            'Comments',
            'Password',
            'Password confirmation',
            'Email',
            'Home screen',
            'Disabled newsletter'
        ];

        foreach ($things_I_should_see as $thing) {
            self::see($thing);
        }
    }

    /**
    * @Then I should see a successful message
    */
    public function iShouldSeeASuccessfulMessage()
    {
        self::see('Successfully created');
    }


    /**
    * @Then I should see an unsuccessful message
    */
    public function iShouldSeeAnUnsuccessfulMessage()
    {
        self::dontSee('Successfully created');
        self::see('Error');
    }

    /**
    * @Then I should see the user in the user list page
    */
    public function iShouldSeeTheUserInTheUserListPage()
    {
        self::amOnPage('/index.php?sec=gusuarios&sec2=godmode/users/user_list&tab=user&pure=0');
        self::see('Rupert');
    }
}
