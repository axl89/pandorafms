<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{

    public function logIn(\AcceptanceTester $I) {
        $I->amOnPage('/');
        $I->fillField('nick', 'admin');
        $I->fillField('pass', 'pandora');
        $I->click('Login');
    }

}
