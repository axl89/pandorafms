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
     * @When /the (.+) entity has values '(.+)'/
     */
	public function dbEntityFill($entity_name, $values) {
		if (substr( $values, 0, 1 ) === "{") {
		   $data = json_decode($values, true);
		} else if (substr( $values, 0, 1 ) === "[") {
		   $data = json_decode($values, false);
		} else {
			throw new \Exception('not a json movida');
		}
		self::haveInDatabase($entity_name, $data);
	}

    /**
     * @When /I (.+) to click (.+)/
     */
     public function iXXXToClickYYY($what_we_can_do, $button_name)
     {
         try {
	         self::click($button_name);
         }
		 catch (\Codeception\Exception\ElementNotFound $e) {
		     if ($what_we_can_do == 'should not be able') {
			     return;
             } else {
		         throw new \Exception($what_we_can_do);
		     }
        }
     }


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
    * @When /I am in the (.+) page\s*(of id (.+))?/
	*/
	public function iAmInTheXXXPageWithOptionalStuff($page_name, $optional_string = null, $optional_id = null)
	{
		switch ($page_name) {
			case 'user creation':
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
				break;
			case 'user detail editor':
				self::amOnPage("/index.php?sec=gusuarios&sec2=godmode/users/configure_user&id=$optional_id");
				break;
				
		}
	}



    /**
    * @Given I am in the user creation page
    */
    public function iAmInTheUserCreationPage()
    {
    }


    /**
    * @Then /I should see the text (.+)/
    */
    public function iShouldSeeTheText($text)
    {
        self::see($text);
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
