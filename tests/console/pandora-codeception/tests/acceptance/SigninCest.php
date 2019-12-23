<?php 

class SigninCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->logIn($I);
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnPage('/index.php?sec=gusuarios&sec2=godmode/users/configure_user&pure=0');
        $I->see('Comments');
    }
}
